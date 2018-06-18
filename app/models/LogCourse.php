<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class LogCourse extends Model
{
    protected $table = 'log_course';
	protected $fillable = ['username', 'modulename', 'formname', 'operation', 'record_id', 'record_code', 'activity'];
	protected $primaryKey = 'log_id';
	public $timestamps = true;
}
