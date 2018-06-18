<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyllabusTable extends Migration
{
    /**
     * Run the migrations.
     * this table combines the classes with the subjects and define the syllabus for the subjects
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus', function (Blueprint $table) {
            $table->increments('syllabus_id');
            $table->text('syllabus',250);
            $table->integer('class_id')->unsigned();
         	$table->foreign('class_id')->references('class_id')->on('sch_classes');
         	$table->integer('subject_id')->unsigned();
         	$table->foreign('subject_id')->references('subject_id')->on('subjects');
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
        Schema::dropIfExists('syllabus');
    }
}
