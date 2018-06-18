<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ClassTransfer extends Model
{
	protected $table = 'class_transfer';
	protected $fillable = ['transfer_id', 'transfer_date', 'student_id', 'class_from', 'remarks', 'class_to', 'operator'];
	protected $primaryKey = 'transfer_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getTransferDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}	
}