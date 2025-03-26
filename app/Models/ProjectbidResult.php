<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectbidResult extends Model
{
    use HasFactory;
    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'result',
      'score',
      'rank',
      'winner',
      'approved',
    ];

    public function vendor()
    {
      return $this->belongsTo(User::class);
    }
}
