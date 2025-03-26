<?php

namespace App\Livewire\Admin\Bidding;

use Livewire\Component;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Mail\BidHoldNotification;
use App\Mail\BidCancelNotification;
use App\Mail\BidResumeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Bidding\VendorInvitation;
use App\Models\ProjectBidding as Biddings;

class ProjectBidding extends Component
{
  use WithPagination;
  protected $biddingLists;
  public $tableHeader;
  public $search, $selectedStatus, $orderBy = 'id', $sort = 'desc', $showAll = false, $firstEnvelope;
  public $selectedBid, $activeEnvelopes = [], $bidHold, $bidCancel, $bidDeadlineDate, $financialTotalAmount;
  public $evaluateBid;

  public function mount()
  {
    $this->tableHeader = [
      'project_id' => 'Project Id',
      'title' => 'Title',
      'type' => 'Type',
      'reserved_price' => 'Reserved Price',
      'envelopes' => 'Selected Envelopes',
      'invited_vendor' => 'Vendors',
      'status' => 'Status',
      'crtd_user' => 'Created By',
      'deadline_date' => 'Deadline Date',
      'action' => 'Action',
    ];
  }

  // Get all bid
  public function getBiddings()
  {

    if ($this->showAll) {
      $getBiddings = Biddings::query();
    } else {
      $getBiddings = Biddings::whereNotIn('status', ['Cancelled(Unpublished)', 'Bid Failure', 'Cancelled(Published)']);
    }

    if ($this->orderBy == 'deadline_date') {
      $getBiddings = $getBiddings->select('*', \DB::raw('COALESCE(extend_date, deadline_date) as new_deadline'))
        ->orderBy('new_deadline', $this->sort);
    } else {
      $getBiddings = $getBiddings->orderBy($this->orderBy, $this->sort);
    }

    if ($this->selectedStatus) {
      if ($this->selectedStatus == 'Publication Extended') {
        return $getBiddings->where("status", 'LIKE', $this->selectedStatus . '%');
      } else {
        return $getBiddings->where("status", $this->selectedStatus);
      }
    }
    return $getBiddings;
  }

  // Search Function
  public function updatedSearch($search)
  {
    $this->resetPage();
    $fields = [
      'project_id',
      'title',
      'status',
    ];

    $model = $this->getBiddings();
    if ($search) {
      $this->biddingLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->biddingLists = $model;
    }
  }

