<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamDivisionTable extends Migration
{
    /**
     * first class, distinction etc
     * this is a variant of exam_grade and should be ignored
     * @return void
     */
    public function up()
    {
        Schema::create('exam_division', function (Blueprint $table) {
            $table->increments('exam_div_id');
            $table->integer('score_div_id')->unsigned();
            $table->float('score_from');
            $table->float('score_to');
			$table->string('remarks',100);
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('class_id')->on('sch_classes');
            $table->foreign('score_div_id')->references('score_div_id')->on('score_div');
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
        Schema::dropIfExists('exam_division');
    }
}
