<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use Log; //the default Log file
use DB; //use the default Database
use Excel;
use Auth;
use App\models\AcctGroup;
use App\models\Banks;
use App\models\Expenses;

class AccountController extends Controller
{
    protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		//$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function showGroup(){
		return view('accts.acct_grp');	
	}
	public function infoGroup(){
		$records =  AcctGroup::orderBy('group_name', 'ASC')->get();
		return view('accts.infogroup', compact('records'));
	}
	public function updateGroup(Request $request){
		//if institute id is blank, then it is a new record, else edit
		if($request->ajax()){
			$logRec = array();
			try{
				$group_id = $request->group_id;
				
				if($group_id === NULL || $group_id ==""){
					$logRec = AcctGroup::create($request->all());
				}else{
					$logRec = AcctGroup::updateOrCreate(['group_id'=>$request->group_id], $request->all());
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Account', 'Group', 'Update');
			}
		}
	}
	
	public function editGroup(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = AcctGroup::where('group_id', '=', $request->group_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Account', 'Group', 'Edit');
			}
		}	
	}
	public function delGroup(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('acct_group')->where('group_id',$request->group_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('acct_group')->where('group_id',$request->group_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Accounts', 'Group', 'Delete', $request->group_id, $request->group_id, 
						'Delete a Group');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Account', 'Group', 'Delete');
		}
	}
	
	/////////////////////////////////////////////////////////////////Banks
	public function showBank(){
		$group = AcctGroup::where('account_category', '=', 'Asset')->orderBy('group_name', 'ASC')->get();
		return view('accts.acct_bank', compact('group'));
	}
	public function infoBank(){
		$records =   Banks::join('acct_group','acct_group.group_id', '=', 'banks.group_id')
						->orderBy('bank_name', 'ASC')
						->get();
		return view('accts.infobank', compact('records'));
	}
	public function updateBank(Request $request){
		//if institute id is blank, then it is a new record, else edit
		if($request->ajax()){
			$logRec = array();
			try{
				$bank_id = $request->bank_id;
				
				if($bank_id === NULL || $bank_id ==""){
					$logRec = Banks::create($request->all());
				
				}else{
					$logRec = Banks::updateOrCreate(['bank_id'=>$request->bank_id], $request->all());
					
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Account', 'Bank', 'Update');
			}
		}
	}
	public function getBankList(){
		$banks = Banks::orderBy('bank_name', 'ASC')->get();
		return response($banks);
	}
	
	public function editBank(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = Banks::where('bank_id', '=', $request->bank_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Account', 'Bank', 'Edit');
			}
		}	
	}
	public function delBank(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('banks')->where('bank_id',$request->bank_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('banks')->where('bank_id',$request->bank_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Accounts', 'Bank', 'Delete', $request->bank_id, $request->bank_id, 
						'Delete a Bank');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Account', 'Bank', 'Delete');
		}
	}
	//////////////////////////////////////////////////////////////////////////////////Expenses
	public function showExp(){
		$group = AcctGroup::where('account_category', '=', 'Expenses')->orderBy('group_name', 'ASC')->get();
		return view('accts.acct_exp', compact('group'));
	}
	public function infoExp(){
		$records =   Expenses::join('acct_group','acct_group.group_id', '=', 'expenses.group_id')
						->orderBy('expense_name', 'ASC')
						->get();
		return view('accts.infoexpense', compact('records'));
	}
	public function updateExp(Request $request){
		//if institute id is blank, then it is a new record, else edit
		if($request->ajax()){
			try{
				$logRec = array();
				$expense_id = $request->expense_id;
				if($expense_id === NULL || $expense_id ==""){
					$logRec = Expenses::create($request->all());
				}else{
					$logRec = Expenses::updateOrCreate(['expense_id'=>$request->expense_id], $request->all());
					
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Account', 'Expense', 'Delete');
			}
		}
	}
	
	public function editExp(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = Expenses::where('expense_id', '=', $request->expense_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Account', 'Expense', 'Edit');
			}
		}	
	}
	
	public function getExpList(){
		$expenses = Expenses::orderBy('expense_name', 'ASC')->get();
		return response($expenses);
	}
	
	public function delExp(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('expenses')->where('expense_id',$request->expense_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('expenses')->where('expense_id',$request->expense_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Accounts', 'Expenses', 'Delete', $request->expense_id, $request->expense_id, 
						'Delete Expense');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Account', 'Expense', 'Delete');
		}
	}
	public function report_error($e, $module, $form, $task){
		file_put_contents('file_error.txt', $e->getMessage(). '\n'. $module. '-'. $form. '-'. $task. PHP_EOL, FILE_APPEND);
		//Log::useFiles(storage_path().'/laravel.log');
		Log::info($e->getMessage());
	}
}
?>