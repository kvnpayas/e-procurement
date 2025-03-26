<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBidAttachmentStatus extends Model
{
  use HasFactory;

  protected $fillable = [
    'bidding_id',
    'file_id',
    'vendor_id',
    'validated_by',
    'envelope',
    'validated_date',
  ];
}