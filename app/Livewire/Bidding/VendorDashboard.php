<?php

namespace App\Livewire\Bidding;

use App\Models\ProjectBidBulletin;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VendorDashboard extends Component
{
  public $vendor;
  public $biddings, $joinProjects, $submitProjects, $wonProjects, $lostProjects, $bulletins;
  public $showFullText = [], $tempLists;

  public function mount()
  {
    $this->vendor = Auth::user();
    $this->biddings = $this->vendor->biddings()->orderBy('updated_at', 'desc')->take(10)->get();
    $join = $this->vendor->biddings()->wherePivotIn('status', ['Joined'])->get()->pluck('id')->toArray();
    $notComplete = $this->vendor->vendorStatus->where('complete', false)->pluck('bidding_id')->toArray();
    $similarityCount = count(array_intersect($notComplete, $join));
    $this->joinProjects =  $similarityCount;

    // bulletins
    $projectId = $this->vendor->biddings->pluck('id')->toArray();
    $this->bulletins = ProjectBidBulletin::whereIn('bidding_id', $projectId)->get();
    // dd($this->bulletins);

    // $bidCompleteId = $this->vendor->biddings->whereIn('status', ['Bid Published', 'Bid Published (Extend)'])->pluck('id')->toArray();
    $bidCompleteId = $this->vendor->biddings()->where(function ($query) {
      $query->whereIn('project_biddings.status', ['Bid Published', 'For Evaluation', 'Under Evaluation', 'For Approval', 'Approved', 'Awarded','On Hold'])
        ->orWhere('project_biddings.status', 'like', 'Publication Extended%');
    })->get()->pluck('id')->toArray();

    $this->submitProjects = $this->vendor->bidStatus()->whereIn('bidding_id', $bidCompleteId)->where('complete', true)->get();
    $this->wonProjects = $this->vendor->biddings()->wherePivotIn('status', ['Winning Bidder'])->get();
    $this->lostProjects = $this->vendor->biddings()->wherePivotIn('status', ['Lost'])->get();

    $this->showTextInit();
  }
  public function showTextInit()
  {
    foreach ($this->bulletins as $bulletin) {
      $this->showFullText[$bulletin->id] = false;
    }
  }
  public function toggleText($id)
  {
    $this->showFullText[$id] = !$this->showFullText[$id];
  }
  public function render()
  {
    return view('livewire.bidding.vendor-dashboard');
  }
}
