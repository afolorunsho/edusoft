<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ExamClass extends Model
{
	protected $table = 'exam_class';
	protected $fillable = ['exam_class_id', 'class_id', 'exam_id', 'exam_weight', 'max_score', 'operator'];
	protected $primaryKey = 'exam_class_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}