<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class SemesterPromotion extends Model
{
	protected $table = 'semester_promotion';
	protected $fillable = ['semester_promotion_id', 'semester_id', 'subject_passed', 'total_score', 'average_score', 'promotion_id', 'student_id', 'class_div_id', 'operator'];
	protected $primaryKey = 'semester_promotion_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	//the promotion_id will bring the class_id, semester_id, minimum score and subject, date and other details
}
