<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('variation_options', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('name');
        });
    }

    public function down()
    {
        Schema::table('variation_options', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};