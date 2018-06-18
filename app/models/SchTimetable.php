<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class SchTimetable extends Model
{
	protected $table = 'sch_timetable';
	protected $fillable = ['timetable_id', 'period_name', 'school_id', 'time_from', 'time_to','operator'];
	protected $primaryKey = 'timetable_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}