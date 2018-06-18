<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemesterPromotionTable extends Migration
{
    /**
     * Run the migrations.
     * for each student, the system checks against the exam_promotion table whether he passed or not
     * thus is the most critical table for pass and failure
     * @return void
     */
    public function up()
    {
        Schema::create('semester_promotion', function (Blueprint $table) {
        	$table->increments('semester_promotion_id');
            $table->integer('subject_passed');
            $table->float('total_score');
            $table->float('average_score');
            $table->integer('class_div_id')->unsigned();
            $table->foreign('class_div_id')->references('class_div_id')->on('class_div');
            $table->integer('promotion_id')->unsigned();
         	$table->foreign('promotion_id')->references('promotion_id')->on('exam_promotion');
			$table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('semester_id')->on('semester');
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
        Schema::dropIfExists('semester_promotion');
    }
}
