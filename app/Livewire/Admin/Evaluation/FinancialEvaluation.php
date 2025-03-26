<?php

namespace App\Livewire\Admin\Evaluation;

use Livewire\Component;
use App\Models\ProjectBidding;
use Illuminate\Support\Carbon;
use App\Models\VendorEnvelopeFile;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Reports\FinancialIndividualReport;

class FinancialEvaluation extends Component
{
  public $bidding, $vendors, $financials;
  public $financialsVendor, $financialResult, $results, $financialOfferFiles;
  public $adminFinancial, $adminVendorResponse, $adminResponse, $adminVendor;
  public $financialFileName, $fileAttachment;
  public $fileStatus = [];
  public $vendorRemarks;

  public function mount($biddingId)
  {
    $this->bidding = ProjectBidding::findOrFail($biddingId);

    $this->vendors = $this->getVendors();

    $this->setVendorResults();

    $this->financials = $this->getFinancials()->get();

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
  public function getFinancials()
  {
    return $this->bidding->financials();
  }
  public function setVendorResults()
  {
    foreach ($this->vendors as $vendor) {
      // $eligibilityResult[$vendor->id]
      foreach ($this->getFinancials()->get() as $financial) {
        $vendorFinancial = $financial->financialVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id)->first();
        $quantity = $this->bidding->financials->where('id', $financial->id)->first()->pivot->quantity;
        if ($vendorFinancial) { // if vendor answer the requirements
          $price = $vendorFinancial->admin_price ? $vendorFinancial->admin_price : $vendorFinancial->price;
          $fees = $vendorFinancial->admin_fees ? $vendorFinancial->admin_fees : $vendorFinancial->other_fees;

          $reserved_price = $this->bidding->financials->where('id', $financial->id)->first()->pivot->bid_price;
          $this->financialsVendor[$vendor->id][$financial->id] = [
            'price' => $price,
            'bid_price' => $financial->pivot->bid_price,
            'fees' => $fees,
            'admin_price' => $vendorFinancial->admin_price,
            'admin_fees' => $vendorFinancial->admin_fees,
            'quantity' => $quantity,
            'total_reserved_price' => $reserved_price * $quantity,
            'amount' => ($price + $fees) * $quantity,
          ];

        } else {
          $this->financialsVendor[$vendor->id][$financial->id] = null;
        }

      }

      // Get Financial files
      $this->financialOfferFiles[$vendor->id] = $this->bidding->financialFiles->where('vendor_id', $vendor->id);
      foreach ($this->financialOfferFiles[$vendor->id] as $file) {
        $fileInitStatus = $this->bidding->fileAttachmentsStatus('financial')->where('file_id', $file->id)->first();
        $this->fileStatus[$file->id] = [
          'file_id' => $file->id,
          'status' => $fileInitStatus ? true : false,
        ];
      }

      // Check if there is null value on every financial
      $filteredArray = array_filter($this->financialsVendor[$vendor->id], function ($value) {
        return $value !== null;
      });

      // Compute Results for Vendors
      if ($this->financialsVendor[$vendor->id] && $this->bidding->financials->count() == count($filteredArray)) {
        $vendorAmount = collect($this->financialsVendor[$vendor->id])->sum('amount');
        $totalReservedPrice = collect($this->financialsVendor[$vendor->id])->sum('total_reserved_price');
        // dd((($totalReservedPrice - $vendorAmount) / ($totalReservedPrice)));
        $diffPercent = (($totalReservedPrice - $vendorAmount) / $totalReservedPrice) * 100;
        if ($this->bidding->score_method == 'Rating') { // Bidding score method is Rating
          if ($this->bidding->reserved_price_switch) {
            if ($diffPercent >= 20) {
              $vendorScore = 100 * $this->bidding->weights()->where('envelope', 'financial')->first()->weight * 0.01;
            } elseif ($diffPercent < 20 && $diffPercent >= 10) {
              $vendorScore = 95 * $this->bidding->weights()->where('envelope', 'financial')->first()->weight * 0.01;
            } elseif ($diffPercent < 10 && $diffPercent >= 5) {
              $vendorScore = 90 * $this->bidding->weights()->where('envelope', 'financial')->first()->weight * 0.01;
            } elseif ($diffPercent < 5 && $diffPercent >= 0) {
              $vendorScore = 85 * $this->bidding->weights()->where('envelope', 'financial')->first()->weight * 0.01;
            } else {
              $vendorScore = 0 * $this->bidding->weights()->where('envelope', 'financial')->first()->weight * 0.01;
            }

            $vendResult = $diffPercent >= 0 ? true : false;
          } else {
            $vendResult = $vendorAmount > 0 ? true : false;
            $vendorScore = null;

          }
          $this->financialResult[$vendor->id] = [
            'total_reserved_price' => $this->bidding->reserved_price_switch ? $totalReservedPrice : '',
            'total_amount' => $vendorAmount,
            'difference' => $this->bidding->reserved_price_switch ? $totalReservedPrice - $vendorAmount : '',
            'difference_percent' => $this->bidding->reserved_price_switch ? $diffPercent : '',
            'score' => $vendorScore,
            'result' => $vendResult,
          ];
        } else { // Bidding score method is Cost
          // dd($this->bidding->reserved_price_switch);
          $diffPercent = $totalReservedPrice;
          $vendorScore = $vendorAmount;
          if ($this->bidding->scrap) {
            $vendResult = $totalReservedPrice > $vendorAmount ? false : true;
            $this->financialResult[$vendor->id] = [
              'total_reserved_price' => $this->bidding->reserved_price_switch ? $totalReservedPrice : 'N/A',
              'total_amount' => $vendorAmount,
              'difference' => $this->bidding->reserved_price_switch ? $totalReservedPrice - $vendorAmount : 'N/A',
              'difference_percent' => (($totalReservedPrice - $vendorAmount) / ($totalReservedPrice)) * 100,
              'score' => $vendorScore,
              'result' => $this->bidding->reserved_price_switch ? $vendResult : true,
            ];
          } else {
            $vendResult = $this->bidding->reserved_price_switch ? ($totalReservedPrice < $vendorAmount ? false : true) : true;
            $this->financialResult[$vendor->id] = [
              'total_reserved_price' => $this->bidding->reserved_price_switch ? $totalReservedPrice : NULL,
              'total_amount' => $vendorAmount,
              'difference' => $totalReservedPrice - $vendorAmount,
              'difference_percent' => (($totalReservedPrice - $vendorAmount) / ($totalReservedPrice)) * 100,
              'score' => $vendorScore,
              'result' => $vendResult,
            ];
          }
        }

        // $data = [
        //   'vendor_id' => $vendor->id,
        //   'result' => $this->financialResult[$vendor->id]['result'],
        //   'score' => $this->financialResult[$vendor->id]['score'],
        // ];

      } else {
        $this->financialResult[$vendor->id] = null;
        // $data = [
        //   'vendor_id' => $vendor->id,
        //   'result' => false,
        //   'score' => 0,
        // ];
      }

      $remarks = $this->bidding->bidEnvelopeStatus->where('vendor_id', $vendor->id)->where('envelope', 'financial')->first();
      $this->vendorRemarks[$vendor->id] = $remarks ? $remarks->remarks : null;
    }
    // dd($this->financialResult);

    // If Score is rating and no reserved price is off
    if ($this->bidding->score_method == 'Rating' && !$this->bidding->reserved_price_switch) {
      uasort($this->financialResult, function ($a, $b) {
        return $a['total_amount'] <=> $b['total_amount'];
      });

      $rank = 1;
      $previousAmount = null;
      foreach ($this->financialResult as $vendorId => &$result) {
        if ($previousAmount !== null && $result['total_amount'] != $previousAmount) {
          $rank++;
        }
        $result['rank'] = $rank;
        $previousAmount = $result['total_amount'];
      }
      unset($result);

      foreach ($this->financialResult as $vendorId => &$result) {
        $intervals = (int) $result['rank'] - 1;
        $deduction = 1;
        while ($intervals > 0) {
          $deduction = $deduction - 0.05;
          $intervals--;
        }
        $weights = $this->bidding->weights->where('envelope', 'financial')->first();

        $result['score'] = ($deduction * ($weights ? ($weights->weight * 0.01) : 0)) * 100;
      }

    }

    foreach ($this->financialResult as $vendorId => $vendorResults) {
      if ($vendorResults) {
        $data = [
          'vendor_id' => $vendorId,
          'result' => $vendorResults['result'],
          'score' => $vendorResults['score'],
        ];
      } else {
        $data = [
          'vendor_id' => $vendorId,
          'result' => false,
          'score' => 0,
        ];
      }
      $vendorResult = $this->bidding->financialResult->where('vendor_id', $vendorId)->first();
      if ($vendorResult) {
        $vendorResult->update($data);
        $updateData = $vendorResult;
        $this->results[$vendorId] = $updateData ? $updateData->toArray() : null;
      } else {
        $createData = $this->bidding->financialResult()->create($data);
        $this->results[$vendorId] = $createData ? $createData->toArray() : null;
      }
    }
    // dd($this->financialResult);
  }

