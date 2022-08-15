<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointments_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('amount');
            $table->string('paymentStatus');
            $table->string('status');
            $table->string('tx_ref')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('charged_amount')->nullable();
            $table->string('processor_response')->nullable();
            $table->timestamps();

            $table->foreign('appointments_id')
                ->references('id')->on('appointments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
