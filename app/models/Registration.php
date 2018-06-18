<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
	protected $table = 'students';
	protected $fillable = ['student_id', 'reg_no', 'tribe','reg_date', 'first_name', 'last_name', 'other_name', 'dob', 'height', 'weight', 'blood', 'gender', 'district', 'region', 'town', 'lga', 'state_origin', 'nationality', 'religion', 'address', 'email', 'phone', 'active', 'photo', 'guardian', 'relationship', 'guard_office', 'guard_home', 'guard_email', 'guard_phone', 'operator'];
	protected $primaryKey = 'student_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getRegDateAttribute($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
	public function getDobAttribute($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
	public function getEnrolDateAttribute($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
	public function setFirstNameAttribute($value)
    {
      	$this->attributes['first_name'] = ucfirst(strtolower($value));
    }
	public function setLastNameAttribute($value)
    {
      	$this->attributes['last_name'] = ucfirst(strtolower($value));
    }
	public function setEmailAttribute($value)
    {
    	$this->attributes['email'] = strtolower($value);
    }
	public function setGuardEmailAttribute($value)
    {
       	$this->attributes['guard_email'] = strtolower($value);
    }
	public function getGenderAttribute($value){
		
		if( $value == "0"){
			return "Female";
		}else{
			return "Male";
		}
	}
	public function setGenderAttribute($value)
    {
		if( $value == "Female"){
			$this->attributes['gender'] = "0";
		}else{
			$this->attributes['gender'] = "1";
		}
    }
	
	public function getOtherNameAttribute($value){
		
		if( $value == null || $value == "" ){
			return "";
		}
	}
	
}