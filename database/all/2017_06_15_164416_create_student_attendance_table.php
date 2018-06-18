<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAttendanceTable extends Migration
{
    /**
     * This is to record student attendance
     * @return void
     */
    public function up()
    {
        Schema::create('student_attendance', function (Blueprint $table) {
            $table->increments('attendance_id');
            $table->date('attendance_date');
            $table->string('arrival_time',100);
            $table->string('remarks',100);
			$table->integer('class_id')->unsigned();
            $table->integer('student_id')->unsigned();
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
        Schema::dropIfExists('student_attendance');
    }
}
