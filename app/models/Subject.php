<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
	protected $table = 'subjects';
	protected $fillable = ['subject_id', 'subject', 'short_name','operator'];
	protected $primaryKey = 'subject_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}