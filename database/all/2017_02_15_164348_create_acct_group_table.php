<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcctGroupTable extends Migration
{
    /**
     * Run the migrations.
     *acct_type is current assets, fixed assets, current liability, admin exp, equipment exp etc
     *while acct_category is assets, income, expenses, capital, liability: no table required
     * @return void
     *create account type table to define the sub-accounts e.f admin exp, equip exp etc 
     */
    public function up()
    {
        Schema::create('acct_group', function (Blueprint $table) {
            $table->increments('group_id');
            $table->string('group_name',100);
            $table->string('account_category',100);
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
        Schema::dropIfExists('acct_group');
    }
}
