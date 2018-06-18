<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreGradeTable extends Migration
{
    /**
     * Run the migrations.
     *w this 1s the various grades for the scores in the school and it is defined per class
     * @return void
     */
    public function up()
    {
        Schema::create('score_grade', function (Blueprint $table) {
            $table->increments('score_grade_id');
            $table->string('score_grade',50);
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
        Schema::dropIfExists('score_grade');
    }
}
