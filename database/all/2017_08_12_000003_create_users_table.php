<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     * this define the user of the system
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('name',100);
            $table->string('email');
            $table->string('secret', 100);
            $table->string('username',50);
			$table->boolean('active');
			$table->timestamp('last_signon');
			$table->timestamp('previous_signon');
			$table->integer('signon_cnt');
			$table->string('photo',200)->nullable();
			$table->string('phone',100)->nullable();
            $table->string('password',100);
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('role_id')->on('roles');
            $table->rememberToken();
            $table->string('operator',100);
            $table->string('reviewer',100);
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
        Schema::dropIfExists('users');
    }
}
