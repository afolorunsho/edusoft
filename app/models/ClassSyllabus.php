<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ClassSyllabus extends Model
{
	protected $table = 'class_syllabus';
	protected $fillable = ['class_syllabus_id', 'syllabus_id', 'class_div_id', 'operator'];
	protected $primaryKey = 'class_syllabus_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	/*
	`class_syllabus`(`class_syllabus_id`, `class_div_id`, `syllabus_id`,
	`syllabus`(`syllabus_id`, `syllabus`, `class_id`, `subject_id`,*/
}