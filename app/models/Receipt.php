<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
	protected $table = 'receipts';
	//the invoice id is system-generated in order to associate a number to a batch of transactions processed together
	protected $fillable = ['receipt_id'];
	protected $primaryKey = 'receipt_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	static public function autoNumber(){
		$id = 0;	
		$ReceiptID = Receipt::max('receipt_id');
		if( $ReceiptID != 0){
			$id = $InvoiceID + 1;
			Receipt::insert(['receipt_id'=>$id]);
		}else{
			$id = 1;
			Receipt::insert(['receipt_id'=>$id]);
		}
		return $id;
	}
}