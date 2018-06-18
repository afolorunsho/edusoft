<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentDisciplineTable extends Migration
{
    /**
     * This records discipline records for the students
     * @return void
     */
    public function up()
    {
        Schema::create('student_discipline', function (Blueprint $table) {
            $table->increments('discipline_id');
            $table->date('discipline_date');
            $table->string('infraction',100);
            $table->string('sanction',100);
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
        Schema::dropIfExists('student_discipline');
    }
}
