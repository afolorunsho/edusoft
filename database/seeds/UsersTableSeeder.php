<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
			'name'=>'Ade Adewuyi',
			'username'=>'aadewuyi',
			'email'=>'infopubtech@gmail.com',
			'password'=>bcrypt('Rc&1052258'),
			'role_id'=>1,
			'remember_token' => str_random(10),
			'active'=>1,
		]);
		User::insert([
			'name'=>'System Administrator',
			'username'=>'administrator',
			'email'=>'infopubtech@gmail.com',
			'password'=>bcrypt('Rc&1052258'),
			'role_id'=>1,
			'remember_token' => str_random(10),
			'active'=>1,
		]);
    }
}
