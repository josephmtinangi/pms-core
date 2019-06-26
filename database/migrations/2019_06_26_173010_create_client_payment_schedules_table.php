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
            $table->date('start_date');
            $table->date('end_date');
            $table->date('expiry_date');
            $table->double('amount_to_be_paid');
            $table->string('currency')->default('TZS');
            $table->string('control_number')->unique();
            $table->boolean('active')->default(true);
            $table->timestamp('paid_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
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
