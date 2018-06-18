<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class FeesDiscount extends Model
{
	protected $table = 'fees_discount';
	protected $fillable = ['discount_id', 'semester_id','discount_date', 'student_id', 'amount', 'discount', 'narration', 'operator'];
	protected $primaryKey = 'discount_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getDiscountDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
	public function getAmount($value){
		return number_format($value, 2, '.', ',');
	}
}