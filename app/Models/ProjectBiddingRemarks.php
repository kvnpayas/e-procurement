<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBiddingRemarks extends Model
{
    use HasFactory;

    protected $fillable = [
      'bidding_id',
      'envelope',
      'remarks',
      'crtd_user',
      'upd_user'
    ];
}
