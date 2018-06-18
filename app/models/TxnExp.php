<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class TxnExp extends Model
{
	protected $table = 'txn_exp';
	//the invoice id is system-generated in order to associate a number to a batch of transactions processed together
	protected $fillable = ['txn_id', 'invoice_id','voucher_no','beneficiary','txn_date', 'bank_payment_id',
		'qty','price','amount','narration','pay_channel','bank_id','expense_id','operator'];
	protected $primaryKey = 'txn_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function expenses(){
        return $this->belongsTo('App\models\Expenses');
    }
    public function banks(){
        return $this->belongsTo('App\models\Banks');
    }
	public function getTxnDateAttribute($value){
		
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}
?>