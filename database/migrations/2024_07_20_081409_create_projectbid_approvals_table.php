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
        Schema::create('projectbid_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidding_id')->references('id')->on('project_biddings')->constrained()->onDelete('cascade');
            $table->foreignId('winner_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->string('status')->nullable();
            $table->integer('prev_winner')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('approver')->nullable();
            $table->boolean('final_approver')->nullable();
            $table->integer('approver_id')->nullable();
            $table->integer('final_approver_id')->nullable();
            $table->boolean('awarded')->nullable();
            $table->dateTime('awarded_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projectbid_approvals');
    }
};
