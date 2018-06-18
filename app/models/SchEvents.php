<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class SchEvents extends Model
{
	protected $table = 'sch_events';
	protected $fillable = ['event_id', 'event_name', 'date_from', 'academic_id', 'event_type_id', 'date_to','operator'];
	protected $primaryKey = 'event_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getDateToAttribute($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
	public function getDateFromAttribute($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
	
}