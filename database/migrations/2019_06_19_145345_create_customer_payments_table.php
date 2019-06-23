<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
class CreateCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_payment_schedule_id');
            $table->string('payer_name');
            $table->double('amount');
            $table->string('amount_type');
            $table->string('currency');
            $table->string('payment_reference');
            $table->string('payment_type');
            $table->string('payment_description')->nullable();
            $table->unsignedInteger('payer_id')->nullable();
            $table->string('transaction_reference');
            $table->string('transaction_channel');
            $table->timestamp('transaction_date')->default(Carbon::now());
            $table->string('token');
            $table->string('checksum');
            $table->string('institution_id')->nullable();
            $table->string('receipt_number');
            $table->timestamps();

            $table->foreign('customer_payment_schedule_id')->references('id')->on('customer_payment_schedules');
            $table->foreign('payer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payments');
    }
}
