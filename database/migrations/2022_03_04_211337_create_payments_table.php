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
            $table->unsignedBigInteger('appointments_id');
            $table->string('transactionReference');
            $table->string('paymentReference');
            $table->string('amountPaid');
            $table->string('totalPayable');
            $table->string('settlementAmount');
            $table->string('paidOn');
            $table->string('paymentStatus');
            $table->string('paymentDescription');
            $table->string('transactionHash');
            $table->string('currency');
            $table->string('paymentMethod');
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
