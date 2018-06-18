<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxnExpTable extends Migration
{
    /**
     * this takes all expense postings from the screen
     * @return void
     */
    public function up()
    {
        Schema::create('txn_exp', function (Blueprint $table) {
            $table->increments('txn_id');
            $table->integer('invoice_id');
            $table->string('voucher_no', 50);
            $table->string('beneficiary', 100);
            $table->date('txn_date');
			$table->float('qty');
			$table->float('price');
			$table->float('amount');
			$table->string('narration',200);
			$table->string('bank_ref',100)->nullable();
			$table->string('pay_channel',100);
			$table->integer('bank_payment_id')->unsigned();
            $table->foreign('bank_payment_id')->references('payment_id')->on('bank_payment');
			$table->integer('bank_id')->unsigned();
			$table->foreign('bank_id')->references('bank_id')->on('banks');
			$table->integer('expense_id')->unsigned();
			$table->foreign('expense_id')->references('expense_id')->on('expenses');
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
        Schema::dropIfExists('txn_exp');
    }
}
