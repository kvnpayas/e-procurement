<?php

namespace App\Livewire\Bidding;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminProtestNotification;

class BiddingResults extends Component
{
  use WithPagination;
  protected $bidLists;
  public $bidFile, $vendorStatus, $search, $orderBy = 'id', $sort = 'desc',
  $messageAction = '';
  public $protestBid, $protestDeadline, $protestMessage, $paragraphs;
  public $viewProtestBid, $protestModel, $protestVendorMessage, $viewVendorProtest, $viewVendorStatus;

  public function getProjectbid()
  {
    $user = Auth::user();
    return $user->biddings()->withPivot('status')
      ->wherePivotIn('status', ['Bid Failure', 'Winning Bidder', 'Lost', 'Awarded', 'Unpublished Bididng', 'Unsuccessful Bidding'])
      ->orderBy($this->orderBy, $this->sort);
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

  public function protestDeadlineDate($date)
  {
    $protestDate = Carbon::parse($date);
    $protestDate->addDays(3);
    $protestDate->setTime(17, 0, 0);
    return $protestDate;
  }
  public function protestDate($id)
  {
    $this->protestBid = $this->getProjectbid()->where('project_biddings.id', $id)->first();
    $date = $this->protestBid->winnerApproval->awarded_date;
    $protestDate = Carbon::parse($date);
    $protestDate->addDays(3);
    $protestDate->setTime(17, 0, 0);
    $this->protestDeadline = $protestDate;
    $this->dispatch('openProtestDateModal');
  }
  public function closeProtestDateModal()
  {
    $this->protestMessage = null;
    $this->dispatch('closeProtestDateModal');
  }

  public function nextProcess()
  {
    $this->validate([
      'protestMessage' => 'required'
    ]);
    $this->paragraphs = array_filter(explode("\n", $this->protestMessage), fn($line) => trim($line) !== '');
    // $this->paragraphs = implode('', array_map(fn($line) => '<p>' . htmlspecialchars($line) . '</p>', $paragraphsArray));
    $this->protestMessage = null;
    $this->dispatch('closeProtestDateModal');
    $this->dispatch('openPreviewProtestModal');
  }

  public function closePreviewProtestModal()
  {
    $this->paragraphs = null;
    $this->dispatch('closePreviewProtestModal');
  }

  public function fileProtest()
  {
    $messages = implode('"\n"', $this->paragraphs);
    $protest = $this->protestBid->protest;
    if (!$protest) {
      $createdProtest = $this->protestBid->protest()->create([
        'bidding_id' => $this->protestBid->id,
        'winning_bidder_id' => $this->protestBid->winnerApproval->winner_id,
        'protest_deadline_date' => $this->protestDeadline,
        'status' => 'Pending',
      ]);
      $createdProtest->vendors()->attach(Auth::user()->id, [
        'protest_message' => $messages,
        'status' => 'Pending'
      ]);
    } else {
      $protestVendorExists = $protest->vendors->where('id', Auth::user()->id)->first();
      if ($protestVendorExists) {
        $protest->vendors()->updateExistingPivot(Auth::user()->id, [
          'protest_message' => $messages,
          'status' => 'Pending'
        ]);
      } else {
        $protest->vendors()->attach(Auth::user()->id, [
          'protest_message' => $messages,
          'status' => 'Pending'
        ]);
      }

      $protest->update(['status' => 'Pending', 'winning_bidder_id' => $this->protestBid->winnerApproval->winner_id,]);
    }

    $bacEmails = User::where('role_id', 4)->get()->pluck('email')->toArray();
    $data = [
      'vendor_name' => Auth::user()->name,
      'bid_id' => $this->protestBid->id,
      'bid_title' => $this->protestBid->title,
      'messages' => $this->paragraphs,
    ];
    Mail::to($bacEmails)->send(new AdminProtestNotification($data));

    $this->dispatch('closePreviewProtestModal');
    $this->dispatch('success-message', ['message' => 'The protest was successfully filed!']);
    // return redirect()
    //   ->route('bid-results')
    //   ->with('success', 'The protest was successfully filed!');
  }

  public function viewProtest($bidId)
  {
    $this->viewProtestBid = $this->getProjectbid()->where('project_biddings.id', $bidId)->first();
    $this->protestModel = $this->viewProtestBid->protest;
    $this->viewVendorProtest = $this->protestModel->vendors->where('pivot.vendor_id', Auth::user()->id)->first();
    $this->viewVendorStatus = $this->viewVendorProtest ? $this->viewVendorProtest->pivot->status : null;
    ;
    $this->protestVendorMessage = $this->viewVendorProtest ? explode('"\n"', $this->viewVendorProtest->pivot->protest_message) : null;
    $this->dispatch('openProtestViewModal');
  }
  public function closeProtestViewModal()
  {
    $this->dispatch('closeProtestViewModal');
  }
  public function render()
  {
    if (!$this->bidLists) {
      $this->bidLists = $this->getProjectbid();
    }
    return view('livewire.bidding.bidding-results', [
      'biddings' => $this->bidLists->paginate(10)
    ]);
  }
}
