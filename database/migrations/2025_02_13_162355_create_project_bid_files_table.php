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
    Schema::create('project_bid_files', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->references('id')->on('project_biddings')->constrained()->onDelete('cascade');
      $table->string('file_name');
      $table->integer('crtd_user')->nullable();
      $table->integer('upd_user')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('project_bid_files');
  }
};
