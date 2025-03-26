<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Role;
use App\Mail\BidClosureAdmin;
use App\Mail\BidClosureVendor;
use App\Models\ProjectBidding;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UpdateBidAndVendorStatus extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:update-bid-and-vendor-status';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update the status of bid and vendors based on deadlines';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $bids = ProjectBidding::where(function ($query) {
      $query->where('status', 'Bid Published')
        ->orWhere('status', 'like', 'Publication Extended%');
    })->get();
    // $now = date('Y-m-d H:i', strtotime(now()));
    $now = Carbon::now();
    $count = 0;
    foreach ($bids as $bid) {
      $deadlinDate = $bid->extend_date ? Carbon::parse($bid->extend_date) : Carbon::parse($bid->deadline_date);
      $checkSubmitVendor = $bid->bidVendorStatus->where('complete', true);
      $vendors = $bid->vendors;
      if ($deadlinDate->lessThanOrEqualTo($now)) {
        $checkJoinedVendors = $bid->vendors->where('pivot.status', 'Joined');
        if ($checkJoinedVendors->isEmpty()) {
          $bid->update(['status' => 'Bid Failure']);
          foreach ($vendors as $vendor) {
            $bid->vendors()->updateExistingPivot($vendor->id, ['status' => 'Bid Failure']);
          }
        } else {

          $bid->update(['status' => 'For Evaluation']);
          $data = [];
          foreach ($checkSubmitVendor as $vendor) {
            $extractVendor = $vendors->where('id', $vendor->vendor_id)->first();
            $data[$vendor->vendor_id] = [
              'submission_date' => $vendor->submission_date,
              'vendor' => $extractVendor ? $extractVendor->name : '',
            ];
          }

          usort($data, function ($a, $b) {
            return strtotime($a['submission_date']) - strtotime($b['submission_date']);
          });

          $approverEmails = Role::whereIn('id', [3, 4])->with('users')->get()->pluck('users.*.email')->flatten()->toArray();

          if ($approverEmails) {
            Mail::to($approverEmails)->send(new BidClosureAdmin($data, $bid));
          }
        }

        // $vendors = $bid->vendors;
        foreach ($vendors as $vendor) {
          if (!$checkSubmitVendor->isEmpty()) {
            if ($vendor->pivot->status == 'Joined') {
              $vendorStatus = $vendor->vendorStatus ? $vendor->vendorStatus->where('bidding_id', $bid->id)->first() : '';
              if ($vendorStatus && $vendorStatus->complete) {
                $bid->vendors()->updateExistingPivot($vendor->id, ['status' => 'For Evaluation']);
              } else {
                $bid->vendors()->updateExistingPivot($vendor->id, ['status' => 'No Response']);
              }

            } elseif ($vendor->pivot->status == 'Invited') {
              $bid->vendors()->updateExistingPivot($vendor->id, ['status' => 'No Response']);
            }
          }
          Mail::to($vendor->email)->send(new BidClosureVendor($bid));
        }
        $count++;
      }
    }
    Log::info('Completed For Evaluation');
    $this->info($count . ' bids successfully updated!');
  }
}
