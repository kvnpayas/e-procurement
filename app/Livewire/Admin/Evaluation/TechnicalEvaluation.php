<?php

namespace App\Livewire\Admin\Evaluation;

use Livewire\Component;
use App\Models\ProjectBidding;
use Illuminate\Support\Carbon;
use App\Models\VendorEnvelopeFile;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Reports\TechnicalIndividualReport;

class TechnicalEvaluation extends Component
{
  public $bidding, $vendors, $technicals;
  public $technicalResult = [], $ratingCompliant, $results = [];
  public $adminResponse, $technicalVendor, $adminAnswer, $adminMulitAnswer, $vendorAdminId;
  public $technicalFileName, $fileAttachment;
  public $fileStatus = [];
  public $vendorRemarks;

  public function mount($biddingId)
  {
    $this->bidding = ProjectBidding::findOrFail($biddingId);

    $this->vendors = $this->getVendors();

    $this->technicals = $this->getTechnicals()->get();

    $this->ratingCompliant = [
      'Fully Compliant' => 100,
      'Partially Compliant' => 90,
      'Non-compliant' => 0,
    ];

    $this->setVendorResults();
  }

  public function getVendors()
  {
    $previousEnv = $this->bidding->progress->prev_envelope;
    if ($previousEnv) {
      $envelope = $previousEnv . 'Result';
      $vendorIds = $this->bidding->{$envelope}->where('result', true)->pluck('vendor_id')->toArray();
      // return $this->bidding->vendors()->whereIn('status', ['Under Evaluation', 'Lost', 'Winning Bidder'])->whereIn('vendor_id', $vendorIds);
      return $this->bidding->vendors()
        ->whereIn('status', ['Under Evaluation', 'Lost', 'Winning Bidder'])->whereIn('vendor_id', $vendorIds)
        ->get()
        ->map(function ($vendor) {
          $vendor->vendorStatus = $vendor->vendorStatus->where('bidding_id', $this->bidding->id)->first();
          return $vendor;
        })
        ->sortBy(function ($vendor) {
          return $vendor->vendorStatus ? $vendor->vendorStatus->submission_date : null;
        })
        ->values();
    } else {
      // return $this->bidding->vendors()->whereIn('status', ['Under Evaluation', 'Lost', 'Winning Bidder']);
      return $this->bidding->vendors()
        ->whereIn('status', ['Under Evaluation', 'Lost', 'Winning Bidder'])
        ->get()
        ->map(function ($vendor) {
          $vendor->vendorStatus = $vendor->vendorStatus->where('bidding_id', $this->bidding->id)->first();
          return $vendor;
        })
        ->sortBy(function ($vendor) {
          return $vendor->vendorStatus ? $vendor->vendorStatus->submission_date : null;
        })
        ->values();
    }
  }
  public function getTechnicals()
  {
    return $this->bidding->technicals();
  }

