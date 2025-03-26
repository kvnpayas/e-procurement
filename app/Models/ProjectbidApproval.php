<?php

namespace App\Models;

use App\Models\ProjectBidding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectbidApproval extends Model
{
    use HasFactory;

    protected $fillable = [
      'bidding_id',
      'status',
      'winner_id',
      'prev_winner',
      'remarks',
      'approver',
      'final_approver',
      'approver_id',
      'final_approver_id',
      'approval_date',
      'final_approval_date',
      'awarded',
      'awarded_date',
    ];

    public function winnerVendor()
    {
      return $this->hasOne(User::class, 'id', 'winner_id');
    }

    public function prevWinner()
    {
      return $this->hasOne(User::class, 'id', 'prev_winner');
    }

    public function bid()
    {
      return $this->belongsTo(ProjectBidding::class, 'bidding_id');
    }

    public function approverUser()
    {
      return $this->hasOne(User::class, 'id', 'approver_id');
    }

    public function finalApproverUser()
    {
      return $this->hasOne(User::class, 'id', 'final_approver_id');
    }
    public function winnerRank($bid)
    {
      return $this->hasOne(ProjectbidResult::class, 'vendor_id', 'winner_id')->where('bidding_id', $bid);
    }

}
