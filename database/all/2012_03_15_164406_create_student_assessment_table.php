<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAssessmentTable extends Migration
{
    /**
     * Run the migrations.
     * This is where you set the various assessment types(e.g pschometric tests etc)
     * this feeds into assessment_param
     * @return void
     */
    public function up()
    {
        Schema::create('student_assessment', function (Blueprint $table) {
            $table->increments('assessment_id');
            $table->string('assessment', 100);
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
        Schema::dropIfExists('student_assessment');
    }
}
