<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBidFile extends Model
{
    use HasFactory;

    protected $fillable = [
      'project_id',
      'file_name',
      'crtd_user',
      'upd_user'
    ];
}
