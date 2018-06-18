<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentExamScoreTable extends Migration
{
    /**
     * This is an aggregation table and updates from exam_score table
     * this aggregates the scores for each exam type 
     * this feeds into student_total_score table to update for each subject
     * @return void
     */
    public function up()
    {
        Schema::create('student_exam_score', function (Blueprint $table) {
            $table->increments('exam_score_id');
            $table->float('exam_score');
			$table->date('exam_date');
            $table->integer('class_div_id')->unsigned();
         	$table->foreign('class_div_id')->references('class_div_id')->on('class_div');
            $table->integer('semester_id')->unsigned();
         	$table->foreign('semester_id')->references('semester_id')->on('semester');
         	$table->integer('subject_id')->unsigned();
         	$table->foreign('subject_id')->references('subject_id')->on('subjects');
         	$table->integer('student_id')->unsigned();
         	$table->foreign('student_id')->references('student_id')->on('students');
         	$table->integer('exam_id')->unsigned();
         	$table->foreign('exam_id')->references('exam_id')->on('exam_name');
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
        Schema::dropIfExists('student_exam_score');
    }
}
