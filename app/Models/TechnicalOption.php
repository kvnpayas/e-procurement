<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalOption extends Model
{
    use HasFactory;

    protected $fillable = [
      'technical_id',
      'name',
      'score',
      'crtd_user',
      'upd_user',
    ];

    public function technical()
    {
      return $this->belongsTo(Technical::class);
    }
}
