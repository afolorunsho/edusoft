<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreDivTable extends Migration
{
    /**
     * Run the migrations.
     * this is a variant of the score_grade. though inactive
     * @return void
     */
    public function up()
    {
        Schema::create('score_div', function (Blueprint $table) {
            $table->increments('score_div_id');
            $table->string('score_div',50);
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
        Schema::dropIfExists('score_div');
    }
}
