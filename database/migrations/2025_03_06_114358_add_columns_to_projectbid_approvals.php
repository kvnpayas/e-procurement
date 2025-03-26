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
        Schema::table('projectbid_approvals', function (Blueprint $table) {
          $table->dateTime('approval_date')->nullable();
          $table->dateTime('final_approval_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projectbid_approvals', function (Blueprint $table) {
          $table->dropColumn(['approval_date', 'final_approval_date']);
        });
    }
};
