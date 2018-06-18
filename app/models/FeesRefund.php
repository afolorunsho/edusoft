<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class FeesRefund extends Model
{
	protected $table = 'fees_refund';
	protected $fillable = ['refund_id', 'refund_date', 'semester_id', 'student_id', 'amount', 'narration', 'bank_payment_id', 'fee_id', 'operator'];
	protected $primaryKey = 'refund_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getRefundDate($value){
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