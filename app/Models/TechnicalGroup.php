<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalGroup extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'description',
      'crtd_user',
      'upd_user',
  ];

  public function technicals()
  {
    return $this->belongsToMany(Technical::class, 'technicals_group_pivot','group_id', 'technical_id');
  }
}
