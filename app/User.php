<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
	protected $fillable = ['name', 'username', 'active', 'phone', 'role_id', 'secret', 'email', 'password'];
	protected $primaryKey='user_id';
	protected $hidden = ['password', 'secret'];
	//protected $hidden = ['password', 'remember_token']; //if you want the token to be part of the signin
	
	public function role()
    {
        return $this->hasOne('App\Role', 'role_id', 'role_id');
    }
	private function getUserRole()
	{
		return $this->role()->getResults();
	}
	public static function hasRole($role_names)
    {
    	if( $role_names == NULL || empty($role_names) || is_null($role_names) || !isset($role_names)){
    		return redirect('/signon')->with('status', 'Your login session has expired...');
    	}else{
			if (Auth::check())
			{
				$check_role =  DB::table('users')
					->join('roles', 'roles.role_id', '=', 'users.role_id')
					->where('users.username', Auth::user()->username)
					->where('roles.role', $role_names)
					->select('roles.role')
					->first();

				return $check_role;
			}
		}
        return false;
    }
	public function setPasswordAttribute($password) {
	   $this->attributes['password'] = bcrypt($password);
   	}
	public function setSecretAttribute($secret) {
	   $this->attributes['secret'] = bcrypt($secret);
   	}
}
