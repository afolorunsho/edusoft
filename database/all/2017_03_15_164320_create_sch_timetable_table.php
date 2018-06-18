<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchTimetableTable extends Migration
{
    /**
     * Run the migrations.
     * the various schools(e.g primary, nursery etc) have academic time table and this is defined here
     * @return void
     */
    public function up()
    {
        Schema::create('sch_timetable', function (Blueprint $table) {
            $table->increments('timetable_id');
            $table->string('period_name',50);
            $table->string('week_day',50)->nullable();
            $table->string('time_from',50);
            $table->string('time_to',50);
            $table->integer('school_id')->unsigned();
         	$table->foreign('school_id')->references('school_id')->on('schools');
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
        Schema::dropIfExists('sch_timetable');
    }
}
