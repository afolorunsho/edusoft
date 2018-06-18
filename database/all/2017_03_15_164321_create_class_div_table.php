<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassDivTable extends Migration
{
    /**
     * Run the migrations.
     * this stores the various divisions for the sch_classes table e.g 5A, 5B etc
     * @return void
     */
    public function up()
    {
        Schema::create('class_div', function (Blueprint $table) {
            $table->increments('class_div_id');
            $table->string('class_div',50);
            $table->string('description',100);
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
        Schema::dropIfExists('class_div');
    }
}
