<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Fees extends Model
{
	protected $table = 'fees';
	protected $fillable = ['fee_id', 'fee_name', 'group_id','operator'];
	protected $primaryKey = 'fee_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}