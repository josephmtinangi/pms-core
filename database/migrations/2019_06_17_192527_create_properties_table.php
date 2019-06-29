<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('property_type_id');
            $table->unsignedInteger('payment_mode_id');
            $table->unsignedInteger('client_id');
            $table->string('physical_address');
            $table->integer('floors')->default(0);
            $table->unsignedInteger('village_id');
            $table->timestamps();

            $table->foreign('property_type_id')->references('id')->on('property_types');
            $table->foreign('payment_mode_id')->references('id')->on('payment_modes');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('village_id')->references('id')->on('villages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
