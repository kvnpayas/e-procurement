<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Role::create([
      'role_name' => 'admin'
    ]);

    Role::create([
      'role_name' => 'Vendor'
    ]);

    Role::create([
      'role_name' => 'BAC(Member)'
    ]);

    Role::create([
      'role_name' => 'BAC(Chairman)'
    ]);

    Role::create([
      'role_name' => 'GM'
    ]);
  }
}
