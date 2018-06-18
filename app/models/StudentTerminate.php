<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentTerminate extends Model
{
	protected $table = 'student_terminate';
	protected $fillable = ['terminate_id', 'terminate_date', 'student_id', 'remarks', 'class_id', 'operator'];
	protected $primaryKey = 'terminate_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getTerminateDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}