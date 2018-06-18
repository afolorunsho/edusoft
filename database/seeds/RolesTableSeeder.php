<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
			['role_id'=>'1', 'role'=>'Administrator'],
			['role_id'=>'2', 'role'=>'Admin'],
			['role_id'=>'3', 'role'=>'IT'],
			['role_id'=>'4', 'role'=>'Operator'],
			['role_id'=>'5', 'role'=>'Supervisor'],
			['role_id'=>'6', 'role'=>'Manager'],
			['role_id'=>'7', 'role'=>'Student'],
			['role_id'=>'8', 'role'=>'Teacher'],
			['role_id'=>'9', 'role'=>'Management'],
			['role_id'=>'10', 'role'=>'Parent'],
			['role_id'=>'11', 'role'=>'CEO'],
			['role_id'=>'50', 'role'=>'New User']
		]);
    }
}
