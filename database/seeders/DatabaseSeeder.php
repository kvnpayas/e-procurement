<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\RoleMenuSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    $this->call(RoleSeeder::class);
    $this->call(MenuSeeder::class);
    $this->call(RoleMenuSeeder::class);
    User::factory()->create([
      'name' => 'superadmin',
      'email' => 'superadmin@test.com',
      'address' => 'Tarlac',
      'Active' => true,
      'password' => Hash::make('password12'),
      'role_id' => 1,
    ]);
  }
}
