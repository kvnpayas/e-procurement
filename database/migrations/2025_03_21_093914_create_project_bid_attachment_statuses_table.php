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
    Schema::create('project_bid_attachment_statuses', function (Blueprint $table) {
      $table->id();
      $table->foreignId('bidding_id')->references('id')->on('project_biddings')->constrained()->onDelete('cascade');
      $table->foreignId('file_id')->references('id')->on('vendor_envelope_files')->constrained()->onDelete('no action');
      $table->foreignId('vendor_id')->references('id')->on('users')->constrained()->onDelete('cascade');
      $table->foreignId('validated_by')->references('id')->on('users')->constrained()->onDelete('no action');
      $table->string('envelope')->nullable();
      $table->dateTime('validated_date')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('project_bid_attachment_statuses');
  }
};
