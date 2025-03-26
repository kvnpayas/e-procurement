<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRecord extends Model
{
  use HasFactory;

  protected $fillable = [
    'inventory_id',
    'description',
    'class_id',
    'receipt_qty',
    'unit_cost',
    'ext_cost',
    'trans_date',
  ];
}
