<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusAndOrderStatusToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add the 'payment_status' column
            $table->string('payment_status')->default('pending')->after('payment_id');

            // Add the 'order_status' column with a default value of 'pending'
            $table->string('order_status')->default('pending')->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the 'payment_status' column
            $table->dropColumn('payment_status');

            // Drop the 'order_status' column
            $table->dropColumn('order_status');
        });
    }
}
