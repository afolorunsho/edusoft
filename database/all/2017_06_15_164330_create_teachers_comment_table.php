<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersCommentTable extends Migration
{
    /**
     * Run the migrations.
     * The teachers have opportunity to comment freely on the students.
     * This is in addition to rating provided by the subjective assessment
     * @return void
     */
    public function up()
    {
        Schema::create('teachers_comment', function (Blueprint $table) {
            $table->increments('comment_id');
            $table->text('comment');
            $table->integer('class_div_id')->unsigned();
         	$table->foreign('class_div_id')->references('class_div_id')->on('class_div');
            $table->integer('semester_id')->unsigned();
         	$table->foreign('semester_id')->references('semester_id')->on('semester');
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
        Schema::dropIfExists('teachers_comment');
    }
}
