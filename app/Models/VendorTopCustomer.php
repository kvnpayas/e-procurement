<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTopCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
      'vendor_id',
      'company_name',
      'address',
      'contact_person',
      'phone_number',
      'mobile_number',
      'email',
    ];
}
