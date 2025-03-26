<?php

namespace App\Livewire\Bidding;

use App\Models\Role;
use Livewire\Component;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Mail\AdminCompleteVendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\ProjectBidVendorStatus;

class BiddingLists extends Component
{
  use WithPagination;
  protected $bidLists;
  public $bidFile, $bidFileAttachment, $vendorStatus, $search, $orderBy = 'id', $sort = 'desc',
  $messageAction = '';
  public $showFullText = [];

  public function mount()
  {
    $this->bids = $this->getProjectbid()->get();
    foreach ($this->bids as $bid) {
      $this->showFullText[$bid->id] = false;
    }
  }
  public function getProjectbid()
  {
    $user = Auth::user();
    return $user->biddings()->withPivot('status')
      ->wherePivotNotIn('status', ['invited', 'Bid Failure', 'Winning Bidder', 'Lost', 'Awarded', 'No Response', 'Unsuccessful Bidding', 'Unpublished Bididng'])
      ->where(function ($query) {
        $query->where('projectbid_vendors.status', '!=', 'Declined')
          ->orWhere(function ($subQuery) {
            $subQuery->where('projectbid_vendors.status', 'Declined')
              ->where('deadline_date', '<=', now()); // when vendors declined and deadline date has been met it will show it on the lists
          });
      })
      ->orderBy($this->orderBy, $this->sort);
  }

  public function getStatus()
  {
    return ProjectBidVendorStatus::where('vendor_id', Auth::user()->id)->get();
  }

  // Search Function
  public function updatedSearch($search)
  {
    $this->resetPage();
    $fields = [
      'project_biddings.id',
      'projectbid_vendors.status',
      'title',
    ];

    $model = $this->getProjectbid();
    if ($search) {
      $this->bidLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->bidLists = $model;
    }
  }

  // Filters and sorts
  public function selectedFilters($params)
  {
    if ($this->orderBy == $params) {
      $this->sort = $this->sort == 'asc' ? 'desc' : 'asc';
    } else {
      $this->orderBy = $params;
      $this->sort = 'desc';
    }
  }

  public function showFile($file)
  {
    // $this->bidFile = $this->getProjectbid()
    //   ->where('project_biddings.id', $id)
    //   ->first();
    $this->bidFile = $file;
    $this->bidFileAttachment = route('view-file', ['file' => $file, 'folder' => 'project_bid']);

    $this->dispatch('openFileModal');
  }

  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }
  public function tagComplete($biddingId)
  {
    $projectbid = $this->getProjectbid()->where('project_biddings.id', $biddingId)->first();

    $statusExists = $this->getStatus()->where('bidding_id', $biddingId)->first();
    if ($statusExists) {
      $statusExists->update([
        'vendor_id' => Auth::user()->id,
        'complete' => true,
        'submission_date' => now(),
      ]);
    }

    $approverEmails = Role::whereIn('id', [3, 4])->with('users')->get()->pluck('users.*.email')->flatten()->toArray();

    if ($approverEmails) {
      Mail::to($approverEmails)->send(new AdminCompleteVendor($projectbid, Auth::user()));
    }

    $this->vendorStatus = $this->getStatus();
    // // $this->biddings[$index]['complete'] = true;
    $this->messageAction = 'You successfully tag ' . $projectbid->title . ' bid as complete.';
    $this->dispatch('vendor-message');
  }

  public function opeEnvelopes($biddingId)
  {
    $currentBid = $this->getProjectbid()->where('project_biddings.id', $biddingId)->first();
    $envelopes = [
      'eligibility' => (bool) $currentBid->eligibility,
      'technical' => (bool) $currentBid->technical,
      'financial' => (bool) $currentBid->financial,
    ];
    $this->firstEnvelope = array_search(true, $envelopes, true);

    if ($this->firstEnvelope) {
      return redirect()
        ->route('bid-lists.' . $this->firstEnvelope . '-envelope', ['bid' => $currentBid->id]);
    }
  }
  public function openSummary($biddingId)
  {
    return redirect()->route('bid-lists.summary', $biddingId);
  }

  public function openBulletin($id)
  {
    return redirect()->route('bid-lists.bid-bulletin', $id);
  }
  public function toggleText($id)
  {
    $this->showFullText[$id] = !$this->showFullText[$id];
  }

  public function render()
  {

    if (!$this->vendorStatus) {
      $this->vendorStatus = $this->getStatus();
    }

    if (!$this->bidLists) {
      $this->bidLists = $this->getProjectbid();
    }
    return view('livewire.bidding.bidding-lists', [
      'biddings' => $this->bidLists->paginate(10)
    ]);
  }
}
