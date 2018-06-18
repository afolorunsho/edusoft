<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentDiscipline extends Model
{
	protected $table = 'student_discipline';
	protected $fillable = ['discipline_id', 'discipline_date', 'student_id', 'infraction', 'remarks', 'sanction', 'class_id', 'operator'];
	protected $primaryKey = 'discipline_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getDisciplineDate($value){
		return date("d/m/Y", strtotime($value));
	}
}