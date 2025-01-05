<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create product_variations table
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // Create variation_options table
        Schema::create('variation_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_id')->constrained('product_variations')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // Create variation_option_sizes table
        Schema::create('variation_option_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id')->constrained('variation_options')->onDelete('cascade');
            $table->string('name');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variation_option_sizes');
        Schema::dropIfExists('variation_options');
        Schema::dropIfExists('product_variations');
    }
};