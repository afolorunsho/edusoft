<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
	protected $table = 'student_promotion';
	protected $fillable = ['promotion_id', 'promotion_date', 'student_id', 'class_from', 'class_to', 'remarks', 'operator'];
	protected $primaryKey = 'promotion_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getPromotionDate($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}