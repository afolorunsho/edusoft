<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentExit extends Model
{
	protected $table = 'student_exit';
	protected $fillable = ['exit_id', 'exit_date', 'student_id', 'reason', 'remarks', 'class_id', 'operator'];
	protected $primaryKey = 'exit_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getExitDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}