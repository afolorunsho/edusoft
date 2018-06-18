<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentExamPromotionTable extends Migration
{
    /**
     * Run the migrations.
     * this table states number of subject passed, failed and the total score.
     * this is checked against the exam promotion table which defines pass and failure
     * @return void
     */
    public function up()
    {
        Schema::create('student_exam_promotion', function (Blueprint $table) {
            $table->increments('exam_promotion_id');
            $table->integer('passed_subject');
            $table->integer('failed_subject');
            $table->float('passed_score');
            $table->integer('promotion_id')->unsigned();
         	$table->foreign('promotion_id')->references('promotion_id')->on('exam_promotion');
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
        Schema::dropIfExists('student_exam_promotion');
    }
}
