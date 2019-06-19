<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerContractRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contract_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_contract_id');
            $table->unsignedInteger('room_id');
            $table->timestamps();

            $table->foreign('customer_contract_id')->references('id')->on('customer_contracts');
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_contract_rooms');
    }
}
