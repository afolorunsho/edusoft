<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesDiscountTable extends Migration
{
    /**
     * this is to record scholarship and discount for school fees
     * @return void
     */
    public function up()
    {
        Schema::create('fees_discount', function (Blueprint $table) {
            $table->increments('discount_id');
            $table->date('discount_date');
            $table->float('amount');
            $table->string('narration',100);
            $table->string('discount',100);
            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('semester_id')->on('semester');
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
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
        Schema::dropIfExists('fees_discount');
    }
}
