<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique()->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->unsignedInteger('client_type_id')->nullable();
            $table->string('phone')->unique();
            $table->string('photo')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->integer('login_count')->default(0);
            $table->string('physical_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('tin')->unique()->nullable();
            $table->timestamp('admin_at')->default(null;
            $table->timestamps();

            $table->foreign('client_type_id')->references('id')->on('client_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
