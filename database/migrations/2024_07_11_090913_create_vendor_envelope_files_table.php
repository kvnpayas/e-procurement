<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_envelope_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidding_id')->references('id')->on('project_biddings')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->integer('envelope_id')->nullable();
            $table->string('envelope');
            $table->string('file')->nullable();
            $table->string('admin_file')->nullable();
            $table->integer('admin_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_envelope_files');
    }
};
