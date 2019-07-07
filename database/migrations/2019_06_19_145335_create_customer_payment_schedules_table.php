<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_contract_id');
            $table->unsignedInteger('bill_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('expiry_date');
            $table->double('amount_to_be_paid');
            $table->string('control_number')->unique();
            $table->boolean('active')->default(true);
            $table->timestamp('paid_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('customer_contract_id')->references('id')->on('customer_contracts');
            $table->foreign('bill_type_id')->references('id')->on('bill_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payment_schedules');
    }
}
