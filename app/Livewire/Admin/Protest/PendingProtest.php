<?php

namespace App\Livewire\Admin\Protest;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Mail\BidProtestOnHold;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Mail;

class PendingProtest extends Component
{
  use WithPagination;
  protected $biddingLists;
  public $tableHeader;
  public $search, $orderBy = 'id', $sort = 'desc';
  public $selectedBidForAward, $selectedWinner, $acceptResult;
  public $selectedProtestBid, $protestVendors;
  public $holdProtestBid, $vendorResults, $vendorProtest = [];
  public $endProtestBid;

  public function mount()
  {
    $this->tableHeader = [
      'id' => 'Project Id',
      'title' => 'Title',
      'status' => 'Status',
      'protests_count' => 'Number of protest',
      'action' => 'Action',
    ];
  }

  public function getBiddings()
  {
    $bids = ProjectBidding::whereIn('status', ['Awarded', 'On Hold Due To Protest'])->whereHas('protest', function ($query) {
      $query->where('status', 'Pending');
    })->withCount('protest');
    return $bids;
  }

  public function protesModal($bidId)
  {
    $this->resetValidation();
    $this->vendorProtest = [];
    $this->selectedProtestBid = $this->getBiddings()->where('id', $bidId)->first();
    $this->protestVendors = $this->selectedProtestBid->protest->vendors->where('pivot.status', 'Pending');
    foreach ($this->protestVendors as $vendor) {
      $this->vendorProtest[$vendor->id] = false;
    }
    $this->dispatch('openProtestModal');
  }
  public function closeProtestModal()
  {
    $this->dispatch('closeProtestModal');
  }
  public function protestDeadlineDate($date)
  {
    $protestDate = Carbon::parse($date);
    $protestDate->addDays(3);
    $protestDate->setTime(17, 0, 0);
    return $protestDate;
  }

  public function holdBidModal($bidId)
  {
    $this->holdProtestBid = $this->getBiddings()->where('id', $bidId)->first();
    $this->vendorResults = array_filter($this->vendorProtest, function ($value) {
      return $value === true;
    });

    $this->validate([
      'vendorResults' => 'required'
    ],[
      'vendorResults.required' => 'Please select a vendor(s) before holding the bid.'
    ]);

    $this->dispatch('openContinueProtestModal');
    $this->dispatch('closeProtestModal');
  }

  public function closeContinueProtestModal()
  {
    $this->dispatch('closeContinueProtestModal');
  }

  public function continueProtest()
  {
    $bidProtests = $this->holdProtestBid->protest;
    $ids = array_keys($this->vendorResults);

    foreach($bidProtests->vendors as $vendor){
      if(!in_array($vendor->id, $ids)){
        $bidProtests->vendors()->updateExistingPivot($vendor->id, [
          'status' => 'Cancelled',
        ]);
      }
    }

    $this->holdProtestBid->update(['status' => 'On Hold Due To Protest']);

    $notifVendor = $bidProtests->vendors ;
    $notifVendor->push($this->holdProtestBid->winnerApproval->winnerVendor);
    foreach($notifVendor as $vend){
      Mail::to($vend->email)->send(new BidProtestOnHold($vend,  $this->holdProtestBid));
    }

    $this->dispatch('closeContinueProtestModal');
    $this->dispatch('success-message', ['message' => 'Bid is put on hold for re-evaluation.']);
    // return redirect()
    //   ->route('protest')
    //   ->with('success', 'Bid is put on hold for re-evaluation.');
  }

  public function endProtestModal($bidId)
  {
    $this->endProtestBid = $this->getBiddings()->where('id', $bidId)->first();
    $this->dispatch('closeProtestModal');
    $this->dispatch('openEndProtestModal');
  }
  public function closeEndProtestModal()
  {
    $this->dispatch('closeEndProtestModal');
  }
  public function endProtest()
  {
    $bidProtest = $this->endProtestBid->protest;

    $bidProtest->update(['status' => 'Cancelled']);

    foreach ($bidProtest->vendors as $vendor) {
      $bidProtest->vendors()->updateExistingPivot($vendor->id, [
        'status' => 'Cancelled',
      ]);
    }

    return redirect()
      ->route('protest')
      ->with('success', 'The protest on this bid has been cancelled.');
  }
  public function render()
  {
    if (!$this->biddingLists) {
      $this->biddingLists = $this->getBiddings();
    }
    return view('livewire.admin.protest.pending-protest', [
      'biddings' => $this->biddingLists->paginate(10)
    ]);
  }
}
