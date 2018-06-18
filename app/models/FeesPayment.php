<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class FeesPayment extends Model
{
	protected $table = 'fees_payment';
	protected $fillable = ['payment_id', 'payment_date', 'student_id', 'amount', 'semester_id', 'narration', 'bank_payment_id', 'class_div_id','fee_id', 'operator'];
	protected $primaryKey = 'payment_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getPaymentDate($value){
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