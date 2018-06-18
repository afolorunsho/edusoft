<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemesterTable extends Migration
{
    /**
     * Run the migrations.
     * these are the school academic terms, called semester. it is tied to the academic table
     * @return void
     */
    public function up()
    {
        Schema::create('semester', function (Blueprint $table) {
            $table->increments('semester_id');
            $table->string('semester',100);
            $table->date('date_from');
            $table->date('date_to');
            $table->integer('academic_id')->unsigned();
            $table->foreign('academic_id')->references('academic_id')->on('academics');
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
        Schema::dropIfExists('semester');
    }
}
