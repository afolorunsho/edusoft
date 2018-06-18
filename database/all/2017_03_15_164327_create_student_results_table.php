<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentResultsTable extends Migration
{
    /**
     * this is an aggregation table
     * this table is updated from student_total_score table
     * @return void
     */
    public function up()
    {
        Schema::create('student_results', function (Blueprint $table) {
            $table->integer('student_id')->unsigned();
			$table->float('total_score');
			$table->integer('class_div_id')->unsigned();
            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('semester_id')->on('semester');
			$table->foreign('student_id')->references('student_id')->on('students');
			$table->foreign('class_div_id')->references('class_div_id')->on('class_div');
			
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_results');
    }
}