  public function adminModal($financialId, $vendorId)
  {
    $adminFinancial = $this->getFinancials()->where('financials.id', $financialId)->get();
    $this->adminVendor = $vendorId;

    $this->adminFinancial = $adminFinancial->map(function ($financial) {
      $financial->bid_price = $financial->pivot->bid_price;
      $financial->quantity = $financial->pivot->quantity;
      return $financial;
    })->first();

    $this->adminVendorResponse = $this->adminFinancial->financialVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendorId)->first();

    $this->adminResponse = [
      'admin_price' => $this->adminVendorResponse ? $this->adminVendorResponse->admin_price : null,
      'admin_fees' => $this->adminVendorResponse ? $this->adminVendorResponse->admin_fees : null,
    ];
    $this->dispatch('openAdminModal');
  }
  public function closeAdminModal()
  {
    $this->adminVendorResponse = [];
    $this->adminFinancial = [];
    $this->resetValidation();
    $this->dispatch('closeAdminModal');
  }
  public function submitAdminResponse()
  {
    $this->validate([
      'adminResponse.admin_price' => 'required|numeric',
      'adminResponse.admin_fees' => 'required|numeric',
    ], [
      'adminResponse.admin_price.required' => 'The admin price field is required.',
      'adminResponse.admin_fees.required' => 'The admin fees field is required.',
      'adminResponse.admin_price.numeric' => 'The admin price field must be a number.',
      'adminResponse.admin_fees.numeric' => 'The admin fees field must be a number.',
    ]);

    if ($this->adminVendorResponse) {
      $this->adminVendorResponse->update([
        'admin_price' => $this->adminResponse['admin_price'],
        'admin_fees' => $this->adminResponse['admin_fees'],
        'admin_user' => Auth::user()->id,
      ]);
    } else {
      $this->adminFinancial->financialVendors()->create([
        'bidding_id' => $this->bidding->id,
        'vendor_id' => $this->adminVendor,
        'admin_price' => $this->adminResponse['admin_price'],
        'admin_fees' => $this->adminResponse['admin_fees'],
        'admin_user' => Auth::user()->id,
      ]);
    }

    $this->setVendorResults();
    $this->dispatch('closeAdminModal');
    $this->dispatch('success-message', ['message' => 'Financial updated!']);
  }


  public function printReport()
  {
    $financialData = [];

    foreach ($this->vendors as $vendor) {
      $financialData[$vendor->id] = FinancialIndividualReport::generateReport($this->bidding->id, $vendor->id);
    }
    // dd($financialData);
    $sortedData = array_values($financialData);
    $this->dispatch('openReportModal', $sortedData, 'financial', $this->bidding->id);
  }

  // view file
  public function viewFile($file, $fileId)
  {
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => 'vendor-file\financial']);
    $this->financialFileName = $file;

    $file = VendorEnvelopeFile::findOrFail($fileId);
    // dd($file);
    // change status
    $fileStatusExists = $this->bidding->fileAttachmentsStatus('financial')->where('file_id', $fileId)->first();
    if (!$fileStatusExists) {
      $this->bidding->fileAttachmentsStatus('financial')->create([
        'bidding_id' => $this->bidding->id,
        'file_id' => $file->id,
        'vendor_id' => $file->vendor_id,
        'validated_by' => Auth::user()->id,
        'envelope' => 'financial',
        'validated_date' => Carbon::now(),
      ]);
    }
    $this->setVendorResults();
    $this->dispatch('checkFileAttachmentStatus');
    $this->dispatch('openFileModal');
  }

  public function updatedVendorRemarks($value, $id)
  {
    $remarks = $this->bidding->bidEnvelopeStatus->where('vendor_id', $id)->where('envelope', 'financial')->first();

    $remarks->remarks = $value;
    $remarks->save();
  }

  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }

  public function render()
  {
    return view('livewire.admin.evaluation.financial-evaluation');
  }
}
