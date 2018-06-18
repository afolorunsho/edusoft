<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionTable extends Migration
{
    /**
     * txn_type id: cheque, online transfer, cash etc
     * txn ref: deposit slip number, chq no, transfer ref no etc
     * @return void
     */
    public function up()
    {
        Schema::create('institution', function (Blueprint $table) {
            $table->increments('institute_id');
            $table->string('sch_code',150);
            $table->string('sch_name',100);
            $table->string('motto',250);
            $table->string('photo_image',250);
            $table->string('logo_image',250);
            $table->string('header_image',250);
			$table->string('phone',200);
			$table->string('email',150)->nullable();
			$table->string('website',150)->nullable();
			$table->string('country',150);
			$table->string('region',10);
			$table->text('address');
			$table->string('reg_no',100);
			$table->date('reg_date',10);
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
        Schema::dropIfExists('institution');
    }
}
