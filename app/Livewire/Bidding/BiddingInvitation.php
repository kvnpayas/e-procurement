<?php

namespace App\Livewire\Bidding;

use Livewire\Component;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BiddingInvitation extends Component
{
  use WithPagination;
  protected $biddingLists;
  public $biddingTitle = '', $bids;
  public $biddingId;
  public $message = '';
  public $bidFile, $bidFileAttachment, $search, $orderBy = 'id', $sort = 'desc';
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
    $now = Carbon::now();

    $user = Auth::user();

    return $user->biddings()
      ->where(function ($query) use ($now) {
        $query->where(function ($query) use ($now) {
          $query->whereNotNull('extend_date')
            ->where('extend_date', '>=', $now);
        })->orWhere(function ($query) use ($now) {
          $query->whereNull('extend_date')
            ->where('deadline_date', '>=', $now);
        });
      })
      ->withPivot('status')
      ->wherePivotIn('status', ['Invited', 'Declined'])
      ->orderBy($this->orderBy, $this->sort);
    // return ProjectBidding::where('deadline_date', '>=', $now);
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
      $this->biddingLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->biddingLists = $model;
    }
  }

  public function toggleText($id)
  {
    $this->showFullText[$id] = !$this->showFullText[$id];
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

  // Accept Modal
  public function acceptBid($bid)
  {
    $this->biddingTitle = $bid['title'];
    $this->biddingId = $bid['id'];
    $this->dispatch('openAcceptModal');
  }
  public function closeAcceptModal()
  {
    $this->dispatch('closeAcceptModal');
  }

  // Accept Invitation update data via api
  public function accept()
  {
    $bid = $this->getProjectbid()->where('project_biddings.id', $this->biddingId)->first();
    $project = [
      'confirm' => true,
      'status' => 'Joined',
      'response_date' => date('Y-m-d H:s', strtotime(Carbon::now())),
    ];

    $bid->vendors()->updateExistingPivot(Auth::user()->id, $project);

    // return redirect()
    //   ->route('bid-invitation')
    //   ->with('success', 'You successfully joined the bid.');

    $this->biddingLists = $this->getProjectbid();
    $this->dispatch('closeAcceptModal');
    $this->dispatch('success-message', ['message' => 'You successfully joined the bid.']);
  }

  // Decline modal
  public function declineBid($bid)
  {
    $this->biddingTitle = $bid['title'];
    $this->biddingId = $bid['id'];
    $this->dispatch('openDeclineModal');
  }
  public function closeDeclineModal()
  {
    $this->dispatch('closeDeclineModal');
  }
  // Decline Invitation update data via api
  public function decline()
  {
    $bid = $this->getProjectbid()->where('project_biddings.id', $this->biddingId)->first();
    $project = [
      'confirm' => false,
      'status' => 'Declined',
      'response_date' => date('Y-m-d H:s', strtotime(Carbon::now())),
    ];

    $bid->vendors()->updateExistingPivot(Auth::user()->id, $project);

    // return redirect()
    //   ->route('bid-invitation')
    //   ->with('success', 'You successfully declined the bid.');

    $this->biddingLists = $this->getProjectbid();
    $this->dispatch('closeDeclineModal');
    $this->dispatch('success-message', ['message' => 'You successfully declined the bid.']);
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
  public function render()
  {
    if (!$this->biddingLists) {
      $this->biddingLists = $this->getProjectbid();
    }
    return view('livewire.bidding.bidding-invitation', [
      'biddings' => $this->biddingLists->paginate(10)
    ]);
  }
}
