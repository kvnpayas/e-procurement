<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialGroup extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'description',
      'crtd_user',
      'upd_user',
  ];

  public function financials()
  {
    return $this->belongsToMany(Financial::class, 'financials_group_pivot','group_id', 'financial_id');
  }
}
