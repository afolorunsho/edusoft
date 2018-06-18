<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentExamScore extends Model
{
	protected $table = 'student_exam_score';
	protected $fillable = ['exam_score_id', 'exam_id', 'exam_score', 'student_id', 'semester_id', 'subject_id', 'class_div_id', 'operator'];
	protected $primaryKey = 'exam_score_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	//the promotion_id will bring the class_id, semester_id, minimum score and subject, date and other details
}
