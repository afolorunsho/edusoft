<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesStudentTable extends Migration
{
    /**
     * some fees are optional and specifically appy to some students ALONE. this table handles this
     * @return void
     */
    public function up()
    {
        Schema::create('fees_student', function (Blueprint $table) {
            $table->increments('fees_student_id');
            $table->integer('fee_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->integer('semester_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
			$table->foreign('fee_id')->references('fee_id')->on('fees');
			$table->foreign('semester_id')->references('semester_id')->on('semester');
			$table->foreign('class_id')->references('class_div_id')->on('class_div');
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
        Schema::dropIfExists('fees_student');
    }
}
