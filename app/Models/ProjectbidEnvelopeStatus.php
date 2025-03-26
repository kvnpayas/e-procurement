<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectbidEnvelopeStatus extends Model
{
    use HasFactory;

    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'envelope',
      'status',
      'remarks',
    ];
}
