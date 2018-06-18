<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPromotionTable extends Migration
{
    /**
     * this takes records from the screen and update the student_promotion table
     * for those meeting the promotion criteria
     * note that the screen was populated with records based on those who passed: total score OR subject passed
     * @return void
     */
    public function up()
    {
        Schema::create('student_promotion', function (Blueprint $table) {
            $table->increments('promotion_id');
            $table->date('promotion_date');
            $table->String('remarks', '50')->nullable();
			$table->integer('class_from')->unsigned();
			$table->integer('class_to')->unsigned();
			$table->integer('student_id')->unsigned();
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
        Schema::dropIfExists('student_promotion');
    }
}
