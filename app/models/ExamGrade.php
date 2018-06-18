<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ExamGrade extends Model
{
	protected $table = 'exam_grade';
	protected $fillable = ['exam_grade_id', 'score_grade_id','score_from', 'score_to', 'class_id', 'remarks','operator'];
	protected $primaryKey = 'exam_grade_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}