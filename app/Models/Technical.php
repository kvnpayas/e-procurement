<?php

namespace App\Models;

use App\Models\ProjectBidding;
use App\Models\TechnicalOption;
use App\Models\TechnicalVendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Technical extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'question',
    'question_type',
    'from',
    'to',
    'passing',
    'attachment',
    'status',
    'remarks',
    'crtd_user',
    'upd_user',
  ];

  public function options()
  {
    return $this->hasMany(TechnicalOption::class, 'technical_id');
  }

  public function biddings()
  {
    return $this->belongsToMany(ProjectBidding::class, 'projectbid_technicals', 'technical_id', 'bidding_id')
      ->withPivot('crtd_user', 'upd_user', 'weight', 'remarks')
      ->withTimestamps();
  }

  public function technicalVendors()
  {
    return $this->hasMany(TechnicalVendor::class);
  }

  public function groups()
  {
    return $this->belongsToMany(TechnicalGroup::class, 'technicals_group_pivot', 'technical_id', 'group_id');
  }

  public function vendorFiles()
  {
    return $this->hasMany(VendorEnvelopeFile::class, 'envelope_id')->where('envelope', 'technical');
  }

}
