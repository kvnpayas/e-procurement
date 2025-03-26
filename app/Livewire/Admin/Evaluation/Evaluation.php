<?php

namespace App\Livewire\Admin\Evaluation;

use App\Models\Role;
use Livewire\Component;
use App\Mail\BidApproval;
use App\Models\ProjectBidding;
use Illuminate\Support\Carbon;
use App\Mail\BidApprovalAPproved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Evaluation extends Component
{
  public $bidding;
  public $envelopes, $currentEnvelope, $step, $final, $bidFailure, $livewireEnvelope;
  public $vendorResults, $tempWinner, $remarks, $rejectVendor, $rejectRemarks;
  public $backStep;
  public $protest, $prevWinningBidder, $vendorsProtest;
  public $proceedButton = false;

  protected $listeners = ['checkFileAttachmentStatus'];

  public function mount($biddingId)
  {
    $this->bidding = ProjectBidding::findOrFail($biddingId);
    $allEnvelopes = [
      'eligibility' => (bool) $this->bidding->eligibility,
      'technical' => (bool) $this->bidding->technical,
      'financial' => (bool) $this->bidding->financial,
    ];
    $data = [];
    $assignStep = 1;
    $filteredArray = array_keys(array_filter($allEnvelopes, function ($value, $envelope) use (&$data, &$assignStep) {
      if ($value === true) {
        $data[$envelope] = $assignStep;
        $assignStep++;
      }
      return $value;
    }, ARRAY_FILTER_USE_BOTH));

    $this->envelopes = $data;
    $this->step = $this->bidding->progress->step;
    $this->final = $this->step == count($this->envelopes) + 1 ? true : false;
    // dd($this->final);
    $this->currentEnvelope = array_search($this->step, $this->envelopes);
    $this->livewireEnvelope = $this->currentEnvelope;
    if ($this->step - 1 == count($this->envelopes)) {
      $resultArray = $this->bidding->finalResult->pluck('result')->toArray();
      if (!in_array(true, array_map('boolval', $resultArray), true)) {
        $this->step = count($this->envelopes) + 1;
        $this->final = true;
        $this->bidFailure = true;
      }
    }
    $this->checkAttachmentsStatus();
  }
  public function openModal()
  {
    $this->dispatch('openProceedModal');
  }
  public function closeProceedModal()
  {
    $this->dispatch('closeProceedModal');
  }

  public function nextEnvelope()
  {
    sleep(2);
    $this->step = $this->step + 1;
    if (count($this->envelopes) + 1 == $this->step) {
      $this->final = true;
    } else {
      $this->livewireEnvelope = array_search($this->step, $this->envelopes);
    }
    $this->currentEnvelope = array_search($this->step - 1, $this->envelopes);

    $model = $this->currentEnvelope . 'Result';
    $resultArray = $this->bidding->{$model}->pluck('result')->toArray();
    if (!in_array(true, array_map('boolval', $resultArray), true)) {
      $this->step = count($this->envelopes) + 1;
      $this->final = true;
      $this->bidFailure = true;
    }
    $this->bidding->progress()->update([
      'step' => $this->step,
      'prev_envelope' => $this->currentEnvelope,
      $this->currentEnvelope . '_submit_user' => Auth::user()->id,
      $this->currentEnvelope . '_submit_date' => Carbon::now(),
    ]);
    $this->checkAttachmentsStatus();
    $this->dispatch('closeProceedModal');

  }

  public function selectWinnerModal()
  {
    $this->vendorResults = $this->bidding->finalResult->where('result', true)->sortBy('rank');
    $rankOne = $this->vendorResults->where('rank', 1);

    if (!$this->bidding->scrap && $this->bidding->score_method == "Rating") {
      if ($rankOne->count() > 1) {
        $this->vendorResults = $this->vendorResults->map(function ($vendor) {
          $vendorResponse = $this->bidding->financialVendors->where('vendor_id', $vendor->vendor_id);
          $totalAmount = 0;
          foreach ($vendorResponse as $response) {
            $quantity = $this->bidding->financials->where('id', $response->financial_id)->first()->pivot->quantity;
            $totalAmount += ($response->price + $response->other_fees) * $quantity;
          }
          $vendor->totalAmount = $totalAmount;
          return $vendor;
        });
        $groupedVendors = $this->vendorResults->groupBy('rank');

        $sortedVendors = collect();

        foreach ($groupedVendors as $rank => $group) {
          if ($group->count() > 1) {
            $sortedGroup = $group->sortBy('totalAmount');
            $sortedVendors = $sortedVendors->concat($sortedGroup);
          }
        }
        $this->vendorResults = $sortedVendors->values();
      }
    }

    $bidReject = $this->bidding->winnerApproval;
    $this->protest = $this->bidding->protest;
    if ($this->protest) {
      $this->prevWinningBidder = $this->protest->winningVendor;
      $vendorIds = $this->bidding->protest->vendors->where('pivot.status', 'Pending')->pluck('id')->toArray();
      $this->vendorsProtest = $this->bidding->vendors->whereIn('id', $vendorIds);
      $this->tempWinner = $this->vendorResults->where('vendor_id', $bidReject->winner_id)->first();
    } else {
      if ($bidReject) {
        $this->tempWinner = $this->vendorResults->where('vendor_id', $bidReject->prev_winner)->first();
        $this->rejectVendor = $this->tempWinner->vendor_id;
        $this->rejectRemarks = $bidReject ? $bidReject->remarks : null;
      } else {
        if ($rankOne->count() != 1) {
          $this->tempWinner = null;
        } else {
          $this->tempWinner = $rankOne ? $rankOne->first() : null;
        }
      }
    }

    $this->dispatch('openAwardModal');
  }
  public function closeAwardModal()
  {
    $this->resetValidation();
    $this->dispatch('closeAwardModal');
  }

  public function selectBidder($vendorId)
  {
    $selectedBidder = $this->vendorResults->where('vendor_id', $vendorId)->first();
    $this->tempWinner = $selectedBidder;
  }
  public function confirmWinnerBidder()
  {
    // Validate winner is null
    $this->validate([
      'tempWinner' => 'required',
    ], [
      'tempWinner' => 'Please select a winner for this bid.'
    ]);
    // Validate if winner is not rank 1 remarks is required
    $this->validate([
      'remarks' => $this->rejectVendor !== null || $this->tempWinner->rank != 1 ? 'required' : '',
    ]);

    $this->dispatch('closeAwardModal');

    $this->dispatch('openConfirmModal');
  }
  public function confirmWinner()
  {
    // Update or create record on project bid approval
    $winnerExist = $this->bidding->winnerApproval;
    $vendorWinner = $this->bidding->vendors()->wherePivotIn('status', ['Under Evaluation', 'Lost', 'Winning Bidder'])->wherePivot('vendor_id', $this->tempWinner->vendor_id)->first();
    if ($this->protest) {
      $winnerExist->update([
        'winner_id' => $this->tempWinner->vendor_id,
        'remarks' => $this->remarks ? $this->remarks : null,
        'approver' => Auth::user()->role->id != 4 ? false : true,
        'final_approver' => false,
        'awarded' => null,
      ]);
    } else {
      if ($winnerExist) {
        $winnerExist->update([
          'winner_id' => $this->tempWinner->vendor_id,
          'remarks' => $this->remarks ? $this->remarks : null,
          'approver' => Auth::user()->role->id != 4 ? false : true,
          'final_approver' => false,
          'awarded' => null,
        ]);
      } else {
        $this->bidding->winnerApproval()->create([
          'winner_id' => $this->tempWinner->vendor_id,
          'remarks' => $this->remarks ? $this->remarks : null,
          'approver' => Auth::user()->role->id != 4 ? false : true,
          'final_approver' => false,
        ]);
      }
    }

    // update the status of bid to Awarded
    $this->bidding->update(['status' => 'For Approval']);

    // Email Approvers
    if (Auth::user()->role->id == 4) {
      $approvers = Role::findOrFail(5)->users;

      $approverId = Auth::user()->role->id;

      $approverEmails = $approvers->pluck('email')->toArray();

      if ($approverEmails) {
        Mail::to($approverEmails)->send(new BidApprovalAPproved($vendorWinner->name, $this->bidding, $approverId));
      }

    } else {

      $approvers = Role::findOrFail(4)->users;

      $approverEmails = $approvers->pluck('email')->toArray();
      $data = [
        'bidding' => $this->bidding,
        'vendor' => $vendorWinner,
      ];
 
      if ($approverEmails) {
        Mail::to($approverEmails)->send(new BidApproval($data));
      }

    }

    $this->dispatch('closeConfirmModal');
    return redirect()
      ->route('project-bidding')
      ->with('success', 'Bid is complete!');
  }
  public function closeConfirmModal()
  {
    $this->dispatch('closeConfirmModal');
  }
  public function biddingFailure()
  {
    $vendors = $this->bidding->vendors;
    foreach ($vendors as $vendor) {
      $this->bidding->vendors()->updateExistingPivot($vendor->id, [
        'status' => 'Bid Failure',
      ]);
    }

    $this->bidding->update(['status' => 'Bid Failure']);

    return redirect()
      ->route('project-bidding')
      ->with('success', 'Biding is tagged as bid failure!');
  }

  public function warning($step)
  {
    $this->backStep = $step;
    $this->dispatch('openWarningModal');
  }
  public function closeWarningModal()
  {
    $this->dispatch('closeWarningModal');
  }
  public function stepBack()
  {
    if (count($this->envelopes) + 1 == $this->step) {
      $this->final = false;
      $this->step = $this->backStep;
    } else {
      $this->step = $this->backStep;
    }

    $this->livewireEnvelope = array_search($this->step, $this->envelopes);
    if ($this->backStep == 1) {
      $this->bidding->progress->update([
        'step' => $this->backStep,
        'prev_envelope' => NULL,
        'envelope_user' => Auth::user()->id,
      ]);
    } else {
      $prevEnvelope = array_search($this->backStep - 1, $this->envelopes);
      $this->bidding->progress->update([
        'step' => $this->backStep,
        'prev_envelope' => $prevEnvelope,
        'envelope_user' => Auth::user()->id,
      ]);
    }
    $this->checkAttachmentsStatus();
    $this->dispatch('closeWarningModal');
  }

  // Check File Attachment Status
  public function checkAttachmentsStatus()
  {
    // dd($this->livewireEnvelope);
    if ($this->livewireEnvelope) {
      $vendorIds = $this->bidding->vendors->whereIn('pivot.status', ['For Evaluation', 'Under Evaluation'])->pluck('id')->toArray();
      
      $currentEnvelopeFiles = $this->bidding->bidAttachmentEnvelopes($this->livewireEnvelope)->whereIn('vendor_id', $vendorIds)->get();

      $validatedFiles = $this->bidding->fileAttachmentsStatus($this->livewireEnvelope)->get();

      if ($currentEnvelopeFiles->count() == $validatedFiles->count()) {
        $this->proceedButton = true;
      } else {
        $this->proceedButton = false;
      }
    }
  }

  public function checkFileAttachmentStatus()
  {
    $this->checkAttachmentsStatus();
  }
  public function render()
  {
    return view('livewire.admin.evaluation.evaluation');
  }
}
