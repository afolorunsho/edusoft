<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\models\Registration;
use App\models\StudentEnrol;
use App\models\AcctGroup;
use App\models\TxnFT;
use App\models\Fees;
use App\models\FeesStruct;
use App\models\FeesInstruct;
use App\models\FeesPayment;
use App\models\FeesRefund;
use App\models\SchClass;
use App\models\StudentService;
use App\models\FeesStudent;
use App\models\Banks;
use App\models\BankPayment;
use App\models\Institution;
use App\models\FeesDiscount;
use App\models\Semester;
use App\Http\Controllers\extend\PDFA5;

use Mail;
use ZipArchive;
use Response;
use Auth;

use Log; //the default Log file
use DB; //use the default Database
use Excel;

class FeesController extends Controller
{
    protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function showFeeHead(){
		$group = AcctGroup::where('account_category', '=', 'Income')
							->orderBy('group_name', 'ASC')
							->get();
		return view('fees.fee_head', compact('group'));
	}
	public function infoFees(){
		try{
			$records =  Fees::join('acct_group','acct_group.group_id', '=', 'fees.group_id')
						->orderBy('fee_name', 'ASC')
						->get();
			return view('fees.infofees', compact('records'));
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Head', 'Info');
		}
	}
	public function updateFees(Request $request){
		//if fees is blank, then it is a new record, else edit
		if($request->ajax()){
			$logRec = array();
			try{
				$fee_id = $request->fee_id;
				if($fee_id === NULL || $fee_id ==""){
					$logRec = Fees::create(
						array(
						'fee_name' => $request->fee_name, 
						'group_id' => $request->group_id, 
						'operator' => $request->operator)
					);
				}
				else{
					$logRec = Fees::updateOrCreate(['fee_id'=>$request->fee_id], $request->all());
					
				}
				return $logRec;				
			} catch (\Exception $e) {
				$this->report_error($e, 'Fees', 'Head', 'Update');
			}
		}
	}
	
