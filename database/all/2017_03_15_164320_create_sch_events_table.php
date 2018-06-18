<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchEventsTable extends Migration
{
    /**
     * Run the migrations.
     * these are the dates for the various school event types
     * @return void
     */
    public function up()
    {
        Schema::create('sch_events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->string('event_name',50);
            $table->integer('academic_id')->nullable();
            $table->integer('event_type_id')->unsigned();
            $table->foreign('event_type_id')->references('event_type_id')->on('event_type');
            $table->date('date_from');
            $table->date('date_to');
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
        Schema::dropIfExists('sch_events');
    }
}
