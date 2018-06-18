<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
	protected $table = 'syllabus';
	protected $fillable = ['syllabus_id', 'syllabus', 'class_id','subject_id', 'operator'];
	protected $primaryKey = 'syllabus_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}