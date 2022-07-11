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
        Schema::create('vend_machine_channels', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->integer('qty')->default(0);
            $table->integer('capacity')->default(0);
            $table->integer('amount')->default(0);
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
        Schema::dropIfExists('vend_machine_channels');
    }
};
