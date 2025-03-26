<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialResult extends Model
{
    use HasFactory;
    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'result',
      'score',
      'remarks',
      'user_id',
    ];
}
