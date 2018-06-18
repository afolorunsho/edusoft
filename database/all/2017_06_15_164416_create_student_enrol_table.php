<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEnrolTable extends Migration
{
    /**
     * this is the enrolment for each student: the class_id column is actually class_div_id
     * this records the section of the student in the school
     * @return void
     */
    public function up()
    {
        Schema::create('student_enrol', function (Blueprint $table) {
            $table->increments('enrol_id');
            $table->date('enrol_date');
            $table->integer('active');
			$table->integer('student_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
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
        Schema::dropIfExists('student_enrol');
    }
}
