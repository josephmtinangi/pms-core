<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->unsignedInteger('customer_type_id');
            $table->string('physical_address');
            $table->string('postal_address');
            $table->string('tin')->unique()->nullable();
            $table->timestamps();

            $table->foreign('customer_type_id')->references('id')->on('customer_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
