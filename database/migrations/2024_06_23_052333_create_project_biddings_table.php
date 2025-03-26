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
        Schema::create('project_biddings', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->nullable();
            $table->string('budget_id')->nullable();
            $table->string('icss_project_id')->nullable();
            $table->string('title');
            $table->string('status');
            $table->string('type');
            $table->longText('instruction_details')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('eligibility')->default(false);
            $table->boolean('technical')->default(false);
            $table->boolean('financial')->default(false);
            $table->integer('invited_vendor')->default(0);
            $table->string('start_date')->nullable();
            $table->string('deadline_date');
            $table->string('extend_date')->nullable();
            $table->string('hold_date')->nullable();
            $table->string('reserved_price')->nullable();
            $table->boolean('reflect_price');
            $table->boolean('reserved_price_switch');
            $table->string('score_method');
            $table->boolean('scrap');
            $table->integer('extend_count')->nullable()->default(0);
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
        Schema::dropIfExists('project_biddings');
    }
};
