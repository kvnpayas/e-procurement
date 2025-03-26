<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ProjectBidding;
use App\Models\ProjectbidApproval;

class Dashboard extends Component
{
  public $biddings, $bidApprovalCount, $bidRejectedCount, $approvedBid, $salesBids, $biddingProtests;

  public function mount()
  {
    $this->biddings = ProjectBidding::all();
    $this->bidApprovalCount = ProjectbidApproval::where('approver', NULL)->orWhere('final_approver', false)->get()->count();
    $this->bidRejectedCount = ProjectbidApproval::where('approver', false)->get()->count();
    $this->approvedBid =  ProjectBidding::where('status', 'Awarded')->limit(10)->get();
    // $this->approvedBid =  ProjectbidApproval::where('approver', true)->orderBy('updated_at', 'desc')->limit(10)->get();
    $this->salesBids =  $this->biddings->where('scrap', true)->where('status', '!=', 'Active')->sortBy('start_date');
    $this->biddingProtests = ProjectBidding::whereIn('status', ['Awarded', 'On Hold Due To Protest'])->whereHas('protest', function ($query) {
      $query->where('status', 'Pending');
    })->get();
  }
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
