<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleMenuSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $superAdmin = Role::find(1);
    $menus = Menu::all();
    foreach ($menus as $menu) {
      $superAdmin->menus()->attach($menu->id, [
        'view' => true,
        'create' => true,
        'update' => true,
        'review' => true
      ]);
    }
  }
}
