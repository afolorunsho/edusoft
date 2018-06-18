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
use App\models\TxnExp;
use App\models\BankPayment;
use App\models\TxnFT;
use App\models\Banks;
use App\models\Receipts;
use App\models\Expenses;

class TxnController extends Controller
{
    protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function showTxnExp(){
		$banks =  Banks::orderBy('bank_name', 'ASC')->get();
		return view('txn.txn_exp', compact('banks'));
	}
	public function searchExp(Request $request){
		$records = array();
		$search_para = $request->search_param;
		if( $search_para == 'all' || $search_para == 'search_all'){
			$records = TxnExp::orderBy('txn_id', 'ASC')
				->join('banks','banks.bank_id', '=', 'txn_exp.bank_id')
				->join('expenses','expenses.expense_id', '=', 'txn_exp.expense_id')
				->get();
		}
		return view('txn.search_exp', compact('records'));
	}
	public function updateTxnExp(Request $request){
		try{
			$logRec = array();
			///convert date and float fields to dbase formats here
			$invoice_id = $request->voucher_no;
			$voucher_no = $request->voucher_no;
			$beneficiary = $request->beneficiary;
			$txn_date = $request->txn_date;
			$pay_channel = $request->pay_channel;
			$bank_ref = $request->bank_ref;
			$bank_id = $request->bank_id;
			$operator = $request->operator;
			$total_amount  = str_replace(",","", $request->total_amount);
			$narration  = $request->description;
			
			if($request->ajax()){
				//update bank payment table first. This is the table that records all payments
				$record = BankPayment::create(
					array(
						'bank_id' => $bank_id,
						'txn_date' => $txn_date,
						'txn_type' => 'OUT',
						'amount' => $total_amount, 
						'channel' => $pay_channel,
						'reference' => $bank_ref,
						'narration' => $narration, 
						'operator' =>$operator
					)
				);
				$payment_id = $record->payment_id;
				
				$c = count($request->qty);
				for($i=0;$i<$c;$i++){
					
					$logRec = TxnExp::create(
						array(
							'bank_payment_id' => $payment_id,
							'voucher_no'  => $voucher_no,
							'beneficiary'  => $beneficiary,
							'txn_date' => $txn_date,
							'pay_channel' => $pay_channel,
							'bank_ref' => $bank_ref,
							'bank_id' =>$bank_id,
							'operator' => $operator,
							'invoice_id' => $invoice_id,
							'expense_id' => $request->expense_id[$i],
							'qty' => str_replace(",","",$request->qty[$i]),
							'price' => str_replace(",","",$request->price[$i]),
							'amount' => str_replace(",","",$request->amount[$i]),
							'narration' => $request->narration[$i]
						)
					);
				}
				return $logRec;
			}
		}catch (Exception $e) {$this->report_error($e, 'Txn', 'Expense', 'Update');}
	}
	public function showTxnFT(){
		$banks =  Banks::orderBy('bank_name', 'ASC')->get();
		return view('txn.txn_ft', compact('banks'));
	}
	public function updateTxnFT(Request $request){
		try{
			///convert date and float fields to dbase formats here
			
			//because of the need to link the two references for transfer(IN and OUT), we have to create another field
			//called bacth_ref. so that if the transfer is needed in txn_ft, then the two sides will also be deleted as well
			$operator = $request->operator;
			$logRec = array();
			
			if($request->ajax()){
				//iterate through the table rows to add each record
				$c = count($request->txn_date);
				for($i=0;$i<$c;$i++){
					
					$record = BankPayment::create(
						array(
							'bank_id' => $request->bank_from[$i],
							'txn_date' => TxnController::toDbaseDate($request->txn_date[$i]),
							'txn_type' => 'OUT',
							'amount' => str_replace(",","",$request->amount[$i]),
							'channel' => $request->pay_channel[$i],
							'reference' => $request->txn_ref[$i],
							'narration' => $request->narration[$i],
							'operator' => $operator
						)
					);
					BankPayment::create(
						array(
							//'payment_id' => $record->payment_id,
							'bank_id' =>  $request->bank_to[$i],
							'txn_date' => TxnController::toDbaseDate($request->txn_date[$i]),
							'txn_type' => 'IN',
							'amount' => str_replace(",","",$request->amount[$i]),
							'channel' => $request->pay_channel[$i],
							'reference' => $request->txn_ref[$i],
							'narration' => $request->narration[$i],
							'operator' => $operator
						)
					);
					$logRec = TxnFT::create(
						array(
							'bank_payment_id' => $record->payment_id,
							'txn_date'  => TxnController::toDbaseDate($request->txn_date[$i]),
							'bank_from' => $request->bank_from[$i],
							'bank_to' => $request->bank_to[$i],
							'pay_channel' => $request->pay_channel[$i],
							'operator' => $operator,
							'bank_ref' =>  $request->txn_ref[$i],
							'amount' => str_replace(",","",$request->amount[$i]),
							'narration' =>$request->narration[$i]
						)
					);
				}
				return $logRec;
			}
		}catch (Exception $e) {$this->report_error($e, 'Txn', 'FT', 'Update');}
	}
	
