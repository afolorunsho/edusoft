<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
	protected $table = 'institution';
	protected $fillable = ['institute_id', 'sch_code', 'sch_name', 'phone', 'email', 'motto','website', 'country', 'region', 'address', 'reg_no', 'reg_date', 'photo_image','logo_image','header_image', 'operator'];
	protected $primaryKey = 'institute_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getRegDateAttribute($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
	/*public function setRegDatAttribute($value){
		$_date = str_replace('/', '-', $value);
		$this->attributes['reg_date'] = date('Y-m-d', strtotime($_date));
	}*/
	
}