<?php

namespace App\Livewire\Admin\Approval;

use App\Models\Role;
use Livewire\Component;
use App\Mail\BidApproval;
use App\Mail\ApprovalLost;
use App\Mail\ApprovalWinner;
use Livewire\WithPagination;
use App\Models\ProjectBidding;
use Illuminate\Support\Carbon;
use App\Mail\BidApprovalAPproved;
use App\Mail\BidApprovalReEvaluate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Reports\AllResultsReport;
use App\Helpers\Reports\FinancialIndividualReport;
use App\Helpers\Reports\TechnicalIndividualReport;
use App\Helpers\Reports\EligibilityIndividualReport;

class Approval extends Component
{
  use WithPagination;
  protected $biddingLists;
  public $tableHeader;
  public $search, $orderBy = 'project_id', $sort = 'desc';
  public $selectedBidReview, $envelopes, $vendorResults, $remarks;
  public $selectedWinner, $acceptResult;
  public $rejectResult, $rejectedWinner, $rejectRemarks;
  public $bidSelected;

  public function mount()
  {
    $this->tableHeader = [
      'id' => 'Project Id',
      'title' => 'Title',
      'type' => 'Type',
      // 'envelopes' => 'Selected Envelopes',
      'reserved_price' => 'Reserved Price',
      'bid_price' => 'Bid Price',
      'winner' => 'Winner',
      'created' => 'Created By',
      'review' => 'Review',
      'action' => 'Action',
    ];


  }
  public function getBiddings()
  {
    $query = ProjectBidding::where('status', 'For Approval')->with('winnerApproval')->orderBy($this->orderBy, $this->sort);

    if (Auth::user()->role->id == 4) {
      $query->whereHas('winnerApproval', function ($q) {
        $q->where('approver', false);
      });
    } elseif (Auth::user()->role->id == 5) {
      $query->whereHas('winnerApproval', function ($q) {
        $q->where('final_approver', false)->where('approver', true);
      });
    }

    return $query;
  }

  public function selectedFilters($params)
  {
    if ($this->orderBy == $params) {
      $this->sort = $this->sort == 'asc' ? 'desc' : 'asc';
    } else {
      $this->orderBy = $params;
      $this->sort = 'desc';
    }
  }

  // public function getVendors($bidding)
  // {
  //   $vendors = $this->sortedVendor($bidding);

  //   foreach ($vendors as $vendor) {
  //     $dataRank = $vendor->finalResult->where('bidding_id', $bidding->id)->first();
  //     $rank = $dataRank ? $dataRank->rank : null;
  //     $dataScore = $bidding->finalResult->where('vendor_id', $vendor->id)->first();
  //     foreach ($this->envelopes as $envelope) {
  //       $model = $envelope . 'Result';
  //       $envelopeResult = $bidding->{$model}->where('vendor_id', $vendor->id)->first();
  //       $reviewSummary[$vendor->id]['envelopes'][$envelope] = $envelopeResult ? $envelopeResult->result : 0;
  //     }

  //     // Add financial Total Amount
  //     if (!$bidding->scrap && $bidding->score_method == "Rating") {
  //       $vendorResponse = $bidding->financialVendors->where('vendor_id', $vendor->id);
  //       $totalAmount = 0;
  //       foreach ($vendorResponse as $response) {
  //         $quantity = $bidding->financials->where('id', $response->financial_id)->first()->pivot->quantity;
  //         $totalAmount += ($response->price + $response->other_fees) * $quantity;
  //       }
  //       $reviewSummary[$vendor->id]['totalAmount'] = $totalAmount;
  //     } else {
  //       $reviewSummary[$vendor->id]['totalAmount'] = null;
  //     }

  //     $winner = $vendor->winBids->where('bidding_id', $bidding->id)->first();
  //     $reviewSummary[$vendor->id]['id'] = $vendor->id;
  //     $reviewSummary[$vendor->id]['name'] = $vendor->name;
  //     $reviewSummary[$vendor->id]['rank'] = $rank;
  //     $reviewSummary[$vendor->id]['score'] = $dataScore ? $dataScore->score : null;
  //     $reviewSummary[$vendor->id]['winner'] = $winner ? $winner->winner_id : 0;

