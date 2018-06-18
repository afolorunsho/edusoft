<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	protected $table = 'invoice';
	//the invoice id is system-generated in order to associate a number to a batch of transactions processed together
	protected $fillable = ['invoice_id'];
	protected $primaryKey = 'invoice_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	static public function autoNumber(){
		$id = 0;	
		$InvoiceID = Invoice::max('invoice_id');
		if( $InvoiceID != 0){
			$id = $InvoiceID + 1;
			Invoice::insert(['invoice_id'=>$id]);
		}else{
			$id = 1;
			Invoice::insert(['invoice_id'=>$id]);
		}
		return $id;
	}
}