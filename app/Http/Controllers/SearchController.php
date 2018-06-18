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
use App\models\Fees;
use App\models\BankPayment;
use App\models\TxnFT;
use App\models\Banks;
use App\models\Receipts;
use App\models\Expenses;
use App\models\Registration;
use App\models\StudentExit;
use App\models\StudentDiscipline;
use App\models\StudentAchievement;
use App\models\StudentAttendance;
use App\models\StudentEnrol;
use App\models\FeesDiscount;
use App\models\ExamScore;

class SearchController extends Controller
{
    protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function enquiryEnrolment(){
		return view("search.qry_enrolment");
	}
	public function enquiryRegistration(){
		return view("search.qry_registration");
	}
	public function enquiryTransfer(){
		return view("search.qry_transfer");
	}
	public function enquiryStudent(){
		//put all the students here, whether active or not
		$student =  Registration::orderBy('reg_no', 'DESC')->get();
		return view('search.qry_student', compact('student'));
	}
	public function enquiryBank(){
		//put all the students here, whether active or not
		$bank =  Banks::orderBy('bank_name', 'DESC')->get();
		return view('search.qry_bank', compact('bank'));
	}
	public function enquiryFees(){
		return view("search.qry_fees");
	}
	public function enquiryExam(){
		return view("search.qry_exam");
	}
	public function enquiryExpense(){
		return view("search.qry_expense");
	}
	public function enquiryAttend(){
		return view("search.qry_attendance");
	}
	public function enquiryScholarship(){
		return view("search.qry_scholarship");
	}
	public function enquiryPerformance(){
		return view("search.qry_performance");
		//$name = DB::table('tbl_name')->whereRaw('MONTH(col_name) = 3')->get();
	}
	public function enquiryStatement(){
		return view("search.qry_statement");
	}
	public function enquiryJounal(){
		return view("search.qry_journal");
	}
	public function queryJounal(Request $request){
		
		$records = array();
		$from_date = $request->start_date;
		$to_date = $request->end_date;
		$search_param = trim($request->search_param);
		try{
			$content ='<table class="table table-hover table-striped table-condensed" id="search-table">';
				
			if($search_param == "Registration"){
				$records = DB::table('students')
					->select('reg_no', 'reg_date', 'first_name', 'last_name', 
						'other_name', 'dob', 'tribe',
						'height', 'weight', 'blood', 'gender', 'district', 'region', 'town', 'lga', 
						'state_origin', 'nationality', 'religion', 
						'address', 'email', 'phone', 'enrol_date', 'active', 'guardian', 
						'relationship', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone', 'operator', 'reviewer', 'created_at')
					->where('created_at', ">=", $from_date)
					->where('created_at', "<=", $to_date)
					->orderBy('created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Reg No</th>';
				$content .='<th>Reg Date</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Other Name</th>';
				$content .='<th>DoB</th>';
				$content .='<th>Tribe</th>';
				$content .='<th>Height</th>';
				$content .='<th>Weight</th>';
				$content .='<th>Blood</th>';
				$content .='<th>Gender</th>';
				$content .='<th>Enrol Date</th>';
				$content .='<th>Guardian</th>';
				$content .='<th>Relationship</th>';
				$content .='<th>Office</th>';
				$content .='<th>Home</th>';
				$content .='<th>Email</th>';
				$content .='<th>Phone</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';			
				foreach ($records as $record){
					$gender = "Female";
					if( $record->gender == 1) $gender = "Male";
					$content .='<tr>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->reg_date.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->other_name.'</td>';
					$content .='<td>'.$record->dob.'</td>';
					$content .='<td>'.$record->tribe.'</td>';
					$content .='<td>'.$record->height.'</td>';
					$content .='<td>'.$record->weight.'</td>';
					$content .='<td>'.$record->blood.'</td>';
					$content .='<td>'.$gender.'</td>';
					$content .='<td>'.$record->enrol_date.'</td>';
					$content .='<td>'.$record->guardian.'</td>';
					$content .='<td>'.$record->relationship.'</td>';
					$content .='<td>'.$record->guard_office.'</td>';
					$content .='<td>'.$record->guard_home.'</td>';
					$content .='<td>'.$record->guard_email.'</td>';
					$content .='<td>'.$record->guard_phone.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
		
			if($search_param == "Enrolment"){
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Reg No</th>';
				$content .='<th>Reg Date</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Other Name</th>';
				$content .='<th>Enrol Date</th>';
				$content .='<th>Class</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				$records =  DB::table('student_enrol as a')
					->join('class_div as b', 'b.class_div_id', '=','a.class_id')
					->join('students as c', 'c.student_id', '=', 'a.student_id')
					->select('c.reg_no', 'c.reg_date', 'c.first_name', 'c.last_name',
						'c.other_name', 'a.enrol_date', 'b.class_div', 'a.operator', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->reg_date.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->other_name.'</td>';
					$content .='<td>'.$record->enrol_date.'</td>';
					$content .='<td>'.$record->class_div.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Academic-Year"){
				$records =  DB::table('academics')
					->select('academic_id','academic','date_from','date_to', 'operator', 'reviewer', 'created_at')
					->where('created_at', ">=", $from_date)
					->where('created_at', "<=", $to_date)
					->orderBy('created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Reg No</th>';
				$content .='<th>Reg Date</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Other Name</th>';
				$content .='<th>Enrol Date</th>';
				$content .='<th>Class</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->reg_date.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->other_name.'</td>';
					$content .='<td>'.$record->enrol_date.'</td>';
					$content .='<td>'.$record->class_div.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Academic-Term"){
				$records =  DB::table('semester as a')
					->join('academics as b', 'b.academic_id', '=', 'a.academic_id')
					->select('semester_id', 'semester', 'academic',
						'a.date_from', 'a.date_to', 'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Semester</th>';
				$content .='<th>Academic Year</th>';
				$content .='<th>Date From</th>';
				$content .='<th>Date To</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='<td>'.$record->academic.'</td>';
					$content .='<td>'.$record->date_from.'</td>';
					$content .='<td>'.$record->date_to.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Other-Events"){
				$records = DB::table('sch_events as a')
					->join('event_type as b', 'b.event_type_id', '=', 'a.event_type_id')
					->select('event_id', 'event_name', 'event_type',
						'a.date_from', 'a.date_to', 'a.operator','a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Event</th>';
				$content .='<th>Event Type</th>';
				$content .='<th>Date From</th>';
				$content .='<th>Date To</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->event_name.'</td>';
					$content .='<td>'.$record->event_type.'</td>';
					$content .='<td>'.$record->date_from.'</td>';
					$content .='<td>'.$record->date_to.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "School"){
				$records = DB::table('schools')
					->select('school_id','school_name','address','sequence', 'operator', 'reviewer', 'created_at')
					->where('created_at', ">=", $from_date)
					->where('created_at', "<=", $to_date)
					->orderBy('created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>School</th>';
				$content .='<th>Sequence</th>';
				$content .='<th>Address</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->school_name.'</td>';
					$content .='<td>'.$record->sequence.'</td>';
					$content .='<td>'.$record->address.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Class"){
				$records = DB::table('sch_classes as a')
					->join('schools as b', 'b.school_id', '=', 'a.school_id')
					->select('school_name','class_name','b.description', 'capacity', 'a.sequence',
						'div_type', 'div_no', 'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>School</th>';
				$content .='<th>Class</th>';
				$content .='<th>Sequence</th>';
				$content .='<th>Capacity</th>';
				$content .='<th>Type</th>';
				$content .='<th>No</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->school_name.'</td>';
					$content .='<td>'.$record->class_name.'</td>';
					$content .='<td>'.$record->sequence.'</td>';
					$content .='<td>'.$record->capacity.'</td>';
					$content .='<td>'.$record->div_type.'</td>';
					$content .='<td>'.$record->div_no.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Assessment"){
				$records = DB::table('student_assessment')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Assessment</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->assessment.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Assessment-Parameter"){
				$records = DB::table('assessment_param as a')
					->join('student_assessment as b', 'b.assessment_id', '=', 'a.assessment_id')
					->join('sch_classes as c','c.class_id', '=', 'a.class_id')
					->select('class_name', 'assessment', 'parameter', 'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Class</th>';
				$content .='<th>Assessment</th>';
				$content .='<th>Parameter</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->class_name.'</td>';
					$content .='<td>'.$record->assessment.'</td>';
					$content .='<td>'.$record->parameter.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Subject"){
				$records = DB::table('subjects')
					->select('subject_id','subject','short_name', 'operator', 'reviewer', 'created_at')
					->where('created_at', ">=", $from_date)
					->where('created_at', "<=", $to_date)
					->orderBy('created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Subject</th>';
				$content .='<th>Short Name</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->subject.'</td>';
					$content .='<td>'.$record->short_name.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			
			if($search_param == "Class-Subject"){
				
				$records = DB::table('syllabus as a')
					->join('sch_classes as b', 'b.class_id', '=', 'a.class_id')
					->join('subjects as c', 'c.subject_id', '=', 'a.subject_id')
					->join('class_syllabus as d', 'd.syllabus_id', '=', 'a.syllabus_id')
					->join('class_div as e', 'e.class_div_id', '=', 'd.class_div_id')
					->select('class_name', 'class_div','subject', 'syllabus', 'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Class</th>';
				$content .='<th>Subject</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->class_div.'</td>';
					$content .='<td>'.$record->subject.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Exam"){
				$records = DB::table('exam_name')
					->select('exam_id','exam_name','short_name', 'operator', 'reviewer', 'created_at')
					->where('created_at', ">=", $from_date)
					->where('created_at', "<=", $to_date)
					->orderBy('created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Exam</th>';
				$content .='<th>Short Name</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->exam_name.'</td>';
					$content .='<td>'.$record->short_name.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Class-Exam"){
				$records =  DB::table('exam_class as a')
					->join('exam_name as b','b.exam_id', '=', 'a.exam_id')
					->join('sch_classes as c','c.class_id', '=', 'a.class_id')
					->select('c.class_name', 'a.exam_id','exam_name', 
						'exam_weight', 'max_score', 'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Class</th>';
				$content .='<th>Exam</th>';
				$content .='<th>Weight</th>';
				$content .='<th>Max Score</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->class_name.'</td>';
					$content .='<td>'.$record->exam_name.'</td>';
					$content .='<td>'.$record->exam_weight.'</td>';
					$content .='<td>'.$record->max_score.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Score-Grade"){
				$records =  DB::table('exam_grade as a')
					->join('score_grade as b','b.score_grade_id', '=', 'a.score_grade_id')
					->join('sch_classes as c','c.class_id', '=', 'a.class_id')
					->select('class_name','score_grade','score_from', 'score_to', 'remarks',
						'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Class</th>';
				$content .='<th>Grade</th>';
				$content .='<th>From</th>';
				$content .='<th>To</th>';
				$content .='<th>Remarks</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->class_name.'</td>';
					$content .='<td>'.$record->score_grade.'</td>';
					$content .='<td>'.$record->score_from.'</td>';
					$content .='<td>'.$record->score_to.'</td>';
					$content .='<td>'.$record->remarks.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Fees"){
				$records =  DB::table('fees as a')
					->join('acct_group as b','b.group_id', '=', 'a.group_id')
					->select('a.fee_name','b.account_category',
						'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Fees</th>';
				$content .='<th>Group</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->fee_name.'</td>';
					$content .='<td>'.$record->account_category.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			
			if($search_param == "Class-Fees"){
				$records =  DB::table('fees_struct as a')
					->join('sch_classes as b','b.class_id', '=', 'a.class_id')
					->join('fees as c','c.fee_id', '=', 'a.fee_id')
					->select('c.fee_name', 'b.class_name', 'a.amount', 'a.start_date', 
						'a.optional', 'a.operator', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Fees</th>';
				$content .='<th>Optional</th>';
				$content .='<th>Class</th>';
				$content .='<th>Amount</th>';
				$content .='<th>Start Date</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$optional = $record->optional;
					$content .='<tr>';
					$content .='<td>'.$record->fee_name.'</td>';
					$content .='<td>'.$optional.'</td>';
					$content .='<td>'.$record->class_name.'</td>';
					$content .='<td>'.$record->amount.'</td>';
					$content .='<td>'.$record->start_date.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Group-Code"){
				$records =  DB::table('acct_group')
					->where('created_at', ">=", $from_date)
					->where('created_at', "<=", $to_date)
					->orderBy('created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Group</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->group_name.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Expense-Code"){
				$records =  DB::table('expenses as a')
					->join('acct_group as b','b.group_id', '=', 'a.group_id')
					->select('a.expense_name', 'b.group_name', 'a.operator', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Expense</th>';
				$content .='<th>Group</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->expense_name.'</td>';
					$content .='<td>'.$record->group_name.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Bank-Code"){
				$records =  DB::table('banks as a')
					->join('acct_group as b','b.group_id', '=', 'a.group_id')
					->select('a.bank_name', 'b.group_name', 'a.operator', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Bank</th>';
				$content .='<th>Group</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->bank_name.'</td>';
					$content .='<td>'.$record->group_name.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Student-Fees"){
				
				$records =  DB::table('fees_student as a')
					->join('fees as b','b.fee_id', '=', 'a.fee_id')
					->join('students as c', 'c.student_id', '=', 'a.student_id')
					->join('semester as d','d.semester_id', '=', 'a.semester_id')
					->select('c.reg_no', 'c.first_name', 'c.last_name',
						'b.fee_name', 'semester', 'a.operator', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Reg No</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Fees</th>';
				$content .='<th>Semester</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->fee_name.'</td>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}				
			}
			if($search_param == "Fees-Instruct"){
				$records =  DB::table('fees_instruction as a')
					->join('sch_classes as b','b.class_id', '=', 'a.class_id')
					->select('a.instruction', 'b.class_name',
						'a.operator', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Class</th>';
				$content .='<th>Fee Instruction</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->class_name.'</td>';
					$content .='<td>'.$record->instruction.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Fees-Payments"){
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 
						'a.student_id', 'd.class_div', 'a.amount','a.narration','semester', 
						'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Date</th>';
				$content .='<th>Reg No</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Fees</th>';
				$content .='<th>Amount</th>';
				$content .='<th>Class</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Semester</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->payment_date.'</td>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->fee_name.'</td>';
					$content .='<td>'.$record->amount.'</td>';
					$content .='<td>'.$record->class_div.'</td>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Fees-Refunds"){
				$records = DB::table('fees_refund as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','c.fee_id', '=', 'a.fee_id')
					->join('semester as d','d.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.refund_date', 'semester',
						'a.student_id', 'a.amount','a.narration', 'a.operator', 'a.reviewer', 'a.created_at')
					->distinct('a.student_id', 'a.refund_date','a.amount')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Date</th>';
				$content .='<th>Reg No</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Fees</th>';
				$content .='<th>Amount</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Semester</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->refund_date.'</td>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->fee_name.'</td>';
					$content .='<td>'.$record->amount.'</td>';
					$content .='<td>'.$record->narration.'</td>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			
			if($search_param == "Fees-Discounts"){
				$records = DB::table('fees_discount as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('semester as c','c.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount', 'a.discount_date', 'a.student_id',
						'a.amount','a.narration','c.semester', 'a.operator', 'a.reviewer', 'a.created_at')
					->distinct('a.student_id', 'a.discount_date','a.amount')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Date</th>';
				$content .='<th>Reg No</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Type</th>';
				$content .='<th>Amount</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Semester</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->discount_date.'</td>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->discount.'</td>';
					$content .='<td>'.$record->amount.'</td>';
					$content .='<td>'.$record->narration.'</td>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
				
			if($search_param == "Bank-Payments"){
				$records = DB::table('bank_payment as a')
					->join('banks as b','b.bank_id', '=', 'a.bank_id')
					->join('fees_payment as c','c.bank_payment_id', '=', 'a.payment_id')
					->join('fees as d','d.fee_id', '=', 'c.fee_id')
					->join('students as e','e.student_id', '=', 'c.student_id')
					->join('semester as f','f.semester_id', '=', 'c.semester_id')
					->join('class_div as g','g.class_div_id', '=', 'c.class_div_id')
					->select('c.bank_payment_id', 'c.payment_date', 'c.amount', 'c.narration', 
						'a.channel', 'a.reference', 'g.class_div', 
						'e.reg_no', 'e.first_name', 'e.last_name','b.bank_name','f.semester','d.fee_name',
						'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Date</th>';
				$content .='<th>Reg No</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Class</th>';
				$content .='<th>Bank</th>';
				$content .='<th>Semester</th>';
				$content .='<th>Fee</th>';
				$content .='<th>Channel</th>';
				$content .='<th>Reference</th>';
				$content .='<th>Amount</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->payment_date.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->class_div.'</td>';
					$content .='<td>'.$record->bank_name.'</td>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='<td>'.$record->fee_name.'</td>';
					$content .='<td>'.$record->channel.'</td>';
					$content .='<td>'.$record->reference.'</td>';
					$content .='<td>'.$record->amount.'</td>';
					$content .='<td>'.$record->narration.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}		
			if($search_param == "Expenses"){
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id',
						'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Date</th>';
				$content .='<th>Expense</th>';
				$content .='<th>Voucher</th>';
				$content .='<th>Bank</th>';
				$content .='<th>Reference</th>';
				$content .='<th>Beneficiary</th>';
				$content .='<th>Channel</th>';
				$content .='<th>Qty</th>';
				$content .='<th>Price</th>';
				$content .='<th>Channel</th>';
				$content .='<th>Amount</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
						
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->txn_date.'</td>';
					$content .='<td>'.$record->expense_name.'</td>';
					$content .='<td>'.$record->voucher_no.'</td>';
					$content .='<td>'.$record->bank_name.'</td>';
					$content .='<td>'.$record->bank_ref.'</td>';
					$content .='<td>'.$record->beneficiary.'</td>';
					$content .='<td>'.$record->pay_channel.'</td>';
					$content .='<td>'.$record->qty.'</td>';
					$content .='<td>'.$record->price.'</td>';
					$content .='<td>'.$record->amount.'</td>';
					$content .='<td>'.$record->narration.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Funds-Transfer"){
				$records = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id',
						'a.operator', 'a.reviewer', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Date</th>';
				$content .='<th>From Bank</th>';
				$content .='<th>To Bank</th>';
				$content .='<th>Reference</th>';
				$content .='<th>Channel</th>';
				$content .='<th>Amount</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->txn_date.'</td>';
					$content .='<td>'.$record->bank_from.'</td>';
					$content .='<td>'.$record-> bank_to.'</td>';
					$content .='<td>'.$record->bank_ref.'</td>';
					$content .='<td>'.$record->pay_channel.'</td>';
					$content .='<td>'.$record->amount.'</td>';
					$content .='<td>'.$record->narration.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Exam-Score"){
				$records = DB::table('exam_score as a')
					->join('semester as b','b.semester_id', '=', 'a.semester_id')
					->join('class_div as c','c.class_div_id', '=', 'a.class_div_id')
					->join('subjects as d','d.subject_id', '=', 'a.subject_id')
					->join('students as e','e.student_id', '=', 'a.student_id')
					->join('exam_name as f','f.exam_id', '=', 'a.exam_id')
					->join('exam_class as g','g.exam_id', '=', 'a.exam_id')
					->select('c.class_div','d.subject','exam_score', 'semester', 'exam_date', 'max_score', 'exam_weight',
						'exam_name', 'reg_no', 'first_name', 'last_name',
						'a.operator', 'a.reviewer', 'a.created_at')
					->distinct('reg_no', 'exam_date', 'exam_name')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Date</th>';
				$content .='<th>Reg No</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Class</th>';
				$content .='<th>Subject</th>';
				$content .='<th>Exam</th>';
				$content .='<th>Score</th>';
				$content .='<th>Maximum Score</th>';
				$content .='<th>Exam Weight</th>';
				$content .='<th>Term</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
				
					$content .='<tr>';
					$content .='<td>'.$record->exam_date.'</td>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->class_div.'</td>';
					$content .='<td>'.$record->subject.'</td>';
					$content .='<td>'.$record->exam_name.'</td>';
					$content .='<td>'.$record->exam_score.'</td>';
					$content .='<td>'.$record->max_score.'</td>';
					$content .='<td>'.$record->exam_weight.'</td>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='</tr>';
				}
			}
		
		if($search_param == "Teachers-Comments"){
				$records = DB::table('teachers_comment as a')
					->join('semester as b','b.semester_id', '=', 'a.semester_id')
					->join('class_div as c','c.class_div_id', '=', 'a.class_div_id')
					->join('students as d','d.student_id', '=', 'a.student_id')
					->select('c.class_div', 'semester', 'comment', 'reg_no', 'first_name', 
						'last_name', 'a.operator', 'a.created_at')
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
								
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Reg No</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Semester</th>';
				$content .='<th>Class</th>';
				$content .='<th>Comment</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='<td>'.$record->class_div.'</td>';
					$content .='<td>'.$record->comment.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			if($search_param == "Result-Assessments"){
				$records = DB::table('term_student_rating as a')
					->join('semester as b','b.semester_id', '=', 'a.semester_id')
					->join('students as d','d.student_id', '=', 'a.student_id')
					->join('assessment_param as e','a.param_id', '=', 'a.param_id')
					->join('student_assessment as f','f.assessment_id', '=', 'e.assessment_id')
					->join('student_enrol as g','g.student_id', '=', 'a.student_id')
					->join('class_div as h','h.class_div_id', '=', 'g.class_id')
					->select('remarks', 'semester', 'assessment', 'reg_no', 'first_name', 'parameter',
						'last_name', 'a.operator', 'a.created_at', 'class_div')
					->where('g.active', "1")
					->where('a.created_at', ">=", $from_date)
					->where('a.created_at', "<=", $to_date)
					->orderBy('a.created_at', 'ASC')
					->get();
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Term</th>';
				$content .='<th>Reg No</th>';
				$content .='<th>Last Name</th>';
				$content .='<th>First Name</th>';
				$content .='<th>Class</th>';
				$content .='<th>Assessment</th>';
				$content .='<th>Parameter</th>';
				$content .='<th>Rating</th>';
				$content .='<th>Operator</th>';
				$content .='<th>Post Date</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				foreach ($records as $record){
					$content .='<tr>';
					$content .='<td>'.$record->semester.'</td>';
					$content .='<td>'.$record->reg_no.'</td>';
					$content .='<td>'.$record->last_name.'</td>';
					$content .='<td>'.$record->first_name.'</td>';
					$content .='<td>'.$record->class_div.'</td>';
					$content .='<td>'.$record->assessment.'</td>';
					$content .='<td>'.$record->parameter.'</td>';
					$content .='<td>'.$record->remarks.'</td>';
					$content .='<td>'.$record->operator.'</td>';
					$content .='<td>'.$record->created_at.'</td>';
					$content .='</tr>';
				}
			}
			$content .='</tbody>';
			$content .='</table>';
			return $content;
			
		}catch (\Exception $e) {$this->report_error($e, 'Search', 'Journal', $search_param);}
	}
	public function queryStatement(Request $request){
		try{
			$yr = $request->sel_year; 
			$mon = $request->sel_month;
			
			$search_param = $request->search_param;
			$month = $mon;
			if (strlen($month) < 2) $month = '0'.$month;
			//build a date string
			$inital_date = $yr.'-'.$month.'-01';
			//get the last date of the month
			$last_date = date('Y-m-t', strtotime($inital_date));
			
			$start_yr = $yr.'-01-01';
			$end_yr = $last_date;
			
			if( $search_param == "Monthly-Summary"){
				
				//greater than start date
				$month_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $inital_date ) ) . "-5 month" ) );
				$month_start_title = $month_start;

				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Category</th>';
				$content .='<th>Type</th>';
				for ($i=0; $i<6; $i++) { 
					//could it be M or Y
					$content .='<th>'.date('M-y', strtotime($month_start_title)).'</th>';
					$month_start_title = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_title ) ) . "+1 month" ) );
				}
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				////////////////////////////////////Start with Expenses
				
				$column_value = array();
				$column_value[0] = 0.00;
				$column_value[1] = 0.00;
				$column_value[2] = 0.00;
				$column_value[3] = 0.00;
				$column_value[4] = 0.00;
				$column_value[5] = 0.00;
				///////////////////////////////assets
				$banks = DB::table('banks')->orderBy('bank_name', 'DESC')->get();
				
				foreach ($banks as $bank){
					$bank_id = $bank->bank_id;
					$date_start = $month_start;
					$total_item = 0.00;
					$content .='<tr>';
					$content .='<td>Cash & Bank</td>';
					$content .='<td>'.$bank->bank_name.'</td>';
					for ($i=0; $i<6; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($date_start));
						$mon = date('n', strtotime($date_start));
						//using bank_payment table
						$cur_out = DB::table('bank_payment as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->whereRaw('MONTH(a.txn_date) ='. $mon)
							->where('a.txn_type', 'OUT')
							->where('a.bank_id', $bank_id)
							->value('balance');
						$cur_in = DB::table('bank_payment as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->whereRaw('MONTH(a.txn_date) ='. $mon)
							->where('a.txn_type', 'IN')
							->where('a.bank_id', $bank_id)
							->value('balance');
						$current = $cur_out - $cur_in;
						
						$column_value[$i] = $column_value[$i] + $current;
						$content .='<td align="right">'.number_format($current, 2, '.', ',').'</td>';
						
						$date_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $date_start ) ) . "+1 month" ) );
					}
					$content .='</tr>';
				}
				//////////////////expenses
				$groups = DB::table('acct_group as a')
							->where('a.account_category', 'Expenses')
							->distinct('a.group_name')
							->get();
							
				foreach ($groups as $group){
					$month_start_exp = $month_start;
					$total_item = 0.00;
					$group_name = $group->group_name;
					$group_id = $group->group_id;
					$content .='<tr>';
					$content .='<td>Expenses</td>';
					$content .='<td>'.$group_name.'</td>';
					for ($i=0; $i<6; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($month_start_exp));
						$mon = date('n', strtotime($month_start_exp));
						
						$total_ = DB::table('txn_exp as a')
							->join('expenses as b','b.expense_id', '=', 'a.expense_id')
							->join('acct_group as c','c.group_id', '=', 'b.group_id')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->whereRaw('MONTH(a.txn_date) ='. $mon)
							->where('b.group_id', $group_id)
							->value('balance');	
						
						$total_ = $total_ * -1;
						$column_value[$i] = $column_value[$i] + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$month_start_exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_exp ) ) . "+1 month" ) );
					}
					$content .='</tr>';
				}
				///////////////////////////////////////////////Income				
				$groups = DB::table('acct_group as a')
						->where('a.account_category', 'Income')
						->orderBy('a.group_name', 'DESC')
						->get();

				foreach ($groups as $group){
					$month_start_inc = $month_start;
					$total_item = 0.00;
					$group_name = $group->group_name;
					$group_id = $group->group_id;
					$content .='<tr>';
					$content .='<td>Income</td>';
					$content .='<td>'.$group_name.'</td>';
					for ($i=0; $i<6; $i++) { 
						$yr = date('Y', strtotime($month_start_inc));
						$mon = date('n', strtotime($month_start_inc));
						//get all the transactions for the expenses_id
						$total_ = DB::table('fees_payment as a')
								->join('fees as b','b.fee_id', '=', 'a.fee_id')
								->join('acct_group as c','c.group_id', '=', 'b.group_id')
								->select(DB::raw('SUM(a.amount) AS balance'))
								->where(DB::raw("(DATE_FORMAT(a.payment_date ,'%Y'))"),$yr)
								->whereRaw('MONTH(a.payment_date) ='. $mon)
								->where('b.group_id', $group_id)
								->value('balance');	
						
						$column_value[$i] = $column_value[$i] + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$month_start_inc = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_inc ) ) . "+1 month" ) );
					}
					$content .='</tr>';
				}
				
				$content .='<tr>';
				$content .='<th>Total</th>';
				$content .='<th></th>';
				$content .='<td align="right">'.number_format($column_value[0], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[1], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[2], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[3], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[4], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[5], 2, '.', ',').'</td>';
				$content .='<th></th>';
				$content .='</tr>';
				
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			else if( $search_param == "Monthly-Detail"){
				
				//greater than start date
				$month_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $inital_date ) ) . "-5 month" ) );
				$month_start_title = $month_start;

				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Category</th>';
				$content .='<th>Type</th>';
				$content .='<th>Item</th>';
				for ($i=0; $i<6; $i++) { 
					//could it be M or Y
					$content .='<th>'.date('M-y', strtotime($month_start_title)).'</th>';
					$month_start_title = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_title ) ) . "+1 month" ) );
				}
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				
				$column_value = array();
				$column_value[0] = 0.00;
				$column_value[1] = 0.00;
				$column_value[2] = 0.00;
				$column_value[3] = 0.00;
				$column_value[4] = 0.00;
				$column_value[5] = 0.00;
				
				$banks = DB::table('banks')->orderBy('bank_name', 'DESC')->get();
				foreach ($banks as $bank){
					$bank_id = $bank->bank_id;
					$date_start = $month_start;
					$total_item = 0.00;
					$content .='<tr>';
					$content .='<td>Assets</td>';
					$content .='<td>Cash & Bank</td>';
					$content .='<td>'.$bank->bank_name.'</td>';
					for ($i=0; $i<6; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($date_start));
						$mon = date('n', strtotime($date_start));
						//using bank_payment table
						$cur_out = DB::table('bank_payment as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->whereRaw('MONTH(a.txn_date) ='. $mon)
							->where('a.txn_type', 'OUT')
							->where('a.bank_id', $bank_id)
							->value('balance');
						$cur_in = DB::table('bank_payment as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->whereRaw('MONTH(a.txn_date) ='. $mon)
							->where('a.txn_type', 'IN')
							->where('a.bank_id', $bank_id)
							->value('balance');
						$current = $cur_out - $cur_in;
						
						$column_value[$i] = $column_value[$i] + $current;
						$content .='<td align="right">'.number_format($current, 2, '.', ',').'</td>';
						
						$date_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $date_start ) ) . "+1 month" ) );
					}
					$content .='</tr>';
				}
				////////////////////////////////////Start with Expenses
				$expenses = DB::table('expenses as a')
							->join('acct_group as b','b.group_id', '=', 'a.group_id')
							->select('a.group_id', 'a.expense_id', 'b.group_name', 'a.expense_name')
							->orderBy('a.group_id', 'DESC')
							->distinct('a.expense_id')
							->get();
				
				foreach ($expenses as $expense){
					$month_start_exp = $month_start;
					$total_item = 0.00;
					$expense_id = $expense->expense_id;
					$expense_name = $expense->expense_name;
					$group_name = $expense->group_name;
					$content .='<tr>';
					$content .='<td>Expenses</td>';
					$content .='<td>'.$group_name.'</td>';
					$content .='<td>'.$expense_name.'</td>';
					for ($i=0; $i<6; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($month_start_exp));
						$mon = date('n', strtotime($month_start_exp));
						
						$total_ =DB::table('txn_exp as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->whereRaw('MONTH(a.txn_date) ='. $mon)
							->where('a.expense_id', $expense_id)
							->value('balance');	
						
						$total_ = $total_ * -1;
						$column_value[$i] = $column_value[$i] + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$month_start_exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_exp ) ) . "+1 month" ) );
					}
					$content .='</tr>';
				}
				///////////////////////////////////////////////Income				
				$fees = DB::table('fees as a')
							->join('acct_group as b','b.group_id', '=', 'a.group_id')
							->select('a.group_id', 'a.fee_id', 'b.group_name', 'a.fee_name')
							->orderBy('a.group_id', 'DESC')
							->distinct('a.fee_id')
							->get();
				foreach ($fees as $fee){
					$fee_id = $fee->fee_id;
					$fee_name = $fee->fee_name;
					$group_name = $fee->group_name;
					
					$month_start_inc = $month_start;
					$total_item = 0.00;
					$content .='<tr>';
					$content .='<td>Income</td>';
					$content .='<td>'.$group_name.'</td>';
					$content .='<td>'.$fee_name.'</td>';
					
					for ($i=0; $i<6; $i++) { 
						$yr = date('Y', strtotime($month_start_inc));
						$mon = date('n', strtotime($month_start_inc));
						//get all the transactions for the expenses_id
						$total_ = DB::table('fees_payment as a')
								->select(DB::raw('SUM(a.amount) AS balance'))
								->where(DB::raw("(DATE_FORMAT(a.payment_date ,'%Y'))"),$yr)
								->whereRaw('MONTH(a.payment_date) ='. $mon)
								->where('a.fee_id', $fee_id)
								->value('balance');	
						
						$column_value[$i] = $column_value[$i] + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$month_start_inc = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_inc ) ) . "+1 month" ) );
					}
					$content .='</tr>';
				}
				$content .='<tr>';
				$content .='<th>Total</th>';
				$content .='<th></th>';
				$content .='<th></th>';
				$content .='<td align="right">'.number_format($column_value[0], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[1], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[2], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[3], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[4], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[5], 2, '.', ',').'</td>';
				$content .='<th></th>';
				$content .='</tr>';
				
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			if( $search_param == "Yearly-Summary"){
				
				//this prepares for 5 years summary
				$year_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $inital_date ) ) . "-4 year" ) );
				$year_start_title = $year_start;
				
				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Category</th>';
				$content .='<th>Type</th>';
				for ($i=0; $i<5; $i++) { 
					//could it be M or Y
					$content .='<th>'.date('Y', strtotime($year_start_title)).'</th>';
					$year_start_title = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_title ) ) . "+1 year" ) );
				}
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				////////////////////////////////////Start with Expenses
				$column_value = array();
				$column_value[0] = 0.00;
				$column_value[1] = 0.00;
				$column_value[2] = 0.00;
				$column_value[3] = 0.00;
				$column_value[4] = 0.00;
				
				$banks = DB::table('banks')->orderBy('bank_name', 'DESC')->get();
				foreach ($banks as $bank){
					$bank_id = $bank->bank_id;
					$date_start = $year_start;
					$total_item = 0.00;
					$content .='<tr>';
					$content .='<td>Cash & Bank</td>';
					$content .='<td>'.$bank->bank_name.'</td>';
					for ($i=0; $i<5; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($date_start));
						$mon = date('n', strtotime($date_start));
						//using bank_payment table
						$cur_out = DB::table('bank_payment as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->where('a.txn_type', 'OUT')
							->where('a.bank_id', $bank_id)
							->value('balance');
						$cur_in = DB::table('bank_payment as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->where('a.txn_type', 'IN')
							->where('a.bank_id', $bank_id)
							->value('balance');
						$current = $cur_out - $cur_in;
						
						$column_value[$i] = $column_value[$i] + $current;
						$content .='<td align="right">'.number_format($current, 2, '.', ',').'</td>';
						
						$date_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $date_start ) ) . "+1 year" ) );
					}
					$content .='</tr>';
				}
				
				$groups = DB::table('acct_group as a')
							->where('a.account_category', 'Expenses')
							->distinct('a.group_name')
							->get();
								
				foreach ($groups as $group){
					$year_start_exp = $year_start;
					$total_item = 0.00;
					$group_name = $group->group_name;
					$group_id = $group->group_id;
					$content .='<tr>';
					$content .='<td>Expenses</td>';
					$content .='<td>'.$group_name.'</td>';
					for ($i=0; $i<5; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($year_start_exp));
						$mon = date('n', strtotime($year_start_exp));
						
						$total_ =DB::table('txn_exp as a')
							->join('expenses as b','b.expense_id', '=', 'a.expense_id')
							->join('acct_group as c','c.group_id', '=', 'b.group_id')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->where('b.group_id', $group_id)
							->value('balance');	
						
						$total_ = $total_ * -1;
						$column_value[$i] = $column_value[$i] + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$year_start_exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_exp ) ) . "+1 year" ) );
					}
					$content .='</tr>';
				}
				///////////////////////////////////////////////Income				
				$groups = DB::table('acct_group as a')
						->where('a.account_category', 'Income')
						->orderBy('a.group_name', 'DESC')
						->get();

				foreach ($groups as $group){
					$year_start_inc = $year_start;
					$total_item = 0.00;
					$group_name = $group->group_name;
					$group_id = $group->group_id;
					$content .='<tr>';
					$content .='<td>Income</td>';
					$content .='<td>'.$group_name.'</td>';
					for ($i=0; $i<5; $i++) { 
						$yr = date('Y', strtotime($year_start_inc));
						$mon = date('n', strtotime($year_start_inc));
						//get all the transactions for the expenses_id
						$total_ = DB::table('fees_payment as a')
								->join('fees as b','b.fee_id', '=', 'a.fee_id')
								->join('acct_group as c','c.group_id', '=', 'b.group_id')
								->select(DB::raw('SUM(a.amount) AS balance'))
								->where(DB::raw("(DATE_FORMAT(a.payment_date ,'%Y'))"),$yr)
								->where('b.group_id', $group_id)
								->value('balance');	
						
						$column_value[$i] = $column_value[$i] + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$year_start_inc = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_inc ) ) . "+1 year" ) );
					}
					$content .='</tr>';
				}
				$content .='<tr>';
				$content .='<th>Total</th>';
				$content .='<th></th>';
				$content .='<td align="right">'.number_format($column_value[0], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[1], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[2], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[3], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[4], 2, '.', ',').'</td>';
				$content .='<th></th>';
				$content .='</tr>';
				
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			else if( $search_param == "Yearly-Detail"){
				
				//greater than start date
				$year_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $inital_date ) ) . "-4 year" ) );
				$year_start_title = $year_start;

				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Category</th>';
				$content .='<th>Type</th>';
				$content .='<th>Item</th>';
				for ($i=0; $i<5; $i++) { 
					//could it be M or Y
					$content .='<th>'.date('Y', strtotime($year_start_title)).'</th>';
					$year_start_title = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_title ) ) . "+1 year" ) );
				}
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
							
				$column_value = array();
				$column_value[0] = 0.00;
				$column_value[1] = 0.00;
				$column_value[2] = 0.00;
				$column_value[3] = 0.00;
				$column_value[4] = 0.00;
				
				$banks = DB::table('banks')->orderBy('bank_name', 'DESC')->get();
				foreach ($banks as $bank){
					$bank_id = $bank->bank_id;
					$date_start = $year_start;
					$total_item = 0.00;
					$content .='<tr>';
					$content .='<td>Assets</td>';
					$content .='<td>Cash & Bank</td>';
					$content .='<td>'.$bank->bank_name.'</td>';
					for ($i=0; $i<5; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($date_start));
						$mon = date('n', strtotime($date_start));
						//using bank_payment table
						$cur_out = DB::table('bank_payment as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->where('a.txn_type', 'OUT')
							->where('a.bank_id', $bank_id)
							->value('balance');
						$cur_in = DB::table('bank_payment as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->where('a.txn_type', 'IN')
							->where('a.bank_id', $bank_id)
							->value('balance');
						$current = $cur_out - $cur_in;
						
						$column_value[$i] = $column_value[$i] + $current;
						$content .='<td align="right">'.number_format($current, 2, '.', ',').'</td>';
						
						$date_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $date_start ) ) . "+1 year" ) );
					}
					$content .='</tr>';
				}
				////////////////////////////////////Start with Expenses
				$expenses = DB::table('expenses as a')
							->join('acct_group as b','b.group_id', '=', 'a.group_id')
							->select('a.group_id', 'a.expense_id', 'b.group_name', 'a.expense_name')
							->orderBy('a.group_id', 'DESC')
							->distinct('a.expense_id')
							->get();
				
				foreach ($expenses as $expense){
					$year_start_exp = $year_start;
					$total_item = 0.00;
					$expense_id = $expense->expense_id;
					$expense_name = $expense->expense_name;
					$group_name = $expense->group_name;
					$content .='<tr>';
					$content .='<td>Expenses</td>';
					$content .='<td>'.$group_name.'</td>';
					$content .='<td>'.$expense_name.'</td>';
					for ($i=0; $i<5; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($year_start_exp));
						
						$total_ =DB::table('txn_exp as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->where('a.expense_id', $expense_id)
							->value('balance');	
						
						$total_ = $total_ * -1;
						$column_value[$i] = $column_value[$i] + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$year_start_exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_exp ) ) . "+1 year" ) );
					}
					$content .='</tr>';
				}
				///////////////////////////////////////////////Income				
				$fees = DB::table('fees as a')
							->join('acct_group as b','b.group_id', '=', 'a.group_id')
							->select('a.group_id', 'a.fee_id', 'b.group_name', 'a.fee_name')
							->orderBy('a.group_id', 'DESC')
							->distinct('a.fee_id')
							->get();
				foreach ($fees as $fee){
					$fee_id = $fee->fee_id;
					$fee_name = $fee->fee_name;
					$group_name = $fee->group_name;
					
					$year_start_inc = $year_start;
					$total_item = 0.00;
					$content .='<tr>';
					$content .='<td>Income</td>';
					$content .='<td>'.$group_name.'</td>';
					$content .='<td>'.$fee_name.'</td>';
					
					for ($i=0; $i<5; $i++) { 
						$yr = date('Y', strtotime($year_start_inc));
						//get all the transactions for the expenses_id
						$total_ = DB::table('fees_payment as a')
								->select(DB::raw('SUM(a.amount) AS balance'))
								->where(DB::raw("(DATE_FORMAT(a.payment_date ,'%Y'))"),$yr)
								->where('a.fee_id', $fee_id)
								->value('balance');	
						
						$column_value[$i] = $column_value[$i] + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$year_start_inc = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_inc ) ) . "+1 year" ) );
					}
					$content .='</tr>';
				}
				$content .='<tr>';
				$content .='<th>Total</th>';
				$content .='<th></th>';
				$content .='<th></th>';
				$content .='<td align="right">'.number_format($column_value[0], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[1], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[2], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[3], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[4], 2, '.', ',').'</td>';
				$content .='<th></th>';
				$content .='</tr>';
				
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
		} catch (\Exception $e) {$this->report_error($e, 'Search', 'Enrolment', 'Enrolment');}
	}
	public function queryPerformance(Request $request){
		try{
			$yr = $request->sel_year; 
			$mon = $request->sel_month;
			
			$search_param = $request->search_param;
			$month = $mon;
			if (strlen($month) < 2) $month = '0'.$month;
			//build a date string
			$inital_date = $yr.'-'.$month.'-01';
			//get the last date of the month
			$last_date = date('Y-m-t', strtotime($inital_date));
			
			$start_yr = $yr.'-01-01';
			$end_yr = $last_date;
			
			if( $search_param == "Monthly-Summary"){
				
				//greater than start date
				$month_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $inital_date ) ) . "-5 month" ) );
				$month_start_title = $month_start;

				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Category</th>';
				$content .='<th>Type</th>';
				for ($i=0; $i<6; $i++) { 
					//could it be M or Y
					$content .='<th>'.date('M-y', strtotime($month_start_title)).'</th>';
					$month_start_title = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_title ) ) . "+1 month" ) );
				}
				$content .='<th>Total</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				////////////////////////////////////Start with Expenses
				$column_value = array();
				$column_value[0] = 0.00;
				$column_value[1] = 0.00;
				$column_value[2] = 0.00;
				$column_value[3] = 0.00;
				$column_value[4] = 0.00;
				$column_value[5] = 0.00;
				
				$groups = DB::table('acct_group as a')
							->where('a.account_category', 'Expenses')
							->distinct('a.group_name')
							->get();
							
				
				foreach ($groups as $group){
					$month_start_exp = $month_start;
					$total_item = 0.00;
					$group_name = $group->group_name;
					$group_id = $group->group_id;
					$content .='<tr>';
					$content .='<td>Expenses</td>';
					$content .='<td>'.$group_name.'</td>';
					for ($i=0; $i<6; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($month_start_exp));
						$mon = date('n', strtotime($month_start_exp));
						
						$total_ =DB::table('txn_exp as a')
							->join('expenses as b','b.expense_id', '=', 'a.expense_id')
							->join('acct_group as c','c.group_id', '=', 'b.group_id')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->whereRaw('MONTH(a.txn_date) ='. $mon)
							->where('b.group_id', $group_id)
							->value('balance');	
						
						$total_ = $total_ * -1;
						$column_value[$i] = $column_value[$i] + $total_;
						$total_item  = $total_item + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$month_start_exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_exp ) ) . "+1 month" ) );
					}
					$content .='<td align="right">'.number_format($total_item, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				///////////////////////////////////////////////Income				
				$groups = DB::table('acct_group as a')
						->where('a.account_category', 'Income')
						->orderBy('a.group_name', 'DESC')
						->get();

				foreach ($groups as $group){
					$month_start_inc = $month_start;
					$total_item = 0.00;
					$group_name = $group->group_name;
					$group_id = $group->group_id;
					$content .='<tr>';
					$content .='<td>Income</td>';
					$content .='<td>'.$group_name.'</td>';
					for ($i=0; $i<6; $i++) { 
						$yr = date('Y', strtotime($month_start_inc));
						$mon = date('n', strtotime($month_start_inc));
						//get all the transactions for the expenses_id
						$total_ = DB::table('fees_payment as a')
								->join('fees as b','b.fee_id', '=', 'a.fee_id')
								->join('acct_group as c','c.group_id', '=', 'b.group_id')
								->select(DB::raw('SUM(a.amount) AS balance'))
								->where(DB::raw("(DATE_FORMAT(a.payment_date ,'%Y'))"),$yr)
								->whereRaw('MONTH(a.payment_date) ='. $mon)
								->where('b.group_id', $group_id)
								->value('balance');	
						
						$column_value[$i] = $column_value[$i] + $total_;
						$total_item  = $total_item + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$month_start_inc = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_inc ) ) . "+1 month" ) );
					}
					$content .='<td align="right">'.number_format($total_item, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='<tr>';
				$content .='<th>Net Profit/(Loss)</th>';
				$content .='<th></th>';
				$content .='<td align="right">'.number_format($column_value[0], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[1], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[2], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[3], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[4], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[5], 2, '.', ',').'</td>';
				$content .='<th></th>';
				$content .='</tr>';
				
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			else if( $search_param == "Monthly-Detail"){
				
				//greater than start date
				$month_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $inital_date ) ) . "-5 month" ) );
				$month_start_title = $month_start;

				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Category</th>';
				$content .='<th>Type</th>';
				$content .='<th>Item</th>';
				for ($i=0; $i<6; $i++) { 
					//could it be M or Y
					$content .='<th>'.date('M-y', strtotime($month_start_title)).'</th>';
					$month_start_title = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_title ) ) . "+1 month" ) );
				}
				$content .='<th>Total</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				////////////////////////////////////Start with Expenses
				$column_value = array();
				$column_value[0] = 0.00;
				$column_value[1] = 0.00;
				$column_value[2] = 0.00;
				$column_value[3] = 0.00;
				$column_value[4] = 0.00;
				$column_value[5] = 0.00;
				
				$expenses = DB::table('expenses as a')
							->join('acct_group as b','b.group_id', '=', 'a.group_id')
							->select('a.group_id', 'a.expense_id', 'b.group_name', 'a.expense_name')
							->orderBy('a.group_id', 'DESC')
							->distinct('a.expense_id')
							->get();
				
				foreach ($expenses as $expense){
					$month_start_exp = $month_start;
					$total_item = 0.00;
					$expense_id = $expense->expense_id;
					$expense_name = $expense->expense_name;
					$group_name = $expense->group_name;
					$content .='<tr>';
					$content .='<td>Expenses</td>';
					$content .='<td>'.$group_name.'</td>';
					$content .='<td>'.$expense_name.'</td>';
					for ($i=0; $i<6; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($month_start_exp));
						$mon = date('n', strtotime($month_start_exp));
						
						$total_ =DB::table('txn_exp as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->whereRaw('MONTH(a.txn_date) ='. $mon)
							->where('a.expense_id', $expense_id)
							->value('balance');	
						
						$total_ = $total_ * -1;
						$column_value[$i] = $column_value[$i] + $total_;
						$total_item  = $total_item + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$month_start_exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_exp ) ) . "+1 month" ) );
					}
					$content .='<td align="right">'.number_format($total_item, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				///////////////////////////////////////////////Income				
				$fees = DB::table('fees as a')
							->join('acct_group as b','b.group_id', '=', 'a.group_id')
							->select('a.group_id', 'a.fee_id', 'b.group_name', 'a.fee_name')
							->orderBy('a.group_id', 'DESC')
							->distinct('a.fee_id')
							->get();
				foreach ($fees as $fee){
					$fee_id = $fee->fee_id;
					$fee_name = $fee->fee_name;
					$group_name = $fee->group_name;
					
					$month_start_inc = $month_start;
					$total_item = 0.00;
					$content .='<tr>';
					$content .='<td>Income</td>';
					$content .='<td>'.$group_name.'</td>';
					$content .='<td>'.$fee_name.'</td>';
					
					for ($i=0; $i<6; $i++) { 
						$yr = date('Y', strtotime($month_start_inc));
						$mon = date('n', strtotime($month_start_inc));
						//get all the transactions for the expenses_id
						$total_ = DB::table('fees_payment as a')
								->select(DB::raw('SUM(a.amount) AS balance'))
								->where(DB::raw("(DATE_FORMAT(a.payment_date ,'%Y'))"),$yr)
								->whereRaw('MONTH(a.payment_date) ='. $mon)
								->where('a.fee_id', $fee_id)
								->value('balance');	
						
						$column_value[$i] = $column_value[$i] + $total_;
						$total_item  = $total_item + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$month_start_inc = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $month_start_inc ) ) . "+1 month" ) );
					}
					$content .='<td align="right">'.number_format($total_item, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='<tr>';
				$content .='<th>Net Profit/(Loss)</th>';
				$content .='<th></th>';
				$content .='<th></th>';
				$content .='<td align="right">'.number_format($column_value[0], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[1], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[2], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[3], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[4], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[5], 2, '.', ',').'</td>';
				$content .='<th></th>';
				$content .='</tr>';
				
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			if( $search_param == "Yearly-Summary"){
				
				//this prepares for 5 years summary
				$year_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $inital_date ) ) . "-4 year" ) );
				$year_start_title = $year_start;
				
				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Category</th>';
				$content .='<th>Type</th>';
				for ($i=0; $i<5; $i++) { 
					//could it be M or Y
					$content .='<th>'.date('Y', strtotime($year_start_title)).'</th>';
					$year_start_title = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_title ) ) . "+1 year" ) );
				}
				$content .='<th>Total</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				////////////////////////////////////Start with Expenses
				$column_value = array();
				$column_value[0] = 0.00;
				$column_value[1] = 0.00;
				$column_value[2] = 0.00;
				$column_value[3] = 0.00;
				$column_value[4] = 0.00;
								
				$groups = DB::table('acct_group as a')
							->where('a.account_category', 'Expenses')
							->distinct('a.group_name')
							->get();
							
				foreach ($groups as $group){
					$year_start_exp = $year_start;
					$total_item = 0.00;
					$group_name = $group->group_name;
					$group_id = $group->group_id;
					$content .='<tr>';
					$content .='<td>Expenses</td>';
					$content .='<td>'.$group_name.'</td>';
					for ($i=0; $i<5; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($year_start_exp));
						$mon = date('n', strtotime($year_start_exp));
						
						$total_ =DB::table('txn_exp as a')
							->join('expenses as b','b.expense_id', '=', 'a.expense_id')
							->join('acct_group as c','c.group_id', '=', 'b.group_id')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->where('b.group_id', $group_id)
							->value('balance');	
						
						$total_ = $total_ * -1;
						$column_value[$i] = $column_value[$i] + $total_;
						$total_item  = $total_item + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$year_start_exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_exp ) ) . "+1 year" ) );
					}
					$content .='<td align="right">'.number_format($total_item, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				///////////////////////////////////////////////Income				
				$groups = DB::table('acct_group as a')
						->where('a.account_category', 'Income')
						->orderBy('a.group_name', 'DESC')
						->get();

				foreach ($groups as $group){
					$year_start_inc = $year_start;
					$total_item = 0.00;
					$group_name = $group->group_name;
					$group_id = $group->group_id;
					$content .='<tr>';
					$content .='<td>Income</td>';
					$content .='<td>'.$group_name.'</td>';
					for ($i=0; $i<5; $i++) { 
						$yr = date('Y', strtotime($year_start_inc));
						$mon = date('n', strtotime($year_start_inc));
						//get all the transactions for the expenses_id
						$total_ = DB::table('fees_payment as a')
								->join('fees as b','b.fee_id', '=', 'a.fee_id')
								->join('acct_group as c','c.group_id', '=', 'b.group_id')
								->select(DB::raw('SUM(a.amount) AS balance'))
								->where(DB::raw("(DATE_FORMAT(a.payment_date ,'%Y'))"),$yr)
								->where('b.group_id', $group_id)
								->value('balance');	
						
						$column_value[$i] = $column_value[$i] + $total_;
						$total_item  = $total_item + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$year_start_inc = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_inc ) ) . "+1 year" ) );
					}
					$content .='<td align="right">'.number_format($total_item, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='<tr>';
				$content .='<th>Net Profit/(Loss)</th>';
				$content .='<th></th>';
				$content .='<td align="right">'.number_format($column_value[0], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[1], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[2], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[3], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[4], 2, '.', ',').'</td>';
				$content .='<th></th>';
				$content .='</tr>';
				
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			else if( $search_param == "Yearly-Detail"){
				
				//greater than start date
				$year_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $inital_date ) ) . "-4 year" ) );
				$year_start_title = $year_start;

				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Category</th>';
				$content .='<th>Type</th>';
				$content .='<th>Item</th>';
				for ($i=0; $i<5; $i++) { 
					//could it be M or Y
					$content .='<th>'.date('Y', strtotime($year_start_title)).'</th>';
					$year_start_title = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_title ) ) . "+1 year" ) );
				}
				$content .='<th>Total</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				////////////////////////////////////Start with Expenses
				$column_value = array();
				$column_value[0] = 0.00;
				$column_value[1] = 0.00;
				$column_value[2] = 0.00;
				$column_value[3] = 0.00;
				$column_value[4] = 0.00;
				
				$expenses = DB::table('expenses as a')
							->join('acct_group as b','b.group_id', '=', 'a.group_id')
							->select('a.group_id', 'a.expense_id', 'b.group_name', 'a.expense_name')
							->orderBy('a.group_id', 'DESC')
							->distinct('a.expense_id')
							->get();
							
				foreach ($expenses as $expense){
					$year_start_exp = $year_start;
					$total_item = 0.00;
					$expense_id = $expense->expense_id;
					$expense_name = $expense->expense_name;
					$group_name = $expense->group_name;
					$content .='<tr>';
					$content .='<td>Expenses</td>';
					$content .='<td>'.$group_name.'</td>';
					$content .='<td>'.$expense_name.'</td>';
					for ($i=0; $i<5; $i++) { 
						//get all the transactions for the expenses_id
						$yr = date('Y', strtotime($year_start_exp));
						
						$total_ =DB::table('txn_exp as a')
							->select(DB::raw('SUM(a.amount) AS balance'))
							->where(DB::raw("(DATE_FORMAT(a.txn_date,'%Y'))"),$yr)
							->where('a.expense_id', $expense_id)
							->value('balance');	
						
						$total_ = $total_ * -1;
						$column_value[$i] = $column_value[$i] + $total_;
						$total_item  = $total_item + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$year_start_exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_exp ) ) . "+1 year" ) );
					}
					$content .='<td align="right">'.number_format($total_item, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				///////////////////////////////////////////////Income				
				$fees = DB::table('fees as a')
							->join('acct_group as b','b.group_id', '=', 'a.group_id')
							->select('a.group_id', 'a.fee_id', 'b.group_name', 'a.fee_name')
							->orderBy('a.group_id', 'DESC')
							->distinct('a.fee_id')
							->get();
				foreach ($fees as $fee){
					$fee_id = $fee->fee_id;
					$fee_name = $fee->fee_name;
					$group_name = $fee->group_name;
					
					$year_start_inc = $year_start;
					$total_item = 0.00;
					$content .='<tr>';
					$content .='<td>Income</td>';
					$content .='<td>'.$group_name.'</td>';
					$content .='<td>'.$fee_name.'</td>';
					
					for ($i=0; $i<5; $i++) { 
						$yr = date('Y', strtotime($year_start_inc));
						//get all the transactions for the expenses_id
						$total_ = DB::table('fees_payment as a')
								->select(DB::raw('SUM(a.amount) AS balance'))
								->where(DB::raw("(DATE_FORMAT(a.payment_date ,'%Y'))"),$yr)
								->where('a.fee_id', $fee_id)
								->value('balance');	
						
						$column_value[$i] = $column_value[$i] + $total_;
						$total_item  = $total_item + $total_;
						$content .='<td align="right">'.number_format($total_, 2, '.', ',').'</td>';
						
						$year_start_inc = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $year_start_inc ) ) . "+1 year" ) );
					}
					$content .='<td align="right">'.number_format($total_item, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='<tr>';
				$content .='<th>Net Profit/(Loss)</th>';
				$content .='<th></th>';
				$content .='<th></th>';
				$content .='<td align="right">'.number_format($column_value[0], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[1], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[2], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[3], 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($column_value[4], 2, '.', ',').'</td>';
				$content .='<th></th>';
				$content .='</tr>';
				
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
		} catch (\Exception $e) {$this->report_error($e, 'Search', 'Enrolment', 'Enrolment');}
	}
	public function queryEnrolment(Request $request){
		try{
			//file_put_contents('file_error.txt', $request->search_param. PHP_EOL, FILE_APPEND);
			
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			if( $search_param == "All"){
				/////now search
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
				return $records;
			}
			else if( $search_param == "Gender"){
				$search_val = "1";
				if($search_val = 'Female' || $search_val = 'FEMALE') {
					$search_val = "0";
				}
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.gender', $search_val)
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Tribe"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.tribe', $search_val)
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Religion"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.religion', $search_val)
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Nationality"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.nationality', $search_val)
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "State"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.state_origin', $search_val)
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "LGA"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.lga', 'like', '%'.$search_val.'%')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Town"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.town', 'like', '%'.$search_val.'%')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Class"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('sch_classes.class_name', 'like', '%'.$search_val.'%')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Section"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('class_div.class_div', 'like', '%'.$search_val.'%')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Phone"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.guard_phone', 'like', '%'.$search_val.'%')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "First Name"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.first_name', 'like', '%'.$search_val.'%')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Last Name"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.last_name', 'like', '%'.$search_val.'%')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Email"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.guard_email', 'like', '%'.$search_val.'%')
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			else if( $search_param == "Tribe"){
				$records =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('students.guard_email', $search_val)
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->where('student_enrol.active', '1')
					->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
			}
			return $records;
			
		} catch (\Exception $e) {$this->report_error($e, 'Search', 'Enrolment', 'Enrolment');}
	}
	public function queryBank(Request $request){
		try{
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			$bank_id = $request->bank_id;
			$operator = trim($request->search_operator);
			
			if( $search_param == "All"){
				$records = DB::table('bank_payment as a')
					->join('banks as b','b.bank_id', '=', 'a.bank_id')
					->select('a.narration', 'a.txn_date', 'a.txn_type', 'a.amount', 'a.channel', 'a.reference', 'b.bank_name', 'a.payment_id', 'a.created_at')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->orderBy('a.txn_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Channel"){
				$records = DB::table('bank_payment as a')
					->join('banks as b','b.bank_id', '=', 'a.bank_id')
					->select('a.narration', 'a.txn_date', 'a.txn_type', 'a.amount', 'a.channel', 'a.reference', 'b.bank_name', 'a.payment_id', 'a.created_at')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.channel', 'like', '%'.$search_val.'%')
					->where('a.bank_id', $bank_id)
					->orderBy('a.txn_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Txn Ref"){
				$records = DB::table('bank_payment as a')
					->join('banks as b','b.bank_id', '=', 'a.bank_id')
					->select('a.narration', 'a.txn_date', 'a.txn_type', 'a.amount', 'a.channel', 'a.reference', 'b.bank_name', 'a.payment_id', 'a.created_at')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.reference', 'like', '%'.$search_val.'%')
					->where('a.bank_id', $bank_id)
					->orderBy('a.txn_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Amount"){
				$records = DB::table('bank_payment as a')
					->join('banks as b','b.bank_id', '=', 'a.bank_id')
					->select('a.narration', 'a.txn_date', 'a.txn_type', 'a.amount', 'a.channel', 'a.reference', 'b.bank_name', 'a.payment_id', 'a.created_at')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.bank_id', $bank_id)
					->where('a.amount', $operator, $search_val)
					->orderBy('a.txn_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Type"){
				$records = DB::table('bank_payment as a')
					->join('banks as b','b.bank_id', '=', 'a.bank_id')
					->select('a.narration', 'a.txn_date', 'a.txn_type', 'a.amount', 'a.channel', 'a.reference', 'b.bank_name', 'a.payment_id', 'a.created_at')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.txn_type', 'like', '%'.$search_val.'%')
					->where('a.bank_id', $bank_id)
					->orderBy('a.txn_date', 'DESC')
					->get();
				return $records;
			}
			//all bank blanaces
			if( $search_param == "Balances"){
				//get the balances for all the accounts: Account||Month Total IN|| Month Total OUT|| 
				//Year Total IN|| Year Total OUT||Year Opening|| Month Opening|| Balance
				/*
				m Numeric representation of a month, with leading zeros 01 through 12
				n Numeric representation of a month, without leading zeros 1 through 12
				F Alphabetic representation of a month January through December
				*/
				//get year: $to_date
				//get month: $to_date
				
				
				$yr = date("Y", strtotime($from_date)); 
				$mon = date("m", strtotime($from_date)); 
				$date = date("d", strtotime($from_date));
				
				$start_yr = $yr.'-01'.'-01';
				$start_mnt = $yr.'-'.$mon.'-01';
				//iterate through the banks in the table
				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
				$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Bank</th>';
				$content .='<th>Year Opening</th>';
				$content .='<th>Year IN</th>';
				$content .='<th>Year OUT</th>';
				$content .='<th>Month Opening</th>';
				$content .='<th>Month IN</th>';
				$content .='<th>Month OUT</th>';
				$content .='<th>Day Opening</th>';
				$content .='<th>Day IN</th>';
				$content .='<th>Day OUT</th>';
				$content .='<th>Closing Balance</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				
				$banks = DB::table('banks')->orderBy('group_id', 'DESC')->get();
				foreach ($banks as $bank){
					$bank_id = $bank->bank_id;
					////////////////////////////OPENING
					$yr_start_in = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $start_yr)
						->where('a.txn_type', 'IN')
						->where('a.bank_id', $bank_id)
						->value('balance');
						
					$yr_start_out = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $start_yr)
						->where('a.txn_type', 'OUT')
						->where('a.bank_id', $bank_id)
						->value('balance');
					$yr_op = $yr_start_in - $yr_start_out;
					//////////////////////////////YEAR
					//where(DB::raw("(DATE_FORMAT(enrol_date,'%Y'))"),date('Y'))
					$yr_in = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '>=', $start_yr)
						->where('a.txn_date', '<=', $from_date)
						->where('a.txn_type', 'IN')
						->where('a.bank_id', $bank_id)
						->value('balance');
						
					$yr_out = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '>=', $start_yr)
						->where('a.txn_date', '<=', $from_date)
						->where('a.txn_type', 'OUT')
						->where('a.bank_id', $bank_id)
						->value('balance');
					/////////////////////MONTH START
					$mo_start_in = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $start_mnt)
						->where('a.txn_type', 'IN')
						->where('a.bank_id', $bank_id)
						->value('balance');
						
					$mo_start_out = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $start_mnt)
						->where('a.txn_type', 'OUT')
						->where('a.bank_id', $bank_id)
						->value('balance');
					$mo_op = $mo_start_in - $mo_start_out;
					/////////////////////////MONTH CURRENT
					$mo_in = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '>=', $start_mnt)
						->where('a.txn_date', '<=', $from_date)
						->where('a.txn_type', 'IN')
						->where('a.bank_id', $bank_id)
						->value('balance');
						
					$mo_out = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '>=', $start_mnt)
						->where('a.txn_date', '<=', $from_date)
						->where('a.txn_type', 'OUT')
						->where('a.bank_id', $bank_id)
						->value('balance');
						
					/////////////////////DAY
					$dy_start_in = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $from_date)
						->where('a.txn_type', 'IN')
						->where('a.bank_id', $bank_id)
						->value('balance');
						
					$dy_start_out = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $from_date)
						->where('a.txn_type', 'OUT')
						->where('a.bank_id', $bank_id)
						->value('balance');
					$dy_op = $dy_start_in - $dy_start_out;
					
					$dy_in = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '=', $from_date)
						->where('a.txn_type', 'IN')
						->where('a.bank_id', $bank_id)
						->value('balance');
						
					$dy_out = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '=', $from_date)
						->where('a.txn_type', 'OUT')
						->where('a.bank_id', $bank_id)
						->value('balance');
					///////////////////////////////////current balance
					$cur_out = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<=', $from_date)
						->where('a.txn_type', 'OUT')
						->where('a.bank_id', $bank_id)
						->value('balance');
					$cur_in = DB::table('bank_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<=', $from_date)
						->where('a.txn_type', 'IN')
						->where('a.bank_id', $bank_id)
						->value('balance');
						
					$current = $cur_in - $cur_out;
					
					$content .='<tr>';
					$content .='<td>'.$bank->bank_name.'</td>';
					$content .='<td align="right">'.number_format($yr_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($yr_in, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($yr_out, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($mo_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($mo_in, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($mo_out, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($dy_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($dy_in, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($dy_out, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($current, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			if( $search_param == "Activities"){
				
				$bank_name = DB::table('banks as a')
							->where('a.bank_id', $bank_id)
							->value('a.bank_name');
				
				//iterate through the banks in the table
				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
				$content .='<tr>';
				$content .='<th>S/N</th>';
				$content .='<th>Bank</th>';
				$content .='<th>Transaction Date</th>';
				$content .='<th>Post Date</th>';
				$content .='<th>Channel</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Reference</th>';
				$content .='<th>IN</th>';
				$content .='<th>OUT</th>';
				$content .='<th>Balance</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				//get the opening balance
				$op_in = DB::table('bank_payment as a')
					->select(DB::raw('SUM(a.amount) AS balance'))
					->where('a.txn_date', '<', $from_date)
					->where('a.txn_type', 'IN')
					->where('a.bank_id', $bank_id)
					->value('balance');
					
				$op_out = DB::table('bank_payment as a')
					->select(DB::raw('SUM(a.amount) AS balance'))
					->where('a.txn_date', '<', $from_date)
					->where('a.txn_type', 'OUT')
					->where('a.bank_id', $bank_id)
					->value('balance');
				$op_bal = $op_in - $op_out;
				$serial = 1;
				$content .='<tr>';
				$content .='<td>'.$serial.'</td>';
				$content .='<td>'.$bank_name.'</td>';
				$content .='<td>'.date("d/m/Y", strtotime($from_date)).'</td>';
				$content .='<td>'.date("d/m/Y", strtotime($from_date)).'</td>';
				$content .='<td></td>';
				$content .='<td>Opening balance</td>';
				$content .='<td></td>';
				$content .='<td align="right">'.number_format($op_in, 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($op_out, 2, '.', ',').'</td>';
				$content .='<td align="right">'.number_format($op_bal, 2, '.', ',').'</td>';
				$content .='</tr>';
				//start iterating through the table for date range where there are transactions
				$items = DB::table('bank_payment as a')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.bank_id', $bank_id)
					->orderBy('a.txn_date', 'ASC')
					->get();
					
				foreach ($items as $item){
					$serial = $serial + 1;
					if($item->txn_type == "IN"){
						$op_bal = $op_bal + $item->amount;
					}else{
						$op_bal = $op_bal - $item->amount;
					}
					$content .='<tr>';
					$content .='<td>'.$serial.'</td>';
					$content .='<td>'.$bank_name.'</td>';
					$content .='<td>'.date("d/m/Y", strtotime($item->txn_date)).'</td>';
					$content .='<td>'.date("d/m/Y", strtotime($item->created_at)).'</td>';
					$content .='<td>'.$item->channel.'</td>';
					$content .='<td>'.$item->narration.'</td>';
					$content .='<td>'.$item->reference.'</td>';
					if($item->txn_type == "IN"){
						$content .='<td align="right">'.number_format($item->amount, 2, '.', ',').'</td>';
						$content .='<td></td>';
					}else{
						$content .='<td></td>';
						$content .='<td align="right">'.number_format($item->amount, 2, '.', ',').'</td>';
					}
					$content .='<td align="right">'.number_format($op_bal, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
		} catch (\Exception $e) {
			$this->report_error($e, 'Search', 'Bank', 'Activities');
		}
	}
	public function queryRegistration(Request $request){
		try{
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			if( $search_param == "All"){
				/////now search
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->select('student_id','reg_no','reg_date', 
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
				
			}
			if( $search_param == "Gender"){
				$search_val = "1";
				if($search_val = 'Female' || $search_val = 'FEMALE') {
					$search_val = "0";
				}
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('gender', $search_val)
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "Tribe"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('tribe', $search_val)
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "Religion"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('religion', $search_val)
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "Nationality"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('nationality', $search_val)
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "State"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('state_origin', 'like', '%'.$search_val.'%')
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "LGA"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('lga', 'like', '%'.$search_val.'%')
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "Town"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('town', 'like', '%'.$search_val.'%')
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "Phone"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('guard_phone', 'like', '%'.$search_val.'%')
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "First Name"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('first_name', 'like', '%'.$search_val.'%')
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			if( $search_param == "Last Name"){
				$records =  Registration::where('reg_date', '>=', $from_date)
					->where('reg_date', '<=', $to_date)
					->where('last_name', 'like', '%'.$search_val.'%')
					->select('student_id','reg_no','reg_date',
						'first_name', 'last_name', 'other_name', 'photo', 'gender',
						'town', 'lga', 'state_origin', 'nationality', 'religion', 'tribe', 'guard_office', 'guard_home', 
						'guard_email', 'guard_phone')
					->orderBy('reg_date', 'DESC')
					->get();
			}
			return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Search', 'Registration', 'Registration');
		}
	}
	public function queryScholarship(Request $request){
		try{
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			$operator = trim($request->search_operator);
			
			if( $search_param == "All"){
				//`fees_discount`(`discount_id`, `discount_date`, `semester_id`, `amount`, `narration`, `discount`, `student_id`
				$records = DB::table('fees_discount as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('student_enrol as c', 'c.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'c.class_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'a.discount', 'e.semester')
					->where('a.discount_date', '>=', $from_date)
					->where('a.discount_date', '<=', $to_date)
					->where('c.active', '1')
					->orderBy('a.discount_date', 'DESC')
					->get();
				return $records;	
			}
			if( $search_param == "Amount"){
				$records = DB::table('fees_discount as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('student_enrol as c', 'c.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'c.class_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'a.discount', 'e.semester')
					->where('a.discount_date', '>=', $from_date)
					->where('a.discount_date', '<=', $to_date)
					->where('c.active', '1')
					->where('a.amount', $operator, $search_val)
					->orderBy('a.discount_date', 'DESC')
					->get();
				
				return $records;	
			}
			
			if( $search_param == "Section"){
				$records = DB::table('fees_discount as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('student_enrol as c', 'c.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'c.class_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'a.discount', 'e.semester')
					->where('a.discount_date', '>=', $from_date)
					->where('a.discount_date', '<=', $to_date)
					->where('c.active', '1')
					->where('d.class_div', 'like', '%'.$search_val.'%')
					->orderBy('a.discount_date', 'DESC')
					->get();
				
				return $records;
			}
			if( $search_param == "Class"){
				/////now search
				$records = DB::table('fees_discount as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('student_enrol as c', 'c.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'c.class_id')
					->join('sch_classes as f','f.class_id', '=', 'd.class_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'a.discount', 'e.semester')
					->where('a.discount_date', '>=', $from_date)
					->where('a.discount_date', '<=', $to_date)
					->where('c.active', '1')
					->where('f.class_name', 'like', '%'.$search_val.'%')
					->orderBy('a.discount_date', 'DESC')
					->get();
					
				return $records;
			}
			if( $search_param == "Term"){
				/////now search
				$records = DB::table('fees_discount as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('student_enrol as c', 'c.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'c.class_id')
					->join('sch_classes as f','f.class_id', '=', 'd.class_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'a.discount', 'e.semester')
					->where('a.discount_date', '>=', $from_date)
					->where('a.discount_date', '<=', $to_date)
					->where('c.active', '1')
					->where('e.semester', 'like', '%'.$search_val.'%')
					->orderBy('a.discount_date', 'DESC')
					->get();
				
				return $records;
			}
			if( $search_param == "Student"){
				/////now search
				$records = DB::table('fees_discount as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('student_enrol as c', 'c.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'c.class_id')
					->join('sch_classes as f','f.class_id', '=', 'd.class_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'a.discount', 'e.semester')
					->where('a.discount_date', '>=', $from_date)
					->where('a.discount_date', '<=', $to_date)
					->where('c.active', '1')
					->where('b.last_name', 'like', '%'.$search_val.'%')
					->orderBy('a.discount_date', 'DESC')
					->get();
				
				return $records;
			}
		} catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Fees', 'Fees');
		}
	}
	public function queryFees(Request $request){
		try{
			
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			
			//file_put_contents('file_error.txt', $from_date. PHP_EOL, FILE_APPEND);
			
			if( $search_param == "All"){
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','c.fee_id', '=', 'a.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;	
			}
			if( $search_param == "Amount"){
				
				/////now search
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('a.amount', $operator, $search_val)
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;	
			}
			if( $search_param == "Channel"){
				/////now search
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('f.channel', 'like', '%'.$search_val.'%')
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Bank"){
				/////now search
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('g.bank_name', 'like', '%'.$search_val.'%')
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Section"){
				/////now search
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->join('sch_classes as h','h.class_id', '=', 'a.class_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('d.class_div', 'like', '%'.$search_val.'%')
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Class"){
				/////now search
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->join('sch_classes as h','h.class_id', '=', 'a.class_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('c.class_name', 'like', '%'.$search_val.'%')
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Code"){
				/////now search
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('c.fee_name', 'like', '%'.$search_val.'%')
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Term"){
				/////now search
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('e.semester', 'like', '%'.$search_val.'%')
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Student"){
				/////now search
				$records = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('b.last_name', 'like', '%'.$search_val.'%')
					->orderBy('a.payment_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Balances"){
				//get the balances for all the accounts: Account||Month Total IN|| Month Total OUT|| 
				//Year Total IN|| Year Total OUT||Year Opening|| Month Opening|| Balance
				/*
				m Numeric representation of a month, with leading zeros 01 through 12
				n Numeric representation of a month, without leading zeros 1 through 12
				F Alphabetic representation of a month January through December
				*/
				//get year: $to_date
				//get month: $to_date
				$yr = date("Y", strtotime($from_date)); 
				$mon = date("m", strtotime($from_date)); 
				$date = date("d", strtotime($from_date));
				
				$start_yr = $yr.'-01'.'-01';
				$start_mnt = $yr.'-'.$mon.'-01';
				//iterate through the banks in the table
				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Fees</th>';
				$content .='<th>Year Opening</th>';
				$content .='<th>Year Movement</th>';
				$content .='<th>Month Opening</th>';
				$content .='<th>Month Movement</th>';
				$content .='<th>Day Opening</th>';
				$content .='<th>Day Movement</th>';
				$content .='<th>Closing Balance</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				$fees = Fees::orderBy('fee_name', 'ASC')->get();
				foreach ($fees as $fee){
					$fee_id = $fee->fee_id;
					$fee_name = $fee->fee_name;
					$yr_op = DB::table('fees_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.payment_date', '<', $start_yr)
						->where('a.fee_id', $fee_id)
						->value('balance');
					
					//where(DB::raw("(DATE_FORMAT(enrol_date,'%Y'))"),date('Y'))
					$yr_mvmnt = DB::table('fees_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.payment_date', '>=', $start_yr)
						->where('a.payment_date', '<=', $from_date)
						->where('a.fee_id', $fee_id)
						->value('balance');
					
					/////////////////////MONTH
					$mo_op = DB::table('fees_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.payment_date', '<', $start_mnt)
						->where('a.fee_id', $fee_id)
						->value('balance');
					
					$mo_mvmnt = DB::table('fees_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.payment_date', '>=', $start_mnt)
						->where('a.payment_date', '<=', $from_date)
						->where('a.fee_id', $fee_id)
						->value('balance');
												
					/////////////////////DAY
					$dy_op = DB::table('fees_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.payment_date', '<', $from_date)
						->where('a.fee_id', $fee_id)
						->value('balance');
					
					$dy_mvmnt = DB::table('fees_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.payment_date', '=', $from_date)
						->where('a.fee_id', $fee_id)
						->value('balance');
					///////////////////////////////////current balance
					$current = DB::table('fees_payment as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.payment_date', '<=', $from_date)
						->where('a.fee_id', $fee_id)
						->value('balance');
						
					$content .='<tr>';
					$content .='<td>'.$fee_name.'</td>';
					$content .='<td align="right">'.number_format($yr_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($yr_mvmnt, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($mo_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($mo_mvmnt, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($dy_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($dy_mvmnt, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($current, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			if( $search_param == "Activities"){
				$fee_name = $search_val;
				$fee_id = DB::table('fees as a')
							->where('a.fee_name', $search_val)
							->value('a.fee_id');
							
				//iterate through the banks in the table
				$serial = 1;
				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Serial</th>';
				$content .='<th>Fees</th>';
				$content .='<th>Transaction Date</th>';
				$content .='<th>Post Date</th>';
				$content .='<th>Reg No</th>';
				$content .='<th>Student</th>';
				$content .='<th>Class</th>';
				$content .='<th>Term</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Bank</th>';
				//$content .='<th>Channel</th>';
				$content .='<th>Movement</th>';
				$content .='<th>Balance</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				//get the opening balance
				$op_bal = DB::table('fees_payment as a')
					->select(DB::raw('SUM(a.amount) AS balance'))
					->where('a.payment_date', '<', $from_date)
					->where('a.fee_id', $fee_id)
					->value('balance');
				
				$content .='<tr>';
				$content .='<td>'.$serial.'</td>';
				$content .='<td>'.$fee_name.'</td>';
				$content .='<td>'.date("d/m/Y", strtotime($from_date)).'</td>';
				$content .='<td>'.date("d/m/Y", strtotime($from_date)).'</td>';
				$content .='<td></td>';
				$content .='<td></td>';
				$content .='<td></td>';
				$content .='<td></td>';
				$content .='<td>Opening balance</td>';
				$content .='<td></td>';
				//$content .='<td></td>';
				$content .='<td></td>';
				$content .='<td align="right">'.number_format($op_bal, 2, '.', ',').'</td>';
				$content .='</tr>';
				//start iterating through the table for date range where there are transactions
				$items = DB::table('fees_payment as a')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('a.fee_id', $fee_id)
					->orderBy('a.payment_date', 'ASC')
					->get();
					
				foreach ($items as $item){
					//get student name
					$student_id =  $item->student_id;
					$reg_no = DB::table('students')->where('student_id', $student_id)->value('reg_no');
					$first_name = DB::table('students')->where('student_id', $student_id)->value('first_name');
					$last_name = DB::table('students')->where('student_id', $student_id)->value('last_name');
					$full_name = $last_name.', '.$first_name;
					//get class
					$class_div_id =  $item->class_div_id;
					$class_name = DB::table('class_div')->where('class_div_id', $class_div_id)->value('class_div');
					//get term
					$term = DB::table('semester')->where('semester_id', $item->semester_id)->value('semester');
					//get bank
					$bank = DB::table('banks')->where('bank_id', $item->bank_payment_id)->value('bank_name');
					//get channel
					//$channel = $item->channel;
					
					$op_bal = $op_bal + $item->amount;
					$serial = $serial + 1;
					
					$content .='<tr>';
					$content .='<td>'.$serial.'</td>';
					$content .='<td>'.$fee_name.'</td>';
					$content .='<td>'.date("d/m/Y", strtotime($item->payment_date)).'</td>';
					$content .='<td>'.date("d/m/Y", strtotime($item->created_at)).'</td>';
					$content .='<td>'.$reg_no.'</td>';
					$content .='<td>'.$full_name.'</td>';
					$content .='<td>'.$class_name.'</td>';
					$content .='<td>'.$term.'</td>';
					$content .='<td>'.$item->narration.'</td>';
					$content .='<td>'.$bank.'</td>';
					//$content .='<td>'.$item->channel.'</td>';
					$content .='<td align="right">'.number_format($item->amount, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($op_bal, 2, '.', ',').'</td>';
					$content .='</tr>';
					
				}
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
		} catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Fees', 'Fees');
		}
	}
	public function queryExam(Request $request){
		try{
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			if( $search_param == "All"){
				
				$records =  DB::table('exam_score')
					->join('semester','semester.semester_id', '=', 'exam_score.semester_id')
					->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
					->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
					->join('students','students.student_id', '=', 'exam_score.student_id')
					->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
					->join('exam_class','exam_class.exam_id', '=', 'exam_score.exam_id')
					->where('exam_score.exam_date', '>=', $from_date)
					->where('exam_score.exam_date', '<=', $to_date)
					->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
						'exam_name', 'reg_no', 'first_name', 'last_name', 'score_id', 'exam_weight', 'max_score')
					->distinct('reg_no', 'exam_date', 'exam_name')
					->orderBy('exam_score.exam_date', 'DESC')
					->orderBy('exam_name', 'ASC')
					->get();
				
			}
			if( $search_param == "Exam"){
				$records =  DB::table('exam_score')
					->join('semester','semester.semester_id', '=', 'exam_score.semester_id')
					->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
					->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
					->join('students','students.student_id', '=', 'exam_score.student_id')
					->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
					->join('exam_class','exam_class.exam_id', '=', 'exam_score.exam_id')
					->where('exam_score.exam_date', '>=', $from_date)
					->where('exam_score.exam_date', '<=', $to_date)
					->where('exam_name', 'like', '%'.$search_val.'%')
					->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
						'exam_name', 'reg_no', 'first_name', 'last_name', 'score_id', 'exam_weight', 'max_score')
					->distinct('reg_no', 'exam_date', 'exam_name')
					->orderBy('exam_score.exam_date', 'DESC')
					->orderBy('exam_name', 'ASC')
					->get();
			}
			if( $search_param == "Subject"){
				$records =  DB::table('exam_score')
					->join('semester','semester.semester_id', '=', 'exam_score.semester_id')
					->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
					->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
					->join('students','students.student_id', '=', 'exam_score.student_id')
					->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
					->join('exam_class','exam_class.exam_id', '=', 'exam_score.exam_id')
					->where('exam_score.exam_date', '>=', $from_date)
					->where('exam_score.exam_date', '<=', $to_date)
					->where('subjects.subject', 'like', '%'.$search_val.'%')
					->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
						'exam_name', 'reg_no', 'first_name', 'last_name', 'score_id', 'exam_weight', 'max_score')
					->distinct('reg_no', 'exam_date', 'exam_name')
					->orderBy('exam_score.exam_date', 'DESC')
					->orderBy('exam_name', 'ASC')
					->get();
			}
			if( $search_param == "Student"){
				$records =  DB::table('exam_score')
					->join('semester','semester.semester_id', '=', 'exam_score.semester_id')
					->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
					->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
					->join('students','students.student_id', '=', 'exam_score.student_id')
					->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
					->join('exam_class','exam_class.exam_id', '=', 'exam_score.exam_id')
					->where('exam_score.exam_date', '>=', $from_date)
					->where('exam_score.exam_date', '<=', $to_date)
					->where('reg_no', 'like', '%'.$search_val.'%')
					->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
						'exam_name', 'reg_no', 'first_name', 'last_name', 'score_id', 'exam_weight', 'max_score')
					->distinct('reg_no', 'exam_date', 'exam_name')
					->orderBy('exam_score.exam_date', 'DESC')
					->orderBy('exam_name', 'ASC')
					->get();
			}
			if( $search_param == "Term"){
				$records =  DB::table('exam_score')
					->join('semester','semester.semester_id', '=', 'exam_score.semester_id')
					->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
					->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
					->join('students','students.student_id', '=', 'exam_score.student_id')
					->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
					->join('exam_class','exam_class.exam_id', '=', 'exam_score.exam_id')
					->where('exam_score.exam_date', '>=', $from_date)
					->where('exam_score.exam_date', '<=', $to_date)
					->where('semester', 'like', '%'.$search_val.'%')
					->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
						'exam_name', 'reg_no', 'first_name', 'last_name', 'score_id', 'exam_weight', 'max_score')
					->distinct('reg_no', 'exam_date', 'exam_name')
					->orderBy('exam_score.exam_date', 'DESC')
					->orderBy('exam_name', 'ASC')
					->get();
			}
			if( $search_param == "Class"){
				$records =  DB::table('exam_score')
					->join('semester','semester.semester_id', '=', 'exam_score.semester_id')
					->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
					->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
					->join('students','students.student_id', '=', 'exam_score.student_id')
					->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
					->join('exam_class','exam_class.exam_id', '=', 'exam_score.exam_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->where('exam_score.exam_date', '>=', $from_date)
					->where('exam_score.exam_date', '<=', $to_date)
					->where('class_name', 'like', '%'.$search_val.'%')
					->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
						'exam_name', 'reg_no', 'first_name', 'last_name', 'score_id', 'exam_weight', 'max_score')
					->distinct('reg_no', 'exam_date', 'exam_name')
					->orderBy('exam_score.exam_date', 'DESC')
					->orderBy('exam_name', 'ASC')
					->get();
			}
			if( $search_param == "Section"){
				$records =  DB::table('exam_score')
					->join('semester','semester.semester_id', '=', 'exam_score.semester_id')
					->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
					->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
					->join('students','students.student_id', '=', 'exam_score.student_id')
					->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
					->join('exam_class','exam_class.exam_id', '=', 'exam_score.exam_id')
					->where('exam_score.exam_date', '>=', $from_date)
					->where('exam_score.exam_date', '<=', $to_date)
					->where('class_div.class_div', 'like', '%'.$search_val.'%')
					->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
						'exam_name', 'reg_no', 'first_name', 'last_name', 'score_id', 'exam_weight', 'max_score')
					->distinct('reg_no', 'exam_date', 'exam_name')
					->orderBy('exam_score.exam_date', 'DESC')
					->orderBy('exam_name', 'ASC')
					->get();
			}
			//return view('search.infoexam_exam')->with('records', $records);
			return view('search.infoexam_exam', compact('records'));
			//return view('search.infoexam_exam', ['records' => $records]);
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Fees', 'Exam', 'Exam');
		}
	}
	public function queryAttend(Request $request){
		try{
			$records = array();
			$from_date = $request->start_date;
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			
			if( $search_param == "All"){
				$records = DB::table('student_attendance as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_id')
					->join('sch_classes as h','h.class_id', '=', 'd.class_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.remarks', 'a.attendance_date', 'd.class_div',
						'a.arrival_time')
					->where('a.attendance_date', $from_date)
					->orderBy('d.class_div', 'DESC')
					->orderBy('b.reg_no', 'DESC')
					->get();
			}
			if( $search_param == "Class"){
				$records = DB::table('student_attendance as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_id')
					->join('sch_classes as h','h.class_id', '=', 'd.class_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.remarks', 'a.attendance_date', 'd.class_div',
						'a.arrival_time')
					->where('a.attendance_date', $from_date)
					->where('h.class_name', 'like', '%'.$search_val.'%')
					->orderBy('d.class_div', 'DESC')
					->orderBy('b.reg_no', 'DESC')
					->get();
			}
			if( $search_param == "Section"){
				$records = DB::table('student_attendance as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_id')
					->join('sch_classes as h','h.class_id', '=', 'd.class_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.remarks', 'a.attendance_date', 'd.class_div',
						'a.arrival_time')
					->where('a.attendance_date', $from_date)
					->where('d.class_div', 'like', '%'.$search_val.'%')
					->orderBy('d.class_div', 'DESC')
					->orderBy('b.reg_no', 'DESC')
					->get();
			}
			return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Search', 'Attend', 'Attend');
		}
	}
	public function queryExpense(Request $request){
		//try{
			$records = array();
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			$operator = trim($request->search_operator);
			
			$from_date = $request->start_date;
			$to_date = "";
			if( $search_param !== "Balances") $to_date = $request->end_date;
			
			if( $search_param == "All"){
				/////now search
				$records = DB::table('txn_exp as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_id')
					->join('expenses as c','c.expense_id', '=', 'a.expense_id')
					->select('a.txn_id','b.bank_name', 'a.beneficiary', 'a.txn_date', 'a.pay_channel', 'a.bank_ref',
						'a.qty', 'a.price', 'a.amount', 'a.narration', 'a.voucher_no', 'c.expense_name', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->orderBy('a.txn_date', 'DESC')
					->get();
				return $records;
			}
			if( $search_param == "Amount"){
				
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
				return $records;
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
				return $records;
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
				return $records;
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
				return $records;
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
				return $records;
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
				return $records;
			}
			if( $search_param == "Balances"){
				//get the balances for all the accounts: Account||Month Total IN|| Month Total OUT|| 
				//Year Total IN|| Year Total OUT||Year Opening|| Month Opening|| Balance
				/*
				m Numeric representation of a month, with leading zeros 01 through 12
				n Numeric representation of a month, without leading zeros 1 through 12
				F Alphabetic representation of a month January through December
				*/
				//get year: $to_date
				//get month: $to_date
				$yr = date("Y", strtotime($from_date)); 
				$mon = date("m", strtotime($from_date)); 
				$date = date("d", strtotime($from_date));
				
				$start_yr = $yr.'-01'.'-01';
				$start_mnt = $yr.'-'.$mon.'-01';
				//iterate through the banks in the table
				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Expense</th>';
				$content .='<th>Year Opening</th>';
				$content .='<th>Year Movement</th>';
				$content .='<th>Month Opening</th>';
				$content .='<th>Month Movement</th>';
				$content .='<th>Day Opening</th>';
				$content .='<th>Day Movement</th>';
				$content .='<th>Closing Balance</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				$expenses = DB::table('expenses')->get();
				foreach ($expenses as $expense){
					$expense_id = $expense->expense_id;
					$expense_name = $expense->expense_name;
					$yr_op = DB::table('txn_exp as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $start_yr)
						->where('a.expense_id', $expense_id)
						->value('balance');
					
					$yr_mvmnt =  DB::table('txn_exp as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '>=', $start_yr)
						->where('a.txn_date', '<=', $from_date)
						->where('a.expense_id', $expense_id)
						->value('balance');
					
					/////////////////////MONTH
					$mo_op = DB::table('txn_exp as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $start_mnt)
						->where('a.expense_id', $expense_id)
						->value('balance');
					
					$mo_mvmnt = DB::table('txn_exp as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '>=', $start_mnt)
						->where('a.txn_date', '<=', $from_date)
						->where('a.expense_id', $expense_id)
						->value('balance');
											
					/////////////////////DAY
					$dy_op = DB::table('txn_exp as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<', $from_date)
						->where('a.expense_id', $expense_id)
						->value('balance');
					
					$dy_mvmnt = DB::table('txn_exp as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '=', $from_date)
						->where('a.expense_id', $expense_id)
						->value('balance');
					
					///////////////////////////////////current balance
					$current = DB::table('txn_exp as a')
						->select(DB::raw('SUM(a.amount) AS balance'))
						->where('a.txn_date', '<=', $from_date)
						->where('a.expense_id', $expense_id)
						->value('balance');
					
					$content .='<tr>';
					$content .='<td>'.$expense_name.'</td>';
					$content .='<td align="right">'.number_format($yr_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($yr_mvmnt, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($mo_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($mo_mvmnt, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($dy_op, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($dy_mvmnt, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($current, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
			if( $search_param == "Activities"){
				//expense
				$expense_name = trim($search_val);
				$expense_id = DB::table('expenses as a')
							->where('a.expense_name', $search_val)
							->value('a.expense_id');
				$serial = 0;
				//iterate through the banks in the table
				$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
				$content .='<tr>';
				$content .='<th>Serial</th>';
				$content .='<th>Expense</th>';
				$content .='<th>Transaction Date</th>';
				$content .='<th>Post Date</th>';
				$content .='<th>Voucher</th>';
				$content .='<th>Beneficiary</th>';
				$content .='<th>Narration</th>';
				$content .='<th>Bank</th>';
				$content .='<th>Channel</th>';
				$content .='<th>Movement</th>';
				$content .='<th>Balance</th>';
				$content .='</tr>';
				$content .='</thead>';
				$content .='<tbody>';
				//get the opening balance
				$op_bal = DB::table('txn_exp as a')
					->select(DB::raw('SUM(a.amount) AS balance'))
					->where('a.txn_date', '<', $from_date)
					->where('a.expense_id', $expense_id)
					->value('balance');
				$serial = $serial + 1;
				
				$content .='<tr>';
				$content .='<td>'.$serial.'</td>';
				$content .='<td>'.$expense_name.'</td>';
				$content .='<td>'.date("d/m/Y", strtotime($from_date)).'</td>';
				$content .='<td>'.date("d/m/Y", strtotime($from_date)).'</td>';
				$content .='<td></td>';
				$content .='<td></td>';
				$content .='<td>Opening balance</td>';
				$content .='<td></td>';
				$content .='<td></td>';
				$content .='<td></td>';
				$content .='<td align="right">'.number_format($op_bal, 2, '.', ',').'</td>';
				$content .='</tr>';
				//start iterating through the table for date range where there are transactions
				$items = DB::table('txn_exp as a')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->where('a.expense_id', $expense_id)
					->orderBy('a.txn_date', 'ASC')
					->get();
					
				foreach ($items as $item){
					$bank_id =  $item->bank_id;
					$bank_name = DB::table('banks')->where('bank_id', $bank_id)->value('bank_name');
					$op_bal = $op_bal + $item->amount;
					
					$serial = $serial + 1;
				
					$content .='<tr>';
					$content .='<td>'.$serial.'</td>';
					$content .='<td>'.$expense_name.'</td>';
					$content .='<td>'.date("d/m/Y", strtotime($item->txn_date)).'</td>';
					$content .='<td>'.date("d/m/Y", strtotime($item->created_at)).'</td>';
					$content .='<td>'.$item->voucher_no.'</td>';
					$content .='<td>'.$item->beneficiary.'</td>';
					$content .='<td>'.$item->narration.'</td>';
					$content .='<td>'.$bank_name.'</td>';
					$content .='<td>'.$item->pay_channel.'</td>';
					$content .='<td align="right">'.number_format($item->amount, 2, '.', ',').'</td>';
					$content .='<td align="right">'.number_format($op_bal, 2, '.', ',').'</td>';
					$content .='</tr>';
				}
				$content .='</tbody>';
					$content .='</table>';
				return $content;
			}
		//} catch (\Exception $e) {$this->report_error($e, 'Search', 'Expense', 'Expense');}
	}
	public function queryFT(Request $request){
		try{
			$records = array();
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = trim($request->search_param);
			$search_val = trim($request->search_val);
			$operator = trim($request->search_operator);
			
			if( $search_param == "All"){
				/////now search
				$records = DB::table('txn_ft as a')
					->join('banks as b', 'b.bank_id', '=', 'a.bank_from')
					->join('banks as c','c.bank_id', '=', 'a.bank_to')
					->select('a.txn_id','b.bank_name as bank_from', 'c.bank_name as bank_to', 'a.amount',
						'a.txn_date', 'a.pay_channel', 'a.bank_ref','a.narration', 'a.bank_payment_id')
					->where('a.txn_date', '>=', $from_date)
					->where('a.txn_date', '<=', $to_date)
					->orderBy('a.txn_date', 'DESC')
					->get();
			}
			if( $search_param == "Amount"){
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
			return $records;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Transaction', 'Funds Transfer', 'Search');
		}
	}
	public function searchStudent(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			$search_param = trim($request->search_param);
			//$search_val = trim($request->search_val);
			$reg_no = $request->reg_no;
			$student_id = StudentController::getStudentID($reg_no);
			$exit_date = StudentExit::where('student_id', $student_id)->value('exit_date');
			$content = '';
			if( $search_param == "Details"){
				/////now search
				$item = Registration::where('student_id', $student_id)->first();
				try{
					//create the table header
					$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
					$content .='<tr>';
					$content .='<th>Data</th>';
					$content .='<th>Value</th>';
					$content .='</tr>';
					$content .='</thead>';
					$content .='<tbody>';
					if( count($item) > 0 ){
						$enrol_date = trim($item->enrol_date);
						
						$content .='<tr>';
						$content .='<td>Registration Date</td>';
						$content .='<td>'.$item->reg_date.'</td>';
						$content .='</tr>';
						if( !empty($enrol_date) && $enrol_date !== 'null' ){
							$content .='<tr>';
							$content .='<td>Enrolment Date</td>';
							$content .='<td>'.$enrol_date.'</td>';
							$content .='</tr>';
						}
						if( !empty($exit_date) && $exit_date !== 'null' ){
							$content .='<tr>';
							$content .='<td>Exit Date</td>';
							$content .='<td>'.date("d/m/Y", strtotime($exit_date)).'</td>';
							$content .='</tr>';
						}
						
						$content .='<tr>';
						$content .='<td>Date of Birth</td>';
						$content .='<td>'.$item->dob.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Gender</td>';
						$content .='<td>'.$item->gender.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Home Town</td>';
						$content .='<td>'.$item->town.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>LGA</td>';
						$content .='<td>'.$item->lga.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>State of Origin</td>';
						$content .='<td>'.$item->state_origin.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Nationality</td>';
						$content .='<td>'.$item->nationality.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Religion</td>';
						$content .='<td>'.$item->religion.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Guardian</td>';
						$content .='<td>'.$item->guardian.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Relationship</td>';
						$content .='<td>'.$item->relationship.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Home Address</td>';
						$content .='<td>'.$item->guard_home.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Office Address</td>';
						$content .='<td>'.$item->guard_office.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Email Address</td>';
						$content .='<td>'.$item->guard_email.'</td>';
						$content .='</tr>';
						$content .='<tr>';
						$content .='<td>Pnone Contact</td>';
						$content .='<td>'.$item->guard_phone.'</td>';
						$content .='</tr>';
					}
					$content .='</tbody>';
					$content .='</table>';
					
					return $content;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Registration', 'Get');
				}
			}
			if( $search_param == "Enrolment"){
				//get the various classes for the student within the specified period
				$items =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
					->join('students','students.student_id', '=', 'student_enrol.student_id')
					->where('student_enrol.student_id', $student_id)
					->where('student_enrol.enrol_date', '>=', $from_date)
					->where('student_enrol.enrol_date', '<=', $to_date)
					->select('student_enrol.enrol_date AS enrol_date', 'class_div AS section')
					->orderBy('student_enrol.enrol_date', 'DESC')
					->get();
				try
				{
					//create the table header
					$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
					$content .='<tr>';
					$content .='<th>Date</th>';
					$content .='<th>Class</th>';
					$content .='</tr>';
					$content .='</thead>';
					$content .='<tbody>';
					foreach ($items as $item)
					{
						$content .='<tr>';
						$content .='<td>'.date("d/m/Y", strtotime($item->enrol_date)).'</td>';
						$content .='<td>'.$item->section.'</td>';
						$content .='</tr>';
					}
					$content .='</tbody>';
					$content .='</table>';
					return $content;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Registration', 'Get');
				}
			}
			if( $search_param == "Exam"){
				$items =  ExamScore::join('semester','semester.semester_id', '=', 'exam_score.semester_id')
						->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
						->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
						->join('students','students.student_id', '=', 'exam_score.student_id')
						->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
						->join('exam_class','exam_class.exam_id', '=', 'exam_score.exam_id')
						->where('exam_score.student_id', '=', $student_id)
						->where('exam_date', '>=', $from_date)
						->where('exam_date', '<=', $to_date)
						->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
							'exam_name', 'reg_no', 'first_name', 'last_name','exam_weight', 'max_score')
						->distinct('reg_no', 'exam_date', 'exam_name')
						->orderBy('exam_score.exam_date', 'DESC')
						->orderBy('exam_name', 'ASC')
						->get();
				try
				{
					//create the table header
					$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
					$content .='<tr>';
					$content .='<th>Date</th>';
					$content .='<th>Class</th>';
					$content .='<th>Subject</th>';
					$content .='<th>Exam</th>';
					$content .='<th>Score</th>';
					$content .='<th>Maximum Score</th>';
					$content .='<th>Exam Weight</th>';
					$content .='<th>Term</th>';
					$content .='</tr>';
					$content .='</thead>';
					$content .='<tbody>';
					foreach ($items as $item)
					{
						$content .='<tr>';
						$content .='<td>'.date("d/m/Y", strtotime($item->exam_date)).'</td>';
						$content .='<td>'.$item->class_div.'</td>';
						$content .='<td>'.$item->subject.'</td>';
						$content .='<td>'.$item->exam_name.'</td>';
						$content .='<td>'.$item->exam_score.'</td>';
						$content .='<td>'.$item->max_score.'</td>';
						$content .='<td>'.$item->exam_weight.'</td>';
						$content .='<td>'.$item->semester.'</td>';
						$content .='</tr>';
					}
					$content .='</tbody>';
					$content .='</table>';
					return $content;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Registration', 'Get');
				}
			}
			if( $search_param == "Fees"){
				//fees paid by the student during the indicated period 
				$items = DB::table('fees_payment as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('fees as c','a.fee_id', '=', 'c.fee_id')
					->join('class_div as d','d.class_div_id', '=', 'a.class_div_id')
					->join('semester as e','e.semester_id', '=', 'a.semester_id')
					->join('bank_payment as f','f.payment_id', '=', 'a.bank_payment_id')
					->join('banks as g','g.bank_id', '=', 'f.bank_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'c.fee_name', 'a.payment_date', 'd.class_div',
						'a.amount','a.narration','e.semester', 'f.channel', 'f.reference','g.bank_name', 'a.bank_payment_id')
					->where('a.payment_date', '>=', $from_date)
					->where('a.payment_date', '<=', $to_date)
					->where('a.student_id', '=', $student_id)
					->orderBy('a.payment_date', 'DESC')
					->get();
				try
				{
					//create the table header
					$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
					$content .='<tr>';
					$content .='<th>Date</th>';
					$content .='<th>Reg No</th>';
					$content .='<th>Name</th>';
					$content .='<th>Class</th>';
					$content .='<th>Term</th>';
					$content .='<th>Fees</th>';
					$content .='<th>Amount</th>';
					$content .='<th>Narration</th>';
					$content .='<th>Channel</th>';
					$content .='<th>Reference</th>';
					$content .='<th>Bank</th>';
					$content .='</tr>';
					$content .='</thead>';
					$content .='<tbody>';
					foreach ($items as $item)
					{
						$content .='<tr>';
						$content .='<td>'.date("d/m/Y", strtotime($item->payment_date)).'</td>';
						$content .='<td>'.$item->reg_no.'</td>';
						$content .='<td>'.$item->last_name.', '.$item->first_name.'</td>';
						$content .='<td>'.$item->class_div.'</td>';
						$content .='<td>'.$item->semester.'</td>';
						$content .='<td>'.$item->fee_name.'</td>';
						$content .='<td align="right">'.number_format($item->amount, 2, '.', ',').'</td>';
						$content .='<td>'.$item->narration.'</td>';
						$content .='<td>'.$item->channel.'</td>';
						$content .='<td>'.$item->reference.'</td>';
						$content .='<td>'.$item->bank_name.'</td>';
						$content .='</tr>';
					}
					$content .='</tbody>';
					$content .='</table>';
					return $content;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Fees', 'Get');
				}
			}
			if( $search_param == "Scholarship"){
				$items = DB::table('fees_discount as a')
					->join('students as b', 'b.student_id', '=', 'a.student_id')
					->join('semester as c','c.semester_id', '=', 'a.semester_id')
					->select('b.reg_no', 'b.last_name', 'b.first_name', 'a.discount', 'a.discount_date', 'a.student_id',
						'a.amount','a.narration','c.semester')
					->where('a.discount_date', '>=', $from_date)
					->where('a.discount_date', '<=', $to_date)
					->where('a.student_id', '=', $student_id)
					->orderBy('a.discount_date', 'DESC')
					->get();
				try
				{
					//create the table header
					$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
					$content .='<tr>';
					$content .='<th>Date</th>';
					$content .='<th>Reg No</th>';
					$content .='<th>Name</th>';
					$content .='<th>Term</th>';
					$content .='<th>Amount</th>';
					$content .='<th>Narration</th>';
					$content .='</tr>';
					$content .='</thead>';
					$content .='<tbody>';
					foreach ($items as $item)
					{
						$content .='<tr>';
						$content .='<td>'.date("d/m/Y", strtotime($item->discount_date)).'</td>';
						$content .='<td>'.$item->reg_no.'</td>';
						$content .='<td>'.$item->last_name.', '.$item->first_name.'</td>';
						$content .='<td>'.$item->semester.'</td>';
						$content .='<td align="right">'.number_format($item->discount, 2, '.', ',').'</td>';
						$content .='<td>'.$item->narration.'</td>';
						$content .='</tr>';
					}
					$content .='</tbody>';
					$content .='</table>';
					return $content;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Scholarship', 'Get');
				}
			}
			if( $search_param == "Attendance"){
				$days = DB::table('student_attendance')
						->where('student_id', $student_id)
						->where('attendance_date', '>=', $from_date)
						->where('attendance_date', '<=', $to_date)
						->distinct()
						->count(['attendance_date']);
						
				$present = DB::table('student_attendance')
						->where('student_id', $student_id)
						->where('attendance_date', '>=', $from_date)
						->where('attendance_date', '<=', $to_date)
						->where('remarks', "Present")
						->distinct()
						->count(['attendance_date']);
				try
				{
					$absent = $days - $present;
					//create the table header
					$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
					$content .='<th>No of Days</th>';
					$content .='<th>Present</th>';
					$content .='<th>Absent</th>';
					$content .='</thead>';
					$content .='<tbody>';
					$content .='<tr>';
					$content .='<td>'.$days.'</td>';
					$content .='<td>'.$present.'</td>';
					$content .='<td>'.$absent.'</td>';
					$content .='</tr>';
					$content .='</tbody>';
					$content .='</table>';
					return $content;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Registration', 'Get');
				}
			}
			if( $search_param == "Discipline"){
				$items = DB::table('student_discipline as a')
						->join('class_div as c','a.class_id', '=', 'c.class_div_id')
						->select('a.infraction', 'a.remarks AS reasons',
							'a.discipline_date', 'a.sanction', 'c.class_div')
						->where('a.student_id', $student_id)
						->where('a.discipline_date', '>=', $from_date)
						->where('a.discipline_date', '<=', $to_date)
						->orderBy('a.discipline_date', 'DESC')
						->get();
				try
				{
					//create the table header
					$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
					$content .='<tr>';
					$content .='<th>Date</th>';
					$content .='<th>Class</th>';
					$content .='<th>Infraction</th>';
					$content .='<th>Reason</th>';
					$content .='<th>Sanction</th>';
					$content .='</tr>';
					$content .='</thead>';
					$content .='<tbody>';
					foreach ($items as $item)
					{
						$content .='<tr>';
						$content .='<td>'.date('d/m/Y', strtotime($item->discipline_date)).'</td>';
						$content .='<td>'.$item->class_div.'</td>';
						$content .='<td>'.$item->infraction.'</td>';
						$content .='<td>'.$item->reasons.'</td>';
						$content .='<td>'.$item->sanction.'</td>';
						$content .='</tr>';
					}
					$content .='</tbody>';
					$content .='</table>';
					return $content;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Registration', 'Get');
				}
			}
			
			if( $search_param == "Awards"){
				$items = DB::table('student_achievement as a')
						->join('class_div as c','a.class_id', '=', 'c.class_div_id')
						->select('a.achievement', 'a.remarks AS reasons',
							'a.achievement_date', 'a.award', 'c.class_div')
						->where('a.student_id', $student_id)
						->where('a.achievement_date', '>=', $from_date)
						->where('a.achievement_date', '<=', $to_date)
						->orderBy('a.achievement_date', 'DESC')
						->get();
				try
				{
					//create the table header
					$content ='<table class="table table-hover table-striped table-condensed" id="table-class-info">';
					$content .='<thead>';
					$content .='<tr>';
					$content .='<th>Date</th>';
					$content .='<th>Class</th>';
					$content .='<th>Achievement</th>';
					$content .='<th>Reason</th>';
					$content .='<th>Award</th>';
					$content .='</tr>';
					$content .='</thead>';
					$content .='<tbody>';
					foreach ($items as $item)
					{
						$content .='<tr>';
						$content .='<td>'.date("d/m/Y", strtotime($item->discipline_date)).'</td>';
						$content .='<td>'.$item->class_div.'</td>';
						$content .='<td>'.$item->achievement.'</td>';
						$content .='<td>'.$item->reasons.'</td>';
						$content .='<td>'.$item->award.'</td>';
						$content .='</tr>';
					}
					$content .='</tbody>';
					$content .='</table>';
					return $content;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Registration', 'Get');
				}
			}
			return $content;
		} catch (\Exception $e) {
			$this->report_error($e, 'Search', 'Student', 'Search');
		}
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