<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTotalScoreTable extends Migration
{
    /**
     * This is updated from the student_exam_score table
     * it totals all the score for taken for each subject irrespective of the exam type
     * this table feeds into student_results table
     */
    public function up()
    {
        Schema::create('student_total_score', function (Blueprint $table) {
            $table->increments('total_score_id');
            $table->float('exam_score');
            $table->integer('exam_grade_id')->unsigned();
         	$table->foreign('exam_grade_id')->references('exam_grade_id')->on('exam_grade');
            $table->integer('class_div_id')->unsigned();
         	$table->foreign('class_div_id')->references('class_div_id')->on('class_div');
            $table->integer('semester_id')->unsigned();
         	$table->foreign('semester_id')->references('semester_id')->on('semester');
         	$table->integer('subject_id')->unsigned();
         	$table->foreign('subject_id')->references('subject_id')->on('subjects');
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
        Schema::dropIfExists('student_total_score');
    }
}
