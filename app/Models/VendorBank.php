<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBank extends Model
{
    use HasFactory;
    protected $fillable = [
      'vendor_id',
      'bank_name',
      'bank_address',
      'account_name',
      'account_number',
    ];
}
