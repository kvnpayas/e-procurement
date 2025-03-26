<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBidVendorStatus extends Model
{
    use HasFactory;

    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'complete',
      'submission_date',
    ];

    
}
