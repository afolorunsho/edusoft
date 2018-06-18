<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
	protected $table = 'semester';
	protected $fillable = ['semester_id', 'semester', 'academic_id', 'date_from', 'date_to', 'operator'];
	protected $primaryKey = 'semester_id';
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
	public function academics(){
        return $this->belongsTo('App\models\Academic');
    }
	
}