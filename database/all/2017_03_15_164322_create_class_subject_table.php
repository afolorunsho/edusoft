<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSubjectTable extends Migration
{
    /**
     * Run the migrations.
     * this table defines the various subjects being taken in the different classes
     * practical, theory, assignments, projects should be created as exam type for the subject
     * and NOT another subject
     * These may, however, be created as another subject
     * @return void
     */
    public function up()
    {
        Schema::create('class_subject', function (Blueprint $table) {
            $table->increments('class_subject_id');
            $table->integer('subject_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->foreign('subject_id')->references('subject_id')->on('subjects');
         	$table->foreign('class_id')->references('class_id')->on('sch_classes');
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
        Schema::dropIfExists('class_subject');
    }
}
