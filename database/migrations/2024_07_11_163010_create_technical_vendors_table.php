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
        Schema::create('technical_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidding_id')->references('id')->on('project_biddings')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->foreignId('technical_id')->references('id')->on('technicals')->constrained()->onDelete('cascade');
            $table->string('answer')->nullable();
            $table->string('admin_answer')->nullable();
            $table->integer('admin_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_vendors');
    }
};
