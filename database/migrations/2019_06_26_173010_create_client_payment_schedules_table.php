<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientPaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_payment_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('property_id');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamp('expiry_date');
            $table->double('amount_to_be_paid');
            $table->string('currency')->default('TZS');
            $table->string('control_number')->unique();
            $table->boolean('active')->default(true);
            $table->timestamp('paid_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_payment_schedules');
    }
}