  //   }

  //   $vendorResults = collect($reviewSummary)->sortBy('rank');

  //   $groupedVendors = $vendorResults->groupBy('rank');

  //   $sortedVendors = collect();

  //   foreach ($groupedVendors as $rank => $group) {
  //     $sortedGroup = $group->sortBy('totalAmount');
  //     $sortedVendors = $sortedVendors->concat($sortedGroup);
  //   }
  //   $vendorResults = $sortedVendors->values();

  //   return $vendorResults;
  // }

  public function getVendors($selectedBidding)
  {
    $bidding = $selectedBidding;
    $getJoinedVendors = $bidding->vendors()->whereIn('status', ['Under Evaluation', 'Lost', 'Winning Bidder']);
    // add data fields to collection
    $vendors = $getJoinedVendors->get()->map(function ($vendor) use ($bidding) {
      $totalScore = 0;
      $getAllResults = [];
      foreach ($this->envelopes as $envelope) {
        $model = $envelope . 'Result';
        $envResult = $envelope . '_result';
        $envScore = $envelope . '_score';
        $envelopeResult = $bidding->{$model}->where('vendor_id', $vendor->id)->first();
        if ($envelope != 'eligibility') {
          $vendor->{$envScore} = $envelopeResult && $envelopeResult->score > 0 ? $envelopeResult->score : 0;
          $vendor->{$envResult} = $envelopeResult ? $envelopeResult->result : 0;
          $totalScore += $vendor->{$envScore};
        } else {
          $vendor->{$envResult} = $envelopeResult ? $envelopeResult->result : 0;
        }
        $getAllResults[$envelope] = $envelopeResult ? $envelopeResult->result : 0;
      }
      $winner = $vendor->winBids->where('bidding_id', $bidding->id)->first();
      
      $vendor->final_result = in_array(false, $getAllResults, false) ? false : true; // Check if there is failed on all envelopes
      $vendor->total_score = $vendor->final_result ? $totalScore : null;
      $vendor->winner = $winner ? $winner->winner_id : 0;
      return $vendor;
    });

    // Add Financial Total Amount
    if ($bidding->financial) {
      $vendors = $vendors->map(function ($vendor) use ($bidding) {
        $vendorFinancials = $bidding->financialVendors->where('vendor_id', $vendor->id);
        $totalAmount = 0;
        foreach ($vendorFinancials as $financial) {
          $finacnialData = $bidding->financials->where('id', $financial->financial_id)->first();
          $quantity = $finacnialData ? $finacnialData->pivot->quantity : 0;
          $price = $financial ? $financial->price : 0;
          $otherFees = $financial ? $financial->other_fees : 0;
          $totalAmount += ($price + $otherFees) * $quantity;
        }
        $vendor->total_amount = $totalAmount;
        return $vendor;
      });
    }

    // Sort and assign ranks to vendors
    $sort = $bidding->scrap || $bidding->score_method == 'Rating' ?
      $vendors->sortByDesc(function ($vendor) {
        return is_null($vendor->total_score) ? -INF : $vendor->total_score;
      }) :
      $vendors->sortBy(function ($vendor) {
        return is_null($vendor->total_score) ? INF : $vendor->total_score;
      });

    // $sort = $bidding->scrap ? $vendors->sortByDesc('total_score') : $vendors->sortBy('total_score');
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

    if (!$bidding->scrap && $bidding->score_method == "Rating") {
      // Check if there is more than 1 on rank 1
      $firstRankCount = $vendors->where('rank', 1)->count();
      if ($firstRankCount > 1) {
        $groupedVendors = $vendors->groupBy('rank');

        $sortedVendors = collect();

        foreach ($groupedVendors as $rank => $group) {
          if ($group->count() > 1) {
            $sortedGroup = $group->sortBy(function ($vendor) use ($bidding) {
              $totalAmount = 0;
              foreach ($bidding->financials as $financial) {
                $financialVendor = $financial->financialVendors
                  ->where('vendor_id', $vendor->id)
                  ->where('bidding_id', $bidding->id)
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

  public function reviewModal($bidId)
  {
    $this->vendorResults = [];
    $this->selectedBidReview = $this->getBiddings()->where('id', $bidId)->first();

    $allEnvelopes = [
      'eligibility' => (bool) $this->selectedBidReview->eligibility,
      'technical' => (bool) $this->selectedBidReview->technical,
      'financial' => (bool) $this->selectedBidReview->financial,
    ];
    $firstEnvelope = array_search(true, $allEnvelopes, true);
    $this->envelopes = array_keys(array_filter($allEnvelopes, function ($value) {
      return $value === true;
    }));

    $this->vendorResults = $this->getVendors($this->selectedBidReview);
    // dd($this->vendorResults);
    $this->remarks = $this->selectedBidReview->winnerApproval ? $this->selectedBidReview->winnerApproval->remarks : null;

    $this->dispatch('openReviewModal');
  }

  public function closeReviewModal()
  {
    $this->envelopes = [];
    $this->vendorResults = [];
    $this->dispatch('closeReviewModal');
  }

  public function acceptModal($bidId)
  {
    $this->bidSelected = $this->getBiddings()->where('id', $bidId)->first();
    $this->acceptResult = $this->bidSelected->winnerApproval;
    $this->selectedWinner = $this->acceptResult->winnerVendor;
    $this->dispatch('openAcceptModal');
  }

  public function closeAcceptModal()
  {
    $this->dispatch('closeAcceptModal');
  }

  public function awardWinner()
  {
    $allVendors = $this->bidSelected->vendors()->wherePivot('status', '!=', 'Declined')->get();
    if (Auth::user()->role->id == 4) {

      $this->acceptResult->update([
        'approver' => true,
        'approval_date' => Carbon::now(),
        'approver_id' => Auth::user()->id,
      ]);

      $approvers = Role::findOrFail(5)->users;
      $vendorWinner = $this->bidSelected->winnerApproval->winnerVendor->name;
      $approverId = Auth::user()->role->id;

      $approverEmails = $approvers->pluck('email')->toArray();

      if ($approverEmails) {
        Mail::to($approverEmails)->send(new BidApprovalAPproved($vendorWinner, $this->bidSelected, $approverId));
      }


      $this->dispatch('closeAcceptModal');
      $this->dispatch('success-message', ['message' => 'The project has been approved and will now proceed to the final approver.']);


    } else if (Auth::user()->role->id == 5) {
      $this->acceptResult->update([
        'final_approver' => true,
        'final_approval_date' => Carbon::now(),
        'final_approver_id' => Auth::user()->id,
      ]);

      $this->bidSelected->update([
        'status' => 'Approved',
      ]);

      $winningBidder = $this->bidSelected->winnerApproval->winnerVendor->name;
      $approverId = Auth::user()->role->id;
      $approverEmails = Role::whereIn('id', [3, 4])->with('users')->get()->pluck('users.*.email')->flatten()->toArray();

      if ($approverEmails) {
        Mail::to($approverEmails)->send(new BidApprovalAPproved($winningBidder, $this->bidSelected, $approverId));
      }

      $this->dispatch('closeAcceptModal');
      $this->dispatch('success-message', ['message' => 'Congratulations! The project has been approved and is now complete.']);
    } else {
      $this->dispatch('closeAcceptModal');
      abort(403, 'Unauthorized');
    }
  }

  public function rejectModal($bidId)
  {
    $bidSelected = $this->getBiddings()->where('id', $bidId)->first();
    $this->rejectResult = $bidSelected->winnerApproval;
    $this->rejectedWinner = $this->rejectResult->winnerVendor;

    $this->dispatch('openRejectModal');
  }
  public function closeRejectModal()
  {
    $this->resetValidation();
    $this->dispatch('closeRejectModal');

  }
  public function rejectWinner()
  {

    if (Auth::user()->role->id == 4) {
      $this->validate([
        'rejectRemarks' => 'required',
      ], [
        'rejectRemarks.required' => 'The remarks field is required.'
      ]);

      $this->rejectResult->update([
        'approver' => false,
        'final_approver' => false,
        'prev_winner' => $this->rejectedWinner->id,
        'remarks' => $this->rejectRemarks,
      ]);

      $this->rejectResult->bid->update([
        'status' => 'Under Evaluation',
      ]);

      $winnerApproval = $this->rejectResult->bid->winnerApproval;
      $winningBidder = $winnerApproval ? $winnerApproval->winnerVendor->name : null;
      $approvers = Role::findOrFail(5)->users;
      $approverEmails = $approvers->pluck('email')->toArray();

      if ($approverEmails) {
        Mail::to($approverEmails)->send(new BidApprovalReEvaluate($winningBidder, $this->rejectResult->bid));
      }

      $this->dispatch('closeRejectModal');
      $this->dispatch('success-message', ['message' => 'Project bid winner has been rejected.']);
    } else if (Auth::user()->role->id == 5) {
      $this->validate([
        'rejectRemarks' => 'required',
      ], [
        'rejectRemarks.required' => 'The remarks field is required.'
      ]);

      $this->rejectResult->update([
        'approver' => false,
        'final_approver' => false,
        'prev_winner' => $this->rejectedWinner->id,
        'remarks' => $this->rejectRemarks,
      ]);

      $this->rejectResult->bid->update([
        'status' => 'Under Evaluation',
      ]);

      $winnerApproval = $this->rejectResult->bid->winnerApproval;
      $winningBidder = $winnerApproval ? $winnerApproval->winnerVendor->name : null;

      $approverEmails = Role::whereIn('id', [3, 4])->with('users')->get()->pluck('users.*.email')->flatten()->toArray();


      if ($approverEmails) {
        Mail::to($approverEmails)->send(new BidApprovalReEvaluate($winningBidder, $this->rejectResult->bid));
      }

      $this->dispatch('closeRejectModal');
      $this->dispatch('success-message', ['message' => 'Project bid winner has been rejected.']);
    } else {
      $this->dispatch('closeAcceptModal');
      abort(403, 'Unauthorized');
    }

    // return redirect()
    //   ->route('approval')
    //   ->with('success', 'Project bid winner has been rejected.');
  }

  public function printReport()
  {
    
    $vendors = $this->getVendors($this->selectedBidReview);
    $rankWithData = AllResultsReport::getAllResults($this->selectedBidReview->id, $vendors);
    $this->dispatch('closeReviewModal');
    $this->dispatch('openReportModal', $rankWithData, 'final', $this->selectedBidReview->id);
  }

  public function getVendorTotalAmount($bidding, $vendorId)
  {
    $financials = $bidding->financials;
    $totalAmount = 0;
    foreach ($financials as $financial) {
      $vendorResult = $financial->financialVendors->where('vendor_id', $vendorId)->where('bidding_id', $bidding->id)->where('financial_id', $financial->id)->first();
      $price = $vendorResult ? $vendorResult->price : 0;
      $otherFees = $vendorResult ? $vendorResult->other_fees : 0;
      $totalAmount += ($price + $otherFees) * $financial->pivot->quantity;
    }
    return $totalAmount;
  }

  public function reviewVendorModal($vendorId)
  {
    $this->vendorResults = $this->getVendors($this->selectedBidReview);
    $this->dispatch('reviewModal', $this->selectedBidReview->id, $vendorId);
  }

  public function bidPackage()
  {
    $vendors = $this->getVendors($this->selectedBidReview);
    $rankWithData = AllResultsReport::getAllResults($this->selectedBidReview->id, $vendors);

    $this->dispatch('closeReviewModal');
    $this->dispatch('bidPackageModal', $this->selectedBidReview->id, $rankWithData);
  }
  public function render()
  {
    if (!$this->biddingLists) {
      $this->biddingLists = $this->getBiddings();
    }
    return view('livewire.admin.approval.approval', [
      'biddings' => $this->biddingLists->paginate(10)
    ]);
  }
}