  public function setVendorResults()
  {
    foreach ($this->vendors as $vendor) {
      foreach ($this->getTechnicals()->get() as $technical) {
        $score = 0;
        $ratingScore = 0;
        $vendorResponse = $technical->technicalVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id)->first();
        if ($vendorResponse) {
          if ($technical->question_type == 'checkbox') {

            $answer = $vendorResponse->admin_answer ? 'Yes' : ($vendorResponse->answer ? 'Yes' : 'No');
            // $answer = $vendorResponse->answer ? 'Yes' : 'No';
            $score = $vendorResponse->admin_answer ? 100 : ($vendorResponse->answer ? 100 : 0);
            // $score = $vendorResponse->answer ? 100 : 0;
            $ratingScore = $score == 100 ? (float) $technical->pivot->weight : 0;
            if ($this->bidding->score_method == 'Cost') {
              $rating = ($score == 100) ? $score : 0;
            } else {
              if ($technical->pivot->weight == 0) {
                $rating = ($score == 100) ? $score : 0;
              } else {
                $rating = $technical->pivot->weight ? ($ratingScore / $technical->pivot->weight) * 100 : 0;
              }
            }
          } elseif ($technical->question_type == 'single-option') {
            $option = $technical->options->where('id', $vendorResponse->answer)->first();
            $adminOption = $technical->options->where('id', $vendorResponse->admin_answer)->first();
            $initScore = $adminOption ? $adminOption->score : ($option ? $option->score : 0);
            $answer = $adminOption ? $adminOption->id : ($option ? $option->id : null);
            $score = $initScore ? $initScore : 0;
            $ratingScore = (($score / 100) * ($technical->pivot->weight * 0.01)) * 100;
            $rating = $technical->pivot->weight ? ($ratingScore / $technical->pivot->weight) * 100 : 0;
          } elseif ($technical->question_type == 'multi-option') {
            $option = explode('&@!', $vendorResponse->answer);
            $adminOption = $vendorResponse->admin_answer ? explode('&@!', $vendorResponse->admin_answer) : null;
            $answer = $adminOption ? $adminOption : ($option ? $option : null);
            $allOption = $technical->options->whereIn('id', $answer);
            $score = $vendorResponse->answer || $vendorResponse->admin_answer ? $allOption->sum('score') : 0;

            $totalScore = $technical->options->sum('score');
            $ratingScore = (($score / $totalScore) * ($technical->pivot->weight * 0.01)) * 100;
            $rating = $technical->pivot->weight ? ($ratingScore / $technical->pivot->weight) * 100 : 0;
          } else {
            $answer = $vendorResponse ? $vendorResponse->admin_answer ? $vendorResponse->admin_answer : $vendorResponse->answer : NULL;
            if ($technical->to) {
              $score = $answer >= $technical->from && $answer <= $technical->to ? 100 : 0;
            } else {
              $score = $answer
                >= $technical->from ? 100 : 0;
            }

            $ratingScore = $score == 100 ? (float) $technical->pivot->weight : 0;
            $rating = $technical->pivot->weight ? ($ratingScore / $technical->pivot->weight) * 100 : 0;
          }
        } else {
          $answer = NULL;
          $score = NULL;
          $ratingScore = NULL;
          $rating = 0;
          $compliance = 'Non-compliance';
        }
        // Compliant Results
        $checkRating = $technical->pivot->weight == 0 ? $score : $rating;
        if ($this->ratingCompliant['Fully Compliant'] == $checkRating) {
          $compliance = 'Fully Compliant';
        } else if (
          $this->ratingCompliant['Fully Compliant'] > $checkRating &&
          $this->ratingCompliant['Non-compliant'] < $checkRating
        ) {
          $compliance = 'Partially Compliant';
        } else {
          $compliance = 'Non-compliant';
        }

        $filesOnBid = $technical->vendorFiles->where('vendor_id', $vendor->id)->where('bidding_id', $this->bidding->id);
        foreach ($filesOnBid as $file) {
          $fileInitStatus = $this->bidding->fileAttachmentsStatus('technical')->where('file_id', $file->id)->first();
          $this->fileStatus[$file->id] = [
            'file_id' => $file->id,
            'status' => $fileInitStatus ? true : false,
          ];
        }
        $fileExist = !$filesOnBid->isEmpty() ? $filesOnBid : null;
        // $files = $eligibility->vendorFiles->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id);
        $this->technicalResult[$vendor->id][$technical->id] = [
          'answer' => $answer ? $answer : NULL,
          'admin_answer' => $vendorResponse ? $vendorResponse->admin_answer : null,
          'score' => $score !== null ? $score : NULL,
          'rating_score' => $ratingScore,
          'rating' => $rating,
          'weight' => $technical->pivot->weight,
          'attach' => $fileExist,
          'compliance' => $compliance
        ];

      }
      $vendorResult = $this->bidding->technicalResult->where('vendor_id', $vendor->id)->first();
      $vendorScore = collect($this->technicalResult[$vendor->id])->sum('rating_score');
      $technicalWeight = $this->bidding->weights->where('envelope', 'technical')->first();

      foreach ($this->technicalResult[$vendor->id] as $data) {
        if ($data['compliance'] == 'Non-compliant') {
          $totalRating = false;
          break;
        } else {
          $totalRating = true;
        }
      }
      // $totalRating = $technicalWeight->weight ? ($vendorScore / $technicalWeight->weight) * 100 : 0;
      $data = [
        'vendor_id' => $vendor->id,
        // 'result' => $totalRating >= $this->ratingCompliant['Non-compliant'] ? true : false,
        'result' => $totalRating,
        'score' => $vendorScore,
      ];
      if ($vendorResult) {
        $vendorResult->update($data);
        $this->results[] = $vendorResult;
      } else {
        $this->results[] = $this->bidding->technicalResult()->create($data);
      }

      $remarks = $this->bidding->bidEnvelopeStatus->where('vendor_id', $vendor->id)->where('envelope', 'technical')->first();
      $this->vendorRemarks[$vendor->id] = $remarks ? $remarks->remarks : null;

    }
    // dd($this->technicalResult);
  }
  public function adminModal($technicalId, $vendorId)
  {
    $this->resetValidation();
    $this->vendorAdminId = $vendorId;
    $this->technicalVendor = $this->getTechnicals()->where('technicals.id', $technicalId)->first();
    $this->adminResponse = $this->technicalVendor->technicalVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendorId)->first();
    // dd($this->adminResponse);

    if ($this->technicalVendor->question_type == 'checkbox') {
      $this->adminAnswer = $this->adminResponse ? $this->adminResponse->admin_answer ? $this->adminResponse->admin_answer : false : false;
    } elseif ($this->technicalVendor->question_type == 'single-option') {
      if ($this->adminResponse && $this->adminResponse->admin_answer) {
        $option = $this->technicalVendor->options->where('id', $this->adminResponse->admin_answer)->first();
        $this->adminAnswer = $option ? $option->id : null;
      } else {
        $this->adminAnswer = null;
      }
    } else if ($this->technicalVendor->question_type == 'multi-option') {
      if ($this->adminResponse && $this->adminResponse->admin_answer) {
        $options = explode('&@!', $this->adminResponse->admin_answer);
        foreach ($options as $data) {
          $option = $this->technicalVendor->options->where('id', $data)->first();
          $this->adminMulitAnswer[$option->id] = true;
        }
      } else {
        $this->adminMulitAnswer = [];
      }
    } else {
      $this->adminAnswer = $this->adminResponse ? $this->adminResponse->admin_answer ? $this->adminResponse->admin_answer : null : null;
    }

    $this->dispatch('openAdminModal');
  }

  public function closeAdminModal()
  {
    $this->dispatch('closeAdminModal');
  }

  public function submitAdminResponse()
  {
    // dd($this->vendorAdminId);
    // $this->technicalVendor->question_type 
    if ($this->technicalVendor->question_type == 'multi-option') {
      $trueValues = array_filter($this->adminMulitAnswer, function ($value) {
        return $value === true;
      });
      $this->adminAnswer = implode('&@!', array_keys($trueValues));
    }

    $this->validate([
      'adminAnswer' => 'required',
    ], [
      'adminAnswer.required' => 'Admin response field is required.'
    ]);

    if ($this->adminResponse) {
      $this->adminResponse->update([
        'admin_answer' => $this->adminAnswer,
        'admin_user' => Auth::user()->id,
      ]);
    } else {
      $this->technicalVendor->technicalVendors()->create([
        'bidding_id' => $this->bidding->id,
        'vendor_id' => $this->vendorAdminId,
        'admin_answer' => $this->adminAnswer,
        'admin_user' => Auth::user()->id,
      ]);
    }

    // return redirect()
    //   ->route('project-bidding.evaluation', $this->bidding->id)
    //   ->with('success', 'Technical updated!');
    $this->setVendorResults();
    $this->dispatch('closeAdminModal');
    $this->dispatch('success-message', ['message' => 'Technical updated!']);
  }

  public function printReport()
  {
    $technicalData = [];

    foreach ($this->vendors as $vendor) {
      $technicalData[$vendor->id] = TechnicalIndividualReport::generateReport($this->bidding->id, $vendor->id);
      // dd($test);
      // foreach ($this->getTechnicals()->get() as $technical) {
      //   $response = $technical->technicalVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id)->where('technical_id', $technical->id)->first();
      //   $technicalData[$vendor->id]['technicals'][$technical->id] = [
      //     'question' => $technical->question,
      //     'question_type' => $technical->question_type,
      //     'passing' => $technical->question_type == 'numeric' || $technical->question_type == 'numeric-percent' ? $technical->from . ' - ' . $technical->to : null,
      //     'admin' => $response ? $response->admin_answer ? true : false : false,
      //   ];
      //   if ($response) {
      //     $answer = $response ? $response->admin_answer ? $response->admin_answer : $response->answer : false;
      //     if ($technical->question_type == 'checkbox') {
      //       $ratingScore = $answer ? $technical->pivot->weight : 0;
      //       $technicalData[$vendor->id]['technicals'][$technical->id]['answer'] = $answer ? 'Yes' : 'No';
      //       $technicalData[$vendor->id]['technicals'][$technical->id]['rating_score'] = $ratingScore;

      //     } elseif ($technical->question_type == 'single-option') {

      //       if ($answer) {
      //         $option = $technical->options->where('id', $answer)->first();
      //         $ratingScore = (($option->score / 100) * ($technical->pivot->weight * 0.01)) * 100;
      //         $technicalData[$vendor->id]['technicals'][$technical->id]['answer'] = $option->name;
      //         $technicalData[$vendor->id]['technicals'][$technical->id]['rating_score'] = $ratingScore;
      //       }
      //     } elseif ($technical->question_type == 'multi-option') {

      //       $arrayAnswer = explode('&@!', $answer);
      //       $options = [];
      //       $totalScore = 0;
      //       foreach ($arrayAnswer as $ans) {
      //         $option = $technical->options->where('id', $ans)->first();
      //         $options[] = $option->name;
      //         $totalScore += $option->score;
      //       }
      //       $ratingScore = (($totalScore / $technical->options->sum('score')) * ($technical->pivot->weight * 0.01)) * 100;
      //       $technicalData[$vendor->id]['technicals'][$technical->id]['answer'] = implode(', ', $options);
      //       $technicalData[$vendor->id]['technicals'][$technical->id]['rating_score'] = $ratingScore;
      //     } else {
      //       if ($technical->to) {
      //         $score = $answer >= $technical->from && $answer <= $technical->to ? 100 : 0;
      //       } else {
      //         $score = $answer
      //           >= $technical->from ? 100 : 0;
      //       }
      //       $ratingScore = $score == 100 ? (float) $technical->pivot->weight : 0;
      //       $technicalData[$vendor->id]['technicals'][$technical->id]['answer'] = $answer;
      //       $technicalData[$vendor->id]['technicals'][$technical->id]['rating_score'] = $ratingScore;
      //     }
      //   } else {
      //     $technicalData[$vendor->id]['technicals'][$technical->id]['answer'] = null;
      //     $technicalData[$vendor->id]['technicals'][$technical->id]['rating_score'] = null;
      //   }
      // }
      // $weight = $this->bidding->weights->where('envelope', 'technical')->first();
      // $score = $this->bidding->technicalResult->where('vendor_id', $vendor->id)->first();
      // $result = $this->bidding->technicalResult->where('vendor_id', $vendor->id)->first();
      // $technicalData[$vendor->id]['name'] = $vendor->name;
      // $technicalData[$vendor->id]['email'] = $vendor->email;
      // $technicalData[$vendor->id]['address'] = $vendor->address;
      // $technicalData[$vendor->id]['number'] = $vendor->number;
      // $technicalData[$vendor->id]['result'] = $result ? $result->result : 0;
      // $technicalData[$vendor->id]['total_rating'] = $weight->weight;
      // $technicalData[$vendor->id]['score'] = $score ? $score->score : null;
    }
    // dd($technicalData);
    $sortedData = array_values($technicalData);
    $this->dispatch('openReportModal', $sortedData, 'technical', $this->bidding->id);
  }
  public function viewFile($file, $fileId)
  {
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => 'vendor-file\technical']);
    $this->technicalFileName = $file;

    $file = VendorEnvelopeFile::findOrFail($fileId);
    // dd($file);
    // change status
    $fileStatusExists = $this->bidding->fileAttachmentsStatus('technical')->where('file_id', $fileId)->first();
    if (!$fileStatusExists) {
      $this->bidding->fileAttachmentsStatus('technical')->create([
        'bidding_id' => $this->bidding->id,
        'file_id' => $file->id,
        'vendor_id' => $file->vendor_id,
        'validated_by' => Auth::user()->id,
        'envelope' => 'technical',
        'validated_date' => Carbon::now(),
      ]);
    }
    $this->setVendorResults();
    $this->dispatch('checkFileAttachmentStatus');
    $this->dispatch('openFileModal');
  }

  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }

  public function updatedVendorRemarks($value, $id)
  {
    $remarks = $this->bidding->bidEnvelopeStatus->where('vendor_id', $id)->where('envelope', 'technical')->first();

    $remarks->remarks = $value;
    $remarks->save();
  }

  public function render()
  {
    return view('livewire.admin.evaluation.technical-evaluation');
  }
}
