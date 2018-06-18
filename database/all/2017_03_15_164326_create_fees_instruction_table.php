<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesInstructionTable extends Migration
{
    /**
     * Run the migrations.
     * fees payment instructions are set per class
     * @return void
     */
    public function up()
    {
        Schema::create('fees_instruction', function (Blueprint $table) {
            $table->increments('instruction_id');
            $table->text('instruction',250);
            $table->integer('class_id')->unsigned();
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
        Schema::dropIfExists('fees_instruction');
    }
}
