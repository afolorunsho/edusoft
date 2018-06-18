<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamClassTable extends Migration
{
    /**
     * Run the migrations.
     * this ties the various exams to subjects and the classes: define max score and exam weight
     * @return void
     */
    public function up()
    {
        Schema::create('exam_class', function (Blueprint $table) {
            $table->increments('exam_class_id');
            $table->float('max_score');
         	$table->float('exam_weight');
         	$table->integer('exam_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('class_id')->on('sch_classes');
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
        Schema::dropIfExists('exam_class');
    }
}
