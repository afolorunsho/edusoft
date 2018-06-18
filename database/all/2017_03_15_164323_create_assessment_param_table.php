<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentParamTable extends Migration
{
    /**
     * Run the migrations.
     * This is where you define the variables for the various subjective assessments
     * This is done per class
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_param', function (Blueprint $table) {
            $table->increments('param_id');
            $table->string('parameter', 100);
            $table->integer('assessment_id')->unsigned();
            $table->foreign('assessment_id')->references('assessment_id')->on('student_assessment');
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('class_id')->on('sch_classes');
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
        Schema::dropIfExists('assessment_param');
    }
}
