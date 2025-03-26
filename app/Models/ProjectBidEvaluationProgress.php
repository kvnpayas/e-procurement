<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBidEvaluationProgress extends Model
{
    use HasFactory;
    protected $fillable = [
      'bidding_id',
      'step',
      'prev_envelope',
      'open_envelope_user',
      'envelope_user',
      'eligibility_submit_date',
      'technical_submit_date',
      'financial_submit_date',
      'eligibility_submit_user',
      'technical_submit_user',
      'financial_submit_user',
      'envelope_open_date',
    ];

    public function open_user()
    {
      return $this->belongsTo(User::class, 'open_envelope_user');
    }

    public function eligibility_user()
    {
      return $this->belongsTo(User::class, 'eligibility_submit_user');
    }
    public function technical_user()
    {
      return $this->belongsTo(User::class, 'technical_submit_user');
    }
    public function financial_user()
    {
      return $this->belongsTo(User::class, 'financial_submit_user');
    }
    
}
