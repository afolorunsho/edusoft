<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class SchClass extends Model
{
	protected $table = 'sch_classes';
	protected $fillable = ['class_id', 'class_name', 'school_id', 
		'description', 'capacity','sequence', 'div_no', 'div_type', 'operator'];
	protected $primaryKey = 'class_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}