	public function editFees(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = Fees::where('fee_id', '=', $request->fee_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Fees', 'Head', 'Edit');
			}
		}	
	}
	public function getFeesList(){
		$fees = Fees::orderBy('fee_name', 'ASC')->get();
		return response($fees);
	}
	
	public function delFees(Request $request){
		try{
			//get the user who made the posting
			$posted_by =  DB::table('fees')->where('fee_id',$request->fee_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('fees')->where('fee_id',$request->fee_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Fees', 'Name', 'Delete', $request->fee_id, $request->fee_id, 
						'-');
				}
				return $logRec;
			}
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Head', 'Delete');
		}
	}
	
	public function excelFees(Request $request){
		try{
			$items = Fees::join('acct_group','acct_group.group_id', '=', 'fees.group_id')
						->select('fee_name', 'group_name', 'fees.operator', 'fees.reviewer', 'fees.created_at', 'fees.updated_at')
						->orderBy('fee_name', 'ASC')
						->get();
			
			return Excel::create('fees-csvfile', function($excel) use($items) {
				$excel->sheet('ExportFile', function($sheet) use($items) {
					$sheet->fromArray($items, null, 'A1', false, false);
					$headings = array('fees', 'group','operator', 'reviewer', 'created_at', 'updated_at');
					$sheet->prependRow(1, $headings);
				});
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Head', 'Excel');
		}
		
	}
	/////////////////////////////////////////Structure
	public function showFeeStruct(){
		$classes = SchClass::orderBy('sequence', 'ASC')->get();
		$fees = Fees::orderBy('fee_name', 'ASC')->get();
		return view('fees.fee_structure', compact('classes', 'fees'));
	}
	public function updateFeeStruct(Request $request){
		//there is no need to delete/edit any wrong structure as the system takes the latest structure
		$start_date = $request->start_date;
		$c = count($request->classes);
		$logRec = array();
		if($request->ajax() && $c > 0){
			try{
				for($i=0;$i<$c;$i++){
					//take the iteration of classes and update all the fees by iterating through the fees table
					$class = $request->classes[$i];
					$n = count($request->fees);
					for($j=0;$j<$n;$j++){
						$fees = $request->fees[$j]; //this is the fee name
						$amount = $request->amount[$j]; //this is the amount defined for the fee name
						//get fees id by using the name
						$fee_id = FeesController::getFeesID($fees);
						//get class id through the class name
						$class_id = FeesController::getClassID($class);
						//remove commas in amount
						$amount = str_replace(',', '', $amount);
						//there is no need to EDIT as the latest definition of fees override the earlier definition if the record exist
						//however, if the date is earlier, then the system should check if any record exist for that date and EDIT it
						//if there is no record at all for the class fees, then it should take the backdate
						$count = FeesStruct::where([ ['class_id', '=', $class_id], ['fee_id', '=', $fee_id] ])->get();
						
						if( strtotime($start_date) <= strtotime('now') ) {
							if( count($count) > 0){
								//if there is a record of the fees in the table, then you can only edit an existing record, no backdating
								$logRec = FeesStruct::where([ 
									['start_date', '=', $start_date],['class_id', '=', $class_id], ['fee_id', '=', $fee_id] 
									])->update(
										array(
											'amount' => $amount,
											'start_date' => $start_date,
											'fee_id' => $fee_id,
											'class_id' => $class_id, 
											'optional' => $request->optional[$j], 
											'operator' => $request->operator
										)
									);
							}else{
								//if date is less than current date and there is no record of the fees in the table, then create a new one
								$logRec = FeesStruct::create(
									array(
										'amount' => $amount,
										'start_date' => $start_date,
										'fee_id' => $fee_id,
										'class_id' => $class_id, 
										'optional' => $request->optional[$j], 
										'operator' => $request->operator
									)
								);
							}
							
						}else{
							$logRec = FeesStruct::create(
								array(
									'amount' => $amount,
									'start_date' => $start_date,
									'fee_id' => $fee_id,
									'class_id' => $class_id, 
									'optional' => $request->optional[$j], 
									'operator' => $request->operator
								)
							);
						}
					}
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Fees', 'Structure', 'Update');
			}
		}
	}
	public function infoFeeStruct(){
		try{
			//iterate through the classess and extract the latest fees under the various categories fees
			$classes = DB::table('sch_classes')->orderBy('sequence', 'ASC')->get();
			$records =  array();
			foreach ($classes as $class)
			{
				$record =  array();
				$class_id = $class->class_id;
				
				//now iterate through fees table
				$fees = DB::table('fees')->orderBy('fee_name', 'ASC')->get();
				foreach ($fees as $fee)
				{
					
					//get the latest amount for this fees in the fee_struct table
					$fees_info = DB::table('fees_struct')
							->where('fee_id', $fee->fee_id)
							->where('class_id', $class_id)
							->where('start_date', '<=', date('Y-m-d'))
							->orderBy('start_date', 'DESC')
							->first();
							
					if(count($fees_info) > 0){
						$record['class_name'] = $class->class_name;
						$record['fee_name'] = $fee->fee_name;
						$record['amount'] = number_format($fees_info->amount, 2, '.', ',');
						$record['start_date'] = date("d/m/Y", strtotime($fees_info->start_date));
						$record['optional'] = $fees_info->optional;
						array_push($records, $record);
					}
				}
			}
			//return view('fees.infostructure', compact('records'));
			return $records;
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Structure', 'Info');
		}
	}
	////////////////////////////////////////////FEES INSTRUCTION
	/////////////////////////////////////////Structure
	public function showFeeInstruct(){
		$classes = SchClass::orderBy('sequence', 'ASC')->get();
		return view('fees.fee_instruction', compact('classes'));
	}
	public function updateFeeInstruct(Request $request){
		//there is no need to delete/edit any wrong structure as the system takes the latest structure
		$c = count($request->classes);
		$logRec = array();
		if($request->ajax() && $c > 0){
			try{
				for($i=0;$i<$c;$i++){
					//take the iteration of classes and update all the fees by iterating through the fees table
					$class_id = $request->classes[$i];
					//delete any existing definition for this class
					DB::table('fees_instruction')->where('class_id',$class_id)->delete();
					//update accordingly
					$logRec = FeesInstruct::create(
						array(
							'class_id' => $class_id,
							'instruction' => $request->instruction_id,
							'operator' => $request->operator
						)
					);
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Fees', 'Structure', 'Update');
			}
		}
	}
	public function infoFeeInstruct(){
		try{
			$records =  DB::table('fees_instruction')
					->join('sch_classes','sch_classes.class_id', '=', 'fees_instruction.class_id')
					->orderBy('sch_classes.sequence', 'DESC')
					->get();	
			//return view('fees.infostructure', compact('records'));
			return $records;
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Structure', 'Info');
		}
	}
	/////////////////////////////////////////Services
	public function showStudentService(){
		//we need to populate the semesters and classes drop down lists
		//get all the classes
		$class = SchClass::orderBy('sequence', 'ASC')->get();
		//get all the semester for the current academic year only
		$semester =  DB::table('semester')
					->orderBy('date_from', 'DESC')
					->get();
		$fees = DB::table('fees')->orderBy('fee_name', 'ASC')->get();
		
		return view('fees.services', compact('class', 'semester', 'fees'));
	}
	public function updateStudentService(Request $request){
		//for a semester, there should be just one definition of fees for a student, NOT multiple
		//
		$c = count($request->students);
		if($request->ajax() && $c > 0){
			$logRec = array();
			try{
				for($i=0;$i<$c;$i++){
					$student_id = $request->students[$i];
					$n = count($request->fees);
					for($j=0;$j<$n;$j++){
						
						$fee_id = $request->fees[$j];
						$class_id = $request->class_div_id;
						$semester_id = $request->semester_id;
						
						//delete any prior definition
						try{
							DB::table('fees_student')
								->where('student_id',student_id)
								->where('fee_id',fee_id)
								->where('semester_id',semester_id)
								->delete();
						} catch (\Exception $e) {}
						$logRec = FeesStudent::create(
							array(
								'semester_id' => $semester_id,
								'fee_id' => $fee_id,
								'class_id' => $class_id, 
								'student_id' => $student_id, 
								'operator' => $request->operator
							)
						);
					}
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Fees', 'Service', 'Update');
			}
		}
	}
	public function getStudentFees(Request $request){
		try{
			//alternatively, pick an optional service and display all those registered for it in the selected semester for a class
			//and indicate whether payment has been made or not
			$class_id = $request->class_id;
			$class_div_id = $request->class_div_id;
			$fee_id = $request->fee_id;
			$semester_id = $request->semester_id;
			
			$records =  array();
			if( !empty($fee_id) && !empty($class_div_id)){
				//get all the active students in the class specified
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('student_enrol.class_id', $class_div_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
					
				foreach ($students as $student)
				{
					$student_id = $student->student_id;
					$class_id = $student->class_id;
					$class_name = $student->class_div;
					
					$record =  array();
					$fees = DB::table('fees')->where('fee_id', $fee_id)->first();
					$fee_id = $fees->fee_id;
					$fee_name = $fees->fee_name;
					//get the current fee for the class specified
					$fees_struct = DB::table('fees_struct')
							->where('fee_id', $fee_id)
							->where('class_id', $class_id)
							->where('start_date', '<=', date('Y-m-d'))
							->orderBy('start_date', 'DESC')
							->first();
						
					if(count($fees_struct)> 0){
						$optional = $fees_struct->optional;
						//if it is optional, then look in the fees_student table and determine if the student enroll for this service
						if( $optional == "Yes"){
							//check wether the student is listed among those optional services
							$services = DB::table('fees_student')
								->where('fee_id', $fee_id)
								->where('class_id', $class_div_id)
								->where('semester_id', $semester_id)
								->where('student_id', $student_id)
								->first();
							//if it is found, then add to record
							if( count($services) > 0) {
								$record['student_id'] = $student_id;
								$record['reg_no'] = $student->reg_no;
								$record['last_name'] = $student->last_name;
								$record['first_name'] = $student->first_name;
								$record['other_name'] = $student->other_name;
								$record['class'] = $class_name;
								$record['fees'] = $fee_name;
								array_push($records, $record);
							}
						}else{
							//if the fees is not optional
							$record['student_id'] = $student_id;
							$record['reg_no'] = $student->reg_no;
							$record['last_name'] = $student->last_name;
							$record['first_name'] = $student->first_name;
							$record['other_name'] = $student->other_name;
							$record['class'] = $class_name;
							$record['fees'] = $fee_name;
							array_push($records, $record);
						}
						
					}
				}
			}
			else if( empty($class_div_id) && empty($fee_id) ){
				//check for the whole class
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('sch_classes.class_id', $class_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'sch_classes.class_id AS class_id', 'class_div', 'class_div_id')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
					
					foreach ($students as $student){
						$student_id = $student->student_id;
						$class_id = $student->class_id;
						$class_name = $student->class_div;
						$class_div_id = $student->class_div_id;
						
						$record =  array();
						$fees = DB::table('fees')->get();
						foreach ($fees as $fee){
							$fee_id = $fee->fee_id;
							$fee_name = $fee->fee_name;
							//get the current fee for the class specified
							$fees_struct = DB::table('fees_struct')
									->where('fee_id', $fee_id)
									->where('class_id', $class_id)
									->where('start_date', '<=', date('Y-m-d'))
									->orderBy('start_date', 'DESC')
									->first();
								
							if(count($fees_struct)> 0){
								$optional = $fees_struct->optional;
								if( $optional == "Yes"){
									//check wether the student is listed among those optional services
									$services = DB::table('fees_student')
										->where('fee_id', $fee_id)
										->where('class_id', $class_div_id)
										->where('semester_id', $semester_id)
										->where('student_id', $student_id)
										->first();
									if( count($services) > 0) {
										$record['student_id'] = $student_id;
										$record['reg_no'] = $student->reg_no;
										$record['last_name'] = $student->last_name;
										$record['first_name'] = $student->first_name;
										$record['other_name'] = $student->other_name;
										$record['class'] = $class_name;
										$record['fees'] = $fee_name;
										array_push($records, $record);
									}
								}else{
									//this means the fee is mandatory
									$record['student_id'] = $student_id;
									$record['reg_no'] = $student->reg_no;
									$record['last_name'] = $student->last_name;
									$record['first_name'] = $student->first_name;
									$record['other_name'] = $student->other_name;
									$record['class'] = $class_name;
									$record['fees'] = $fee_name;
									array_push($records, $record);
								}
							}
						}
					}
			}
			else if( empty($fee_id) && !empty($class_div_id) ){
				//check the whole section and NOT a specific fee
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('student_enrol.class_id', $class_div_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
					
				foreach ($students as $student){
					$student_id = $student->student_id;
					$class_id = $student->class_id;
					$class_name = $student->class_div;
					
					$record =  array();
					$fees = DB::table('fees')->get();
					foreach ($fees as $fee){
						$fee_id = $fee->fee_id;
						$fee_name = $fee->fee_name;
						//get the current fee for the class specified
						$fees_struct = DB::table('fees_struct')
								->where('fee_id', $fee_id)
								->where('class_id', $class_id)
								->where('start_date', '<=', date('Y-m-d'))
								->orderBy('start_date', 'DESC')
								->first();
							
						if(count($fees_struct)> 0){
							$optional = $fees_struct->optional;
							if( $optional == "Yes"){
								//check wether the student is listed among those optional services
								$services = DB::table('fees_student')
									->where('fee_id', $fee_id)
									->where('class_id', $class_div_id)
									->where('semester_id', $semester_id)
									->where('student_id', $student_id)
									->first();
								if( count($services) > 0) {
									$record['student_id'] = $student_id;
									$record['reg_no'] = $student->reg_no;
									$record['last_name'] = $student->last_name;
									$record['first_name'] = $student->first_name;
									$record['other_name'] = $student->other_name;
									$record['class'] = $class_name;
									$record['fees'] = $fee_name;
									array_push($records, $record);
								}
							}else{
								//this means the fee is mandatory
								$record['student_id'] = $student_id;
								$record['reg_no'] = $student->reg_no;
								$record['last_name'] = $student->last_name;
								$record['first_name'] = $student->first_name;
								$record['other_name'] = $student->other_name;
								$record['class'] = $class_name;
								$record['fees'] = $fee_name;
								array_push($records, $record);
							}
						}
					}
				}
			}else if( empty($class_div_id) && !empty($fee_id) ){
				//search the fees for the whole class and not section
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('sch_classes.class_id', $class_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'sch_classes.class_id AS class_id', 'class_div', 'class_div_id')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
					
					foreach ($students as $student){
						$student_id = $student->student_id;
						$class_id = $student->class_id;
						$class_name = $student->class_div;
						$class_div_id = $student->class_div_id;
						
						$record =  array();
						
						$fees = DB::table('fees')->where('fee_id', $fee_id)->first();
						$fee_id = $fees->fee_id;
						$fee_name = $fees->fee_name;
						//get the current fee for the class specified
						$fees_struct = DB::table('fees_struct')
								->where('fee_id', $fee_id)
								->where('class_id', $class_id)
								->where('start_date', '<=', date('Y-m-d'))
								->orderBy('start_date', 'DESC')
								->first();
							
						if(count($fees_struct)> 0){
							$optional = $fees_struct->optional;
							if( $optional == "Yes"){
								//check wether the student is listed among those optional services
								$services = DB::table('fees_student')
									->where('fee_id', $fee_id)
									->where('class_id', $class_div_id)
									->where('semester_id', $semester_id)
									->where('student_id', $student_id)
									->first();
								if( count($services) > 0) {
									$record['student_id'] = $student_id;
									$record['reg_no'] = $student->reg_no;
									$record['last_name'] = $student->last_name;
									$record['first_name'] = $student->first_name;
									$record['other_name'] = $student->other_name;
									$record['class'] = $class_name;
									$record['fees'] = $fee_name;
									array_push($records, $record);
								}
							}else{
								//this means the fee is mandatory
								$record['student_id'] = $student_id;
								$record['reg_no'] = $student->reg_no;
								$record['last_name'] = $student->last_name;
								$record['first_name'] = $student->first_name;
								$record['other_name'] = $student->other_name;
								$record['class'] = $class_name;
								$record['fees'] = $fee_name;
								array_push($records, $record);
							}
						}
					}
			}
			return $records;
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Fees', 'Info');
		}
	}
	public function getDivServices(Request $request){
		try{
			//the aim of this function is to define optional services for students in a particular class
			//hence get the class_id for the selected record and extract all optional fees for that class for selection
			//update should first delete any optional fees defined for that class and replace with the new ones
			//hence single record class_section optional fees
			$class_div_id = $request->class_div_id;
			//get class_id for the class_div_id
			$class_id = DB::table('class_div')->where('class_div_id',$class_div_id)->value('class_id');
			//extract all the optional "Yes" in the fees_struct table and get the fee_ids for this class
			$lists = FeesStruct::where('class_id',$class_id)
						->distinct()->where('optional','Yes')->get();
			//now use this list to extract the applicable optional fees 
			$lines = array();
			foreach ($lists as $list){
				$line = array();
				$fee_id = $list->fee_id;
				//get the fee name
				$fee_name = DB::table('fees')->where('fee_id',$fee_id)->value('fee_name');
				//get the current amount
				$current = DB::table('fees_struct')
					->where('fee_id',$fee_id)
					->where('start_date', '<=', date('Y-m-d'))
					->orderBy('start_date', 'DESC')
					->first();
				$fee_amount = $current->amount;
				$line['fee_id'] = $fee_id;
				$line['fee_name'] = $fee_name;
				$line['fee_amount'] = number_format($fee_amount, 2, '.', ',');
				array_push($lines, $line);
			}
			return $lines;
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Div-Service', 'Info');
		}
	}
	/////////////////////////////////////////////////////FEES PAYMENT
	//bank payment records fees paid into an account, the txn_id here is linked to fee_payment table 
	//fee payment records fees paid by students
	public function showFeePay(){
		$banks =  Banks::orderBy('bank_name', 'ASC')->get();
		$semester =  DB::table('semester')
					->join('academics','academics.academic_id', '=', 'semester.academic_id')
					->orderBy('academics.date_from', 'DESC')
					->orderBy('semester.date_from', 'DESC')
					->get();
					
		///////////////
		$class = SchClass::orderBy('sequence', 'ASC')->get();
		//get all the semester for the current academic year only
		$academic =  DB::table('academics')
					->orderBy('date_from', 'DESC')
					->get();
		$fees = DB::table('fees')->orderBy('fee_name', 'ASC')->get();
					
		return view('fees.fee_payment', compact('banks', 'semester','academic', 'fees', 'class'));
	}
	public function updateFeesPayment(Request $request){
		try{
			$logRec = array();
			$record = array();
			$txn_date = FeesController::toDbaseDate($request->txn_date);	
			$total_amount = str_replace(",","",$request->total_amount);	
			$channel = $request->channel;	
			$bank_id = $request->bank_id;	
			$reference = $request->reference;	
			$narration = $request->narration;
			//first update bank_payment and pick the payment_id
			$payment_id = $request->payment_id;
			if(!empty($payment_id)){
				//delete the records in fees payment
				DB::table('fees_payment')
					->where('bank_payment_id', $payment_id)
					->delete();
				//delete the records in bank payment
				DB::table('bank_payment')
					->where('payment_id', $payment_id)
					->delete();
			}
			$record = BankPayment::create(
				array(
					'bank_id' => $bank_id,
					'txn_date' => $txn_date,
					'txn_type' => 'IN',
					'amount' => $total_amount, 
					'channel' => $channel,
					'reference' => $reference,
					'narration' => $narration, 
					'operator' => $request->operator
				)
			);
			$c = count($request->regno);
			
			for($i=0;$i<$c;$i++){
				//get student class
				$reg_no = $request->regno[$i];
				$class_div_id = StudentController::getStudentClass($reg_no);
				//then update fees_payment table
				$logRec = FeesPayment::create(
					array(
						'class_div_id' => $class_div_id,
						'bank_payment_id' => $record->payment_id,
						'semester_id' => $request->semester_id,
						'payment_date' => $txn_date,
						'student_id' => $reg_no, 
						'amount' => str_replace(",","",$request->amount[$i]),
						'fee_id' => $request->fees[$i],
						'narration' => $narration, 
						'operator' => $request->operator
					)
				);
			}
			return $record;
		} catch (Exception $e) {
			$this->report_error($e, 'Fees', 'Payment', 'Update');
        }
	}
	public function delFeesBatch(Request $request){
		
		//$payment_id =  DB::table('fees_payment')->where('payment_id',$request->payment_id)->value('bank_payment_id');
		$payment_id =  $request->payment_id;
		//file_put_contents('file_error.txt', '0'. PHP_EOL, FILE_APPEND);
		//now get the records from the bank_payment table
		$bank = DB::table('bank_payment as a')
						->join('banks as b','b.bank_id', '=', 'a.bank_id')
						->where('a.payment_id',$payment_id)
						->where('a.txn_type','IN')
						->first();
		$record =  array();
		$records =  array();
		
		//file_put_contents('file_error.txt', '1'. PHP_EOL, FILE_APPEND);
		$record['txn_date'] = date("d/m/Y", strtotime($bank->txn_date));
		$record['semester'] = '';
		$record['channel'] = $bank->channel;
		$record['bank'] = $bank->bank_name;
		$record['reference'] = $bank->reference;
		$record['description'] = $bank->narration;
		$record['total_amount'] = number_format($bank->amount, 2, '.', ',');
		$record['reg_no'] = '';
		$record['last_name'] = '';
		$record['first_name'] = '';
		$record['fees'] = '';
		$record['amount'] = '';
		///////////////////////////////////////////
		array_push($records, $record);
		
		$fees_record = DB::table('fees_payment as a')
						->join('bank_payment as f', 'f.payment_id', '=', 'a.bank_payment_id')
						->join('banks as b', 'b.bank_id', '=', 'f.bank_id')
						->join('fees as c','c.fee_id', '=', 'a.fee_id')
						->join('semester as d','d.semester_id', '=', 'a.semester_id')
						->join('students as e', 'e.student_id', '=', 'a.student_id')
						->select('a.payment_id',  'a.payment_date', 'a.amount', 'a.narration', 'f.channel', 'f.reference',
							'd.semester', 'b.bank_name', 'c.fee_name', 'e.reg_no', 'e.last_name', 'e.first_name')
						->where('a.bank_payment_id',$payment_id)
						->get();
		
		//file_put_contents('file_error.txt', '2'. PHP_EOL, FILE_APPEND);
		foreach ($fees_record as $fees){
			$record =  array();
			//file_put_contents('file_error.txt', $fees->bank_name. PHP_EOL, FILE_APPEND);
			
			$record['txn_date'] = date("d/m/Y", strtotime($fees->payment_date));
			$record['semester'] = $fees->semester;
			$record['channel'] = $bank->channel;
			$record['bank'] = $fees->bank_name;
			$record['reference'] = $bank->reference;
			$record['description'] = $bank->narration;
			$record['total_amount'] = '';
			$record['reg_no'] = $fees->reg_no;
			$record['last_name'] = $fees->last_name;
			$record['first_name'] = $fees->first_name;
			$record['fees'] = $fees->fee_name;
			$record['amount'] = number_format($fees->amount, 2, '.', ',');
			///////////////////////////////////////////
			array_push($records, $record);
		}
		return $records;
	}
	public function delPayment(Request $request){
		$operator = $request->operator;	
		$payment_id = $request->payment_id;
		
		$logRec = array();
		try{
			$posted_by =  DB::table('fees_payment')->where('bank_payment_id', $payment_id)->value('operator');
			
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				
				//delete the records in fees payment first
				$logRec = DB::table('fees_payment')
					->where('bank_payment_id', $payment_id)
					->delete();
				//then delete the records in bank payment
				DB::table('bank_payment')
					->where('payment_id', $payment_id)
					->delete();
					
				if(count($logRec) > 0){
					SysController::postLog('Fees', 'Payment', 'Delete', $payment_id, $payment_id,'-');
				}
			}
			return $logRec;
		} catch (\Exception $e) {$this->report_error($e, 'Fees', 'Payment', 'Delete');}
	}
	public function searchFeesPmt(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('fees_payment as a')
				->join('students as b', 'b.student_id', '=', 'a.student_id')
				->join('fees as c','a.fee_id', '=', 'c.fee_id')
				->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
				->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'a.student_id', 'd.class_div',
					'a.amount','a.narration','a.student_id')
				->where('a.payment_date', '>=', $from_date)
				->where('a.payment_date', '<=', $to_date)
				->orderBy('a.payment_date', 'DESC')
				->get();
					
			//return view('fees.infofeespmt', compact('records'));
			return $records;
			
		} catch (\Exception $e) {$this->report_error($e, 'Fees', 'Payment', 'Search');}
	}
	public function editPayment(Request $request){
		try{
			$records = DB::table('bank_payment as a')
				->join('fees_payment as b', 'b.bank_payment_id', '=', 'a.payment_id')
				->join('students as c', 'b.student_id', '=', 'c.student_id')
				->join('fees as d', 'd.fee_id', '=', 'b.fee_id')
				->join('banks as e', 'e.bank_id', '=', 'a.bank_id')
				->select('e.bank_name', 'a.bank_id', 'a.payment_id', 'a.txn_date', 'a.reference', 'a.narration', 
					'b.semester_id', 'b.fee_id', 'a.channel', 'a.amount AS total_amount', 'b.amount AS fee_amount', 
					'b.student_id', 'c.reg_no', 'c.last_name','c.first_name', 'd.fee_name')
				->where('a.payment_id', $request->payment_id)
				->orderBy('a.reference', 'DESC')
				->get();
			return $records;
		} catch (\Exception $e) {$this->report_error($e, 'Fees', 'Payment', 'Edit');}
	}
	public function searchBankPmt(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('bank_payment as a')
				->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
				->select('b.bank_name', 'a.payment_id', 'a.txn_date', 'a.reference', 'a.narration',
					'a.channel', 'a.amount')
				->where('a.txn_type', 'IN')
				->where('a.txn_date', '>=', $from_date)
				->where('a.txn_date', '<=', $to_date)
				->orderBy('a.txn_date', 'DESC')
				->get();
			
			//return view('fees.infofeespmt', compact('records'));
			return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Bank-Payment', 'Search');
		}
	}
	public function searchLodge(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('txn_ft as a')
				->join('banks as b','a.bank_from', '=', 'b.bank_id')
				->join('banks as c','a.bank_to', '=', 'c.bank_id')
				->select('a.txn_id', 'a.amount', 'a.txn_date', 'a.narration', 'a.bank_ref', 
					'a.pay_channel','b.bank_name as bank_from','c.bank_name as bank_to')
				->where('a.txn_date', '>=', $from_date)
				->where('a.txn_date', '<=', $to_date)
				->orderBy('a.txn_date', 'DESC')
				->get();
				
			return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Lodgement', 'Search');
		}
	}
	public function getStudentPayment(Request $request){
		try{
			//you should be able to get 
			$class_div_id = $request->class_div_id;
			$fee_id = $request->fee_id;
			$semester_id = $request->semester_id;
			
			$records =  array();
			//get all the active students in the class specified
			$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
				->join('students','students.student_id', '=', 'student_enrol.student_id')
				->where('students.active', "1")
				->where('student_enrol.active', "1")
				->where('student_enrol.class_id', $class_div_id)
				->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id')
				->orderBy('student_enrol.student_id', 'DESC')
				->get();
			
			foreach ($students as $student)
			{
				$student_id = $student->student_id;
				$class_id = $student->class_id;
				
				$record =  array();
				$fees = DB::table('fees')->where('fee_id', $fee_id)->first();
				$fee_id = $fees->fee_id;
				//get the current fee for the class specified
				$fees_struct = DB::table('fees_struct')
						->where('fee_id', $fee_id)
						->where('class_id', $class_id)
						->where('start_date', '<=', date('Y-m-d'))
						->orderBy('start_date', 'DESC')
						->first();
					
				if(count($fees_struct)> 0){
					$optional = $fees_struct->optional;
					if( $optional == "Yes"){
						//check wether the student is listed among those optional services
						$services = DB::table('fees_student')
							->where('fee_id', $fee_id)
							->where('class_id', $class_div_id)
							->where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->first();
						if( count($services) > 0) {
							$record['student_id'] = $student_id;
							$record['reg_no'] = $student->reg_no;
							$record['last_name'] = $student->last_name;
							$record['first_name'] = $student->first_name;
							$record['other_name'] = $student->other_name;
						}
					}else{
						//this means the fee is mandatory
						$record['student_id'] = $student_id;
						$record['reg_no'] = $student->reg_no;
						$record['last_name'] = $student->last_name;
						$record['first_name'] = $student->first_name;
						$record['other_name'] = $student->other_name;
					}
					array_push($records, $record);
				}
			}
			return $records;
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Student-Payment', 'Search');
		}
	}
	public function getPayDetails(Request $request){
		$payment_id = $request->payment_id;
		//this shows in a dialog box
		
	}
	public function printFeeReceipt(Request $request){
		
		$student_id = $request->student_id; 
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		//file_put_contents('file_error.txt', $start_date. PHP_EOL, FILE_APPEND);
		try{ //A4: 210 × 297 millimeters
			
			$rows = DB::table('fees_payment')
						->join('bank_payment', 'bank_payment.payment_id', '=', 'fees_payment.bank_payment_id')
						->join('fees', 'fees.fee_id', '=', 'fees_payment.fee_id')
						->join('semester', 'semester.semester_id', '=', 'fees_payment.semester_id')
						->where('student_id', $student_id)
						->where('payment_date', '>=', $start_date)
						->where('payment_date', '<=', $end_date)						
						->orderBy('payment_date', 'ASC')->get();
				
			if ( count($rows)> 0){
				//get regno of the student
				$reg_no = StudentController::getStudentRegNo($student_id);
				$line = DB::table('students')->where('student_id', $student_id)->first();
				$section = StudentController::getStudentClassName($student_id);
				
				$name = $line->last_name.', '.$line->first_name;
				if( $line->other_name !== NULL && $line->other_name !== "-"){
					$name = $name.' '.$line->other_name;
				}
				
				$pdf_file = $reg_no.'_fees_statement.pdf';
				$A5PDF = new PDFA5();
				
				$A5PDF->AddPage('L','A5');
				//AddPage('P','A5');
				//////////////////////////////////////////////////////////////END OF HEADING
				//$pdf = new PDF();
				$A5PDF->Ln(2);
				$A5PDF->SetFont('Arial', 'B', 14); //set font
				$A5PDF->Cell(200, 5, 'FEES PAYMENTS', 0, 0, 'C');
				$A5PDF->SetFont('Arial', '', 10); //set font
				$A5PDF->Ln(10);
				
				$A5PDF->SetX(10);
				$A5PDF->SetFont('Arial', 'B', 9); //set font
				$A5PDF->Cell(15, 5, 'Reg. No.:', 0, 0, 'L');
				
				$A5PDF->SetFont('Arial', '', 9); //set font
				$A5PDF->Cell(10, 5, $reg_no, 0, 0, 'L');
				
				$A5PDF->SetFont('Arial', 'B', 9); //set font
				$A5PDF->Cell(10, 5, 'Name:', 0, 0, 'L');
				
				$A5PDF->SetFont('Arial', '', 9); //set font
				$A5PDF->Cell(50, 5, $name, 0, 0, 'L');
				
				$A5PDF->SetFont('Arial', 'B', 9); //set font
				$A5PDF->Cell(10, 5, 'Class:', 0, 0, 'L');
				
				$A5PDF->SetFont('Arial', '', 9); //set font
				$A5PDF->Cell(35, 5, $section, 0, 0, 'L');
				
				$A5PDF->SetFont('Arial', 'B', 9); //set font
				$A5PDF->Cell(15, 5, 'Period:', 0, 0, 'L');
				
				$A5PDF->SetFont('Arial', '', 9); //set font
				$A5PDF->Cell(40, 5, date("d/m/Y", strtotime($start_date)).' - '.date("d/m/Y", strtotime($end_date)), 0, 0, 'L');
				
				$A5PDF->Ln();
				$A5PDF->SetX(10);
				
				//table heading here
				$A5PDF->SetX(10); //start at  10
				$A5PDF->SetFont('Arial', 'B', 8); //set font
				$A5PDF->Cell(20, 5, 'Date', 1, 0, 'L'); //add 35
				$A5PDF->Cell(30, 5, 'Fees', 1, 0, 'L'); //add 35
				$A5PDF->Cell(50, 5, 'Description', 1, 0, 'L'); //add another 35
				$A5PDF->Cell(20, 5, 'Amount', 1, 0, 'L'); //then add 25
				$A5PDF->Cell(70, 5, 'Term', 1, 0, 'L');  //add 75
				$A5PDF->Ln();
					
				$A5PDF->SetFont('Arial', '', 8); //set font
				$total = 0;
				foreach ($rows as $record){
					//get student name and fees name
					$A5PDF->Cell(20, 5, date("d/m/Y", strtotime($record->payment_date)), 1, 0, 'R');
					$A5PDF->Cell(30, 5, $record->fee_name, 1, 0, 'L');
					$A5PDF->Cell(50, 5, $record->narration, 1, 0, 'L');
					$A5PDF->Cell(20, 5, number_format($record->amount, 2, '.', ','), 1, 0, 'R');
					$A5PDF->Cell(70, 5, $record->semester, 1, 0, 'L');
					
					$A5PDF->Ln();
				}
				$path = storage_path('reports/pdf').'/'.$pdf_file;
				$A5PDF->Output($path, 'F');
				
				//just in case error is generated. it should not stop program flow
				try{ 
					//SysController::sendStudentMail($reg_no, "RECEIPT", $pdf_file, $path);
				} catch (Exception $e) {}	
				
				$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
				\File::copy($path,$destination);
				
				return $pdf_file;
				
			}
				
		} catch (Exception $e) {
			$this->report_error($e, 'Fees', 'Payment', 'Print');
			return redirect()->back();
		}
	}
	public function printPayDetails(Request $request){
		$payment_id = $request->payment_id;
		$repeat = 2;
		//send the receipt to the email address of the guardian, if available
		try{ //A4: 210 × 297 millimeters
			
			$rows = DB::table('fees_payment')->where('bank_payment_id', $payment_id)->orderBy('student_id', 'DESC')->get();
			if ( count($rows)> 0){
				$pdf_file = $payment_id.'_fees.pdf';
				$A5PDF = new PDFA5();
				$A5PDF->AddPage('L','A5');
				//AddPage('P','A5');
				//////////////////////////////////////////////////////////////END OF HEADING
				//$pdf = new PDF();
				$A5PDF->Ln(2);
				$A5PDF->SetFont('Arial', 'B', 14); //set font
				$A5PDF->Cell(200, 5, 'RECEIPT', 0, 0, 'C');
				$A5PDF->SetFont('Arial', '', 10); //set font
				$A5PDF->Ln();
				
				//header information here
				$payment = DB::table('bank_payment')
						->where('payment_id', $payment_id)
						->first();
				$A5PDF->SetX(10);
				$A5PDF->SetFont('Arial', 'B', 10); //set font
				$A5PDF->Cell(30, 5, 'Receipt No:', 0, 0, 'L');
				$A5PDF->SetFont('Arial', '', 10); //set font
				$A5PDF->Cell(60, 5, $payment->payment_id, 0, 0, 'L');
				$A5PDF->SetFont('Arial', 'B', 10); //set font
				$A5PDF->Cell(50, 5, 'Date:', 0, 0, 'L');
				$A5PDF->SetFont('Arial', '', 10); //set font
				$A5PDF->Cell(25, 5, date("d/m/Y", strtotime($payment->txn_date)), 0, 0, 'L');
				$A5PDF->Ln();
				$A5PDF->SetX(10);
				$A5PDF->SetFont('Arial', 'B', 10); //set font
				$A5PDF->Cell(30, 5, 'Amount:', 0, 0, 'L');
				$A5PDF->SetFont('Arial', '', 10); //set font
				$A5PDF->Cell(60, 5, number_format($payment->amount, 2, '.', ','), 0, 0, 'L');
				$A5PDF->SetFont('Arial', 'B', 10); //set font
				$A5PDF->Cell(50, 5, 'Channel/Reference:', 0, 0, 'L');
				$A5PDF->SetFont('Arial', '', 10); //set font
				$A5PDF->Cell(25, 5, $payment->channel.'/'.$payment->reference, 0, 0, 'L');
				$A5PDF->Ln();
				$A5PDF->SetX(10);
				$A5PDF->SetFont('Arial', 'B', 10); //set font
				$A5PDF->Cell(30, 5, 'Description:', 0, 0, 'L');
				$A5PDF->SetFont('Arial', '', 10); //set font
				$A5PDF->Cell(100, 5, $payment->narration, 0, 0, 'L');
				$A5PDF->Ln();
				$A5PDF->Ln();
				//table heading here
				$A5PDF->SetX(10); //start at  10
				$A5PDF->SetFont('Arial', 'B', 10); //set font
				$A5PDF->Cell(20, 5, 'Reg No', 1, 0, 'L'); //add 35
				$A5PDF->Cell(30, 5, 'Last Name', 1, 0, 'L'); //add 35
				$A5PDF->Cell(30, 5, 'First Name', 1, 0, 'L'); //add another 35
				$A5PDF->Cell(30, 5, 'Class', 1, 0, 'L');  //add 75
				$A5PDF->Cell(40, 5, 'Fees', 1, 0, 'L');  //add 75
				$A5PDF->Cell(25, 5, 'Amount', 1, 0, 'L'); //then add 25
				$A5PDF->Ln();
					
				$A5PDF->SetFont('Arial', '', 10); //set font
				$old_name = "";
				$reg_no = "";
				$fee_id = "";
				foreach ($rows as $record){
					//get student name and fees name
					$student_id = $record->student_id;
					$fee_id = $record->fee_id;
					$student = Registration::where('student_id', $student_id)->first();
					$enrol = StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
						->where('student_enrol.student_id', $student_id)
						->where('student_enrol.active', "1")
						->first();
						
					$fees = DB::table('fees')->where('fee_id',$fee_id)->first();
					$name = $student->last_name. ', '.$student->first_name;
					$reg_no = $student->reg_no;
					
					$A5PDF->SetX(10);
					if( $old_name !== $name){
						$A5PDF->Cell(20, 5, $student->reg_no, 1, 0, 'L');
						$A5PDF->Cell(30, 5, $student->last_name, 1, 0, 'L');
						$A5PDF->Cell(30, 5, $student->first_name, 1, 0, 'L');
					}else{
						$A5PDF->Cell(20, 5, "", 1, 0, 'L');
						$A5PDF->Cell(30, 5, "", 1, 0, 'L');
						$A5PDF->Cell(30, 5, "", 1, 0, 'L');
					}
					$A5PDF->Cell(30, 5, $enrol->class_div, 1, 0, 'L');
					$A5PDF->Cell(40, 5, $fees->fee_name, 1, 0, 'L');
					$A5PDF->Cell(25, 5, number_format($record->amount, 2, '.', ','), 1, 0, 'R');
					$A5PDF->Ln();
					$old_name = $name;
				}
				$path = storage_path('reports/pdf').'/'.$pdf_file;
				$A5PDF->Output($path, 'F');
				
				//just in case error is generated. it should not stop program flow
				try{ 
					//SysController::sendStudentMail($reg_no, "RECEIPT", $pdf_file, $path);
				} catch (Exception $e) {}	
				
				$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
				\File::copy($path,$destination);
				return $pdf_file;
				
			}
				
		} catch (Exception $e) {
			$this->report_error($e, 'Fees', 'Payment', 'Print');
			return redirect()->back();
		}
	}
	/////////////////////////////////////////////REFUND
	public function showFeeRefund(){
		$semester =  DB::table('semester')
					->join('academics','academics.academic_id', '=', 'semester.academic_id')
					->orderBy('academics.date_from', 'DESC')
					->orderBy('semester.date_from', 'DESC')
					->get();
		$banks =  Banks::orderBy('bank_name', 'ASC')->get();
		return view('fees.fee_refund', compact('banks', 'semester'));
	}
	public function updateFeesRefund(Request $request){
		try{
			$logRec = array();
			$txn_date = FeesController::toDbaseDate($request->txn_date);	
			$total_amount = str_replace(",","",$request->total_amount);	
			$channel = $request->channel;	
			$bank_id = $request->bank_id;	
			$reference = $request->reference;	
			$narration = $request->narration;
			//first update bank_refund and pick the refund_id
			$record = BankPayment::create(
				array(
					'bank_id' => $bank_id,
					'txn_date' => $txn_date,
					'txn_type' => 'OUT',
					'amount' => $total_amount, 
					'channel' => $channel,
					'reference' => $reference,
					'narration' => $narration, 
					'operator' => $request->operator
				)
			);
			$c = count($request->regno);
		
			for($i=0;$i<$c;$i++){
			
				$logRec = FeesRefund::create(
					array(
						'bank_payment_id' => $record->payment_id,
						'semester_id' => $request->semester_id,
						'refund_date' => $txn_date,
						'student_id' => $request->regno[$i], 
						'amount' => str_replace(",","",$request->amount[$i]),
						'fee_id' => $request->fees[$i],
						'narration' => $narration, 
						'operator' => $request->operator
					)
				);
			}
			return $record;
		} catch (Exception $e) {
			$this->report_error($e, 'Fees', 'Refund', 'Update');
			return redirect()->back();
		}
	}
	public function searchFeesRefund(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('fees_refund as a')
				->join('students as b', 'b.student_id', '=', 'a.student_id')
				->join('fees as c','a.fee_id', '=', 'c.fee_id')
				->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.refund_date', 'a.student_id',
					'a.amount','a.narration')
				->where('a.refund_date', '>=', $from_date)
				->where('a.refund_date', '<=', $to_date)
				->orderBy('a.refund_date', 'DESC')
				->get();
				
			//return view('fees.infofeespmt', compact('records'));
			return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Refund', 'Search');
		}
	}
	public function searchBankRefund(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('bank_payment as a')
				->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
				->select('b.bank_name', 'a.payment_id', 'a.txn_date', 'a.reference', 'a.narration',
					'a.channel', 'a.amount')
				->where('a.txn_type', 'OUT')
				->where('a.txn_date', '>=', $from_date)
				->where('a.txn_date', '<=', $to_date)
				->orderBy('a.txn_date', 'DESC')
				->get();
			
			//return view('fees.infofeespmt', compact('records'));
			return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Refund-Bank', 'Search');
		}
	}
	
	public function printRefundDetails(Request $request){
		$refund_id = $request->refund_id;
		$repeat = 2;
			
		try{ //A4: 210 × 297 millimeters
			
			$rows = DB::table('fees_refund')->where('bank_payment_id', $refund_id)->orderBy('student_id', 'DESC')->get();
			if ( count($rows)> 0){
				$pdf_file = 'refund.pdf';
				
				//////////////////////////////////////////////////////////////END OF HEADING
				//$pdf = new PDF();
				$this->pdf->Ln(2);
				$this->pdf->SetFont('Arial', 'B', 14); //set font
				$this->pdf->Cell(200, 5, 'REFUND', 0, 0, 'C');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Ln();
				
				//header information here
				$payment = DB::table('bank_payment')
						->where('payment_id', $refund_id)
						->first();
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(30, 5, 'Receipt No:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(60, 5, $payment->payment_id, 0, 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(50, 5, 'Date:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(25, 5, date("d/m/Y", strtotime($payment->txn_date)), 0, 0, 'L');
				$this->pdf->Ln();
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(30, 5, 'Amount:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(60, 5, number_format($payment->amount, 2, '.', ','), 0, 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(50, 5, 'Channel/Reference:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(25, 5, $payment->channel.'/'.$payment->reference, 0, 0, 'L');
				$this->pdf->Ln();
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(30, 5, 'Description:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(100, 5, $payment->narration, 0, 0, 'L');
				$this->pdf->Ln();
				//table heading here
				$this->pdf->SetX(10); //start at  10
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Reg No', 1, 0, 'L'); //add 35
				$this->pdf->Cell(30, 5, 'Last Name', 1, 0, 'L'); //add 35
				$this->pdf->Cell(30, 5, 'First Name', 1, 0, 'L'); //add another 35
				$this->pdf->Cell(20, 5, 'Class', 1, 0, 'L');  //add 75
				$this->pdf->Cell(50, 5, 'Fees', 1, 0, 'L');  //add 75
				$this->pdf->Cell(25, 5, 'Amount', 1, 0, 'L'); //then add 25
				$this->pdf->Ln();
					
				$this->pdf->SetFont('Arial', '', 10); //set font
				$old_name = "";
				foreach ($rows as $record){
					$student_id = $record->student_id;
					$fee_id = $record->fee_id;
					$student = Registration::where('student_id', $student_id)->first();
					$enrol = StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
						->where('student_enrol.student_id', $student_id)
						->where('student_enrol.active', "1")
						->first();
						
					$fees = DB::table('fees')->where('fee_id',$fee_id)->first();
					$name = $student->last_name. ', '.$student->first_name;
					$this->pdf->SetX(10);
					if( $old_name !== $name){
						$this->pdf->Cell(20, 5, $student->reg_no, 1, 0, 'L');
						$this->pdf->Cell(30, 5, $student->last_name, 1, 0, 'L');
						$this->pdf->Cell(30, 5, $student->first_name, 1, 0, 'L');
					}else{
						$this->pdf->Cell(20, 5, "", 1, 0, 'L');
						$this->pdf->Cell(30, 5, "", 1, 0, 'L');
						$this->pdf->Cell(30, 5, "", 1, 0, 'L');
					}
					$this->pdf->Cell(20, 5, $enrol->class_div, 1, 0, 'L');
					$this->pdf->Cell(50, 5, $fees->fee_name, 1, 0, 'L');
					$this->pdf->Cell(25, 5, number_format($record->amount, 2, '.', ','), 1, 0, 'R');
					$this->pdf->Ln();
					$old_name = $name;
				}
				$path = storage_path('reports/pdf').'/'.$pdf_file;
				$this->pdf->Output($path, 'F');
				
				$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
				\File::copy($path,$destination);
				return $pdf_file;
			}
				
		} catch (Exception $e) {
			$this->report_error($e, 'Fees', 'Refund', 'Print');
			return redirect()->back();
		}
		
	}
	////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////DISCOUNT 
	public function showDiscount(){
		$semester =  DB::table('semester')
					->join('academics','academics.academic_id', '=', 'semester.academic_id')
					->orderBy('academics.date_from', 'DESC')
					->orderBy('semester.date_from', 'DESC')
					->get();
					
		return view('fees.fee_discount', compact('semester'));
	}
	public function updateDiscount(Request $request){
		try{
			$logRec = array();
			$txn_date = FeesController::toDbaseDate($request->txn_date);	
			$narration = $request->narration;
			$c = count($request->regno);
			
			for($i=0;$i<$c;$i++){
				$logRec = FeesDiscount::create(
					array(
						'semester_id' => $request->semester_id,
						'discount_date' => $txn_date,
						'student_id' => $request->regno[$i], 
						'amount' => str_replace(",","",$request->amount[$i]),
						'discount' => $request->fees[$i],
						'narration' => $narration, 
						'operator' => $request->operator
					)
				);
			}
			return $logRec;
		} catch (Exception $e) {
            $this->report_error($e, 'Fees', 'Discount', 'Update');
        }
	}
	public function searchDiscount(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('fees_discount as a')
				->join('students as b', 'b.student_id', '=', 'a.student_id')
				->join('semester as c','c.semester_id', '=', 'a.semester_id')
				->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount', 'a.discount_date', 'a.student_id',
					'a.amount','a.narration','c.semester')
				->where('a.discount_date', '>=', $from_date)
				->where('a.discount_date', '<=', $to_date)
				->orderBy('a.discount_date', 'DESC')
				->get();
				
			//return view('fees.infofeespmt', compact('records'));
			return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Discount', 'Search');
		}
	}
	/////////////////////////////////////////
	///////////////////FEES DUE
	/////////////////////////////////////////Services
	public function showFeesDue(){
		//we need to populate the semesters and classes drop down lists
		//get all the classes
		$class = SchClass::orderBy('sequence', 'ASC')->get();
		//get all the semester for the current academic year only
		$semester =  DB::table('semester')
					->join('academics','academics.academic_id', '=', 'semester.academic_id')
					->orderBy('academics.date_from', 'DESC')
					->orderBy('semester.date_from', 'DESC')
					->get();
		$fees = DB::table('fees')->orderBy('fee_name', 'ASC')->get();
		return view('fees.fee_due', compact('class', 'semester', 'fees'));
	}
	public function getFeesDue(Request $request){
		try{
			//alternatively, pick an optional service and display all those registered for it in the selected semester for a class
			//and indicate whether payment has been made or not
			$class_div_id = $request->class_div_id;
			$fee_id = $request->fee_id;
			$semester_id = $request->semester_id;
			
			//get semester date_from : the implication of this is that semester fees should be set before the begining of that semester
			$date_from =  DB::table('semester')->where('semester_id', $semester_id)->value('date_from');
			
			$records =  array();
			//get all the active and ensrolled students in the class
			$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
				->join('students','students.student_id', '=', 'student_enrol.student_id')
				->where('students.active', "1")
				->where('student_enrol.active', "1")
				->where('student_enrol.class_id', $class_div_id)
				->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id')
				->orderBy('student_enrol.student_id', 'DESC')
				->get();
			
			foreach ($students as $student)
			{
				$student_id = $student->student_id;
				$class_id = $student->class_id;
				//after getting the corresponding class_id, then get all the fees associated with this studenr based on the class
				//mandatory and optional fees, then extract payments, refunds and discounts
				//first. get all the fees due for the class
				$record =  array();
				$charge = 0;
				//get the current fee definition for the specified class and end date term
				$fees_struct = DB::table('fees_struct')
						->where('fee_id', $fee_id)
						->where('class_id', $class_id)
						->where('start_date', '<=', $date_from)
						->orderBy('start_date', 'DESC')
						->first();
				//if the fee is defined for this class
				if(count($fees_struct)> 0){
					//is it optional, then check whether it is defined for the student
					$optional = $fees_struct->optional;
					if( $optional == "Yes"){
						//check wether the student is listed among those optional services for the specified semester
						$services = DB::table('fees_student')
							->where('fee_id', $fee_id)
							->where('class_id', $class_div_id)
							->where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->first();
						if( count($services) > 0) {
							$charge = $charge + $fees_struct->amount;
						}
					}else{
						//this means the fee is mandatory
						$charge = $charge + $fees_struct->amount;
					}
				}
				//get all the payments for the semester
				$payments = DB::table('fees_payment')
						->where('semester_id', $semester_id)
						->where('fee_id', $fee_id)
						->where('student_id', $student_id)
						->sum('amount');
				$due = $payments - $charge;
				$record['student_id'] = $student_id;
				$record['reg_no'] = $student->reg_no;
				$record['last_name'] = $student->last_name;
				$record['first_name'] = $student->first_name;
				$record['other_name'] = $student->other_name;
				$record['charge'] = number_format($charge, 2, '.', ',');
				$record['payment'] = number_format($payments, 2, '.', ',');
				$record['due'] = number_format($due, 2, '.', ',');
				
				//get all the payments for the specified semester
				array_push($records, $record);
			}
			return $records;
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Due', 'Search');
		}
	}
	public function getYearlyFees(Request $request){
		try{
			//the aim here is to aggregate all the fees paid by a student in the academic year per fees type
			
			$class_div_id = $request->class_div_id;
			$academic_id = $request->academic_id;
			$fee_id = $request->fee_id;
			$class_id = $request->class_id;
			$content = "";
			//get semester date_from : the implication of this is that semester fees should be set before the begin of that semester
			$date_from =  DB::table('academics')->where('academic_id', $academic_id)->value('date_from');
			$date_to =  DB::table('academics')->where('academic_id', $academic_id)->value('date_to');
			
			//there is no need to set date range as the semester has already being selected and this is defined in all the receipts
			$records =  array();
			if( empty($fee_id) && !empty($class_div_id)){
				//get all the active and ensrolled students in the school
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('student_enrol.class_id', $class_div_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
				
				foreach ($students as $student)
				{
					$student_id = $student->student_id;
					//iterate through the fees table
					$fees = DB::table('fees')->get();
					$charge = 0;
					foreach ($fees as $fee){
						$fee_id = $fee->fee_id;
						//for this fee, iterate through all the semesters in the academic year and extract the fees paid by the student
						$payments = DB::table('fees_payment as a')
							->join('semester as b','b.semester_id', '=', 'a.semester_id')
							->join('academics as c','c.academic_id', '=', 'b.academic_id')
							->where('a.fee_id', $fee_id)
							->where('c.academic_id', $academic_id)
							->where('student_id', $student_id)
							->sum('amount');
						
						$content .='<tr>';
						$content .='<td>'.$student->reg_no.'</td>';
						$content .='<td>'.$student->last_name.'</td>';
						$content .='<td>'.$student->first_name.'</td>';
						$content .='<td>'.$student->class_div.'</td>';
						$content .='<td>'.$fee->fee_name.'</td>';
						$content .='<td>'.number_format($payments, 2, '.', ',').'</td>';
						$content .='</tr>';
					}
				}
			}
			else if( empty($class_div_id) && !empty($fee_id)){
				//get all the active and ensrolled students in the school
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('sch_classes.class_id', $class_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div', 'class_div.class_div_id')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
				
				foreach ($students as $student)
				{
					$student_id = $student->student_id;
					//iterate through the fees table
					$fee = DB::table('fees')->where('fee_id', $fee_id)->first();
					$fee_name = $fee->fee_name;
					$payments = DB::table('fees_payment as a')
							->join('semester as b','b.semester_id', '=', 'a.semester_id')
							->join('academics as c','c.academic_id', '=', 'b.academic_id')
							->where('a.fee_id', $fee_id)
							->where('c.academic_id', $academic_id)
							->where('student_id', $student_id)
							->sum('amount');
							
					$content .='<tr>';
					$content .='<td>'.$student->reg_no.'</td>';
					$content .='<td>'.$student->last_name.'</td>';
					$content .='<td>'.$student->first_name.'</td>';
					$content .='<td>'.$student->class_div.'</td>';
					$content .='<td>'.$fee_name.'</td>';
					$content .='<td>'.number_format($payments, 2, '.', ',').'</td>';
					$content .='</tr>';
					
				}
			}
			else if( !empty($fee_id) && !empty($class_div_id)){
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('student_enrol.class_id', $class_div_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
					
				foreach ($students as $student)
				{
					$student_id = $student->student_id;
					//iterate through the fees table
					$fee = DB::table('fees')->where('fee_id', $fee_id)->first();
					$fee_name = $fee->fee_name;
					
					//for this fee, iterate through all the semesters in the academic year and extract the fees paid by the student
					$payments = DB::table('fees_payment as a')
						->join('semester as b','b.semester_id', '=', 'a.semester_id')
						->join('academics as c','c.academic_id', '=', 'b.academic_id')
						->where('a.fee_id', $fee_id)
						->where('c.academic_id', $academic_id)
						->where('student_id', $student_id)
						->sum('amount');
					
					$content .='<tr>';
					$content .='<td>'.$student->reg_no.'</td>';
					$content .='<td>'.$student->last_name.'</td>';
					$content .='<td>'.$student->first_name.'</td>';
					$content .='<td>'.$student->class_div.'</td>';
					$content .='<td>'.$fee_name.'</td>';
					$content .='<td>'.number_format($payments, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
			}
			else{
				//get all the active and ensrolled students in the school
				//get all the active and ensrolled students in the school
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('sch_classes.class_id', $class_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div', 'class_div.class_div_id')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
				
				foreach ($students as $student)
				{
					 $student_id = $student->student_id;
					//iterate through the fees table
					$fees = DB::table('fees')->get();
					$charge = 0;
					foreach ($fees as $fee){
						$fee_id = $fee->fee_id;
						$fee_name = $fee->fee_name;
						//for this fee, iterate through all the semesters in the academic year and extract the fees paid by the student
						$payments = DB::table('fees_payment as a')
							->join('semester as b','b.semester_id', '=', 'a.semester_id')
							->join('academics as c','c.academic_id', '=', 'b.academic_id')
							->where('a.fee_id', $fee_id)
							->where('c.academic_id', $academic_id)
							->where('a.student_id', $student_id)
							->sum('a.amount');
						
						$content .='<tr>';
						$content .='<td>'.$student->reg_no.'</td>';
						$content .='<td>'.$student->last_name.'</td>';
						$content .='<td>'.$student->first_name.'</td>';
						$content .='<td>'.$student->class_div.'</td>';
						$content .='<td>'.$fee_name.'</td>';
						$content .='<td>'.number_format($payments, 2, '.', ',').'</td>';
						$content .='</tr>';
					}
				}
			}
			return $content;
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Due-All', 'Search');
		}
	}
	public function getAllFeesDue(Request $request){
		try{
			//the aim here is to extract all the fees due for a particular semester for a class section
			//therefore all non-optional fees for the class should be picked
			//pick optional fees defined for the students for the term
			
			$class_div_id = $request->class_div_id;
			$semester_id = $request->semester_id;
			$fee_id = $request->fee_id;
			$class_id = $request->class_id;
			
			//get semester date_from : the implication of this is that semester fees should be set before the begining of that semester
			$date_from =  DB::table('semester')->where('semester_id', $semester_id)->value('date_from');
			$date_to =  DB::table('semester')->where('semester_id', $semester_id)->value('date_to');
			
			//there is no need to set date range as the semester has already being selected and this is defined in all the receipts
			$records =  array();
			if( empty($fee_id) && !empty($class_div_id)){
				//get all the active and ensrolled students in the school
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('student_enrol.class_id', $class_div_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
				
				foreach ($students as $student)
				{
					$student_id = $student->student_id;
					//after getting the corresponding class_id, then 
					//get all the fees associated with this student based on the class
					//mandatory and optional fees, then extract payments, refunds and discounts
					//first. get all the fees due for the class
					$record =  array();
					//iterate through the fees table
					$fees = DB::table('fees')->get();
					$charge = 0;
					foreach ($fees as $fee){
						$fee_id = $fee->fee_id;
						//get the current fee definition for the specified class and end date term
						$fees_struct = DB::table('fees_struct')
								->where('fee_id', $fee_id)
								->where('class_id', $class_id)
								->where('start_date', '<=', $date_to)
								->orderBy('start_date', 'DESC')
								->first();
						//if the fee is defined for this class
						if(count($fees_struct)> 0){
							$optional = $fees_struct->optional;
							//if it is optional, then look in the fees_student table and determine if the student enroll for this service
							if( $optional == "Yes"){
								//check wether the student is listed among those optional services
								$services = DB::table('fees_student')
									->where('fee_id', $fee_id)
									->where('class_id', $class_div_id)
									->where('semester_id', $semester_id)
									->where('student_id', $student_id)
									->first();
								//if it is found, then add to record
								if( count($services) > 0) {
									$charge = $charge + $fees_struct->amount;
								}
							}else{
								$charge = $charge + $fees_struct->amount;
							}
						}
						///////
					}
					//get all the payments for the semester
					$payments = DB::table('fees_payment')
							->where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->sum('amount');
					//get all the discount for the semester
					$discounts = DB::table('fees_discount')
							->where('semester_id', $semester_id)
							->where('discount', 'Discount')
							->where('student_id', $student_id)
							->sum('amount');
					//get all the schorlarship for the semester
					$scholarship = DB::table('fees_discount')
							->where('semester_id', $semester_id)
							->where('discount', 'Scholarship')
							->where('student_id', $student_id)
							->sum('amount');
					//get all the refunds for the semester
					$refunds = DB::table('fees_refund')
							->where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->sum('amount');
					$due = ($payments + $scholarship + $discounts) - ($charge + $refunds);
					$record['student_id'] = $student_id;
					$record['reg_no'] = $student->reg_no;
					$record['last_name'] = $student->last_name;
					$record['first_name'] = $student->first_name;
					$record['other_name'] = $student->other_name;
					$record['class_name'] = $student->class_div;
					$record['charge'] = number_format($charge, 2, '.', ',');
					$record['discount'] = number_format($discounts, 2, '.', ',');
					$record['scholarship'] = number_format($scholarship, 2, '.', ',');
					$record['refund'] = number_format($refunds, 2, '.', ',');
					$record['payment'] = number_format($payments, 2, '.', ',');
					$record['due'] = number_format($due, 2, '.', ',');
					
					//get all the payments for the specified semester
					array_push($records, $record);
				}
			}
			else if( !empty($fee_id) && !empty($class_div_id)){
				//if the fee and class section are selected
				//note that it is ONLY enrolment table that uses class_id as the class_div_id
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('student_enrol.class_id', $class_div_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
				//get all the students for this section
				foreach ($students as $student)
				{
					$student_id = $student->student_id;
					$class_id = $student->class_id;
					$class_name = $student->class_div;
					$record =  array();
					$charge = 0;
					//in the fees table, get the specified fee_id name
					$fee_name = DB::table('fees')->where('fee_id', $fee_id)->value('fee_name');
					//get the current fee for the class specified: note that start_date is the effective date of the fee
					$fees_struct = DB::table('fees_struct')
							->where('fee_id', $fee_id)
							->where('class_id', $class_id)
							->where('start_date', '<=', date('Y-m-d'))
							->orderBy('start_date', 'DESC')
							->first();
					//if you have fees defined for this class
					if(count($fees_struct)> 0){
						$optional = $fees_struct->optional;
						//if it is optional, then look in the fees_student table and 
						//determine if the student enroll for this service
						if( $optional == "Yes"){
							//check wether the student is listed among those optional services
							//NOTE here that class_id refers to class_div_id
							$services = DB::table('fees_student')
								->where('fee_id', $fee_id)
								->where('class_id', $class_div_id)
								->where('semester_id', $semester_id)
								->where('student_id', $student_id)
								->first();
							//if it is found, then add to record
							if( count($services) > 0) {
								$charge = $charge + $fees_struct->amount;
							}
						}else{
							//include this fees for all the students if it is NOT optional
							$charge = $charge + $fees_struct->amount;
						}
					}
					//get all the payments by the student for the semester
					$payments = DB::table('fees_payment')
							->where('semester_id', $semester_id)
							->where('fee_id', $fee_id)
							->where('student_id', $student_id)
							->sum('amount');
					//get all the discount for the semester
					/*$discounts = DB::table('fees_discount')
							->where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->sum('amount');*/
					//get all thediscount for the semester
					$scholarship = 0.00;
					$discounts = 0.00;
					//get all the refunds for the semester
					$refunds = 0.00;
					//since we are taking fees and refunds, discount and scholarship are NOT fees specific, then 
					//they should be ignored 
					$due = ($payments) - ($charge);
					$record['student_id'] = $student_id;
					$record['reg_no'] = $student->reg_no;
					$record['last_name'] = $student->last_name;
					$record['first_name'] = $student->first_name;
					$record['other_name'] = $student->other_name;
					$record['class_name'] = $student->class_div;
					$record['charge'] = number_format($charge, 2, '.', ',');
					$record['discount'] = number_format($discounts, 2, '.', ',');
					$record['scholarship'] = number_format($scholarship, 2, '.', ',');
					$record['refund'] = number_format($refunds, 2, '.', ',');
					$record['payment'] = number_format($payments, 2, '.', ',');
					$record['due'] = number_format($due, 2, '.', ',');
					
					array_push($records, $record);
				}
			}
			else{
				//get all the active and ensrolled students in the school
				$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->where('sch_classes.class_id', $class_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div', 'class_div.class_div_id')
					->orderBy('student_enrol.student_id', 'DESC')
					->get();
				
				foreach ($students as $student)
				{
					 $class_div_id  = $student->class_div_id;
					 $student_id = $student->student_id;
					//after getting the corresponding class_id, then get all the fees associated with this student based on the class
					//mandatory and optional fees, then extract payments, refunds and discounts
					//first. get all the fees due for the class
					$record =  array();
					//iterate through the fees table
					$fees = DB::table('fees')->get();
					$charge = 0;
					foreach ($fees as $fee){
						$fee_id = $fee->fee_id;
						//get the current fee definition for the specified class and end date term
						$fees_struct = DB::table('fees_struct')
								->where('fee_id', $fee_id)
								->where('class_id', $class_id)
								->where('start_date', '<=', $date_to)
								->orderBy('start_date', 'DESC')
								->first();
						//if the fee is defined for this class
						if(count($fees_struct)> 0){
							$optional = $fees_struct->optional;
							//if it is optional, then look in the fees_student table and determine if the student enroll for this service
							if( $optional == "Yes"){
								//check wether the student is listed among those optional services
								$services = DB::table('fees_student')
									->where('fee_id', $fee_id)
									->where('class_id', $class_div_id)
									->where('semester_id', $semester_id)
									->where('student_id', $student_id)
									->first();
								//if it is found, then add to record
								if( count($services) > 0) {
									$charge = $charge + $fees_struct->amount;
								}
							}else{
								$charge = $charge + $fees_struct->amount;
							}
						}
						///////
					}
					//get all the payments for the semester
					$payments = DB::table('fees_payment')
							->where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->sum('amount');
					//get all the discount for the semester
					$discounts = DB::table('fees_discount')
							->where('semester_id', $semester_id)
							->where('discount', 'Discount')
							->where('student_id', $student_id)
							->sum('amount');
					//get all the schorlarship for the semester
					$scholarship = DB::table('fees_discount')
							->where('semester_id', $semester_id)
							->where('discount', 'Scholarship')
							->where('student_id', $student_id)
							->sum('amount');
					//get all the refunds for the semester
					$refunds = DB::table('fees_refund')
							->where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->sum('amount');
					$due = ($payments + $scholarship + $discounts) - ($charge + $refunds);
					$record['student_id'] = $student_id;
					$record['reg_no'] = $student->reg_no;
					$record['last_name'] = $student->last_name;
					$record['first_name'] = $student->first_name;
					$record['other_name'] = $student->other_name;
					$record['class_name'] = $student->class_div;
					$record['charge'] = number_format($charge, 2, '.', ',');
					$record['discount'] = number_format($discounts, 2, '.', ',');
					$record['scholarship'] = number_format($scholarship, 2, '.', ',');
					$record['refund'] = number_format($refunds, 2, '.', ',');
					$record['payment'] = number_format($payments, 2, '.', ',');
					$record['due'] = number_format($due, 2, '.', ',');
					
					//get all the payments for the specified semester
					array_push($records, $record);
					
				}
			}
			return $records;
		}catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Due-All', 'Search');
		}
	}
	public function emailStudentDues(Request $request){
		$semester_id = $request->semester_id;
		$student_id = $request->student_id;
		$semester = SetController::semester_name($semester_id);
		$reg_no = StudentController::getStudentRegNo($student_id);
		
		$pdf_file = $semester.'_'.$reg_no.'_due.pdf';
		$path = storage_path('reports/pdf/bills').'/'.$pdf_file;
		
		SysController::sendStudentMail($reg_no, " FEES STATEMENT", $pdf_file, $path);
		return $pdf_file;
	}
	public function emailStudentBills(Request $request){
		$semester_id = $request->semester_id;
		$student_id = $request->student_id;
		$semester = SetController::semester_name($semester_id);
		$reg_no = StudentController::getStudentRegNo($student_id);
		
		$pdf_file = $semester.'_'.$reg_no.'_bill.pdf';
		$path = storage_path('reports/pdf/bills').'/'.$pdf_file;
		
		SysController::sendStudentMail($reg_no, "BILL", $pdf_file, $path);
		return $pdf_file;
	}
	public function printAllFees(Request $request){
		$class_id = $request->class_id;
		$semester_id = $request->semester_id;
		$student_id = $request->student_id;
		return $this->generateOutstanding($class_id, $semester_id, $student_id);
	}
	public function generateOutstanding($class_id, $semester_id, $student_id){
		
		$date_from =  SetController::semester_start($semester_id);
		$date_to =  SetController::semester_end($semester_id);
		$semester = SetController::semester_name($semester_id);
		
		try{ //A4: 210 × 297 millimeters
			//get a unique record of student
			$student =  StudentEnrol::join('students','students.student_id', '=', 'student_enrol.student_id')
					->join('class_div','class_div.class_div_id', '=', 'student_enrol.class_id')
					->where('class_div.class_id', $class_id)
					->where('student_enrol.active', "1")
					->where('students.active', "1")
					->where('students.student_id', $student_id)
					->select('class_div.class_div_id', 'student_enrol.student_id', 
						'reg_no', 'first_name', 'last_name', 'class_div')
					->orderBy('student_enrol.student_id', 'DESC')
					->first();
							
			if( count($student) > 0){
			
				//the student is ACTIVE and get the current class
				//if the current class is the class being searched, then proceed
				$class_div_id = $student->class_div_id;
				
				$pdf_file = $semester.'_'.$student->reg_no.'_due.pdf';
				$this->pdf->AddPage('P','A4');
				//////////////////////////////////////////////////////////////END OF HEADING
				//$pdf = new PDF();
				$this->pdf->Ln(2);
				$this->pdf->SetFont('Arial', 'B', 14); //set font
				$this->pdf->Cell(200, 5, 'OUTSTANDING FEES', 0, 0, 'C');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Ln();
				
				//semester, student name and class
				$name = $student->last_name. ', '.$student->first_name;
				$reg_no = $student->reg_no;
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Name:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(80, 5, $name, 0, 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Reg No:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(35, 5, $reg_no, 0, 0, 'L');
				$this->pdf->Ln();
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Term:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(80, 5, $semester, 0, 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Class:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(35, 5, $student->class_div, 0, 0, 'L');
				
				$this->pdf->Ln();
				//list fees(name, amount charge), payment(amount paid)
				//factor in refund, discount and schorlarship at the bottom as these are not based on fees name
				//show bet amount due and the banks to use for payments and due date
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(70, 5, 'Fees', 'LTR',0,'L',0); //cell with left,top, and right borders
				$this->pdf->Cell(30, 5, 'Fees Amount', 'LTR',0,'L',0); //cell with left,top, and right borders
				$this->pdf->Cell(30, 5, 'Amount Paid', 'LTR',0,'L',0); //cell with left,top, and right borders
				$this->pdf->Cell(30, 5, 'Balance', 'LTR',0,'L',0); //cell with left,top, and right borders
				$this->pdf->Ln();
					
				$this->pdf->SetFont('Arial', '', 10); //set font
				//get all the fees
				$fees = DB::table('fees')->get();
				$total_out = 0;
				foreach ($fees as $fee){
					//for each fees
					$this->pdf->SetX(10);
					$charge = 0;
					$payments = 0;
					//check whether this fee is applicable
					$fee_id = $fee->fee_id;
					//get the current fee definition for the specified class and end date term
					//the fees have to be defined before the end of the term.
					$fees_struct = DB::table('fees_struct')
							->where('fee_id', $fee_id)
							->where('class_id', $class_id)
							->where('start_date', '<=', $date_to)
							->orderBy('start_date', 'DESC')
							->first();
					//if the fee is defined for this class
					
					if(count($fees_struct) > 0){
						//the fee is for this class
						//is it optional, then check whether it is defined for the student
						$optional = $fees_struct->optional;
						if( $optional == "Yes"){
							//check wether the student is listed among those optional services for the specified semester
							$services = DB::table('fees_student')
								->where('fee_id', $fee_id)
								->where('class_id', $class_div_id)
								->where('semester_id', $semester_id)
								->where('student_id', $student_id)
								->first();
							if( count($services) > 0) {
								$charge = $charge + $fees_struct->amount;
							}
						}else{
							//this means the fee is mandatory
							$charge = $charge + $fees_struct->amount;
						}
						
						$payments = DB::table('fees_payment')
									->where('fee_id', $fee_id)
									->where('semester_id', $semester_id)
									->where('student_id', $student_id)
									->sum('amount');
						//get fees name
						$fee_name = DB::table('fees')
									->where('fee_id', $fee_id)
									->value('fee_name');
						$outstanding = $payments - $charge;
						$this->pdf->Cell(70, 5, $fee_name, 'LTRB',0,'L',0);
						$this->pdf->Cell(30, 5, number_format($charge, 2, '.', ','), 'TRB',0,'R',0); //cell with left,top, and right borders
						$this->pdf->Cell(30, 5, number_format($payments, 2, '.', ','), 'TRB',0,'R',0); //cell with left,top, and right borders
						$this->pdf->Cell(30, 5, number_format($outstanding, 2, '.', ','), 'TRB',0,'R',0); //cell with left,top, and right borders
						$this->pdf->Ln();
						$total_out = $total_out + $outstanding;
						
					}
				}
				//get total discount, scholarship, refund for the student for the term
				$discounts = DB::table('fees_discount')
						->where('semester_id', $semester_id)
						->where('discount', 'Discount')
						->where('student_id', $student_id)
						->sum('amount');
				//get all the schorlarship for the semester
				$scholarship = DB::table('fees_discount')
						->where('semester_id', $semester_id)
						->where('discount', 'Scholarship')
						->where('student_id', $student_id)
						->sum('amount');
				//get all the refunds for the semester
				$refunds = DB::table('fees_refund')
						->where('semester_id', $semester_id)
						->where('student_id', $student_id)
						->sum('amount');
				
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(30, 5, 'Total Unpaid Fees:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(130, 5, number_format($total_out, 2, '.', ','), 0, 0, 'R');
				$this->pdf->Ln();
				if ($refunds > 0 ){
					$total_out = $total_out - $refunds;
					$this->pdf->SetX(10);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(30, 5, 'Refunds:', 0, 0, 'L');
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(130, 5, number_format($refunds, 2, '.', ','), 0, 0, 'R');
					$this->pdf->Ln();
				}
				if ($scholarship > 0 ){
					$total_out = $total_out + $scholarship;
					$this->pdf->SetX(10);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(30, 5, 'Scholarship:', 0, 0, 'L');
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(130, 5, number_format($scholarship, 2, '.', ','), 0, 0, 'R');
					$this->pdf->Ln();
				}
				if ($discounts > 0 ){
					$total_out = $total_out + $discounts;
					$this->pdf->SetX(10);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(30, 5, 'Discounts:', 0, 0, 'L');
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(130, 5, number_format($discounts, 2, '.', ','), 0, 0, 'R');
					$this->pdf->Ln();
				}
				$this->pdf->SetX(10);
				$this->pdf->Cell(70, 5, '', 0, 0, 'L');
				$this->pdf->Cell(30, 5, '', 0, 0, 'L');
				$this->pdf->Cell(30, 5, '', 0, 0, 'L');
				$this->pdf->Cell(30, 5, str_repeat("-", 20), 0, 0, 'R');
				
				$this->pdf->Ln();
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(30, 5, 'Net Unpaid Amount[Negative means debt]:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(130, 5, number_format($total_out, 2, '.', ','), 0, 0, 'R');
				$this->pdf->Ln();
				$this->pdf->SetX(10);
				$this->pdf->Cell(70, 5, '', 0, 0, 'L');
				$this->pdf->Cell(30, 5, '', 0, 0, 'L');
				$this->pdf->Cell(30, 5, '', 0, 0, 'L');
				$this->pdf->Cell(30, 5, str_repeat("=", 12), 0, 0, 'R');
				//include the bank payment instruction
				$bank_details = DB::table('fees_instruction')->where('class_id', $class_id)->value('instruction');
				if( $bank_details !== NULL && $bank_details !== ""){
					$this->pdf->Ln(10);
					$this->pdf->SetX(10);
					//include Bank Details:  here: $class_id; $semester_id
					$bank_details = DB::table('fees_instruction')->where('class_id', $class_id)->value('instruction');
					$this->pdf->Cell(25, 5, 'Bank Details: ', 0, 0, 'L');
					$this->pdf->MultiCell(150, 5, $bank_details);
				}
				
				$reg_no = $student->reg_no;
				$path = storage_path('reports/pdf/bills').'/'.$pdf_file;
				$this->pdf->Output($path, 'F');
				
				$destination = $this->public_folder.'/reports/pdf/bills/'.$pdf_file;
				\File::copy($path,$destination);
				return $pdf_file;
				
			}
		} catch (Exception $e) {$this->report_error($e, 'Fees', 'All', 'Print');}
	}
	public function printStudentFees(Request $request){
		$class_div_id = $request->class_div_id;
		$student_id = $request->student_id;
		$semester_id = $request->semester_id;
		$class_id = DB::table('class_div')->where('class_div_id', $class_div_id)->value('class_id');
		
		return $this->generateOutstanding($class_id, $semester_id, $student_id);
		//$reg_no = StudentController::getStudentRegNo($student_id);
		//return $pdf_file;
	}
	
	public function printStudentBill(Request $request){
		////
		$student_id = $request->student_id;
		$semester_id = $request->semester_id;
		$class_id = $request->class_id;
		
		$date_to =  DB::table('semester')->where('semester_id', $semester_id)->value('date_to');
		$semester_name = SetController::semester_name($semester_id);
		
		$student =  StudentEnrol::join('students','students.student_id', '=', 'student_enrol.student_id')
					->join('class_div','class_div.class_div_id', '=', 'student_enrol.class_id')
					->where('class_div.class_id', $class_id)
					->where('student_enrol.active', "1")
					->where('students.active', "1")
					->where('students.student_id', $student_id)
					->select('student_enrol.student_id AS student_id','reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_id AS class_id', 'class_div', 'class_div.class_div_id')
					->orderBy('student_enrol.student_id', 'DESC')
					->first();
			
		try{ //A4: 210 × 297 millimeters
			if( count($student) > 0){
				$class_div_id = $student->class_div_id;
				$reg_no = $student->reg_no;
				$class_div = $student->class_div;
				
				$pdf_file = $semester_name.'_'.$reg_no.'_bill.pdf';
				
				$this->pdf->AliasNbPages();
				$this->pdf->AddPage('P','A4');
				//////////////////////////////////////////////////////////////END OF HEADING
				//$pdf = new PDF();
				$this->pdf->Ln(2);
				$this->pdf->SetFont('Arial', 'B', 14); //set font
				$this->pdf->Cell(200, 5, 'STUDENT BILL', 0, 0, 'C');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Ln();
				
				//semester, student name and class
				$name = $student->last_name. ', '.$student->first_name;
				$reg_no = $student->reg_no;
				
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Name:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(80, 5, $name, 0, 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Reg No:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(35, 5, $reg_no, 0, 0, 'L');
				$this->pdf->Ln();
				
				$semester = DB::table('semester')->where('semester_id',$semester_id)->value('semester');
				
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Term:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(80, 5, $semester, 0, 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Class:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(35, 5, $class_div, 0, 0, 'L');
				
				$this->pdf->Ln();
				$this->pdf->SetX(10);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(50, 5, 'Class', 'LTR',0,'L',0); //cell with left,top, and right borders
				$this->pdf->Cell(70, 5, 'Fees', 'LTR',0,'L',0); //cell with left,top, and right borders
				$this->pdf->Cell(30, 5, 'Optional', 'LTR',0,'L',0); //cell with left,top, and right borders
				$this->pdf->Cell(30, 5, 'Fees Amount', 'LTR',0,'L',0); //cell with left,top, and right borders
				$this->pdf->Ln();
					
				$this->pdf->SetFont('Arial', '', 10); //set font
				
				//iterate through the class table to get all the classes
				$s_classes = DB::table('sch_classes')->orderBy('sequence', 'ASC')->get();
				$old_name = "";
				foreach ($s_classes as $s_class){
					$class_id = $s_class->class_id;
					$class_name = $s_class->class_name;
					$fees = DB::table('fees')->orderBy('fee_name', 'ASC')->get();
					
					foreach ($fees as $fee){
						if( $old_name == $class_name) $class_name = "";
						$this->pdf->SetX(10);
						$charge = 0;
						//check whether this fee is applicable
						$fee_id = $fee->fee_id;
						$fee_name = $fee->fee_name;	
						$my_fees = DB::table('fees_struct')
								->join('sch_classes','sch_classes.class_id', '=', 'fees_struct.class_id')
								->where('fee_id', $fee_id)
								->where('sch_classes.class_id', $class_id)
								->where('start_date', '<=', $date_to)
								->orderBy('start_date', 'DESC')
								->first();
						//if the fee is defined for this class
						if(count($my_fees)> 0){
							$optional = $my_fees->optional;
							$charge = $my_fees->amount;
							//file_put_contents('file_error.txt', $optional. PHP_EOL, FILE_APPEND);
										
							$this->pdf->Cell(50, 5, $class_name, 'LTRB',0,'L',0);
							$this->pdf->Cell(70, 5, $fee_name, 'LTRB',0,'L',0);
							$this->pdf->Cell(30, 5, $optional, 'TRB',0,'R',0); //cell with left,top, and right borders
							$this->pdf->Cell(30, 5, number_format($charge, 2, '.', ','), 'TRB',0,'R',0); 
							$this->pdf->Ln();
							$old_name = $class_name;
						}
					}
				}
				$bank_details = DB::table('fees_instruction')->where('class_id', $class_id)->value('instruction');
				if( $bank_details !== NULL && $bank_details !== ""){
					$this->pdf->Ln();
					$this->pdf->SetX(10);
					$bank_details = DB::table('fees_instruction')->where('class_id', $class_id)->value('instruction');
					$this->pdf->Cell(25, 5, 'Bank Details: ', 0, 0, 'L');
					$this->pdf->MultiCell(150, 5, $bank_details);
				}
				//send email of the file here
				$reg_no = $student->reg_no;
				$path = storage_path('reports/pdf/bills').'/'.$pdf_file;
				$this->pdf->Output($path, 'F');
				
				$destination = $this->public_folder.'/reports/pdf/bills/'.$pdf_file;
				\File::copy($path,$destination);
				return $pdf_file;
			}
		} catch (Exception $e) {
			$this->report_error($e, 'Fees', 'Bill', 'Pdf');
		}
	}
	//////////////////////////////////////////////////////////
	////////////////////////////////////////IMPORTS
	public function importPayment(Request $request){
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
					$content = '<table class="table table-hover table-striped table-condensed" id="import_payment" style="font-size:100%">';
					$i = 0;//initialize
					while (!feof($handle)) {
						$value[] = fgets($handle, 1024);
						$lineArr = explode("\t", $value[$i]);
							
						list($date, $reg_no, $last_name, $first_name, $fees, $amount, $bank, $narration) = $lineArr;
						//get the last name o the student and the first name based on the reg no
						if( $i == 0){
							$content .='<thead>';
							$content .='<tr>';
							$content .='<th>'.$date.'</th>';
							$content .='<th>'.$reg_no.'</th>';
							$content .='<th>'.$last_name.'</th>';
							$content .='<th>'.$first_name.'</th>';
							$content .='<th>'.$fees.'</th>';
							$content .='<th>'.$amount.'</th>';
							$content .='<th>'.$bank.'</th>';
							$content .='<th>'.$narration.'</th>';
							$content .='</tr>';
							$content .='</thead>';
							$content .='<tbody>';
						}
						if( $i > 0){
							$last_name = StudentController::getLastName(trim($reg_no));
							$first_name = StudentController::getFirstName(trim($reg_no));
							$content .='<tr>';
							$content .='<td name="payment_date[]">'.str_replace('"', '',$date).'</td>';
							$content .='<td name="reg_no[]">'.str_replace('"', '',trim($reg_no)).'</td>';
							$content .='<td name="last_name[]">'.str_replace('"', '',$last_name).'</td>';
							$content .='<td name="first_name[]">'.str_replace('"', '',$first_name).'</td>';
							$content .='<td name="fees[]">'.str_replace('"', '',$fees).'</td>';
							$content .='<td name="amount[]">'.str_replace('"', '',$amount).'</td>';
							$content .='<td name="bank[]">'.str_replace('"', '',$bank).'</td>';
							$content .='<td name="narration[]">'.str_replace('"', '',$narration).'</td>';
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
			$this->report_error($e, 'Fees', 'Payment', 'Upload'); 
		}
	}
	public function updatePaymentImport(Request $request){
		try{
			$logRec = array();
			$payment_date = $request->payment_date;	
			$student_id = StudentController::getStudentID($request->reg_no);
			$fees_id = $this->getFeesID(trim($request->fees));
			$bank_id = $this->getBankID(trim($request->bank));
			if( !empty($student_id) && !empty($fees_id) && !empty($bank_id)){
				$reference = $request->reg_no;	
				$narration = $request->narration;
				//first update bank_payment and pick the payment_id
				$record = BankPayment::create(
					array(
						'bank_id' => $bank_id,
						'txn_date' => $payment_date,
						'txn_type' => 'IN',
						'amount' => $request->amount, 
						'channel' => 'bulk',
						'reference' => $reference,
						'narration' => $narration, 
						'operator' => $request->operator
					)
				);
				//then update fees_payment table
				$class_div_id = StudentController::getStudentClass($student_id);
				$logRec = FeesPayment::create(
					array(
						'class_div_id' => $class_div_id, 
						'bank_payment_id' => $record->payment_id,
						'semester_id' => $request->semester,
						'payment_date' => $payment_date,
						'student_id' => $student_id, 
						'amount' => $request->amount, 
						'fee_id' => $fees_id,
						'narration' => $narration,
						'operator' => $request->operator
					)
				);
			}
			return $logRec;
		} catch (Exception $e) {
			$this->report_error($e, 'Fees', 'Payment', 'Import');
        }
	}
	public function excelBankPayment(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			
			$csv = BankPayment::select('b.bank_payment_id', 'b.payment_date', 'b.amount', 'b.narration', 
						'bank_payment.channel', 'bank_payment.reference', 'f.class_div', 
						'd.reg_no', 'd.first_name', 'd.last_name','a.bank_name','e.semester','c.fee_name',
						'b.operator', 'b.reviewer', 'b.created_at')
					->join('banks as a','a.bank_id', '=', 'bank_payment.bank_id')
					->join('fees_payment as b','b.bank_payment_id', '=', 'bank_payment.payment_id')
					->join('fees as c','c.fee_id', '=', 'b.fee_id')
					->join('students as d','d.student_id', '=', 'b.student_id')
					->join('semester as e','e.semester_id', '=', 'b.semester_id')
					->join('class_div as f','f.class_div_id', '=', 'b.class_div_id')
					->where('txn_date', '>=', $from_date)
					->where('txn_date', '<=', $to_date)
					->orderBy('txn_date', 'ASC')
					->get();
			
			return \Excel::create('bank-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('payment_id', 'payment_date', 'amount', 'narration', 
						'channel', 'reference', 'class_div', 
						'reg_no', 'first_name', 'last_name','bank_name','semester','fees',
						'operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
				
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (Exception $e) {
			$this->report_error($e, 'Fees', 'Payment-Bank', 'Excel');
			return redirect()->back();
		}
	}
	//to get indebtedness, get the enrolment date and estimate the various classes attended and their respective fees
	//or run fees due and put in an account and update payments/refunds from there
	//////////////////////////////////////////////////////////////////////
	/////////////////////////////////UTILITY FUNCTIONS
	
	public function getBankID($bank){
		return DB::table('banks')->where('bank_name', trim($bank))->value('bank_id');
	}
	public function getFeesID($fees){
		return DB::table('fees')->where('fee_name',trim($fees))->value('fee_id');
	}
	public function getClassID($class){
		return DB::table('sch_classes')->where('class_name',$class)->value('class_id');
	}
	public function getClassSection($class){
		return DB::table('class_div')->where('class_id',$class)->value('class_div_id');
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
		//Log::useFiles(storage_path().'/laravel.log');
		Log::info($e->getMessage());
	}
}
?>