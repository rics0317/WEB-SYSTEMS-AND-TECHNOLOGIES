<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('variation_option_id')->nullable()->after('variations');
            $table->unsignedBigInteger('variation_option_size_id')->nullable()->after('variation_option_id');

            $table->foreign('variation_option_id')->references('id')->on('variation_options')->onDelete('set null');
            $table->foreign('variation_option_size_id')->references('id')->on('variation_option_sizes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['variation_option_id']);
            $table->dropForeign(['variation_option_size_id']);

            $table->dropColumn('variation_option_id');
            $table->dropColumn('variation_option_size_id');
        });
    }
};
