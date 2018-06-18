<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
	protected $table = 'student_attendance';
	protected $fillable = ['attendance_id', 'attendance_date', 'student_id', 'arrival_time', 'remarks', 'class_id', 'operator'];
	protected $primaryKey = 'attendance_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getAttendanceDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}