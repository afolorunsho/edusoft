<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuardiansTable extends Migration
{
    /**
     * this is to record, separately, student guardian: however one table is used for bot
     * @return void
     */
    public function up()
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->increments('guardian_id');
            $table->string('first_name');
			$table->string('last_name');
			$table->string('relationship', 50);
			$table->string('phone',50);
			$table->string('email',50);
			$table->text('work_place');
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
        Schema::dropIfExists('guardians');
    }
}
