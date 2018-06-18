<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class FeesInstruct extends Model
{
	protected $table = 'fees_instruction';
	protected $fillable = ['instruction_id', 'instruction', 'class_id','operator'];
	protected $primaryKey = 'instruction_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}