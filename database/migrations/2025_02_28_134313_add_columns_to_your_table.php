<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('role_menu', function (Blueprint $table) {
      $table->boolean('view')->default(true);
      $table->boolean('create')->default(false);
      $table->boolean('update')->default(false);
      $table->boolean('review')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('role_menu', function (Blueprint $table) {
      $table->dropColumn(['view', 'create', 'update', 'review']);
    });
  }
};
