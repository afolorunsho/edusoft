<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class FeesStruct extends Model
{
	protected $table = 'fees_struct';
	protected $fillable = ['struct_id', 'amount', 'start_date', 'fee_id', 'optional', 'class_id','operator'];
	protected $primaryKey = 'struct_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getStartDateAttribute($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}