<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class AcctGroup extends Model
{
	
	protected $table = 'acct_group';
	protected $fillable = ['group_id', 'group_name', 'account_category','operator'];
	protected $primaryKey = 'group_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}