	public function searchFT(Request $request){
		$records = array();
		$search_para = $request->search_param;
		if( $search_para == 'all' || $search_para == 'search_all'){
			$records = DB::table('txn_ft as a')
				->join('banks as b','a.bank_from', '=', 'b.bank_id')
				->join('banks as c','a.bank_to', '=', 'c.bank_id')
				->select('a.txn_id', 'a.amount', 'a.txn_date', 'a.narration', 'a.bank_ref', 
					'a.pay_channel','b.bank_name as bank_from','c.bank_name as bank_to')
				->orderBy('a.txn_date', 'DESC')
				->get();
		}
		return view('txn.search_ft', compact('records'));
	}
	public function queryExpense(Request $request){
		try{
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = $request->search_param;
			$search_val = $request->search_val;
			$operator = '=';
			if( $search_param == "All"){
				/////now search
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id')
					->where('a.created_at', '>=', $from_date)
					->where('a.created_at', '<=', $to_date)
					->orderBy('a.created_at', 'DESC')
					->get();
			}
			if( $search_param == "Amount"){
				$operator = $search_val[0];
				if( $operator == "<" || $operator == ">"){
					//If length is omitted, the substring starting from start until the end of the string will be returned. 
					$search_val = substr($search_val, 1);  
				}
				$search_val = str_replace(',', '', $search_val);
				/////now search
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.amount', $operator, $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Channel"){
				/////now search
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.pay_channel', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Beneficiary"){
				/////now search
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.beneficiary', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Voucher"){
				/////now search
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('c.voucher_no', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Code"){
				/////now search
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('c.expense_name', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Bank"){
				/////now search
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('b.bank_name', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			//return view('txn.search_exp', compact('records'));
			return $records;
			
		} catch (\Exception $e) {$this->report_error($e, 'Fees', 'Bank-Payment', 'Search');}
	}
	public function queryFT(Request $request){
		try{
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = $request->search_param;
			$search_val = $request->search_val;
			$operator = '=';
			if( $search_param == "All"){
				/////now search
				$records = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id')
					->where('a.created_at', '>=', $from_date)
					->where('a.created_at', '<=', $to_date)
					->orderBy('a.created_at', 'DESC')
					->get();
			}
			if( $search_param == "Amount"){
				$operator = $search_val[0];
				if( $operator == "<" || $operator == ">"){
					//If length is omitted, the substring starting from start until the end of the string will be returned. 
					$search_val = substr($search_val, 1);  
				}
				$search_val = str_replace(',', '', $search_val);
				/////now search
				$records = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.amount', $operator, $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Channel"){
				/////now search
				$records = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.pay_channel', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Bank_From"){
				/////now search
				$records = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('b.bank_name', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Bank_To"){
				/////now search
				$records = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('c.bank_name', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Txn_Ref"){
				/////now search
				$records = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.bank_ref', $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			return view('txn.search_ft', compact('records'));
			//return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Transaction', 'Funds Transfer', 'Search');
		}
	}
	public function delExpBatch(Request $request){
		
		$payment_id =  DB::table('txn_exp')->where('txn_id',$request->txn_id)->value('bank_payment_id');
		//now get the records from the bank_payment table
		$bank = DB::table('bank_payment as a')
						->join('banks as b','b.bank_id', '=', 'a.bank_id')
						->where('a.payment_id',$payment_id)
						->where('a.txn_type','OUT')
						->first();
		$record =  array();
		$records =  array();
		
		$record['txn_date'] = date("d/m/Y", strtotime($bank->txn_date));
		$record['voucher'] = '';
		$record['beneficiary'] = '';
		$record['channel'] = $bank->channel;
		$record['bank'] = $bank->bank_name;
		$record['reference'] = $bank->reference;
		$record['description'] = $bank->narration;
		$record['total_amount'] = number_format($bank->amount, 2, '.', ',');
		$record['expense'] = '';
		$record['qty'] = '';
		$record['price'] = '';
		$record['amount'] = '';
		$record['narration'] = '';
		///////////////////////////////////////////
		array_push($records, $record);
		
		$expense_record = DB::table('txn_exp as a')
						->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
						->join('expenses as c','c.expense_id', '=', 'a.expense_id')
						->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
							'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name')
						->where('a.bank_payment_id',$payment_id)
						->get();
		foreach ($expense_record as $expense){
			$record =  array();
			$record['txn_date'] = '';
			$record['voucher'] = $expense->voucher_no;
			$record['beneficiary'] = $expense->beneficiary;
			$record['channel'] = $bank->channel;
			$record['bank'] = $bank->bank_name;
			$record['reference'] = $bank->reference;
			$record['description'] = $bank->narration;
			$record['total_amount'] = '';
			$record['expense'] = $expense->expense_name;
			$record['qty'] = $expense->qty;
			$record['price'] = $expense->price;
			$record['amount'] = number_format($expense->amount, 2, '.', ',');
			$record['narration'] = $expense->narration;
			///////////////////////////////////////////
			array_push($records, $record);
		}
		return $records;
	}
	public function delExpense(Request $request){
		try{
			//get the user who made the posting
			$posted_by =  DB::table('txn_exp')->where('txn_id',$request->txn_id)->value('operator');
			$post_code = DB::table('txn_exp')->where('txn_id',$request->txn_id)->value('expense_id');
			$post_des = DB::table('txn_exp')->where('txn_id',$request->txn_id)->value('narration');
			
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				//get the payment reference from txn_exp
				$payment_id =  DB::table('txn_exp')->where('txn_id',$request->txn_id)->value('bank_payment_id');
				//now delete this record, the entire batch making this posting in txn_exp table
				$logRec = DB::table('txn_exp')->where('bank_payment_id',$payment_id)->delete();
				//delete the entire batch from BankPayment Table
				DB::table('bank_payment')->where('payment_id',$payment_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Transaction', 'Expense', 'Delete', $post_code, $post_des,'-');
				}
				return $logRec;
			}
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Transaction', 'Expense', 'Delete');
		}
	}
	public function delTransfer(Request $request){
		try{
			$posted_by =  DB::table('txn_ft')->where('txn_id',$request->txn_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				//get the payment reference
				$payment_id =  DB::table('txn_ft')->where('txn_id',$request->txn_id)->value('bank_payment_id');
				
				$post_code = DB::table('txn_ft')->where('txn_id',$request->txn_id)->value('bank_from');
				$post_des = DB::table('txn_ft')->where('txn_id',$request->txn_id)->value('narration');
				
				//delete the entire batch from BankPayment Table
				DB::table('bank_payment')->where('payment_id',$payment_id)->delete();
				//now delete the posting: note that this one-to-one unlike the expense
				$logRec = DB::table('txn_ft')->where('txn_id',$request->txn_id)->delete();

				if(count($logRec) > 0){
					SysController::postLog('Transaction', 'Transfer', 'Delete', $post_code, $post_des, '-');
				}
				return $logRec;
			}
		}
		catch (\Exception $e) {$this->report_error($e, 'Transaction', 'Transfer', 'Delete');}
	}
	public function excelExpense(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$csv = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id',
						'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->orderBy('a.txn_date', 'ASC')
					->get();
			
			return \Excel::create('expense-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('txn_id','bank_name', 'beneficiary', 'txn_date', 'pay_channel', 'bank_ref',
						'qty', 'price', 'amount', 'narration', 'voucher_no', 'expense_name', 'bank_payment_id',
						'operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
				
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'Excel');
			return redirect()->back();
		}
	}
	public function excelTransfer(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$csv = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id',
						'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->orderBy('a.txn_date', 'ASC')
					->get();
			
			return \Excel::create('transfer-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('txn_id','bank_from', 'bank_to', 'amount', 
						'txn_date', 'pay_channel', 'bank_ref','narration', 'bank_payment_id',
						'operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'Excel');
			return redirect()->back();
		}
	}
	public function importExpense(Request $request){
		try{
			ini_set("auto_detect_line_endings", true);
			$global_array = array();
			$file_load = $request->file('table_file');
			
			//if a file is present on the request using the hasFile method
			if ($request->hasFile('table_file')) {
				//may verify that there were no problems uploading the file via the isValid method
				if ($file_load->isValid()) {
					$temp = $_FILES['table_file']['tmp_name'];
					$handle = fopen($temp, 'r');
					
					$content = '<table class="table table-hover table-striped table-condensed" id="import_expense_table" style="font-size:100%">';
					$i = 0;//initialize
					while (!feof($handle)) {
						$value[] = fgets($handle, 1024);
						$lineArr = explode("\t", $value[$i]);
							
						list($date, $voucher, $beneficiary, $channel, $bank, $reference, $expense, $qty, $price, $amount, $narration) = $lineArr;
						//take the first line as the header
						if( $i == 0){
							$content .='<thead>';
							$content .='<tr>';
							$content .='<th>'.str_replace("\"","",$date).'</th>';
							$content .='<th>'.str_replace("\"","",$voucher).'</th>';
							$content .='<th>'.str_replace("\"","",$beneficiary).'</th>';
							$content .='<th>'.str_replace("\"","",$channel).'</th>';
							$content .='<th>'.str_replace("\"","",$bank).'</th>';
							$content .='<th>'.str_replace("\"","",$reference).'</th>';
							$content .='<th>'.str_replace("\"","",$expense).'</th>';
							$content .='<th>'.str_replace("\"","",$qty).'</th>';
							$content .='<th>'.str_replace("\"","",$price).'</th>';
							$content .='<th>'.str_replace("\"","",$amount).'</th>';
							$content .='<th>'.str_replace("\"","",$narration).'</th>';
							$content .='</tr>';
							$content .='</thead>';
							$content .='<tbody>';
						}
						if( $i > 0){
							$content .='<tr>';
							$content .='<td>'.str_replace("\"","",$date).'</td>';
							$content .='<td>'.str_replace("\"","",$voucher).'</td>';
							$content .='<td>'.str_replace("\"","",$beneficiary).'</td>';
							$content .='<td>'.str_replace("\"","",$channel).'</td>';
							$content .='<td>'.str_replace("\"","",$bank).'</td>';
							$content .='<td>'.str_replace("\"","",$reference).'</td>';
							$content .='<td>'.str_replace("\"","",$expense).'</td>';
							$content .='<td>'.str_replace("\"","",$qty).'</td>';
							$content .='<td>'.str_replace("\"","",$price).'</td>';
							$content .='<td>'.str_replace("\"","",$amount).'</td>';
							$content .='<td>'.str_replace("\"","",$narration).'</td>';
							$content .='</tr>';
						}
						$i++;
					}
					$content .='</tbody></table>';
					fclose($handle);
					return $content;
				}
			}
		}
		catch (Exception $e) { 
			$this->report_error($e, 'Txn', 'Expense', 'Upload'); 
		}
	}
	//work on this function
	public function updateExpenseImport(Request $request){
		try{
			$logRec = array();
			//get bank_id, expense id
			$invoice_id = "-";
			$voucher_no = $request->voucher;
			$beneficiary = $request->beneficiary;
			$txn_date = $request->expense_date;
			$pay_channel = $request->channel;
			$bank_ref = $request->reference;
			$bank_id = $this->getBankID(trim($request->bank));
			$expense_id = $this->getExpenseID(trim($request->expense));
			$qty = $request->qty;
			$price = $request->price;
			$amount = $request->amount;
			$narration = $request->narration;
			$operator = $request->operator;
			$total_amount  = str_replace(",","", $request->amount);
			
			if(!empty($expense_id) && !empty($bank_id)){
				//update bank payment table first. This is the table that records all payments
				$record = BankPayment::create(
					array(
						'bank_id' => $bank_id,
						'txn_date' => $txn_date,
						'txn_type' => 'OUT',
						'amount' => $total_amount, 
						'channel' => $pay_channel,
						'reference' => $bank_ref,
						'narration' => $narration, 
						'operator' =>$operator
					)
				);
				$payment_id = $record->payment_id;
				
				$logRec = TxnExp::create(
					array(
						'bank_payment_id' => $payment_id,
						'voucher_no'  => $voucher_no,
						'beneficiary'  => $beneficiary,
						'txn_date' => $txn_date,
						'pay_channel' => $pay_channel,
						'bank_ref' => $bank_ref,
						'bank_id' =>$bank_id,
						'operator' => $operator,
						'invoice_id' => $invoice_id,
						'expense_id' => $expense_id,
						'qty' => $qty,
						'price' => $price,
						'amount' => $amount,
						'narration' => $narration
					)
				);
				return $logRec;
			}
		}catch (Exception $e) {$this->report_error($e, 'Txn', 'Expense', 'Update');}
		
	}
	public function importLodgement(Request $request){
		try{
			ini_set("auto_detect_line_endings", true);
			$global_array = array();
			$file_load = $request->file('table_file');
			
			//if a file is present on the request using the hasFile method
			if ($request->hasFile('table_file')) {
				//may verify that there were no problems uploading the file via the isValid method
				if ($file_load->isValid()) {
					$temp = $_FILES['table_file']['tmp_name'];
					$handle = fopen($temp, 'r');
					$content = '<table class="table table-hover table-striped table-condensed" id="import_lodgement_table" style="font-size:100%">';
					$i = 0;//initialize
					while (!feof($handle)) {
						$value[] = fgets($handle, 1024);
						$lineArr = explode("\t", $value[$i]);
							
						list($date, $channel, $paying, $receiving, $reference, $amount, $narration) = $lineArr;
						//get the last name o the student and the first name based on the reg no
						if( $i == 0){
							$content .='<thead>';
							$content .='<tr>';
							$content .='<th>'.str_replace("\"","",$date).'</th>';
							$content .='<th>'.str_replace("\"","",$channel).'</th>';
							$content .='<th>'.str_replace("\"","",$paying).'</th>';
							$content .='<th>'.str_replace("\"","",$receiving).'</th>';
							$content .='<th>'.str_replace("\"","",$reference).'</th>';
							$content .='<th>'.str_replace("\"","",$amount).'</th>';
							$content .='<th>'.str_replace("\"","",$narration).'</th>';
							$content .='</tr>';
							$content .='</thead>';
							$content .='<tbody>';
						}
						if( $i > 0){
							$content .='<tr>';
							$content .='<td>'.str_replace("\"","",$date).'</td>';
							$content .='<td>'.str_replace("\"","",$channel).'</td>';
							$content .='<td>'.str_replace("\"","",$paying).'</td>';
							$content .='<td>'.str_replace("\"","",$receiving).'</td>';
							$content .='<td>'.str_replace("\"","",$reference).'</td>';
							$content .='<td>'.str_replace("\"","",$amount).'</td>';
							$content .='<td>'.str_replace("\"","",$narration).'</td>';
							$content .='</tr>';
						}
						$i++;
					}
					$content .='</tbody></table>';
					fclose($handle);
					return $content;
				}
			}
		}
		catch (Exception $e) { 
			$this->report_error($e, 'Fees', 'Lodgement', 'Upload'); 
		}
	}
	public function updateImportLodgement(Request $request){
		//NB: work on payment ID to tie related IN and OUt in banp_payment table
		try{
			$logRec = array();
			$lodgement_date = $request->lodgement_date;
			//get the ids for the codes used in the table	
			$paying_id = $this->getBankID(trim($request->paying));
			$receiving_id = $this->getBankID(trim($request->receiving));
			
			if( !empty($receiving_id) && !empty($paying_id)){
				$record = BankPayment::create(
					array(
						'bank_id' => $paying_id,
						'txn_date' => $lodgement_date,
						'txn_type' => 'OUT',
						'amount' =>  str_replace(",","",$request->amount),
						'channel' =>  $request->channel,
						'reference' => $request->reference,
						'narration' => $request->narration,
						'operator' => $request->operator
					)
				);
				BankPayment::create(
					array(
						//'payment_id' => $record->payment_id,
						'bank_id' =>  $receiving_id,
						'txn_date' => $lodgement_date,
						'txn_type' => 'IN',
						'amount' => str_replace(",","",$request->amount),
						'channel' =>  $request->channel,
						'reference' => $request->reference,
						'narration' => $request->narration,
						'operator' => $request->operator
					)
				);
				$logRec = TxnFT::create(
					array(
						'bank_payment_id' => $record->payment_id,
						'txn_date'  => $lodgement_date,
						'bank_from' => $paying_id,
						'bank_to' => $receiving_id,
						'pay_channel' => $request->channel,
						'operator' => $request->operator,
						'bank_ref' =>  $request->reference,
						'amount' => str_replace(",","",$request->amount),
						'narration' =>$request->narration
					)
				);
			}
			return $logRec;
		} catch (Exception $e) {
			$this->report_error($e, 'Txn', 'Lodgement', 'Import');
        }
	}
	
	public function excelLodgement(Request $request){
		//increase memory space
		
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			
			$csv = TxnFT::select('txn_id', 'txn_date', 'amount', 'narration', 'bank_ref', 'pay_channel', 
						'a.bank_name as from_bank', 'b.bank_name as to_bank', 'txn_ft.operator', 'txn_ft.reviewer', 'txn_ft.created_at')
					->join('banks as a','txn_ft.bank_from', '=', 'a.bank_id')
					->join('banks as b','txn_ft.bank_to', '=', 'b.bank_id')
					->where('txn_date', '>=', $from_date)
					->where('txn_date', '<=', $to_date)
					->orderBy('txn_date', 'ASC')
					->get();
			
			return \Excel::create('bank-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('txn_id', 'txn_date', 'amount', 'narration', 'bank_ref', 'pay_channel', 
						'bank_from', 'bank_to', 'operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
				
			})->store('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (Exception $e) {
			$this->report_error($e, 'Student', 'Lodgement', 'Excel');
		}
	}
	public function getBankID($bank){
		return DB::table('banks')->where('bank_name', trim($bank))->value('bank_id');
	}
	public function getExpenseID($expense){
		return DB::table('expenses')->where('expense_name', trim($expense))->value('expense_id');
	}
	public function toDbaseDate($value){
		//return STR_TO_DATE($value, '%d/%m/%Y');
		$_date = str_replace('/', '-', $value);
		$_date = date('Y-m-d', strtotime($_date));
		return $_date;
		//return DATE_FORMAT(STR_TO_DATE($value, '%d/%m/%Y'), '%Y-%m-%d');
	}
	public function report_error($e, $module, $form, $task){
		file_put_contents('file_error.txt', $e->getMessage(). '\n'. $module. '-'. $form. '-'. $task. PHP_EOL, FILE_APPEND);
		Log::info($e->getMessage());
	}
	
}

?>