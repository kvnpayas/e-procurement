<?php

namespace App\Livewire\Admin\Evaluation;

use Livewire\Component;
use App\Models\ProjectBidding;
use App\Helpers\Reports\AllResultsReport;
use App\Helpers\Reports\FinancialIndividualReport;
use App\Helpers\Reports\TechnicalIndividualReport;
use App\Helpers\Reports\EligibilityIndividualReport;

class FinalEvaluation extends Component
{
  public $bidding, $vendors, $finalResults;
  public $envelopes;
  public $selectedVendor, $finalEnvelopes;

  public function mount($biddingId)
  {
    $this->bidding = ProjectBidding::findOrFail($biddingId);
    $allEnvelopes = [
      'eligibility' => (bool) $this->bidding->eligibility,
      'technical' => (bool) $this->bidding->technical,
      'financial' => (bool) $this->bidding->financial,
    ];
    $firstEnvelope = array_search(true, $allEnvelopes, true);

    $envelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
    });

    foreach ($envelopes as $envelope => $value) {
      $weight = $this->bidding->weights->where('envelope', $envelope)->first();
      $envelopes[$envelope] = $weight ? $weight->weight : null;
    }
    $this->envelopes = $envelopes;
    $this->vendors = $this->getVendors();

    $this->setVendorResults();
    // $this->eligibilities = $this->getEligibilities()->get();

  }

  public function getVendors()
  {
    $getJoinedVendors = $this->bidding->vendors()->whereIn('status', ['Under Evaluation', 'Lost', 'Winning Bidder']);

    // add data fields to collection
    $vendors = $getJoinedVendors->get()->map(function ($vendor) {
      $totalScore = 0;
      $getAllResults = [];
      foreach ($this->envelopes as $envelope => $value) {
        $model = $envelope . 'Result';
        $envResult = $envelope . '_result';
        $envScore = $envelope . '_score';
        $envelopeResult = $this->bidding->{$model}->where('vendor_id', $vendor->id)->first();
        if ($envelope != 'eligibility') {
          $vendor->{$envScore} = $envelopeResult && $envelopeResult->score > 0 ? $envelopeResult->score : 0;
          $vendor->{$envResult} = $envelopeResult ? $envelopeResult->result : 0;
          $totalScore += $vendor->{$envScore};
        } else {
          $vendor->{$envResult} = $envelopeResult ? $envelopeResult->result : 0;
        }
        $getAllResults[$envelope] = $envelopeResult ? $envelopeResult->result : 0;
      }
      // dd($getAllResults);
      $vendor->final_result = in_array(false, $getAllResults, false) ? false : true; // Check if there is failed on all envelopes
      $vendor->total_score = $vendor->final_result ? $totalScore : null;
      return $vendor;
    });
    // Sort and assign ranks to vendors
    $sort = $this->bidding->scrap || $this->bidding->score_method == 'Rating' ?
      $vendors->sortByDesc(function ($vendor) {
        return is_null($vendor->total_score) ? -INF : $vendor->total_score;
      }) :
      $vendors->sortBy(function ($vendor) {
        return is_null($vendor->total_score) ? INF : $vendor->total_score;
      });

    // $sort = $this->bidding->scrap ? $vendors->sortByDesc('total_score') : $vendors->sortBy('total_score');
    $rank = 1;
    $previousScore = null;
    $vendors = $sort->map(function ($vendor) use (&$rank, &$previousScore) {
      if ($vendor->total_score !== $previousScore) {
        $previousScore = $vendor->total_score;
        $vendor->rank = $rank;
      } else {
        $rank--;
        $vendor->rank = $rank;
      }
      $rank++;
      return $vendor;
    });

    if (!$this->bidding->scrap && $this->bidding->score_method == "Rating") {
      // Check if there is more than 1 on rank 1
      $firstRankCount = $vendors->where('rank', 1)->count();
      if ($firstRankCount > 1) {
        $groupedVendors = $vendors->groupBy('rank');

        $sortedVendors = collect();

        foreach ($groupedVendors as $rank => $group) {
          if ($group->count() > 1) {
            $sortedGroup = $group->sortBy(function ($vendor) {
              $totalAmount = 0;
              foreach ($this->bidding->financials as $financial) {
                $financialVendor = $financial->financialVendors
                  ->where('vendor_id', $vendor->id)
                  ->where('bidding_id', $this->bidding->id)
                  ->first();
                if ($financialVendor) {
                  $totalAmount += ($financialVendor->price + $financialVendor->other_fees) * $financial->pivot->quantity;
                }
              }
              return $totalAmount;
            })->values();
          } else {
            $sortedGroup = $group;
          }
          $sortedVendors = $sortedVendors->concat($sortedGroup);
        }

        $vendors = $sortedVendors->values();
      }
    }
    return $vendors;
  }
  public function setVendorResults()
  {
    foreach ($this->vendors as $vendor) {

      $vendorResult = $this->bidding->finalResult->where('vendor_id', $vendor->id)->first();
      $data = [
        'vendor_id' => $vendor->id,
        'result' => $vendor->final_result,
        'score' => $vendor->total_score,
        'rank' => $vendor->rank,
      ];
      if ($vendorResult) {
        $vendorResult->update($data);
      } else {
        $this->bidding->finalResult()->create($data);
      }
    }
  }

  public function reviewModal($vendorId)
  {
    $this->dispatch('reviewModal', $this->bidding->id, $vendorId);

    // $this->dispatch('openReviewModal');

    // dd($this->finalEnvelopes);
    // $this->dispatch('openReviewModal');
  }
  public function closeReviewModal()
  {
    $this->dispatch('closeReviewModal');
  }

  public function printReport()
  {
    $rankWithData = AllResultsReport::getAllResults($this->bidding->id, $this->vendors);
    $this->dispatch('openReportModal', $rankWithData, 'final', $this->bidding->id);
  }

  public function render()
  {
    $this->vendors = $this->getVendors();
    return view('livewire.admin.evaluation.final-evaluation');
  }
}
