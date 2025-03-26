<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Menu::create([
      'name' => 'User Maintenance',
      'route_name' => 'user-maintenance',
      'module' => 'Maintenance',
    ]);

    Menu::create([
      'name' => 'Vendor Maintenance',
      'route_name' => 'vendor-maintenance',
      'module' => 'Maintenance',
    ]);

    Menu::create([
      'name' => 'Scrap Maintenance',
      'route_name' => 'scrap-maintenance',
      'module' => 'Maintenance',
    ]);

    Menu::create([
      'name' => 'Access Rights',
      'route_name' => 'access-rights',
      'module' => 'Maintenance',
    ]);

    Menu::create([
      'name' => 'Class Product/Services',
      'route_name' => 'class-maintenance',
      'module' => 'Maintenance',
    ]);

    // Envelopes
    Menu::create([
      'name' => 'Eligibility Maintenance',
      'route_name' => 'eligibility-envelope',
      'module' => 'Envelope Maintenance',
    ]);

    Menu::create([
      'name' => 'Technical Maintenance',
      'route_name' => 'technical-envelope',
      'module' => 'Envelope Maintenance',
    ]);

    Menu::create([
      'name' => 'Financial Maintenance',
      'route_name' => 'financial-envelope',
      'module' => 'Envelope Maintenance',
    ]);

    // Bidding
    Menu::create([
      'name' => 'Project Bidding',
      'route_name' => 'project-bidding',
      'module' => 'e-Procurement',
    ]);

    Menu::create([
      'name' => 'Project Bidding Approval',
      'route_name' => 'approval',
      'module' => 'e-Procurement',
    ]);

    Menu::create([
      'name' => 'Project Bid Awarding',
      'route_name' => 'awarding',
      'module' => 'e-Procurement',
    ]);

    Menu::create([
      'name' => 'Pending Protest',
      'route_name' => 'protest',
      'module' => 'e-Procurement',
    ]);


  }
}
