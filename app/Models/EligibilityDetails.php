<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EligibilityDetails extends Model
{
  use HasFactory;

  protected $fillable = [
    'eligibility_id',
    'field',
    'field_type',
    'status',
    'validate_date',
    'crtd_user',
    'upd_user',
  ];

  public function eligibility()
  {
    return $this->belongsTo(Eligibility::class);
  }
}
