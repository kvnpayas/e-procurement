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
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_id');
            $table->longText('description')->nullable();
            $table->string('class_id')->nullable();
            $table->string('uom')->nullable();
            $table->float('unit_price', 2)->nullable();
            $table->float('unit_cost', 2)->nullable();
            $table->integer('type')->nullable();
            $table->integer('available_quantity')->nullable();
            $table->integer('quantity_on_hand')->nullable();
            $table->boolean('scrap')->default(false);
            $table->integer('crtd_user')->nullable();
            $table->integer('updtd_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financials');
    }
};
