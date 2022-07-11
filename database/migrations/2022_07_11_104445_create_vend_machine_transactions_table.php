<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vend_machine_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->string('order_id');
            $table->datetime('transaction_datetime');
            $table->integer('amount')->default(0);

            $table->bigInteger('payment_method_id');
            $table->bigInteger('vend_machine_channel_id');
            $table->bigInteger('vend_machine_channel_error_id')->nullable();
            $table->bigInteger('vend_machine_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vend_machine_transactions');
    }
};
