<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariationColumnsToStocksTable extends Migration
{
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('variation_option_id')->nullable();
            $table->unsignedBigInteger('variation_option_size_id')->nullable();

            $table->foreign('variation_option_id')->references('id')->on('variation_options')->onDelete('cascade');
            $table->foreign('variation_option_size_id')->references('id')->on('variation_option_sizes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['variation_option_id']);
            $table->dropForeign(['variation_option_size_id']);

            $table->dropColumn('variation_option_id');
            $table->dropColumn('variation_option_size_id');
        });
    }
}
