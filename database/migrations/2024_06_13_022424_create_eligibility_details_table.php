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
        Schema::create('eligibility_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eligibility_id')->references('id')->on('eligibilities')->constrained()->onDelete('cascade');
            $table->string('field');
            $table->string('field_type');
            $table->string('status');
            $table->boolean('validate_date')->nullable()->default(false);
            $table->integer('crtd_user');
            $table->integer('upd_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eligibility_details');
    }
};
