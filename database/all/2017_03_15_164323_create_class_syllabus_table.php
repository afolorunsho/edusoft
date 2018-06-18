<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSyllabusTable extends Migration
{
    /**
     * Run the migrations.
     * this table links the syllabus table to the class sections
     * @return void
     */
    public function up()
    {
        Schema::create('class_syllabus', function (Blueprint $table) {
            $table->increments('class_syllabus_id');
            $table->integer('class_div_id')->unsigned();
         	$table->foreign('class_div_id')->references('class_div_id')->on('class_div');
         	$table->integer('syllabus_id')->unsigned();
         	$table->foreign('syllabus_id')->references('syllabus_id')->on('syllabus');
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
        Schema::dropIfExists('class_syllabus');
    }
}
