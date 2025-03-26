<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBidBulletin extends Model
{
    use HasFactory;
    protected $fillable = [
      'bidding_id',
      'title',
      'description',
      'attach_name',
      'type',
      'crtd_user',
      'upd_user',
    ];

    public function bid()
    {
      return $this->belongsTo(ProjectBidding::class, 'bidding_id');
    }
 
}
