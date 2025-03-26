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
    Schema::table('project_bid_awards', function (Blueprint $table) {
      $table->integer('awarded_by')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('project_bid_awards', function (Blueprint $table) {
      $table->dropColumn(['awarded_by']);
    });
  }
};
