<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EligibilityResult extends Model
{
    use HasFactory;
    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'result',
      'remarks',
      'user_id',
    ];

    public function vendors()
    {
      return $this->belongsTo(User::class, 'vendor_id');
    }
}
