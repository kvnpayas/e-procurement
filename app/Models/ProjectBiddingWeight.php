<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBiddingWeight extends Model
{
    use HasFactory;

    protected $fillable = [
      'bidding_id',
      'envelope',
      'weight',
      'crtd_user',
      'upd_user',
    ];
}
