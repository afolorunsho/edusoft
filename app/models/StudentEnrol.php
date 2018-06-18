<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentEnrol extends Model
{
	protected $table = 'student_enrol';
	//the class_id here is the class_div_id
	protected $fillable = ['enrol_id','enrol_date', 'active', 'student_id', 'class_id', 'operator'];
	protected $primaryKey = 'enrol_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getEnrolDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}