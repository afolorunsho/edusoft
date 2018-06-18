<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesRefundTable extends Migration
{
    /**
     * this is to record fees refund to students
     * @return void
     */
    public function up()
    {
        Schema::create('fees_refund', function (Blueprint $table) {
            $table->increments('refund_id');
            $table->date('refund_date');
            $table->float('amount');
            $table->string('narration',100);
            $table->integer('bank_payment_id')->unsigned();
            $table->integer('fee_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('semester_id')->on('semester');
			$table->foreign('fee_id')->references('fee_id')->on('fees');
			$table->foreign('bank_payment_id')->references('payment_id')->on('bank_payment');
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
        Schema::dropIfExists('fees_refund');
    }
}
