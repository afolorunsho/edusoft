<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
	
	protected $table = 'expenses';
	protected $fillable = ['expense_id', 'expense_name', 'group_id','operator'];
	protected $primaryKey = 'expense_id';
	public $timestamps = true;  //this is necessary to tell laravel not to update these fields as they may not be created
	
}