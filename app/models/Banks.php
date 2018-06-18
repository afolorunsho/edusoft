<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Banks extends Model
{
	
	protected $table = 'banks';
	protected $fillable = ['bank_id', 'bank_name', 'group_id','operator'];
	protected $primaryKey = 'bank_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}