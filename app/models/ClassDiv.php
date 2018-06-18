<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ClassDiv extends Model
{
	protected $table = 'class_div';
	protected $fillable = ['class_div_id', 'class_div', 'description', 'class_id', 'operator'];
	protected $primaryKey = 'class_div_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created

}