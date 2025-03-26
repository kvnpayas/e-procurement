<?php

namespace App\Livewire\Admin\Awarding;

use Livewire\Component;
use App\Mail\ApprovalLost;
use App\Mail\ApprovalWinner;
use Livewire\WithPagination;
use App\Models\ProjectBidding;
use Illuminate\Support\Carbon;
use App\Mail\ProtestApprovalLost;
use App\Mail\ProtestApprovalWinner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Awarding extends Component
{
  use WithPagination;
  protected $biddingLists;
  public $tableHeader;
  public $search, $orderBy = 'project_id', $sort = 'desc';
  public $selectedBidForAward, $selectedWinner, $acceptResult;

  public function mount()
  {
    $this->tableHeader = [
      'project_id' => 'Project Id',
      'title' => 'Title',
      'type' => 'Type',
      'reserved_price' => 'Reserved Price',
      'winner' => 'Winner',
      'rank' => 'Rank',
      'action' => 'Action',
    ];
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

  public function getBiddings()
  {
    return ProjectBidding::where('status', 'Approved')->orderBy($this->orderBy, $this->sort);
  }
  public function awardModal($bidId)
  {
    $this->selectedBidForAward = $this->getBiddings()->where('id', $bidId)->first();
    $this->acceptResult = $this->selectedBidForAward->winnerApproval;
    $this->selectedWinner = $this->acceptResult->winnerVendor;
    $this->dispatch('openAwardModal');
  }

  public function closeAwardModal()
  {
    $this->dispatch('closeAwardModal');
  }

  public function awardWinner()
  {
    $allVendors = $this->selectedBidForAward->vendors()->wherePivot('status', '!=', 'Declined')->get();
    $notJoinVendors = $this->selectedBidForAward->vendors()->wherePivotIn('status', ['Declined', 'No Response'])->get();
    $protestData = $this->selectedBidForAward->protest;
    $this->acceptResult->update([
      'awarded' => true,
      'awarded_date' => Carbon::now()->format('Y-m-d H:i:s'),
    ]);

    $this->selectedBidForAward->update([
      'status' => 'Awarded',
    ]);

    // check if bidaward is existing
    $bidAward = $this->selectedBidForAward->bidAward;
    $vendorRank = $this->acceptResult->winnerRank($this->selectedBidForAward->id)->first();
    if ($bidAward) {
      $bidAward->update([
        'winner_id' => $this->acceptResult->winner_id,
        'rank' => $vendorRank ? $vendorRank->rank : null,
        'award_date' => Carbon::now(),
        'awarded_by' => Auth::user()->id,
      ]);
    } else {
      $this->selectedBidForAward->bidAward()->create([
        'winner_id' => $this->acceptResult->winner_id,
        'rank' => $vendorRank ? $vendorRank->rank : null,
        'award_date' => Carbon::now(),
        'awarded_by' => Auth::user()->id,
      ]);
    }

    if ($protestData) {

      $awardedBidder = $protestData->winningVendor;

      // get ids of particicpating protest vendor
      $vendorIds = $protestData->vendors->where('pivot.status', 'Pending')->pluck('pivot.vendor_id')->toArray();
      array_push($vendorIds, $awardedBidder->id);


      $allVendors = $this->selectedBidForAward->vendors()->whereIn('users.id', $vendorIds)->get();

      // Update status bid protest
      $protestData->update(['status' => 'Complete']);

      // Update status vendor protest
      foreach ($protestData->vendors as $vendor) {
        $protestData->vendors()->updateExistingPivot($vendor->id, [
          'status' => 'Complete',
        ]);
      }

      foreach ($allVendors as $vendor) {
        if ($vendor->id == $this->selectedWinner->id) {
          if ($vendor->id == $protestData->winning_bidder_id) {
            $message = 'We are pleased to inform you that after a thorough re-evaluation of the bids, your company has been selected and is still the winning bidder for (' . strtoupper($this->selectedBidForAward->title) . '). This decision follows a protest that was lodged and subsequently reviewed.';
          } else {
            $message = 'We are pleased to inform you that after a thorough re-evaluation of the bids, your company has been selected as the winning bidder for (' . strtoupper($this->selectedBidForAward->title) . '). This decision follows a protest that was lodged and subsequently reviewed.';
          }
          Mail::to($vendor->email)->send(new ProtestApprovalWinner($this->selectedBidForAward, $vendor->name, $message));
          $this->selectedBidForAward->vendors()->updateExistingPivot($vendor->id, ['status' => 'Winning Bidder']);
        } else {
          if ($vendor->id == $protestData->winning_bidder_id) {
            $message = 'We regret to inform you that following a re-evaluation process triggered by a protest, the original bid award has been revised. As a result, your company is no longer the winning bidder for (' . strtoupper($this->selectedBidForAward->title) . ').';
            // Mail::to($vendor->email)->send(new ApprovalLost($this->selectedBidForAward, $vendor->name, $this->selectedWinner->name));
          } else {
            $message = 'We have concluded the re-evaluation process that was initiated due to your protest. After careful consideration, we regret to inform you that your company is not the winning bidder for (' . strtoupper($this->selectedBidForAward->title) . ').';
          }
          Mail::to($vendor->email)->send(new ProtestApprovalLost($this->selectedBidForAward, $vendor->name, $message));
          $this->selectedBidForAward->vendors()->updateExistingPivot($vendor->id, ['status' => 'Lost']);
        }
      }

    } else {
      foreach ($allVendors as $vendor) {
        if ($vendor->id == $this->selectedWinner->id) {
          $this->selectedBidForAward->vendors()->updateExistingPivot($vendor->id, ['status' => 'Winning Bidder']);
          Mail::to($vendor->email)->send(new ApprovalWinner($this->selectedBidForAward, $vendor->name));
        } else {
          $this->selectedBidForAward->vendors()->updateExistingPivot($vendor->id, ['status' => 'Lost']);
          Mail::to($vendor->email)->send(new ApprovalLost($this->selectedBidForAward, $vendor->name, $this->selectedWinner->name));
        }
      }

      foreach ($notJoinVendors as $vendor) {
        $this->selectedBidForAward->vendors()->updateExistingPivot($vendor->id, ['status' => 'Awarded']);
      }

    }

    $this->dispatch('closeAwardModal');
    $this->dispatch('success-message', ['message' => 'Congratulations! The project has been awarded.']);
    // return redirect()
    //   ->route('awarding')
    //   ->with('success', 'Congratulations! A project bid has been awarded.');
  }

  public function render()
  {
    if (!$this->biddingLists) {
      $this->biddingLists = $this->getBiddings();
    }
    return view('livewire.admin.awarding.awarding', [
      'biddings' => $this->biddingLists->paginate(10)
    ]);
  }
}
