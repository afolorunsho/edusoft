<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{
	protected $table = 'academics';
	protected $fillable = ['academic_id', 'academic', 'date_from', 'date_to', 'active','operator'];
	protected $primaryKey = 'academic_id';
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