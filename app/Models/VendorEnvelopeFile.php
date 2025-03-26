<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorEnvelopeFile extends Model
{
    use HasFactory;
    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'envelope_id',
      'envelope',
      'file',
      'admin_file',
      'admin_user',
    ];
}
