<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class TeachersComment extends Model
{
	protected $table = 'teachers_comment';
	protected $fillable = ['comment_id', 'student_id', 'comment', 'class_div_id', 'semester_id', 'operator'];
	protected $primaryKey = 'teachers_comment';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	//the promotion_id will bring the class_id, semester_id, minimum score and subject, date and other details
}
