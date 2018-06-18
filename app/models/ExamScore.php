<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ExamScore extends Model
{
	protected $table = 'exam_score';
	protected $fillable = ['score_id', 'exam_date', 'student_id', 'exam_score', 
		'exam_id', 'subject_id', 'class_div_id', 'semester_id', 'remarks', 'operator'];
	protected $primaryKey = 'score_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getExamDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
	
}
	