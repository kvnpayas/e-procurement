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
    Schema::table('project_bid_evaluation_progress', function (Blueprint $table) {
      $table->dateTime('envelope_open_date')->nullable();
      $table->dateTime('eligibility_submit_date')->nullable();
      $table->integer('eligibility_submit_user')->nullable();
      $table->integer('technical_submit_user')->nullable();
      $table->dateTime('technical_submit_date')->nullable();
      $table->dateTime('financial_submit_date')->nullable();
      $table->integer('financial_submit_user')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('project_bid_evaluation_progress', function (Blueprint $table) {
      $table->dropColumn(['envelope_submit_date', 'eligibility_submit_date', 'technical_submit_date', 'financial_submit_date', 'eligibility_submit_user', 'technical_submit_user', 'financial_submit_user']);
    });
  }
};
