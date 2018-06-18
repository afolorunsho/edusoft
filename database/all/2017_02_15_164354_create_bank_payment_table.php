<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankPaymentTable extends Migration
{
    /**
     * bank_payment table records all IN and OUT from a bank account
     * @return void
     */
    public function up()
    {
        Schema::create('bank_payment', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->date('txn_date');
            $table->string('txn_type',100);
            $table->float('amount');
            $table->string('channel',100);
            $table->string('reference',100)->nullable();
            $table->string('narration',100)->nullable();
            $table->integer('bank_id')->unsigned();
            $table->foreign('bank_id')->references('bank_id')->on('banks');
			$table->string('operator',100);
            $table->string('reviewer',100)->nullable();
            $table->timestamps();
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_payment');
    }
}
