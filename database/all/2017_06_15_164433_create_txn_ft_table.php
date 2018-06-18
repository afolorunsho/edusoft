<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxnFTTable extends Migration
{
    /**
     * this is for lodgement and other forms of funds transfer
     * @return void
     */
    public function up()
    {
        Schema::create('txn_ft', function (Blueprint $table) {
            $table->increments('txn_id');
            $table->date('txn_date');
			$table->float('amount');
			$table->string('narration',200);
			$table->string('bank_ref',100)->nullable();
			$table->string('pay_channel',100);
			$table->integer('bank_payment_id')->unsigned();
            $table->foreign('bank_payment_id')->references('payment_id')->on('bank_payment');
			$table->integer('bank_to')->unsigned();
			$table->foreign('bank_to')->references('bank_id')->on('banks');
			$table->integer('bank_from')->unsigned();
			$table->foreign('bank_from')->references('bank_id')->on('banks');
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
        Schema::dropIfExists('txn_ft');
    }
}
