<?php

namespace App\Models;

use App\Models\VendorEnvelopeFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechnicalVendor extends Model
{
    use HasFactory;

    protected $fillable = [
      'bidding_id',
      'vendor_id',
      'technical_id',
      'answer',
      'admin_answer',
      'admin_user',
    ];

    public function vendorAttachments($biddingId, $technicalId)
    {
      return $this->hasMany(VendorEnvelopeFile::class, 'vendor_id', 'vendor_id')->where('envelope', 'technical')->where('bidding_id', $biddingId)->where('envelope_id', $technicalId);
    }
}
