<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesStructTable extends Migration
{
    /**
     * this is the fee structure for ecah class with optional fees indicated, amount aand start_date
     * @return void
     */
    public function up()
    {
        Schema::create('fees_struct', function (Blueprint $table) {
            $table->increments('struct_id');
            $table->float('amount');
            $table->date('start_date');
            $table->string('optional',5);
            $table->integer('fee_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('class_id')->on('sch_classes');
			$table->foreign('fee_id')->references('fee_id')->on('fees');
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
        Schema::dropIfExists('fees_struct');
    }
}
