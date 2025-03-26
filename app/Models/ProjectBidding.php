<?php

namespace App\Models;

use App\Models\User;
use App\Models\Financial;
use App\Models\Technical;
use App\Models\Eligibility;
use App\Models\ProjectBidFile;
use App\Models\FinancialResult;
use App\Models\FinancialVendor;
use App\Models\ProjectBidAward;
use App\Models\TechnicalResult;
use App\Models\ProjectbidResult;
use App\Models\ProjectbidProtest;
use App\Models\ProjectbidApproval;
use App\Models\ProjectBidBulletin;
use App\Models\ProjectBiddingRemarks;
use App\Models\ProjectBidVendorStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectBidEvaluationProgress;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectBidding extends Model
{
  use HasFactory;

  protected $fillable = [
    'project_id',
    'budget_id',
    'icss_project_id',
    'title',
    'status',
    'type',
    'instruction_details',
    'attachment',
    'eligibility',
    'technical',
    'financial',
    'invited_vendor',
    'start_date',
    'deadline_date',
    'extend_date',
    'hold_date',
    'reserved_price',
    'reflect_price',
    'reserved_price_switch',
    'score_method',
    'scrap',
    'extend_count',
    'crtd_user',
    'upd_user',
  ];

  public function created_user()
  {
    return $this->belongsTo(User::class, 'crtd_user');
  }

  public function updated_user()
  {
    return $this->belongsTo(User::class, 'upd_user');
  }
  public function weights()
  {
    return $this->hasMany(ProjectBiddingWeight::class, 'bidding_id');
  }

  // Bidding ENVELOPES
  public function eligibilities()
  {
    return $this->belongsToMany(Eligibility::class, 'projectbid_eligibilities', 'bidding_id', 'eligibility_id')
      ->withPivot('crtd_user', 'upd_user', 'remarks')
      ->withTimestamps();
  }

  public function eligibilityVendors()
  {
    return $this->hasMany(EligibilityVendor::class, 'bidding_id');
  }

  public function technicals()
  {
    return $this->belongsToMany(Technical::class, 'projectbid_technicals', 'bidding_id', 'technical_id')
      ->withPivot('crtd_user', 'upd_user', 'weight', 'remarks')
      ->withTimestamps();
  }

  public function technicalVendors()
  {
    return $this->hasMany(TechnicalVendor::class, 'bidding_id');
  }

  public function financials()
  {
    return $this->belongsToMany(Financial::class, 'projectbid_financials', 'bidding_id', 'financial_id')
      ->withPivot('crtd_user', 'upd_user', 'bid_price', 'quantity', 'remarks')
      ->withTimestamps();
  }

  public function financialVendors()
  {
    return $this->hasMany(FinancialVendor::class, 'bidding_id');
  }


  // Bidding Vendor
  public function vendors()
  {
    return $this->belongsToMany(User::class, 'projectbid_vendors', 'bidding_id', 'vendor_id')
      ->withPivot('status', 'confirm', 'response_date', 'crtd_user', 'upd_user');
  }

  public function bidEnvelopeStatus()
  {
    return $this->hasMany(ProjectbidEnvelopeStatus::class, 'bidding_id');
  }
  public function bidVendorStatus()
  {
    return $this->hasMany(ProjectBidVendorStatus::class, 'bidding_id');
  }
  public function progress()
  {
    return $this->hasOne(ProjectBidEvaluationProgress::class, 'bidding_id');
  }
  public function bulletins()
  {
    return $this->hasMany(ProjectBidBulletin::class, 'bidding_id');
  }

  // Envelope Results
  public function eligibilityResult()
  {
    return $this->hasMany(EligibilityResult::class, 'bidding_id');
  }
  public function technicalResult()
  {
    return $this->hasMany(TechnicalResult::class, 'bidding_id');
  }

  public function financialResult()
  {
    return $this->hasMany(FinancialResult::class, 'bidding_id');
  }
  public function finalResult()
  {
    return $this->hasMany(ProjectbidResult::class, 'bidding_id');
  }

  public function winnerApproval()
  {
    return $this->hasOne(ProjectbidApproval::class, 'bidding_id');
  }
  // Envelope Results

  public function protest()
  {
    return $this->hasOne(ProjectbidProtest::class, 'bidding_id');
  }

  public function envelopeRemarks()
  {
    return $this->hasMany(ProjectBiddingRemarks::class, 'bidding_id');
  }

  public function financialFiles()
  {
    return $this->hasMany(VendorEnvelopeFile::class, 'bidding_id')->where('envelope', 'financial');
  }

  public function projectBidFiles()
  {
    return $this->hasMany(ProjectBidFile::class, 'project_id');
  }

  public function bidAward()
  {
    return $this->hasOne(ProjectBidAward::class, 'bidding_id');
  }

  public function bidAttachmentEnvelopes($envelope)
  {
    return $this->hasMany(VendorEnvelopeFile::class, 'bidding_id')->where('envelope', $envelope);
  }

  public function fileAttachmentsStatus($envelope)
  {
    return $this->hasMany(ProjectBidAttachmentStatus::class, 'bidding_id')->where('envelope', $envelope);
  }
}