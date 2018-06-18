<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTimetableTable extends Migration
{
    /**
     * Run the migrations.
     * This is the timetable per class for all the subjects
     * @return void
     */
    public function up()
    {
        Schema::create('class_timetable', function (Blueprint $table) {
            $table->increments('timetable_id');
            $table->integer('subject_id')->unsigned();
         	$table->foreign('subject_id')->references('subject_id')->on('subjects');
			$table->string('week_day',50);
			$table->string('time_from',50);
            $table->string('time_to',50);
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
        Schema::dropIfExists('class_timetable');
    }
}
