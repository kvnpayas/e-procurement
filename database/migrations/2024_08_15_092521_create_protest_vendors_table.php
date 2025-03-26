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
        Schema::create('protest_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('protest_id')->references('id')->on('projectbid_protests')->constrained()->onDelete('cascade');
            $table->integer('vendor_id');
            $table->string('protest_message');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protest_vendors');
    }
};
