<?php

namespace App\Models;

use App\Models\ProjectBidding;
use App\Models\EligibilityGroup;
use App\Models\EligibilityVendor;
use App\Models\EligibilityDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Eligibility extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'description',
      'status',
      'crtd_user',
      'upd_user',
    ];

    public function details()
    {
      return $this->hasMany(EligibilityDetails::class, 'eligibility_id')->where('status', 'Active');
    }

    public function groups()
  {
    return $this->belongsToMany(EligibilityGroup::class, 'eligibilities_group_pivot','eligibility_id', 'group_id');
  }

  public function biddings()
    {
      return $this->belongsToMany(ProjectBidding::class, 'projectbid_eligibilities', 'eligibility_id', 'bidding_id')
                  ->withPivot('crtd_user', 'upd_user', 'remarks')
                  ->withTimestamps();
    }

    public function eligibilityVendors()
    {
        return $this->hasMany(EligibilityVendor::class);
    }
    public function vendorFiles()
    {
        return $this->hasMany(VendorEnvelopeFile::class, 'envelope_id')->where('envelope', 'eligibility');
    }
}
