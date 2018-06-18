<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     * default academic id is the relevant academic date for the current year
     * opportunity to change it at the top to another calendar year
     * @return void
     */
    public function up()
    {
        Schema::create('academics', function (Blueprint $table) {
            $table->increments('academic_id');
            $table->string('academic',50);
            $table->date('date_from');
            $table->date('date_to');
            $table->boolean('active');
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
        Schema::dropIfExists('academics');
    }
}
