<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Role extends Authenticatable
{
    //
	protected $table = 'roles';
	protected $fillable=['role_id', 'role'];
	protected $primaryKey='role_id';
	
	public function user()
    {
        return $this->hasOne('App\User', 'role_id', 'role_id');
    }
}
