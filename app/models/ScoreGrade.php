<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ScoreGrade extends Model
{
	protected $table = 'score_grade';
	protected $fillable = ['score_grade_id', 'score_grade', 'operator'];
	protected $primaryKey = 'score_grade_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}