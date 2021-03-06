<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class BankPayment extends Model
{
	protected $table = 'bank_payment';
	protected $fillable = ['payment_id', 'bank_id', 'txn_date', 'txn_type', 'amount', 'channel', 'reference', 'narration', 'operator'];
	protected $primaryKey = 'payment_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getTxnDate($value){
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