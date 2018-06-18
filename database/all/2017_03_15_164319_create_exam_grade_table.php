<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamGradeTable extends Migration
{
    /**
     * This is where the score grades per class is defined. it is linked to the score_grade table
     * @return void
     */
    public function up()
    {
        Schema::create('exam_grade', function (Blueprint $table) {
            $table->increments('exam_grade_id');
            $table->integer('score_grade_id')->unsigned();
            $table->float('score_from');
            $table->float('score_to');
			$table->string('remarks',100);
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('class_id')->on('sch_classes');
            $table->foreign('score_grade_id')->references('score_grade_id')->on('score_grade');
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
        Schema::dropIfExists('exam_grade');
    }
}
