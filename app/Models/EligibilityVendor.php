<?php

namespace App\Models;

use App\Models\EligibilityDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EligibilityVendor extends Model
{
    use HasFactory;
    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'eligibility_id',
      'eligibility_detail_id',
      'response',
      'admin_response',
    ];

    public function details()
    {
      return $this->belongsTo(EligibilityDetails::class, 'eligibility_detail_id');
    }

    public function vendorAttachments($biddingId, $eligibilitylId)
    {
      return $this->hasMany(VendorEnvelopeFile::class, 'vendor_id', 'vendor_id')->where('envelope', 'eligibility')->where('bidding_id', $biddingId)->where('envelope_id', $eligibilitylId);
    }
}
