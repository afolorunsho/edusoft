<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentExamPromotion extends Model
{
	protected $table = 'student_exam_promotion';
	protected $fillable = ['exam_promotion_id', 'promotion_id', 'student_id', 'passed_subject', 'passed_score', 'failed_subject', 'operator'];
	protected $primaryKey = 'exam_promotion_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	//the promotion_id will bring the class_id, semester_id, minimum score and subject, date and other details
}
