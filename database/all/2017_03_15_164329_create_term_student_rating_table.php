<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermStudentRatingTable extends Migration
{
    /**
     * Run the migrations.
     * This table is to rate the students for the subjective assessment per term
     * @return void
     */
    public function up()
    {
        Schema::create('term_student_rating', function (Blueprint $table) {
            $table->increments('rating_id');
            $table->text('remarks', 250);
            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('semester_id')->on('semester');
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->integer('assessment_id')->unsigned();
            $table->foreign('assessment_id')->references('assessment_id')->on('student_assessment');
            $table->integer('param_id')->unsigned();
            $table->foreign('param_id')->references('param_id')->on('assessment_param');
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
        Schema::dropIfExists('term_student_rating');
    }
}
