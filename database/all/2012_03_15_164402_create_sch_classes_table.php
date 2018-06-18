<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchClassesTable extends Migration
{
    /**
     * Run the migrations.
     * class or Course: it ties with the school
     * div type: numeric or alphabet
     * div no: number of division in the class
     * @return void
     */
    public function up()
    {
        Schema::create('sch_classes', function (Blueprint $table) {
            $table->increments('class_id');
            $table->string('class_name',50);
            $table->string('description',100);
            $table->integer('capacity');
            $table->integer('sequence'); 
            $table->integer('div_no');
            $table->string('div_type',50);
            $table->integer('school_id')->unsigned();
         	$table->foreign('school_id')->references('school_id')->on('schools');
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
        Schema::dropIfExists('sch_classes');
    }
}
