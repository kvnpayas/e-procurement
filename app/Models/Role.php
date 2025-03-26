<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
  use HasFactory;

  protected $fillable = [
    'role_name',
    'view',
    'create',
    'update',
    'review',
  ];

  public function users()
  {
    return $this->hasMany(User::class, 'role_id');
  }

  public function menus()
  {
    return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id')
      ->withPivot('crtd_user', 'upd_user', 'view', 'create', 'update', 'review')
      ->withTimestamps();
  }
}
