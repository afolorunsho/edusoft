<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ExamPromotion extends Model
{
	protected $table = 'exam_promotion';
	protected $fillable = ['promotion_id', 'promotion_date', 'passed_score', 'average_score', 'passed_subject', 'class_id', 'semester_id','operator'];
	protected $primaryKey = 'promotion_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
	public function getPromotionDateAttribute($value){
		if( $value == "0000-00-00" || $value == NULL ){
			return "";
		}else{
			return date("d/m/Y", strtotime($value));
		}
	}
}
