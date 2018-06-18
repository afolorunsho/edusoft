<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamPromotionTable extends Migration
{
    /**
     * Run the migrations.
     * this sets the configuration for failure and passes per class per semester
     * @return void
     */
    public function up()
    {
        Schema::create('exam_promotion', function (Blueprint $table) {
            $table->increments('promotion_id');
            $table->integer('passed_subject');
            $table->float('passed_score');
            $table->float('average_score');
            $table->date('promotion_date');
            $table->integer('class_id')->unsigned();
         	$table->foreign('class_id')->references('class_id')->on('sch_classes');
			$table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('semester_id')->on('semester');
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
        Schema::dropIfExists('exam_promotion');
    }
}
