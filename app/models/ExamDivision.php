<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ExamDivision extends Model
{
	protected $table = 'exam_division';
	protected $fillable = ['exam_div_id', 'score_div_id','score_from', 'score_to', 'class_id', 'remarks', 'operator'];
	protected $primaryKey = 'exam_div_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}