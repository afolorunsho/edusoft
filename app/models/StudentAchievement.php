<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentAchievement extends Model
{
	protected $table = 'student_achievement';
	protected $fillable = ['achievement_id', 'achievement_date', 'student_id', 'achievement', 'remarks', 'award', 'class_id', 'operator'];
	protected $primaryKey = 'achievement_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getAchievementDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}