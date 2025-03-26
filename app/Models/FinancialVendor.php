<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialVendor extends Model
{
    use HasFactory;

    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'financial_id',
      'price',
      'other_fees',
      'admin_price',
      'admin_fees',
      'admin_user',
    ];
    
}
