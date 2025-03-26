<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBidAward extends Model
{
  use HasFactory;
  protected $fillable = [
    'bidding_id',
    'winner_id',
    'rank',
    'award_date',
    'awarded_by',
  ];

  public function winnerVendor()
  {
    return $this->hasOne(User::class, 'id', 'winner_id');
  }

  public function awardedBy()
  {
    return $this->hasOne(User::class, 'id', 'awarded_by');
  }
}
