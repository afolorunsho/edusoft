<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentExitTable extends Migration
{
    /**
     * this records those exiting the school for various reasons
     * @return void
     */
    public function up()
    {
        Schema::create('student_exit', function (Blueprint $table) {
            $table->increments('exit_id');
            $table->date('exit_date');
            $table->string('reason',100);
			$table->text('remarks');
			$table->integer('class_id')->unsigned();
			$table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->foreign('class_id')->references('class_div_id')->on('class_div');
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
        Schema::dropIfExists('student_exit');
    }
}
