<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
	protected $table = 'schools';
	protected $fillable = ['school_id', 'school_name', 'sequence', 'address','operator'];
	protected $primaryKey = 'school_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}