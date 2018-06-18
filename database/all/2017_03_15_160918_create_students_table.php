<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('student_id');
            $table->string('reg_no',50);
            $table->date('reg_date');
           	$table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('other_name',50)->nullable();
            $table->date('dob');
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('blood',50)->nullable();
            $table->boolean('gender');
            $table->string('district',100);
            $table->string('region',100);
            $table->string('tribe',100);
            $table->string('town',100);
            $table->string('lga',100);
            $table->string('state_origin',100);
            $table->string('nationality',100);
            $table->string('religion',100);
            $table->text('address');
            $table->string('email')->nullable();
            $table->string('phone',100)->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('enrol')->default(0);
            $table->date('enrol_date')->nullable();
			$table->string('photo',200)->nullable();
			$table->string('guardian',150)->nullable();
			$table->string('guard_photo')->nullable();
			$table->string('relationship',50)->nullable();
			$table->text('guard_office')->nullable();
			$table->text('guard_home')->nullable();
			$table->string('guard_email')->nullable();
            $table->string('guard_phone',100)->nullable();
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
        Schema::dropIfExists('students');
    }
}
