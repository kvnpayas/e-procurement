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
        Schema::create('technicals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('question')->nullable();
            $table->string('question_type')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('passing')->nullable();
            $table->string('attachment')->nullable();
            $table->string('status');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('technicals');
    }
};
