<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ExamName extends Model
{
	protected $table = 'exam_name';
	protected $fillable = ['exam_id', 'exam_name', 'short_name','operator'];
	protected $primaryKey = 'exam_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}