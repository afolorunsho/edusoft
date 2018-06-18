<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesTable extends Migration
{
    /**
     * this is to define the various course fees. note that the level_id is linked to program_id which is the course
     *this is the same as fee structure
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->increments('fee_id');
            $table->string('fee_name',100);
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('group_id')->on('acct_group');
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
        Schema::dropIfExists('fees');
    }
}
