<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensetypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expensetypes', function (Blueprint $table) {
            $table->increments('expense_type_id');
            $table->string('expense_type',50);
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
        Schema::dropIfExists('expensetypes');
    }
}
