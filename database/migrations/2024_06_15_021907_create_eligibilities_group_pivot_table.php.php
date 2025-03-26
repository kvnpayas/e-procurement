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
      Schema::create('eligibilities_group_pivot', function (Blueprint $table) {
        $table->id();
        $table->foreignId('eligibility_id')->references('id')->on('eligibilities')->constrained()->onDelete('cascade');
        $table->string('group_id')->references('id')->on('eligibility_groups')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('eligibilities_group_pivot');
    }
};
