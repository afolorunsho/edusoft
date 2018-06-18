<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTransferTable extends Migration
{
    /**
     * This enables you to transfer student from one section to the other 
     * note that movement across classes is recorded in the student_promotion table
     * @return void
     */
    public function up()
    {
        Schema::create('class_transfer', function (Blueprint $table) {
            $table->increments('transfer_id');
            $table->date('transfer_date');
            $table->text('remarks');
            $table->integer('student_id')->unsigned();
            $table->integer('class_from')->unsigned();
            $table->integer('class_to')->unsigned();
         	$table->foreign('student_id')->references('student_id')->on('students');
         	$table->foreign('class_from')->references('class_div_id')->on('class_div');
         	$table->foreign('class_to')->references('class_div_id')->on('class_div');
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
