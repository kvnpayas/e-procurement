<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorNature extends Model
{
    use HasFactory;

    protected $fillable = [
      'vendor_id',
      'name',
      'others',
    ];
}
