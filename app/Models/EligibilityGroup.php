<?php

namespace App\Models;

use App\Models\Eligibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EligibilityGroup extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'description',
      'crtd_user',
      'upd_user',
  ];

  public function eligibilities()
  {
    return $this->belongsToMany(Eligibility::class, 'eligibilities_group_pivot','group_id', 'eligibility_id');
  }
}
