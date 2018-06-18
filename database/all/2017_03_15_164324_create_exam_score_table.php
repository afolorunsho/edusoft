<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamScoreTable extends Migration
{
    /**
     * this is raw exam table for students. This is processed into student_exam_score
     * it is captured from the screen
     * @return void
     */
    public function up()
    {
        Schema::create('exam_score', function (Blueprint $table) {
            $table->increments('score_id');
            $table->date('exam_date');
            $table->integer('student_id')->unsigned();
			$table->float('exam_score');
			$table->string('remarks',100)->nullable();
			$table->integer('exam_id')->unsigned();
			$table->integer('subject_id')->unsigned();
            $table->integer('class_div_id')->unsigned();
            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('semester_id')->on('semester');
			$table->foreign('exam_id')->references('exam_id')->on('exam_name');
			$table->foreign('subject_id')->references('subject_id')->on('subjects');
			$table->foreign('student_id')->references('student_id')->on('students');
			$table->foreign('class_div_id')->references('class_div_id')->on('class_div');
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
        Schema::dropIfExists('exam_score');
    }
}
