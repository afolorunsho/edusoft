<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesPaymentTable extends Migration
{
    /**
     * this is to record various fees payment by students
     * @return void
     */
    public function up()
    {
        Schema::create('fees_payment', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->date('payment_date');
            $table->float('amount');
            $table->string('narration',100);
            $table->integer('class_div_id')->unsigned();
            $table->foreign('class_div_id')->references('class_div_id')->on('class_div');
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('semester_id')->on('semester');
			$table->integer('fee_id')->unsigned();
            $table->foreign('fee_id')->references('fee_id')->on('fees');
			$table->integer('bank_payment_id')->unsigned();
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
        Schema::dropIfExists('fees_payment');
    }
}
