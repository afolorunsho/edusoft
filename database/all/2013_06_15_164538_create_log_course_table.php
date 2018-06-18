<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogCourseTable extends Migration
{
    /**
     * operation is add, edit, review, delete
     * record_id is the system generated id
     * this is the user assigned code for the transation
     * activity captures other details not captured above(i.e inputs for all the fields)
     * @return void
     */
    public function up()
    {
        Schema::create('log_course', function (Blueprint $table) {
            $table->increments('log_id');
            $table->string('username',100);
            $table->string('modulename',50);
            $table->string('formname',50);
            $table->string('operation',25);
            $table->string('record_id',25);
            $table->string('record_code',50);
            $table->text('activity');
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
        Schema::dropIfExists('log_course');
    }
}