  public function updatedSelectedStatus()
  {
    $this->search = '';
    $this->resetPage();
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

  // Open envelopes routes
  public function envelopeRoute($id)
  {
    $currentBid = $this->getBiddings()->where('id', $id)->first();
    $envelopes = [
      'eligibility' => (bool) $currentBid->eligibility,
      'technical' => (bool) $currentBid->technical,
      'financial' => (bool) $currentBid->financial,
    ];
    $this->firstEnvelope = array_search(true, $envelopes, true);

    if ($this->firstEnvelope) {
      return redirect()
        ->route('project-bidding.' . $this->firstEnvelope . '-envelope', ['biddingId' => $currentBid->id]);
    }
  }

  // Modal Start Bid
  public function startBid($id)
  {
    $this->bidDeadlineDate = null;
    $this->selectedBid = $this->getBiddings()->where('id', $id)->first();
    $allEnvelopes = [
      'eligibilities' => $this->selectedBid->eligibility,
      'technicals' => $this->selectedBid->technical,
      'financials' => $this->selectedBid->financial,
    ];
    $this->bidDeadlineDate = $this->selectedBid->extend_date ? $this->selectedBid->extend_date : $this->selectedBid->deadline_date;
    $this->activeEnvelopes = array_keys(array_filter($allEnvelopes));

    foreach ($this->activeEnvelopes as $env) {
      if ($this->selectedBid->{$env}->count() == 0) {
        $this->addError('envRequirements', 'One of the envelope has no requirements!');
      } else {
        $this->resetValidation('envRequirements');
      }
    }


    if ($this->selectedBid->vendors->count() == 0) {
      $this->addError('envVendors', 'No vendors on this bid');
    } else {
      $this->resetValidation('envVendors');
    }

    if ($this->bidDeadlineDate < date('Y-m-d H:i')) {
      // $this->addError('bidDeadlineDate', 'The bid deadline date field must be a date after '.date('Y-m-d H:i').'.');
    } else {
      $this->resetValidation('bidDeadlineDate');
    }

    if ($allEnvelopes['financials']) {
      $this->financialTotalAmount = $this->selectedBid->financials->sum(function ($financial) {
        return $financial->pivot->bid_price * $financial->pivot->quantity;
      });
      if ($this->selectedBid->reserved_price_switch) {

        if ($this->selectedBid->reserved_price != $this->financialTotalAmount) {
          $this->addError('financialTotalAmount', 'The financial total amount must equal to reserved price.');
        } else {
          $this->resetValidation('financialTotalAmount');
        }
      } else {
        $this->resetValidation('financialTotalAmount');
      }
    }


    $this->dispatch('openStartBidModal');
  }

  // Start biding and update status
  public function startBidding()
  {
    $this->validate([
      'bidDeadlineDate' => 'after:' . date('Y-m-d H:i')
    ]);
    $data = [
      'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
      'status' => $this->selectedBid->status == 'On Hold' && $this->selectedBid->extend_date ? 'Publication Extended(' . $this->selectedBid->extend_count . ')' : 'Bid Published',
    ];
    $allEnvelopes = [
      'eligibility' => $this->selectedBid->eligibility,
      'technical' => $this->selectedBid->technical,
      'financial' => $this->selectedBid->financial,
    ];

    $envelopes = array_keys(array_filter($allEnvelopes));
    foreach ($this->selectedBid->vendors as $vendorStatus) {
      foreach ($envelopes as $envelope) {
        $envelopeStatusExists = $this->selectedBid->bidEnvelopeStatus->where('vendor_id', $vendorStatus->id)->where('envelope', $envelope)->first();
        if (!$envelopeStatusExists) {
          $this->selectedBid->bidEnvelopeStatus()->create([
            'vendor_id' => $vendorStatus->id,
            'envelope' => $envelope,
            'status' => false,
          ]);
        }
      }
      $vendorStatusExists = $this->selectedBid->bidVendorStatus->where('vendor_id', $vendorStatus->id)->first();
      if (!$vendorStatusExists) {
        $this->selectedBid->bidVendorStatus()->create([
          'vendor_id' => $vendorStatus->id,
          'complete' => false
        ]);
      }
    }

    $vendorData = [];
    if ($this->selectedBid->status == 'Active') {
      foreach ($this->selectedBid->vendors as $vendor) {
        $vendorData[$vendor->id] = ['status' => 'Invited'];
        Mail::to($vendor->email)->send(new VendorInvitation($this->selectedBid));
      }
      $this->selectedBid->vendors()->sync($vendorData);
    } else {
      foreach ($this->selectedBid->vendors as $vendor) {
        if ($vendor->pivot->status == 'On Hold') {
          $vendor->pivot->update(['status' => 'Joined']);
          Mail::to($vendor->email)->send(new BidResumeNotification($this->selectedBid, $vendor));
        }
      }
    }

    $this->selectedBid->update($data);
    $this->dispatch('closeStartBidModal');
    $this->dispatch('success-message', ['message' => 'Bidding for ' . $this->selectedBid->title . ' has been successful!']);
    // return redirect()->route('project-bidding')->with('success', 'Bidding for ' . $this->selectedBid->title . ' has been successful!');
  }

  // hold bid modal
  public function holdBid($id)
  {
    $this->bidHold = $this->getBiddings()->where('id', $id)->first();
    $this->dispatch('openHoldModal');
  }

  // Hold Bidding update Status bid and vendor
  public function holdBidding()
  {
    $this->bidHold->update([
      'status' => 'On Hold',
      'hold_date' => now(),
      'upd_user' => Auth::user()->id,
    ]);

    foreach ($this->bidHold->vendors as $vendor) {
      if ($vendor->pivot->status == 'Joined') {
        $vendor->pivot->update(['status' => 'On Hold']);
        Mail::to($vendor->email)->send(new BidHoldNotification($this->bidHold, $vendor));
      }
    }

    $this->dispatch('closeHoldModal');
    $this->dispatch('success-message', ['message' => 'Bidding for ' . $this->bidHold->title . ' has been hold.']);
    // return redirect()->route('project-bidding')->with('success', 'Bidding for ' . $this->bidHold->title . ' has been hold.');
  }

  // Cancel modal
  public function cancelBid($id)
  {
    $this->bidCancel = $this->getBiddings()->where('id', $id)->first();
    $this->dispatch('openCancelModal');
  }

  // Cancel bid update status bid and vendor
  public function cancelBidding()
  {
    $this->bidCancel->update(['status' => $this->bidCancel->status == 'On Hold' ? 'Cancelled(Published)' : 'Cancelled(Unpublished)']);

    foreach ($this->bidCancel->vendors as $vendor) {
      // if ($vendor->pivot->status == 'On Hold') {
      $vendor->pivot->update(['status' => 'Bid Cancelled']);
      Mail::to($vendor->email)->send(new BidCancelNotification($this->bidCancel, $vendor));
      // }
    }

    $this->dispatch('closeCancelModal');
    $this->dispatch('success-message', ['message' => 'Bidding for ' . $this->bidCancel->title . ' has been cancel.']);
    // return redirect()->route('project-bidding')->with('success', 'Bidding for ' . $this->bidCancel->title . ' has been cancel.');
  }

  public function evaluateBidModal($id)
  {
    $this->evaluateBid = $this->getBiddings()->where('id', $id)->first();
    $this->dispatch('openEvaluateModal');
  }
  public function evaluateBidding()
  {
    sleep(2);
    if ($this->evaluateBid->status == 'For Evaluation') {
      $this->evaluateBid->update(['status' => 'Under Evaluation']);

      foreach ($this->evaluateBid->vendors as $vendor) {
        if ($vendor->pivot->status == 'For Evaluation') {
          $vendor->pivot->status = 'Under Evaluation';
          $vendor->pivot->save();
        }
      }

      $this->evaluateBid->progress()->create([
        'step' => 1,
        'open_envelope_user' => Auth::user()->id,
        'envelope_user' => Auth::user()->id,
        'envelope_open_date' => Carbon::now(),
      ]);

    } else {
      $progress = $this->evaluateBid->progress;
      if ($progress) {
        $progress->update([
          'envelope_user' => Auth::user()->id,
        ]);
      }
    }


    return redirect()->route('project-bidding.evaluation', $this->evaluateBid->id);
  }

  public function downloadPdf($attachFile)
  {
    foreach ($this->activeEnvelopes as $env) {
      if ($this->selectedBid->{$env}->count() == 0) {
        $this->addError('envRequirements', 'One of the envelope has no requirements!');
      } else {
        $this->resetValidation('envRequirements');
      }
    }

    if ($this->selectedBid->vendors->count() == 0) {
      $this->addError('envVendors', 'No vendors on this bid');
    } else {
      $this->resetValidation('envVendors');
    }
    // $filePath = 'storage/project_bid/' . $attachFile;
    $filePath = storage_path('app/public/project_bid/' . $attachFile);
    return response()->download($filePath, $attachFile, [
      'Content-Type' => 'application/pdf',
    ]);
  }

  // CLOSE MODAL
  public function closeStartBidModal()
  {
    $this->dispatch('closeStartBidModal');
  }
  public function closeHoldModal()
  {
    $this->dispatch('closeHoldModal');
  }
  public function closeCancelModal()
  {
    $this->dispatch('closeCancelModal');
  }
  public function closeEvaluateModal()
  {
    $this->dispatch('closeEvaluateModal');
  }
  // CLOSE MODAL

  public function bulletin($id)
  {
    return redirect()
      ->route('project-bidding.bid-bulletin', $id);
  }

  public function results($id)
  {
    return redirect()
      ->route('project-bidding.bid-results', $id);
  }
  public function render()
  {
    if (!$this->biddingLists) {
      $this->biddingLists = $this->getBiddings();
    }
    return view('livewire.admin.bidding.project-bidding', [
      'biddings' => $this->biddingLists->paginate(10)
    ]);
  }
}
