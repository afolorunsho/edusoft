<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
	protected $table = 'event_type';
	protected $fillable = ['event_type_id', 'event_type', 'operator'];
	protected $primaryKey = 'event_type_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}