<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class FeesStudent extends Model
{
	//this is to record optional fees for students in a particular class for a semester
	protected $table = 'fees_student';
	protected $fillable = ['fees_student_id', 'fee_id', 'semester_id', 'student_id', 'class_id','operator'];
	protected $primaryKey = 'fees_student_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}
