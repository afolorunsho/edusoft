<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ScoreDiv extends Model
{
	protected $table = 'score_div';
	protected $fillable = ['score_div_id', 'score_div', 'operator'];
	protected $primaryKey = 'score_div_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}