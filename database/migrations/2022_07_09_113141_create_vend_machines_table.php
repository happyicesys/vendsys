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
        Schema::create('vend_machines', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->integer('temp')->nullable();
            $table->datetime('temp_datetime')->nullable();
            $table->integer('coin_amount')->default(0);
            $table->integer('firmware_ver')->nullable();
            $table->boolean('is_door_open')->default(0);
            $table->boolean('is_sensor_normal')->default(1);
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
        Schema::dropIfExists('vend_machines');
    }
};
