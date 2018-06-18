<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class TxnFT extends Model
{
	protected $table = 'txn_ft';
	//the invoice id is system-generated in order to associate a number to a batch of transactions processed together
	protected $fillable = ['txn_id', 'txn_date', 'amount','narration', 'bank_ref','pay_channel','bank_from','bank_to','operator','bank_payment_id'];
	protected $primaryKey = 'txn_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
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