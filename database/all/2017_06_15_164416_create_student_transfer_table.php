<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTransferTable extends Migration
{
    /**
     * this is to transfer a student from the institution to another institution entirely
     * @return void
     */
    public function up()
    {
        Schema::create('student_transfer', function (Blueprint $table) {
            $table->increments('transfer_id');
            $table->date('transfer_date');
			$table->text('new_school');
			$table->text('reason');
			$table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('student_id')->on('students');
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
        Schema::dropIfExists('student_transfer');
    }
}
