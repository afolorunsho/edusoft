<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\models\ExamName;
use App\models\ExamClass;
use App\models\SchClass; //sch_classes
use App\models\ClassDiv;
use App\models\ScoreDiv;
use App\models\ScoreGrade;
use App\models\ExamDivision;
use App\models\ExamGrade;
use App\models\Semester;
use App\models\Subject;
use App\models\ExamScore;
use App\models\StudentTotalScore;
use App\models\StudentExamScore;
use App\models\ExamPromotion; //to record promotion parameters
use App\models\StudentExamPromotion; //to record ormotion distribution
use App\models\SemesterPromotion; //to record student promotion
use App\models\StudentEnrol; //to keep track of student classes
use App\models\StudentPromotion; //this keeps records of all promotion
use App\models\TeachersComment; //this is to vcapture teachers semester comments
use App\Http\Controllers\extend\PDFA5;

use App\models\Syllabus;
use Log; //the default Log file
use DB; //use the default Database
use Excel;
use Auth;

class ExamsController extends Controller
{
    protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function showExamName(){
		return view('exam.exam_name');	
	}
	public function infoExamName(Request $request){
		
		$records =  ExamName::orderBy('exam_name', 'ASC')->get();
		return view('exam.infoexamname', compact('records'));
	}
	public function updateExamName(Request $request){
		//if institute id is blank, then it is a new record, else edit
		if($request->ajax()){
			$logRec = array();
			try{
				$exam_id = $request->exam_id;
				
				if($exam_id === NULL || $exam_id ==""){
					$logRec = ExamName::create($request->all());
				}else{
					$logRec = ExamName::updateOrCreate(['exam_id'=>$request->exam_id], $request->all());
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Exams', 'Name', 'Update');
			}
		}
	}
	
	public function editExamName(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = ExamName::where('exam_id', '=', $request->exam_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Exams', 'Name', 'Edit');
			}
		}	
	}
	public static function getExamID($exam){
		$exam_id = ExamName::where('exam_name', '=', $exam)->value('exam_id');
		return $exam_id;
	}
	public function delExamName(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('exam_name')->where('exam_id',$request->exam_id)->value('operator');
			$post_code = DB::table('exam_name')->where('exam_id',$request->exam_id)->value('exam_id');
			$post_des = DB::table('exam_name')->where('exam_id',$request->exam_id)->value('exam_name');
				
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('exam_name')->where('exam_id',$request->exam_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Exam', 'Name', 'Delete', $post_code, $post_des, '-');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Exams', 'Name', 'Delete');
		}
	}
	//////////////PDF generate
	
	public function pdfExamName(){
		
		try{ //A4: 210 × 297 millimeters
			$pdf_file = 'exam_name.pdf';
			$this->pdf->AddPage();
			//////////////////////////////////////////////////////////////END OF HEADING
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'EXAMS', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records =  ExamName::orderBy('exam_name', 'ASC')->get();
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(20, 5, 'ID', 1, 0, 'L');
			$this->pdf->Cell(75, 5, 'Exam Name', 1, 0, 'L');
			$this->pdf->Cell(75, 5, 'Full Name', 1, 0, 'L');
			$this->pdf->Ln(5);
				
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(20, 5, $record->exam_id, 1, 0, 'L');
				$this->pdf->Cell(75, 5, $record->exam_name, 1, 0, 'L');
				$this->pdf->Cell(75, 5, $record->short_name, 1, 0, 'L');
				$this->pdf->Ln(5);
			}
			//end of the table
			//$contents = File::get(storage_path().DIRECTORY_SEPARATOR.'public_folder.txt');
			//$this->public_folder = $contents;
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Exams', 'Name', 'Pdf');
		}
	}
	public function excelExamName(){
		$records =  ExamName::select('exam_id','exam_name','short_name', 'operator', 'reviewer', 'created_at')
					->orderBy('exam_name', 'ASC')->get();
		
		//$sheet->fromArray($data);
		$csv = $records;  // stored the data in a array
		
		return Excel::create('exam_name-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('exam_id','exam_name','short_name', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
			
		})->save('xlsx', $this->public_folder.'/reports/excel', true);
	}
	////////////////////////////////////////////////////////////////////////////////////
	public function showExamClass(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		return view('exam.exam_class', compact('class'));
	}
	public function infoExamClass(Request $request){
		//what is captured in Exam Class:
		//the class_id
		//the subject_id
		//the exam_id along with the max_score and exam_weight
		$records =  ExamClass::join('exam_name','exam_name.exam_id', '=', 'exam_class.exam_id')
					->join('sch_classes','sch_classes.class_id', '=', 'exam_class.class_id')
					->orderBy('class_name', 'ASC')->get();
		return view('exam.infoexamclass', compact('records'));
	}
	public function updateExamClass(Request $request){
		//NB This is subkect to further review as exam should be per subject. Subject should be per Class section
		//hence define exam ONLY for the class subjects.
		//only one exam defintion is allowed for the class, hence delete any other possibe record in the table
		try{
			$logRec = array();
			//remove previous exam definition for this class subject
			DB::table('exam_class')
				->where('class_id',$request->class_id)
				->delete();
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Name', 'Update');}
		try{
			$c = count($request->exam_id);
			$record = "";
			
			for($i=0;$i<$c;$i++){
				$logRec = ExamClass::create(
					array(
						'class_id' => $request->class_id,
						'exam_id' => $request->exam_id[$i], 
						'exam_weight' => $request->exam_weight[$i], 
						'max_score' => $request->max_score[$i],
						'operator' => $request->operator
					)
				);
			}
			return $logRec;
		}catch (Exception $e) {$this->report_error($e, 'Exams', 'Class', 'Update');}
	}
	public function pdfExamClass(){
		
		try{ //A4: 210 × 297 millimeters
			$pdf_file = 'exam_name.pdf';
			$this->pdf->AddPage();
			//////////////////////////////////////////////////////////////END OF HEADING
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'EXAMS', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records =  ExamName::orderBy('exam_name', 'ASC')->get();
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(20, 5, 'ID', 1, 0, 'L');
			$this->pdf->Cell(50, 5, 'Exam Name', 1, 0, 'L');
			$this->pdf->Cell(100, 5, 'Full Name', 1, 0, 'L');
			$this->pdf->Ln(5);
				
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(20, 5, $record->exam_id, 1, 0, 'L');
				$this->pdf->Cell(50, 5, $record->exam_name, 1, 0, 'L');
				$this->pdf->Cell(100, 5, $record->short_name, 1, 0, 'L');
				$this->pdf->Ln(5);
			}
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Exams', 'Class', 'Pdf');
		}
	}
	public function excelExamClass(){
		
		try{
			$records =  ExamClass::join('exam_name','exam_name.exam_id', '=', 'exam_class.exam_id')
			->join('sch_classes','sch_classes.class_id', '=', 'exam_class.class_id')
			->select('class_name', 'exam_class.exam_id','exam_name', 
				'exam_weight', 'max_score', 'exam_class.operator', 'exam_class.reviewer', 'exam_class.created_at')
			->orderBy('exam_class.created_at', 'ASC')
			->get();
			$csv = $records;  // stored the data in a array
			
			return Excel::create('exam_class-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('class_name', 'exam_id','exam_name', 'exam_weight', 
					'max_score', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
				
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (\Exception $e) {$this->report_error($e, 'Exams', 'Subjects', 'Excel');}
	}
	public function listExams(Request $request){
		$records =  ExamName::orderBy('exam_name', 'ASC')->get();
		return $records;
	}
	////////////////////////////////////////////////////////////////////////////////////EXAM SCORE DIVISION
	public function showExamDiv(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		$score_div =  ScoreDiv::orderBy('score_div', 'ASC')->get();
		return view('exam.exam_div', compact('class', 'score_div'));
	}
	//this extracts the list of defined score division rating defined for use in view
	public function getScoreDiv(){
		$score_div =  ScoreDiv::orderBy('score_div', 'ASC')->get();
		return $score_div;
	}
	//this returns the score div id for use in updating class score division rating
	public function getScoreDivID($score_div_name){
		$score_div_id = ScoreDiv::where('score_div', '=', $score_div_name)->value('score_div_id');
		return $score_div_id;
	}
	public function createScoreDiv(Request $request){
		//ensure that the code is not duplicated: score_div: score_div, operator: operator
		try{
			$logRec = array();
			if($request->ajax()){
				$record = ScoreDiv::where('score_div', '=', $request->score_div)->get();
				if(count($record) == 0)							
				{
					$logRec = ScoreDiv::create(
						array(
							'score_div' => $request->score_div,
							'operator' => $request->operator
						));	
				}
				return $logRec;
			}
		} catch (Exception $e) {
			$this->report_error($e, 'Exams', 'Score-Div', 'Update');
		}
	}
	
	public function updateClassScoreDiv(Request $request){
		$logRec = array();
		//only one exam score division is allowed for a class, any existing one should be deleted
		try{
			DB::table('exam_division')->where('class_id',$request->class_id)->delete();
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Class-Score', 'Delete');}
		
		try{
			$c = count($request->score_from);
			for($i=0;$i<$c;$i++){
				
				$logRec = ExamDivision::create(
					array(
						'class_id' => $request->class_id,
						'score_from' => $request->score_from[$i], 
						'score_to' => $request->score_to[$i], 
						'score_div_id' => $request->div_id[$i],
						'remarks' => $request->remarks[$i],
						'operator' => $request->operator
					)
				);
			}
			
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Class-Div-Score', 'Update');}
		return $logRec;
	}
	//get exam records: include score descrition and class name in the return
	public function getScoreDivList(){
		try{
			$records =  ExamDivision::join('score_div','score_div.score_div_id', '=', 'exam_division.score_div_id')
				->join('sch_classes','sch_classes.class_id', '=', 'exam_division.class_id')
				->select('sch_classes.class_name','score_div.score_div', 'exam_division.score_from', 'exam_division.score_to', 'remarks')
				->orderBy('sch_classes.sequence', 'ASC')
				->orderBy('score_from', 'ASC')->get();
			return view('exam.infoexamdiv', compact('records'));
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Class-Div-Score', 'List');}
		
	}
	public function excelExamDivScore(){
		try{
			$records =  ExamDivision::join('score_div','score_div.score_div_id', '=', 'exam_division.score_div_id')
				->join('sch_classes','sch_classes.class_id', '=', 'exam_division.class_id')
				->select('class_name','score_div','score_from', 'score_to', 'remarks',
					'exam_division.operator', 'exam_division.reviewer', 'exam_division.created_at')
				->orderBy('sch_classes.sequence', 'ASC')
				->orderBy('score_from', 'ASC')->get();
			//$sheet->fromArray($data);
			$csv = $records;  // stored the data in a array
			
			return Excel::create('exam_name-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('class_name','score_div','score_from', 'score_to', 'remarks','operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
				})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Class-Div-Score', 'Excel');}
	}
	///////////////////////////////////////////////////////////////////////////////////EXAM SCORE GRADE
	public function showExamGrade(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		$score_grade =  ScoreGrade::orderBy('score_grade', 'ASC')->get();
		return view('exam.exam_grade', compact('class', 'score_grade'));
	}
	//this extracts the list of defined score division rating defined for use in view
	public function getScoreGrade(){
		$score_grade =  ScoreGrade::orderBy('score_grade', 'ASC')->get();
		return $score_grade;
	}
	//this returns the score div id for use in updating class score division rating
	public function getScoreGradeID($score_grade_name){
		$score_grade_id = ScoreGrade::where('score_grade', '=', $score_grade_name)->value('score_grade_id');
		return $score_grade_id;
	}
	
	public function createScoreGrade(Request $request){
		//ensure that the code is not duplicated: score_grade: score_grade, operator: operator
		try{
			$logRec = array();
			if($request->ajax()){
				$record = ScoreGrade::where('score_grade', '=', $request->score_grade)->get();
				if(count($record) == 0)							
				{
					$logRec = ScoreGrade::create(
						array(
							'score_grade' => $request->score_grade,
							'operator' => $request->operator
						));
				}
			}
			return $logRec;	
		} catch (Exception $e) {
			$this->report_error($e, 'Exams', 'Score-Grade', 'Update');
		}
	}
	
	public function updateClassScoreGrade(Request $request){
		//only one exam score division is allowed for a class, any existing one should be deleted
		$logRec = array();
		try{
			
			DB::table('exam_grade')->where('class_id',$request->class_id)->delete();
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Class-Score', 'Delete');}
		
		try{
			$c = count($request->score_from);
			for($i=0;$i<$c;$i++){
				$logRec = ExamGrade::create(
					array(
						'class_id' => $request->class_id,
						'score_from' => $request->score_from[$i], 
						'score_to' => $request->score_to[$i], 
						'score_grade_id' => $request->grade_id[$i],
						'remarks' => $request->remarks[$i],
						'operator' => $request->operator
					)
				);
			}
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Class-Score-Grade', 'Update');}
		return $logRec;
	}
	public function getGradeRecord(Request $request){
		$class_id = $request->class_id;
		$records =  ExamGrade::join('score_grade','score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
				->join('sch_classes','sch_classes.class_id', '=', 'exam_grade.class_id')
				->select('class_name','score_grade', 'score_grade.score_grade_id', 'score_from', 'score_to', 'remarks', 'exam_grade.class_id')
				->where('exam_grade.class_id', $class_id)
				->orderBy('score_from', 'ASC')->get();
				
		return $records;
	}
	//get exam records: include score descrition and class name in the return
	public function getScoreGradeList(){
		try{
			$records =  ExamGrade::join('score_grade','score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
				->join('sch_classes','sch_classes.class_id', '=', 'exam_grade.class_id')
				->select('sch_classes.class_name','score_grade.score_grade', 'exam_grade.score_from', 'exam_grade.score_to', 'remarks')
				->orderBy('sch_classes.sequence', 'ASC')
				->orderBy('score_from', 'ASC')->get();
			return view('exam.infoexamgrade', compact('records'));
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Class-Score-Grade', 'List');}
		
	}
	public function excelExamGradeScore(){
		try{
			$records =  ExamGrade::join('score_grade','score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
				->join('sch_classes','sch_classes.class_id', '=', 'exam_grade.class_id')
				->select('class_name','score_grade','score_from', 'score_to', 'remarks',
					'exam_grade.operator', 'exam_grade.reviewer', 'exam_grade.created_at')
				->orderBy('sch_classes.sequence', 'ASC')
				->orderBy('score_from', 'ASC')->get();
			//$sheet->fromArray($data);
			$csv = $records;  // stored the data in a array
			
			return Excel::create('exam_name-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('class_name','score_grade','score_from', 'score_to', 'remarks', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Class-Score-Grade', 'Excel');}
	}
	///////////////////////////////////////////////////////////////////////////////////EXAM SCORE
	public function showExamScore(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		$exams =  ExamName::orderBy('exam_name', 'ASC')->get();
		$semester =  Semester::orderBy('date_from', 'DESC')->get();
		$subject =  Subject::orderBy('subject', 'ASC')->get();
		return view('exam.exam_score', compact('class','exams', 'semester','subject'));
	}
	public function editStudentScore(Request $request){
		try{
			$class_div_id = $request->class_div_id;
			$subject_id = $request->subject_id;
			$exam_id = $request->exam_id;
			$semester_id = $request->semester_id;
			$exam_date = $this->toDbaseDate(trim($request->exam_date));
			
			$records =  ExamScore::join('students','students.student_id', '=', 'exam_score.student_id')
				->where('exam_score.semester_id', '=', $semester_id)
				->where('exam_score.class_div_id', '=', $class_div_id)
				->where('exam_score.subject_id', '=', $subject_id)
				->where('exam_score.exam_date', '=', $exam_date)
				->where('exam_score.exam_id', '=', $exam_id)
				->select('exam_score', 'reg_no', 'first_name', 'last_name', 'remarks', 'exam_score.student_id AS student_id')
				->orderBy('last_name', 'DESC')
				->get();
					
			return $records;
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Score', 'Edit');}
	}
	public function studentClassExam(Request $request){
		$class_div_id = $request->class_div_id;
		$exam_id = $request->exam_id;
		$exam_date = $request->exam_date;
		$class_id = $request->class_id;
		$semester_id = $request->semester_id;
		
		//file_put_contents('file_error.txt', $exam_date. PHP_EOL, FILE_APPEND);
					
		//get students in the first column(with student subject on the other columns)
		try{
			$subjects = DB::table('syllabus')
				->join('class_syllabus', 'class_syllabus.syllabus_id', '=', 'syllabus.syllabus_id')
				->join('subjects', 'subjects.subject_id', '=', 'syllabus.subject_id')
				->select('syllabus.class_id AS class_id','syllabus.subject_id AS subject_id', 'class_name', 'subject')
				->where('class_syllabus.class_div_id', '=', $class_div_id)
				->distinct('subjects.subject_id')
				->orderBy('subjects.subject', 'ASC')
				->select('subjects.subject', 'subjects.subject_id')
				->get();
						
			$content ='<table class="table table-borderless table-condensed table-hover table-fixed" id="tbl_class_score" style="font-size:100%; margin:5px;">';;
			$content .='<thead class="thead-dark">';
			$content .='<tr>';
			$content .='<th width="15px" class="zui-sticky-col">Reg No</th>';
			$content .='<th width="30px">Name</th>';
			
			foreach ($subjects as $subject){
				$content .='<th width="30px">'.$subject->subject.'</th>';
			}
			$content .='</tr>';
			$content .='</thead>';
			$content .='<tbody>';
			//get all the students for the indicated class
			$students = StudentController::getClassEnrolment($class_div_id);
			foreach ($students as $student){
				$content .='<tr>';
				$content .='<td width="15px" class="zui-sticky-col">
					<input type="text" name="regno[]" class="reg_no" value="'.$student->reg_no.'" readonly style="width: 100%; border: 0; outline: 0; background: transparent; border-right: 1px solid #D3D3D3;">
					</td>';
				$content .='<td width="30px" style = "border: 0; outline: 0; background: transparent; border-right: 1px solid #D3D3D3;">'.$student->last_name. ', '.$student->first_name.'</td>';
				
				//check if there is a score for the subject, exam and date and populate
				foreach ($subjects as $subject){
					//there is no need for class here as the student cannot be in different classes on the same date
					$exam_score = DB::table('exam_score')
						->where('exam_date', '=', $exam_date)
						->where('exam_id', '=', $exam_id)
						->where('semester_id', '=', $semester_id)
						->where('class_div_id', '=', $class_div_id)
						->where('subject_id', '=', $subject->subject_id)
						->where('student_id', '=', $student->student_id)
						->value('exam_score');
							
					$content .='<td width="30px"><input type="text" name="score[]" class="score amount text-right" value="'.$exam_score.'" style="width: 100%; border: 0; outline: 0; background: transparent; border-right: 1px solid #D3D3D3;" onkeyup="return allow_number(this)"></td>';
					//border: 0; outline: 0; background: transparent; border-right: 1px solid #D3D3D3;
				}
				$content .='</tr>';
			}
			$content .='</tbody>';
			$content .='</table>';
			return $content;
			
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Score', 'Class');}
	}
	public function getExamMaxScore(Request $request){
		try{
			$exam_id = $request->exam_id;
			$class_id = $request->class_id;
			$max_score = ExamClass::where('class_id', '=', $class_id)
					->where('exam_id', '=', $exam_id)
					->value('max_score');
					
			return $max_score;
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Maximum-Score', 'Get');}
	}
	public function getExamMaxScoreFunction($exam_id, $class_id){
		try{
			$max_score = ExamClass::where('class_id', '=', $class_id)
					->where('exam_id', '=', $exam_id)
					->value('max_score');
					
			return $max_score;
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Maximum-Score', 'Get');}
	}
	
	public function updateClassScore(Request $request){
		//$c = count($request->exam_score_data); //this count the nunmber of rows in the table
		$logRec = array();
		if($request->ajax()){
			try{
				$operator = $request->operator;
				$exam_date = $request->exam_date;
				$class_div_id = $request->class_div_id;
				$class_id = $request->class_id;
				$semester_id = $request->semester_id;
				$exam_id = $request->exam_id;
				$reg_no = $request->reg_no;
				
				$max_score = $this->getExamMaxScoreFunction($exam_id, $class_id);
				$student_id = StudentController::getStudentID($reg_no);
				//pick only the subjects 
				$subjects = DB::table('syllabus')
					->join('class_syllabus', 'class_syllabus.syllabus_id', '=', 'syllabus.syllabus_id')
					->join('subjects', 'subjects.subject_id', '=', 'syllabus.subject_id')
					->select('syllabus.class_id AS class_id','syllabus.subject_id AS subject_id', 'class_name', 'subject')
					->where('class_syllabus.class_div_id', '=', $class_div_id)
					->distinct('subjects.subject_id')
					->orderBy('subjects.subject', 'ASC')
					->select('subjects.subject', 'subjects.subject_id')
					->get();
				$count = count($subjects);
				$i = 0;
				foreach ($subjects as $subject){
					$subject_id = $subject->subject_id;
					$score = $request->class_score[$i];	
					if(empty($score)) $score = 0;
					//it will only update where the score is less or equal to maximum score
					if($score <= $max_score){
						//delete any previous record for the subject
						try{
							$logRec = DB::table('exam_score')
										->where('exam_id',$exam_id)
										->where('semester_id',$semester_id)
										->where('subject_id',$subject_id)
										->where('exam_date',$exam_date)
										->where('student_id',$student_id)
										->delete();
						} catch (Exception $e) {}
						$logRec = ExamScore::create(
							array(
								'exam_date' => $exam_date,
								'exam_id' => $exam_id, 
								'semester_id' => $semester_id, 
								'subject_id' => $subject_id, 
								'class_div_id' => $class_div_id, 
								'student_id' => $student_id, 
								'exam_score' => $score,
								'remarks' => 'Present', 
								'operator' => $request->operator
							)
						);
					}
					$i = $i + 1;
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Exams', 'Class Score', 'Update');
			}
			return $logRec;
		}
	}
	public function updateExamScore(Request $request){
		$c = count($request->exam_score);
		$logRec = array();
		if($request->ajax() && $c > 0){
			try{
				$operator = $request->operator;
				$exam_date = $request->exam_date;
				$class_div_id = $request->class_div_id;
				$semester_id = $request->semester_id;
				$subject_id = $request->subject_id;
				$exam_id = $request->exam_id;
				
				for($i=0;$i<$c;$i++){
					$class_id = MasterController::getClassID($class_div_id);
					$max_score = $this->getExamMaxScoreFunction($exam_id, $class_id);
					$score = $request->exam_score[$i];
					
					if(empty($score)) $score = 0;
					//delete any previous one
					if($score <= $max_score){
						try{
							$logRec = DB::table('exam_score')
										->where('exam_id',$request->exam_id)
										->where('semester_id',$request->semester_id)
										->where('subject_id',$request->subject_id)
										->where('exam_date',$request->exam_date)
										->where('student_id',$request->students[$i])
										->delete();
						} catch (Exception $e) {}
						$logRec = ExamScore::create(
							array(
								'exam_date' => $request->exam_date,
								'exam_id' => $request->exam_id, 
								'semester_id' => $request->semester_id, 
								'subject_id' => $request->subject_id, 
								'class_div_id' => $request->class_div_id, 
								'student_id' => $request->students[$i], 
								'exam_score' => $score,
								'remarks' => $request->remarks[$i], 
								'operator' => $request->operator
							)
						);
					}
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Exams', 'Score', 'Update');
			}
			return $logRec;
		}
	}
	
	public function updateScoreImport(Request $request){
		//check whether there was a posting for this student, subject, term, exam type and replace where exist
		//get the semester id: semester_p
		//get the subject id
		//get the student id
		//get the exam type id
		
		try{
			$logRec = array();
			$semester_id = $request->semester_id; //this is ok as the id is picked from select option
			
			$exam = $request->exam;
			$exam_date = $request->exam_date;
			$reg_no = $request->reg_no;
			$subject = $request->subject;
			$class_section = $request->class_section;
			$exam_score = $request->exam_score;
			
			$exam_id = $this->getExamID($exam);
			$student_id = StudentController::getStudentID($reg_no);
			$subject_id = MasterController::getSubjectID($subject);
			$section_id = MasterController::getClassSectionID($class_section);
			if( !empty($student_id) && !empty($section_id) && !empty($subject_id) && !empty($exam_id)){
				
				if($exam_score == "" || $exam_score === NULL) $exam_score = 0;
				//delete any existing record
				try{
					$logRec = DB::table('exam_score')
							->where('exam_id',$exam_id)
							->where('semester_id',$semester_id)
							->where('subject_id',$subject_id)
							->where('exam_date',$exam_date)
							->where('student_id',$student_id)
							->delete();
				} catch (Exception $e) {}
				//update attendance table class
				$logRec = ExamScore::create(
					array(
						'exam_date' => $exam_date,
						'exam_id' => $exam_id, 
						'semester_id' => $semester_id, 
						'subject_id' => $subject_id, 
						'class_div_id' => $section_id, 
						'student_id' => $student_id, 
						'exam_score' => $exam_score,
						'remarks' => 'Present', 
						'operator' => $request->operator
					)
				);
			}
			return $logRec;
		} catch (Exception $e) {
			$this->report_error($e, 'Exam', 'Score', 'Import-Update');
        }
	}
	public function scoreImport(Request $request){
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
					$content = '<table class="table table-hover table-striped table-condensed" id="import_score" style="font-size:100%">';
					
					$i = 0;//initialize
					while (!feof($handle)) {
						$value[] = fgets($handle, 1024);
						$lineArr = explode("\t", $value[$i]);
						
						list($exam, $date, $subject, $reg_no, $last_name, $first_name, $class, $score) = $lineArr;
						//get the last name o the student and the first name based on the reg no
						if( $i == 0){
							$content .='<thead>';
							$content .='<tr>';
							$content .='<th>'.$exam.'</th>';
							$content .='<th>'.$date.'</th>';
							$content .='<th>'.$subject.'</th>';
							$content .='<th>'.$reg_no.'</th>';
							$content .='<th>'.$last_name.'</th>';
							$content .='<th>'.$first_name.'</th>';
							$content .='<th>'.$class.'</th>';
							$content .='<th>'.$score.'</th>';
							$content .='</tr>';
							$content .='</thead>';
							$content .='<tbody>';
						}
						if( $i > 0){
							$last_name = StudentController::getLastName(trim($reg_no));
							$first_name = StudentController::getFirstName(trim($reg_no));
							$student_id = StudentController::getStudentID(trim($reg_no));
							$class =  StudentController::getStudentSection($student_id);
							//check that the exam id defined for the class
							//check that the subject is defined for the class
							$content .='<tr>';
							$content .='<td>'.str_replace('"', '',$exam).'</td>';
							$content .='<td>'.str_replace('"', '',$date).'</td>';
							$content .='<td>'.str_replace('"', '',$subject).'</td>';
							$content .='<td>'.str_replace('"', '',$reg_no).'</td>';
							$content .='<td>'.str_replace('"', '',$last_name).'</td>';
							$content .='<td>'.str_replace('"', '',$first_name).'</td>';
							$content .='<td>'.str_replace('"', '',$class).'</td>';
							$content .='<td>'.str_replace('"', '',$score).'</td>';
							$content .='</tr>';
						}
						$i++;
					}
					$content .='</tbody></table>';
					fclose($handle);
					
					//file_put_contents('file_error.txt', $content. PHP_EOL, FILE_APPEND);
					return $content;
				}
			}
		}
		catch (Exception $e) { 
			$this->report_error($e, 'Exam', 'Score', 'Import'); 
		}
	}
	public function pdfExamScores(Request $request){
		$reg_no = $request->reg_no;
		$semester_id = $request->semester_id;
		
		$semester_name = SetController::semester_name($semester_id);
		$student_id = StudentController::getStudentID($reg_no);
		//create a file
		$pdf_file = $semester_name.'_'.$reg_no.'_scores.pdf';
		///////////////YOU are iterating through the raw exam scores
		$rows = DB::table('exam_score as a')
					->where('a.semester_id', '=', $semester_id)
					->where('a.student_id', '=', $student_id)
					->orderBy('a.exam_date', 'ASC')
					->orderBy('a.subject_id', 'ASC')
					->get();
		
		if(count($rows)>0){
			$class_id = '';
			$line = DB::table('students')->where('student_id', $student_id)->first();
			
			try{ //A4: 210 × 297 millimeters
				$photo = $line->photo;
				
				$class_name = StudentController::getStudentClassName($student_id);
				$section = StudentController::getStudentClass($student_id);
				$class_id = MasterController::getClassID($section);
				
				//now start the PDF generation
				$this->pdf->AddPage('P');
				
				$this->pdf->Ln(2);
				$this->pdf->SetFont('Arial', 'B', 14); //set font
				$this->pdf->Cell(200, 5, $semester_name. ' EXAM SCORES', 0, 0, 'C');
				$this->pdf->Ln(10);
				//image, x, y, widht, height, image type
				if(!empty($photo)) $this->pdf->Image(storage_path('app/photo/student/'.$photo),185, 50, 15, 15, ''); 
				
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->SetX(10);
				$this->pdf->Cell(18, 5, 'Reg. No:', 0, 0, 'L'); //width, height, text, next line, border, alignment
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(10, 5, $line->reg_no, 0, 0, 'L');
				$this->pdf->SetX(70);
				
				$name = $line->last_name.', '.$line->first_name;
				if( $line->other_name !== NULL && $line->other_name !== "-"){
					$name = $name.' '.$line->other_name;
				}
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Name:', 0, 0, 'L');
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(50, 5, $name, 0, 0, 'L');
				
				$this->pdf->SetX(135);
				$this->pdf->SetFont('Arial', 'B', 10); //set font
				$this->pdf->Cell(20, 5, 'Class:', 0, 0, 'L'); //width, height, text, next line, border, alignment
				$this->pdf->SetFont('Arial', '', 10); //set font
				$this->pdf->Cell(25, 5, $class_name, 0, 0, 'L');
				
				$this->pdf->Ln(10);
				$x = $this->pdf->GetX();
				$y = $this->pdf->GetY();
				
				////////////////CREATE COLUMNS
				$this->pdf->SetFont('Arial', 'B', 9); //set font
				$this->pdf->Multicell(25, 5, "Date\n ", 1, 'L');
				$x = $x + 25;
				$this->pdf->SetXY($x, $y);
				
				$this->pdf->Multicell(50, 5, "Subject\n ", 1, 'L');
				$x = $x + 50;
				$this->pdf->SetXY($x, $y);
						
				$this->pdf->Multicell(20, 5, "Exam\n Type", 1, 'L');
				$x = $x + 20;
				$this->pdf->SetXY($x, $y);
				
				$this->pdf->Multicell(20, 5, "Exam\n Weight", 1, 'L');
				$x = $x + 20;
				$this->pdf->SetXY($x, $y);
				
				$this->pdf->Multicell(20, 5, "Maximum\n Score", 1, 'L');
				$x = $x + 20;
				$this->pdf->SetXY($x, $y);
				
				$this->pdf->Multicell(20, 5, "Score\n Obtained", 1, 'L');
				$x = $x + 20;
				$this->pdf->SetXY($x, $y);
				
				$this->pdf->Multicell(25, 5, "Remarks\n ", 1, 'L');
				$x = $x + 25;
				
				$x = $this->pdf->GetX();
				$y = $this->pdf->GetY();
				
				$this->pdf->SetXY($x, $y);
				////////////////////////NOW GET THE DATA INSIDE
				//$this->pdf->Ln(5);
				
				$old_date = '';
				$old_subject = '';
				$old_exam = '';
				$old_present = '';
				foreach ($rows as $row){
					//get date
					$exam_date = $row->exam_date;
					
					$subject_id = $row->subject_id;
					$exam_id = $row->exam_id;
					$score = $row->exam_score;
					$remarks = $row->remarks;
					$class_exam = DB::table('exam_name')
							->join('exam_class', 'exam_class.exam_id', '=', 'exam_name.exam_id')
							->where('exam_class.exam_id',  $exam_id)
							->where('exam_class.class_id',  $class_id)
							->first();
					
					$this->pdf->SetFont('Arial', '', 9); //set font
					
					if( $old_date !== $exam_date){
						$this->pdf->Cell(25, 5, date("d/m/Y", strtotime($exam_date)), 1, 0, 'R');
					}else{
						$this->pdf->Cell(25, 5, '', 1, 0, 'C');
					}
					if( $old_subject !== $class_exam->subject){
						$this->pdf->Cell(50, 5, $class_exam->subject, 1, 0, 'L');
					}else{
						$this->pdf->Cell(50, 5, '', 1, 0, 'C');
					}
					if( $old_exam !== $class_exam->exam_name){
						$this->pdf->Cell(20, 5, $class_exam->exam_name, 1, 0, 'C');
					}else{
						$this->pdf->Cell(20, 5, '"', 1, 0, 'C');
					}
					$this->pdf->Cell(20, 5, $class_exam->exam_weight.'%', 1, 0, 'R');
					$this->pdf->Cell(20, 5, $class_exam->max_score, 1, 0, 'R');
					////////////////////
					$this->pdf->Cell(20, 5, $score, 1, 0, 'R');
					
					if( $old_present !== $remarks){
						$this->pdf->Cell(25, 5, $remarks, 1, 0, 'L');
					}else{
						$this->pdf->Cell(25, 5, '"', 1, 0, 'C');
					}
					
					$this->pdf->Ln(5);
					
					$old_date = $exam_date;
					$old_subject = $class_exam->subject;
					$old_exam = $class_exam->exam_name;
					$old_present = $remarks;
					
				}
				$path = storage_path('reports/pdf/scores').'/'.$pdf_file;
				$this->pdf->Output($path, 'F');
				
				$destination = $this->public_folder.'/reports/pdf/scores/'.$pdf_file;
				\File::copy($path,$destination);
				
				return $pdf_file;
				
			} catch (Exception $e) {
				$this->report_error($e, 'Exams', 'Semester-Scores', 'Pdf');
			}
			
		}
	}
	public function excelExamScores(Request $request){
		$semester_id = $request->semester_id;
		try{
			$records =  ExamScore::join('semester','semester.semester_id', '=', 'exam_score.semester_id')
				->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
				->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
				->join('students','students.student_id', '=', 'exam_score.student_id')
				->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
				->where('exam_score.semester_id', '=', $semester_id)
				->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
					'exam_name', 'reg_no', 'first_name', 'last_name',
					'exam_score.operator', 'exam_score.reviewer', 'exam_score.created_at')
				->orderBy('exam_score.exam_date', 'DESC')
				->orderBy('exam_name', 'ASC')->get();
			//$sheet->fromArray($data);
			$csv = $records;  // stored the data in a array
			
			return Excel::create('exam_score-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('class_section','subject','exam_score', 'semester', 'exam_date',
					'exam_name', 'reg_no', 'first_name', 'last_name',
					'operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
				})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Score', 'Excel');}
	}
	//get total score for a student in a term per exam type
	
	//get total score for a student in academic year
	
	//get scores for a subject in a term for all the exams
	
	//get scores for a term for all the subjects and all the exams in datatable
	public function listExamScore(Request $request){
		$semester_id = $request->semester_id;
		try{
			$records =  ExamScore::join('semester','semester.semester_id', '=', 'exam_score.semester_id')
				->join('class_div','class_div.class_div_id', '=', 'exam_score.class_div_id')
				->join('subjects','subjects.subject_id', '=', 'exam_score.subject_id')
				->join('students','students.student_id', '=', 'exam_score.student_id')
				->join('exam_name','exam_name.exam_id', '=', 'exam_score.exam_id')
				->where('exam_score.semester_id', '=', $semester_id)
				->select('class_div.class_div','subjects.subject','exam_score', 'semester', 'exam_date',
					'exam_name', 'reg_no', 'first_name', 'last_name', 'score_id',
					'exam_score.operator', 'exam_score.reviewer', 'exam_score.created_at')
				->orderBy('exam_score.exam_date', 'DESC')
				->orderBy('exam_name', 'ASC')->get();
			
			return view('exam.infoexamscore', compact('records'));
			
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Exam-Score', 'Excel');}
	}
	
	///////////////////////////////////////////////////////////////////SEMESTER SCORE PROCESSING
	public function showTermScore(){
		$semester =  Semester::orderBy('date_from', 'DESC')->get();
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		return view('exam.semester_score', compact('semester','class'));
	}
	//this function processes the total exam scores and aggregate for terminal results
	public function semesterScore(Request $request){
		$semester_id = $request->semester_id;
		$operator = Auth::user()->username;
		
		try{
			$logRec = array();
			//delete any score generation for the semester
			DB::table('student_exam_score')->where('semester_id', $semester_id)->delete();
			DB::table('student_total_score')->where('semester_id', $semester_id)->delete();
			DB::table('student_results')->where('semester_id', $semester_id)->delete();
			
			//file_put_contents('file_error.txt', 'start'. PHP_EOL, FILE_APPEND);
			//iterate through the classes one by one in the order specified
			$sch_classes = DB::table('sch_classes')->orderBy('sequence', 'ASC')->get();
			//the aim here is to get the total score per subject for a student
			//to do this, pick the subjects, get the various exams for the subject and aggregate the student scores for each exams under
			//the subject 
			foreach ($sch_classes as $class){
				//get the class_id
				$class_id = $class->class_id;
				//get all the subjects for this class
				$subjects = DB::table('subjects as a')
							->join('syllabus as b', 'b.subject_id','a.subject_id')
							->select('b.subject_id AS subject_id')
							->where('b.class_id', '=', $class_id)
							->distinct('a.subject_id')
							->get();
				//get all the subjects being offerred in the class
				foreach ($subjects as $subject){
					$subject_id = $subject->subject_id;
					//now determine which student took the subject and then the exams
					//get the actively enrolled students for this class offering the selected subject
							
					$students = DB::table('exam_score as a')
							->join('student_enrol as b', 'b.student_id','a.student_id')
							->join('class_div as c', 'c.class_div_id','b.class_id')
							->select('c.class_div_id AS section','a.student_id','c.class_id')
							->where('b.active', '1')
							->where('c.class_id', $class_id)
							->where('a.subject_id', $subject_id)
							->where('a.semester_id', $semester_id)
							->distinct('a.student_id')
							->get();
					//get total score by a student for each subject
					$total_score = 0;
					foreach ($students as $student){
						$student_id = $student->student_id;
						$class_div_id = $student->section;
						
						//file_put_contents('file_error.txt', $student_id. PHP_EOL, FILE_APPEND);
						//now aggregate all the scores for this subject for this student
						//go through the exams for this class and subject
						$exams = DB::table('exam_class')
									->where('exam_class.class_id', '=', $class_id)
									->get();
						foreach ($exams as $exam){
							//file_put_contents('file_error.txt', 'exams'. PHP_EOL, FILE_APPEND);
							$exam_id = $exam->exam_id;
							//get the average score for all the exams under this typw for the student
							$score = DB::table('exam_score as a')
									->select(DB::raw('AVG(a.exam_score) AS total_score'))
									->where('a.semester_id', '=', $semester_id)
									->where('a.subject_id', '=', $subject_id)
									->where('a.exam_id', '=', $exam_id)
									->where('a.student_id', '=', $student_id)
									->value('total_score');
							
							$weight =  $exam->exam_weight;
							$max_score = $exam->max_score;
							$gross = 100/$max_score;
							
							$e_score = $score * $gross;
							//find average score: this is to 100
							//file_put_contents('file_error.txt', $count. PHP_EOL, FILE_APPEND);
							//$e_score = $e_score / $count;
							//then ascertain the weight and derive total score for the exam
							$e_score = ($e_score*$weight)/100;
							//this aggregates all the scores for each exam type: irrespective of how many times the exam was taken
							if( $e_score > 0 ){
								$logRec = StudentExamScore::create(
									array(
										'class_div_id' => $class_div_id,
										'exam_id' => $exam_id,
										'subject_id' => $subject_id,
										'exam_score' =>  $e_score, 
										'student_id' =>  $student_id,
										'semester_id' => $semester_id,
										'operator' => $operator
									)
								);
							}
							//get the total score for the subject
							$total_score = $total_score + $e_score;
						}
						//get the total score for the subject here
						$total_score = DB::table('student_exam_score as a')
								->select(DB::raw('SUM(a.exam_score) AS scores'))
								->where('a.student_id', $student_id)
								->where('a.subject_id', $subject_id)
								->where('a.semester_id', $semester_id)
								->value('scores');
								
						//now you are through with all the exams for the subject for the student
						//then update
						if( $total_score > 0){
							//determine the grade for the score
							$grades = ExamGrade::where('score_to','>=', $total_score)
									->where('class_id', $class_id)
									->orderBy('score_to', 'ASC')
									->first();
							//if there is a grade for the class score, the  use it			
							if( count( $grades)>0 ){
								//file_put_contents('file_error.txt', $student_id. PHP_EOL, FILE_APPEND);
								
								$exam_grade = $grades->exam_grade_id;
								//update the table for the student, subject, score, semester, class etc: ie total score per subject for the student
								//it also grade the scores
								$logRec = StudentTotalScore::create(
									array(
										'class_div_id' => $class_div_id,
										'subject_id' => $subject_id,
										'exam_score' =>  $total_score,
										'exam_grade_id' =>  $exam_grade, 
										'student_id' =>  $student_id,
										'semester_id' => $semester_id,
										'operator' => $operator
									)
								);
							}
						}
					}
				}
			}
			DB::table('student_exam_score')->where('exam_score', '<=', 0)->delete();
			DB::table('student_total_score')->where('exam_score', '<=', 0)->delete();
			//having aggregated the scores, now proceed to generate results for the classes.
			//iterate through the student_total_score
			$rows = DB::table('student_total_score')
						->select('student_id', 'semester_id', 'class_div_id')
						->distinct('student_id')
						->get();
			
			foreach ($rows as $row){
				$student_id = $row->student_id;
				$semester_id = $row->semester_id;
				$class_div_id = $row->class_div_id;
				
				$total_score = DB::table('student_total_score as a')
					->select(DB::raw('SUM(a.exam_score) AS scores'))
					->where('a.student_id', $student_id)
					->where('a.semester_id', $semester_id)
					->where('a.class_div_id', $class_div_id)
					->value('scores');	
				
				DB::table('student_results')->insert(
					 array(
						'class_div_id' => $class_div_id,
						'semester_id' => $semester_id,
						'total_score' =>  $total_score,
						'student_id' =>  $student_id
					 )
				);
			}
			return $logRec;
			
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Exam-Score', 'Excel');}
	}
	
	public function getClassScore(Request $request){
		$semester_id = $request->semester_id;
		$class_id = $request->class_id;
		//this should get the scores for the class for that semester
		try{
			if(!empty($class_id)){
				$record = DB::table('student_total_score as a')
					->join('class_div as b','b.class_div_id', '=', 'a.class_div_id')
					->join('students as c', 'c.student_id', '=', 'a.student_id')
					->join('subjects as d', 'd.subject_id', '=', 'a.subject_id')
					->join('exam_grade as e', 'e.exam_grade_id', '=', 'a.exam_grade_id')
					->join('score_grade as f', 'f.score_grade_id', '=', 'e.score_grade_id')
					->where('a.semester_id', $semester_id)
					->where('b.class_id', $class_id)
					->where('a.exam_score','>', 0)
					->get();
				
				return $record;
			}else{
				$record = DB::table('student_total_score as a')
					->join('class_div as b','b.class_div_id', '=', 'a.class_div_id')
					->join('students as c', 'c.student_id', '=', 'a.student_id')
					->join('subjects as d', 'd.subject_id', '=', 'a.subject_id')
					->join('exam_grade as e', 'e.exam_grade_id', '=', 'a.exam_grade_id')
					->join('score_grade as f', 'f.score_grade_id', '=', 'e.score_grade_id')
					->where('a.semester_id', $semester_id)
					->where('a.exam_score','>', 0)
					->get();
				
				return $record;	
			}
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Semester-Score', 'Excel'); }
	}
	//this returns the total score per student for a class section
	public function getStudentScore(Request $request){
		$semester_id = $request->semester_id;
		$class_div_id = $request->class_div_id;
		try{
			$rows = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
				->join('students', 'students.student_id', '=', 'student_total_score.student_id')
				->select('reg_no', 'last_name', 'first_name', 'class_div', 
					'student_total_score.student_id AS student_reg')
				->where('student_total_score.class_div_id', $class_div_id)
				->where('student_total_score.semester_id', $semester_id)
				->distinct('student_total_score.student_id')
				->orderBy('student_total_score.student_id', 'ASC')
				->get();
			$records = array();
			$old_student = "";
			foreach ($rows as $row){
				$record = array();
				$student_id = $row->student_reg;
				if($old_student !== $student_id){
					$record['reg_no'] = $row->reg_no;
					$record['last_name'] = $row->last_name;
					$record['first_name'] = $row->first_name;
					//$record['class_div'] = $row->class_div;
					//get the number of subjects for this student
					$no_subject = DB::table('student_total_score as a')
								->select(DB::raw('COUNT(a.student_id) AS exams'))
								->where('a.student_id', $student_id)
								->where('a.semester_id', $semester_id)
								->where('a.class_div_id', $class_div_id)
								->value('exams');	
					$total_score = DB::table('student_total_score as a')
								->select(DB::raw('SUM(a.exam_score) AS scores'))
								->where('a.student_id', $student_id)
								->where('a.semester_id', $semester_id)
								->where('a.class_div_id', $class_div_id)
								->value('scores');	
					$comment = 	DB::table('teachers_comment')
								->where('student_id', $student_id)
								->where('semester_id', $semester_id)
								->value('comment');
					
					$record['no_subject'] = $no_subject;
					$record['score_total'] = $no_subject * 100;
					//get the total score for this student
					$record['score_obtained'] = $total_score;
					$record['average_score'] = number_format($total_score/$no_subject, 2, '.', ',');
					//get the subject passed
					$subject_f = StudentTotalScore::join('exam_grade', 'exam_grade.exam_grade_id', '=', 
									'student_total_score.exam_grade_id')
								->join('score_grade', 'score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
								->where('student_total_score.semester_id', $semester_id)
								->where('student_total_score.student_id', $student_id)
								->where('exam_grade.remarks', 'Failure')
								->get();
					$subject_f = count($subject_f);
					//get the subject failed
					$record['subject_failed'] = $subject_f;
					$record['subject_passed'] = $no_subject - $subject_f;
					$record['comment'] = $comment;
					$old_student = $student_id;
					array_push($records, $record);
				}
			}
			return $records;
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Semester-Score', 'Get');}
	}
	public function emailResult(Request $request){
		//this should just email NOT generate
		$semester_id = $request->semester_id;
		$reg_no = $request->reg_no;
		return $this->sendMail($semester_id, $reg_no);
	}
	public static function sendMail($semester_id, $reg_no){
		
		$semester_name = SetController::semester_name($semester_id);
		$pdf_file = $semester_name.'_'.$reg_no.'_result.pdf';
		$path = storage_path('reports/pdf/results').'/'.$pdf_file;
		
		if (file_exists($path)){
			SysController::sendStudentMail($reg_no, "REPORT SLIP", $pdf_file, $path);
			return "Result sent to ". $reg_no;
		}else{
			return "File not found, please run the terminal process";	
		}
	}
	//this totals all the scores for the exams into a single column
	public function pdfSingleResult(Request $request){
		
		//$semester_name = SetController::semester_name($request->semester_id);
		$reg_no = $request->reg_no;
		
		//file_put_contents('file_error.txt', 'start '.$reg_no. PHP_EOL, FILE_APPEND);
		$semester_id = $request->semester_id;
		//$pdf_file = $semester_name.'_'.$reg_no.'_result.pdf';
		//return $pdf_file;
	
		$semester_start = SetController::semester_start($semester_id);
		$semester_end = SetController::semester_end($semester_id);
		$semester_name = SetController::semester_name($semester_id);
		$semester_next = SetController::semester_next($semester_id); //this gets the next semester start date
		$student_id = StudentController::getStudentID($reg_no);
		//create a file
		$pdf_file = $semester_name.'_'.$reg_no.'_result.pdf';
			
		$rows = StudentTotalScore::join('subjects', 'subjects.subject_id', '=', 'student_total_score.subject_id')
			->join('students', 'students.student_id', '=', 'student_total_score.student_id')
			->join('exam_grade', 'exam_grade.exam_grade_id', '=', 'student_total_score.exam_grade_id')
			->join('score_grade', 'score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
			->select('student_total_score.student_id AS student_id', 
				'student_total_score.subject_id AS subject_id','exam_grade.remarks AS remarks', 'score_grade', 'exam_score','subject','student_total_score.class_div_id as section')
			->where('student_total_score.semester_id', $semester_id)
			->where('student_total_score.student_id', $student_id)
			->orderBy('subjects.subject', 'ASC')
			->get();
			
		//student must exist in the total score for the selected semester
		if(count($rows)>0){
			//get attendance
			$class_id = '';
			
			$total_attendance = DB::table('student_attendance')
								->select('attendance_date')
								->where('attendance_date', '>=', $semester_start)
								->where('attendance_date', '<=', $semester_end)
								->distinct()
								->count('attendance_date');
			$student_attendance = DB::table('student_attendance')
								->select('attendance_date')
								->where('attendance_date', '>=', $semester_start)
								->where('attendance_date', '<=', $semester_end)
								->where('student_id', $student_id)
								->where('remarks', 'Present')
								->distinct()
								->count('attendance_date');
			//check if the student passed based on the overall result criteria: the student should be in the table else failed
			$passed = DB::table('semester_promotion')
								->where('semester_id', $semester_id)
								->where('student_id', $student_id)
								->get();
			$outcome = "Failed";
			if (count($passed)> 0) $outcome = "Passed";
			
			//get the details of this student
			$line = DB::table('students')->where('student_id', $student_id)->first();
			
			if (count($line)> 0){
				//file_put_contents('file_error.txt', $pdf_file. PHP_EOL, FILE_APPEND);
					
				try{ //A4: 210 × 297 millimeters
					$photo = $line->photo;
					
					$class_name = StudentController::getStudentClassName($student_id);
					$section = StudentController::getStudentClass($student_id);
					$age = date_diff(date_create($line->dob), date_create('now'))->y;
					
					//bring in the teachers comment for this student: NB this may no loner be necessary because of the assessment
					$comment = TeachersComment::where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->value('comment');
							
					//now start the PDF generation
					$this->pdf->AddPage('P');
					
					$this->pdf->Ln(2);
					$this->pdf->SetFont('Arial', 'B', 14); //set font
					$this->pdf->Cell(200, 5, 'TERM RESULT', 0, 0, 'C');
					$this->pdf->Ln(10);
					//image, x, y, widht, height, image type
					if(!empty($photo)) $this->pdf->Image(storage_path('app/photo/student/'.$photo),185, 50, 15, 15, ''); 
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->SetX(10);
					$this->pdf->Cell(20, 5, 'Reg. No:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(10, 5, $line->reg_no, 0, 0, 'L');
					$this->pdf->SetX(70);
					
					$name = $line->last_name.', '.$line->first_name;
					if( $line->other_name !== NULL && $line->other_name !== "-"){
						$name = $name.' '.$line->other_name;
					}
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Name:', 0, 0, 'L');
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(50, 5, $name, 0, 0, 'L');
					
					$this->pdf->SetX(135);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Class:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, $class_name, 0, 0, 'L');
					/////////////////////////////
					$this->pdf->Ln(5);
					$this->pdf->SetX(10);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Term:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(10, 5, $semester_name, 0, 0, 'L');
					$this->pdf->SetX(70);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Weight:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, $line->weight, 0, 0, 'L');
					
					$this->pdf->SetX(110);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(15, 5, 'Height:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, $line->height, 0, 0, 'L');
					
					$this->pdf->SetX(135);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Age:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, $age, 0, 0, 'L');
					
					$this->pdf->Ln(5);
					$this->pdf->SetX(10);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Date:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, date('d/m/Y'), 0, 0, 'L');
					$this->pdf->SetX(70);
					
					//get attendance
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Attendance:', 0, 0, 'L');
					$this->pdf->Cell(45, 5, $student_attendance. ' out of '. $total_attendance, 0, 0, 'L');
					$this->pdf->SetX(135);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Next Term:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, date('d/m/Y', strtotime($semester_next)), 0, 0, 'L'); 
					
					$this->pdf->Ln(5);
					$this->pdf->SetFont('Arial', 'B', 9); //set font
					$this->pdf->SetX(10);
					$this->pdf->Cell(50, 5, 'Subject', 1, 0, 'L');
					$this->pdf->Cell(15, 5, 'Score', 1, 0, 'L');
					$this->pdf->Cell(20, 5, "Highest", 1, 0, 'L');
					$this->pdf->Cell(20, 5, "Lowest", 1, 0, 'L');
					$this->pdf->Cell(25, 5, "Position", 1, 0, 'L');
					$this->pdf->Cell(25, 5, "Students", 1, 0, 'L');
					$this->pdf->Cell(15, 5, 'Grade', 1, 0, 'L');
					$this->pdf->Cell(20, 5, 'Remarks', 1, 0, 'L');
					$this->pdf->Ln();
					$count = 0;
					$total_score = 0;
					
					foreach ($rows as $row){
						//get each subject
						$subject = $row->subject;
						$subject_id = $row->subject_id;
						$exam_score = $row->exam_score;
						$score_grade = $row->score_grade;
						$remarks = $row->remarks;
						$class_id = DB::table('class_div')->where('class_div_id', $row->section)->value('class_id');
						
						$class_highest = DB::table('student_total_score as a')
									->where('a.subject_id', $subject_id)
									->where('a.semester_id', $semester_id)
									->where('a.class_div_id', $section)
									->max('a.exam_score');
						
						$class_lowest = DB::table('student_total_score as a')
									->where('a.subject_id', $subject_id)
									->where('a.semester_id', $semester_id)
									->where('a.class_div_id', $section)
									->min('a.exam_score');
						
						$subject_highest = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
							->where('student_total_score.subject_id', $subject_id)
							->where('student_total_score.semester_id', $semester_id)
							->where('class_div.class_id', $class_id)
							->max('student_total_score.exam_score');
						
						$subject_lowest = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
							->where('student_total_score.subject_id', $subject_id)
							->where('student_total_score.semester_id', $semester_id)
							->where('class_div.class_id', $class_id)
							->min('student_total_score.exam_score');
						//the logic here is that to get the position, you must count the other scores after the desired score
						//e.g if total population is 20, and the score is at 10th position, that means that
						$class_position = DB::table('student_total_score as a')
									->where('a.subject_id', $subject_id)
									->where('a.semester_id', $semester_id)
									->where('a.class_div_id', $section)
									->where('a.exam_score', '>=' , $exam_score)
									->count();
						
						$subject_position = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
							->where('student_total_score.subject_id', $subject_id)
							->where('student_total_score.semester_id', $semester_id)
							->where('class_div.class_id', $class_id)
							->where('student_total_score.exam_score', '>=' , $exam_score)
							->count();
						
						$student_count =DB::table('student_total_score as a')
							->where('a.subject_id', $subject_id)
							->where('a.semester_id', $semester_id)
							->where('a.class_div_id', $section)
							->count();
							
						$this->pdf->SetFont('Arial', '', 9); //set font
					
						$this->pdf->SetX(10);
						$this->pdf->Cell(50, 5, $subject, 1, 0, 'L');
						$this->pdf->Cell(15, 5, $exam_score, 1, 0, 'R');
						$this->pdf->Cell(20, 5, $class_highest, 1, 0, 'R');
						$this->pdf->Cell(20, 5, $class_lowest, 1, 0, 'R');
						$this->pdf->Cell(25, 5, $class_position, 1, 0, 'C');
						$this->pdf->Cell(25, 5, $student_count, 1, 0, 'C');
						$this->pdf->Cell(15, 5, $score_grade, 1, 0, 'C');
						$this->pdf->Cell(20, 5, $remarks, 1, 0, 'L');
						$this->pdf->Ln(5);
						$count = $count + 1;
						$total_score = $total_score + $exam_score;
					}
					$student_total_score = DB::table('student_results')
										->where('student_id', $student_id)
										->where('semester_id', $semester_id)
										->value('total_score');
					
					$class_position = DB::table('student_results')
										->where('semester_id', $semester_id)
										->where('class_div_id', $section)
										->where('total_score', '>=' , $student_total_score)
										->count();
										
					$no_in_class =  DB::table('student_results')
										->where('class_div_id', $section)
										->where('semester_id', $semester_id)
										->distinct('class_div_id')
										->count();
					$this->pdf->Ln(5);
					$this->pdf->SetX(10);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(21, 5, 'Total Score:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(12, 5, $count * 100, 0, 0, 'L');
					$this->pdf->SetX(41);
					
					//get attendance
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(28, 5, 'Score Obtained:', 0, 0, 'L');
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(12, 5, $total_score, 0, 0, 'L');
					$this->pdf->SetX(83);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(16, 5, 'Average:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$average = number_format($total_score/$count, 2, '.', '');//no comma
					$this->pdf->Cell(10, 5, $average, 0, 0, 'L'); 
					$this->pdf->SetX(110);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(26, 5, 'Class Position:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(10, 5, $class_position, 0, 0, 'L');
					$this->pdf->SetX(141);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(23, 5, 'No in Class:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(10, 5, $no_in_class, 0, 0, 'L');
					
					$pass_record = DB::table('semester_promotion')
								->where('semester_id', $semester_id)
								->get();
								
					if( count($pass_record)>0){
						$this->pdf->SetX(170);
						$this->pdf->SetFont('Arial', 'B', 10); //set font
						$this->pdf->Cell(17, 5, 'Outcome:', 0, 0, 'L'); //width, height, text, next line, border, alignment
						$this->pdf->SetFont('Arial', '', 10); //set font
						$this->pdf->Cell(10, 5, $outcome, 0, 0, 'L');
					}
					/*
					next is the assessment of the student, if any for the specified term
					*/
					//there is also provision for other note on the student
					//is he rated for the semester
					//iterate through the assessment ids
					$assessments = DB::table('student_assessment')->get();
					foreach ($assessments as $assessment){							
						$assessment_id = $assessment->assessment_id;
						//is there one for the semester and for the student?
						$remarks = DB::table('term_student_rating')
							->where('student_id', $student_id)
							->where('semester_id', $semester_id)
							->where('assessment_id', $assessment_id)
							->value('remarks');
						
						if( !empty($remarks)){
							
							//creating the headings, which is the parameter
							//`assessment_param`(`param_id`, `parameter`, `assessment_id`, `class_id`
							$params = DB::table('assessment_param')
									->where('assessment_id', $assessment_id)
									->where('class_id', $class_id)
									->orderBy('param_id', 'ASC')
									->get();
							//this list the paramer accross the page: vertically
							if(count($params) > 0){
								$this->pdf->SetFont('Arial', 'B', 9); //set font
								$this->pdf->Ln(10);
								$this->pdf->SetX(10);
								$this->pdf->Cell(30, 5, $assessment->assessment, 0, 0, 'L');
								//the above is the caption
								//then below is the heading: with maximum of 5 parameter
								$this->pdf->Ln(5);
								$this->pdf->SetX(10);
							
								foreach ($params as $param){
									$this->pdf->Cell(40, 5, $param->parameter, 1, 0, 'L');
								}
								//now get content/variables for each parametr in similar manner
								$this->pdf->Ln(5);
								$this->pdf->SetX(10);
								$this->pdf->SetFont('Arial', '', 9); //set font
								
								$remarks = DB::table('term_student_rating')
									->where('student_id', $student_id)
									->where('semester_id', $semester_id)
									->where('assessment_id', $assessment_id)
									->orderBy('param_id', 'ASC')
									->get();
									
								foreach ($remarks as $remark){
									$this->pdf->Cell(40, 5, $remark->remarks, 1, 0, 'L');
									//$this->pdf->MultiCell(40, 5, $remark->remarks);
									//$this->pdf->MultiCell(150, 5, $bank_details);
									//$this->pdf->MultiCell(80, 5, $address, 0, 'L');
								}
							}
						}
					}
					//next is the treachers comment: this may have been included in the assessment
					
					if( $comment !== NULL && $comment !== ""){
						$this->pdf->Ln(5);
						$this->pdf->SetX(10);
						$this->pdf->Cell(30, 5, "Teacher's Comment: ", 0, 0, 'L');
						$this->pdf->Cell(150, 5, $comment, 0, 0, 'L');
					}
					
					$path = storage_path('reports/pdf/results').'/'.$pdf_file;
					$this->pdf->Output($path, 'F');
					
					$destination = $this->public_folder.'/reports/pdf/results/'.$pdf_file;
					\File::copy($path,$destination);
					
					return $pdf_file;
					
					//send email of the file here
				} catch (Exception $e) {
					$this->report_error($e, 'Exams', 'Semester-Result', 'Pdf');
				}
			}
			
		}
	}
	//this prints the result per exam type and the weight in bracket against the type
	public function pdfNewResult(Request $request){
		
		//$semester_name = SetController::semester_name($request->semester_id);
		$reg_no = $request->reg_no;
		//file_put_contents('file_error.txt', 'start '.$reg_no. PHP_EOL, FILE_APPEND);
		$semester_id = $request->semester_id;
		//$pdf_file = $semester_name.'_'.$reg_no.'_result.pdf';
		//return $pdf_file;
	
		$semester_start = SetController::semester_start($semester_id);
		$semester_end = SetController::semester_end($semester_id);
		$semester_name = SetController::semester_name($semester_id);
		$semester_next = SetController::semester_next($semester_id); //this gets the next semester start date
		$student_id = StudentController::getStudentID($reg_no);
		$class_name = StudentController::getStudentClassName($student_id);
		$section = StudentController::getStudentClass($student_id);
		$class_id = MasterController::getClassID($section);
		
		//create a file
		$pdf_file = $semester_name.'_'.$reg_no.'_detail_result.pdf';
			
		$rows = StudentTotalScore::join('subjects', 'subjects.subject_id', '=', 'student_total_score.subject_id')
			->join('students', 'students.student_id', '=', 'student_total_score.student_id')
			->join('exam_grade', 'exam_grade.exam_grade_id', '=', 'student_total_score.exam_grade_id')
			->join('score_grade', 'score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
			->select('student_total_score.student_id AS student_id', 
				'student_total_score.subject_id AS subject_id','exam_grade.remarks AS remarks', 
				'score_grade', 'exam_score','subject','student_total_score.class_div_id as section')
			->where('student_total_score.semester_id', $semester_id)
			->where('student_total_score.student_id', $student_id)
			->orderBy('subjects.subject', 'ASC')
			->get();
			
		//student must exist in the total score for the selected semester
		if(count($rows)>0){
			//get attendance
			$total_attendance = DB::table('student_attendance')
								->select('attendance_date')
								->where('attendance_date', '>=', $semester_start)
								->where('attendance_date', '<=', $semester_end)
								->distinct()
								->count('attendance_date');
			$student_attendance = DB::table('student_attendance')
								->select('attendance_date')
								->where('attendance_date', '>=', $semester_start)
								->where('attendance_date', '<=', $semester_end)
								->where('student_id', $student_id)
								->where('remarks', 'Present')
								->distinct()
								->count('attendance_date');
			//check if the student passed based on the overall result criteria: the student should be in the table else failed
			$passed = DB::table('semester_promotion')
						->where('semester_id', $semester_id)
						->where('student_id', $student_id)
						->get();
			$outcome = "Failed";
			if (count($passed)> 0) $outcome = "Passed";
			
			//get the details of this student
			$line = DB::table('students')->where('student_id', $student_id)->first();
			
			if (count($line)> 0){
				//file_put_contents('file_error.txt', $pdf_file. PHP_EOL, FILE_APPEND);
					
				try{ //A4: 210 × 297 millimeters
					$photo = $line->photo;
					 
					$age = date_diff(date_create($line->dob), date_create('now'))->y;
					
					//bring in the teachers comment for this student: NB this may no loner be necessary because of the assessment
					$comment = TeachersComment::where('semester_id', $semester_id)
							->where('student_id', $student_id)
							->value('comment');
							
					//now start the PDF generation
					$this->pdf->AddPage('P');
					
					$this->pdf->Ln(2);
					$this->pdf->SetFont('Arial', 'B', 14); //set font
					$this->pdf->Cell(200, 5, $semester_name. ' RESULT', 0, 0, 'C');
					$this->pdf->Ln(10);
					//image, x, y, widht, height, image type
					if(!empty($photo)) $this->pdf->Image(storage_path('app/photo/student/'.$photo),185, 50, 15, 15, ''); 
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->SetX(10);
					$this->pdf->Cell(18, 5, 'Reg. No:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(10, 5, $line->reg_no, 0, 0, 'L');
					$this->pdf->SetX(70);
					
					$name = $line->last_name.', '.$line->first_name;
					if( $line->other_name !== NULL && $line->other_name !== "-"){
						$name = $name.' '.$line->other_name;
					}
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Name:', 0, 0, 'L');
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(50, 5, $name, 0, 0, 'L');
					
					$this->pdf->SetX(135);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Class:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, $class_name, 0, 0, 'L');
					/////////////////////////////
					$this->pdf->Ln(5);
					$this->pdf->SetX(10);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					
					$this->pdf->Cell(18, 5, 'Weight:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					//student weight, if not NULL
					$this->pdf->Cell(25, 5, $line->weight, 0, 0, 'L');
					
					$this->pdf->SetX(70);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Height:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					//student height, if not NULL
					$this->pdf->Cell(50, 5, $line->height, 0, 0, 'L');
					
					$this->pdf->SetX(135);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Age:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, $age, 0, 0, 'L');
					
					$this->pdf->Ln(5);
					$this->pdf->SetX(10);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(18, 5, 'Date:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, date('d/m/Y'), 0, 0, 'L');
					$this->pdf->SetX(70);
					
					//get attendance
					//ONLY if attendance was taken
					if( $total_attendance > 0 ){
						$this->pdf->SetFont('Arial', 'B', 10); //set font
						$this->pdf->Cell(21, 5, 'Attendance:', 0, 0, 'L');
						$this->pdf->SetFont('Arial', '', 10); //set font
						
						$this->pdf->Cell(45, 5, $student_attendance. ' out of '. $total_attendance, 0, 0, 'L');
						$this->pdf->SetX(135);
					}
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Next Term:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(25, 5, date('d/m/Y', strtotime($semester_next)), 0, 0, 'L'); 
					$this->pdf->Ln(5);
					$this->pdf->SetX(10);
					
					$x = $this->pdf->GetX();
					$y = $this->pdf->GetY();
					
					$col1 = "Subject\n ";
					$this->pdf->SetFont('Arial', 'B', 9); //set font
					$this->pdf->Multicell(60, 5, $col1, 1, 'L');
					$x = $x + 60;
					$this->pdf->SetXY($x, $y);
					
					$class_exams = DB::table('exam_name')
							->join('exam_class', 'exam_class.exam_id', '=', 'exam_name.exam_id')
							->where('exam_weight', '>', 0)
							->where('exam_class.class_id',  $class_id)
							->orderBy('exam_name.exam_name')
							->distinct(['exam_name.exam_name'])
							->get();
					$old_exam = "";
					foreach ($class_exams as $class_exam){
						$exam_name = $class_exam->exam_name;
						if( $old_exam !== $exam_name){
							$this->pdf->Multicell(12, 5, $class_exam->exam_name."\n[".$class_exam->exam_weight."%]", 1, 'L');
							$x = $x + 12;
							$this->pdf->SetXY($x, $y);
						}
						$old_exam = $exam_name;
					}
					$student_total_score = 0;
					$total_score = 0;
					$subject_count = 0; // this is count the number of subjects taken by the student
					if( count($class_exams) > 0){
						$this->pdf->SetFillColor(224,224,224);
						$this->pdf->Multicell(12, 5, "Total\n Score", 1,1);
						$x = $x + 12;
						$this->pdf->SetXY($x, $y);
						
						$this->pdf->SetFillColor(255,255,255);
						$this->pdf->Multicell(15, 5, "Highest\n Score ", 1);
						$x = $x + 15;
						$this->pdf->SetXY($x, $y);
						
						$this->pdf->Multicell(15, 5, "Lowest\n Score", 1);
						$x = $x + 15;
						$this->pdf->SetXY($x, $y);
						
						$this->pdf->Multicell(15, 5, "Position\n ", 1);
						$x = $x + 15;
						$this->pdf->SetXY($x, $y);
						
						$this->pdf->Multicell(15, 5, "Score\n Grade", 1);
						$x = $x + 15;
						$this->pdf->SetXY($x, $y);
						
						$this->pdf->SetFillColor(204,255,255);
						$this->pdf->Multicell(20, 5, "Remarks\n ", 1,1);
						$this->pdf->SetFillColor(255,255,255);
						
						foreach ($rows as $row){
							//get each subject
							$subject = $row->subject;
							$subject_id = $row->subject_id;
							$exam_score = $row->exam_score;
							$score_grade = $row->score_grade;
							$remarks = $row->remarks;
							
							$class_highest = DB::table('student_total_score as a')
										->where('a.subject_id', $subject_id)
										->where('a.semester_id', $semester_id)
										->where('a.class_div_id', $section)
										->max('a.exam_score');
							
							$class_lowest = DB::table('student_total_score as a')
										->where('a.subject_id', $subject_id)
										->where('a.semester_id', $semester_id)
										->where('a.class_div_id', $section)
										->min('a.exam_score');
							
							$subject_highest = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
								->where('student_total_score.subject_id', $subject_id)
								->where('student_total_score.semester_id', $semester_id)
								->where('class_div.class_id', $class_id)
								->max('student_total_score.exam_score');
							
							$subject_lowest = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
								->where('student_total_score.subject_id', $subject_id)
								->where('student_total_score.semester_id', $semester_id)
								->where('class_div.class_id', $class_id)
								->min('student_total_score.exam_score');
							//the logic here is that to get the position, you must count the other scores after the desired score
							//e.g if total population is 20, and the score is at 10th position, that means that
							$class_position = DB::table('student_total_score as a')
										->where('a.subject_id', $subject_id)
										->where('a.semester_id', $semester_id)
										->where('a.class_div_id', $section)
										->where('a.exam_score', '>=' , $exam_score)
										->count();
							
							$subject_position = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
								->where('student_total_score.subject_id', $subject_id)
								->where('student_total_score.semester_id', $semester_id)
								->where('class_div.class_id', $class_id)
								->where('student_total_score.exam_score', '>=' , $exam_score)
								->count();
							
							$student_count =DB::table('student_total_score as a')
								->where('a.subject_id', $subject_id)
								->where('a.semester_id', $semester_id)
								->where('a.class_div_id', $section)
								->count();
								
							$this->pdf->SetFont('Arial', '', 9); //set font
						
							$this->pdf->SetX(10);
							$this->pdf->Cell(60, 5, $subject, 1, 0, 'L');
							$total_score = 0;
							
							$old_exam = "";
							foreach ($class_exams as $class_exam){
								$exam_name = $class_exam->exam_name;
								if( $old_exam !== $exam_name){
									$exam_id = $class_exam->exam_id;
									$exam_score = DB::table('exam_score as a')
											->select(DB::raw('AVG(a.exam_score) AS total_score'))
											->where('a.semester_id', '=', $semester_id)
											->where('a.subject_id', '=', $subject_id)
											->where('a.exam_id', '=', $exam_id)
											->where('a.student_id', '=', $student_id)
											->value('total_score');
									
									$weight = $class_exam->exam_weight/100;
									$max_score = $class_exam->max_score;
									
									$exam_score = ($exam_score * 100 * $weight)/$max_score;
									$total_score = $total_score + $exam_score;
									
									$this->pdf->Cell(12, 5, $exam_score, 1, 0, 'R');
								}
								$old_exam = $exam_name;
							}
							$this->pdf->SetFillColor(224,224,224);
							$this->pdf->Cell(12, 5, $total_score, 1, 0, 'R', TRUE);
							$this->pdf->SetFillColor(255,255,255);
							$this->pdf->Cell(15, 5, $class_highest, 1, 0, 'R');
							$this->pdf->Cell(15, 5, $class_lowest, 1, 0, 'R');
							$this->pdf->Cell(15, 5, $class_position, 1, 0, 'C');
							$this->pdf->Cell(15, 5, $score_grade, 1, 0, 'C');
							if($remarks == "Failure"){
								$this->pdf->SetFillColor(255,153,153);
							}else{
								$this->pdf->SetFillColor(204,255,255);
							}
							$this->pdf->Cell(20, 5, $remarks, 1, 0, 'L', TRUE);
							$this->pdf->SetFillColor(255,255,255);
							$this->pdf->Ln(5);
							$subject_count = $subject_count + 1;
							$student_total_score = $student_total_score + $total_score;
						}
					}
					
					$class_position = DB::table('student_results')
										->where('semester_id', $semester_id)
										->where('class_div_id', $section)
										->where('total_score', '>=' , $student_total_score)
										->count();
										
					$no_in_class =  DB::table('student_results')
										->where('class_div_id', $section)
										->where('semester_id', $semester_id)
										->distinct('class_div_id')
										->count();
					//$this->pdf->Ln(5);
					$this->pdf->SetX(10);
					//subject count
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(21, 5, 'Total Score:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(12, 5, $subject_count * 100, 0, 0, 'L');
					$this->pdf->SetX(41);
					
					//get attendance
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(28, 5, 'Score Obtained:', 0, 0, 'L');
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(12, 5, $student_total_score, 0, 0, 'L');
					$this->pdf->SetX(83);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(16, 5, 'Average:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$average = 0;
					if($subject_count > 0)$average = number_format($student_total_score/$subject_count, 2, '.', '');//no comma
					$this->pdf->Cell(10, 5, $average, 0, 0, 'L'); 
					$this->pdf->SetX(110);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(26, 5, 'Class Position:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(10, 5, $class_position, 0, 0, 'L');
					$this->pdf->SetX(141);
					
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(21, 5, 'No in Class:', 0, 0, 'L'); //width, height, text, next line, border, alignment
					$this->pdf->SetFont('Arial', '', 10); //set font
					$this->pdf->Cell(5, 5, $no_in_class, 0, 0, 'L');
					
					$pass_record = DB::table('semester_promotion')
								->where('semester_id', $semester_id)
								->get();
					//if overall result was defined		
					if( count($pass_record)>0){
						
						$this->pdf->SetX(167);
						$this->pdf->SetFont('Arial', 'B', 10); //set font
						$this->pdf->Cell(17, 5, 'Outcome:', 0, 0, 'L'); //width, height, text, next line, border, alignment
						if($outcome == "Passed"){
							$this->pdf->SetTextColor(51,102,0);	
						}else{
							$this->pdf->SetTextColor(194,8,8);	
						}
						$this->pdf->Cell(10, 5, $outcome, 0, 0, 'L');
						$this->pdf->SetTextColor(0,0,0);
						$this->pdf->SetFont('Arial', '', 10); //set font
						
					}
					$this->pdf->Ln(5);
					///////////////////////////Score Grade Keys here
					$grades = DB::table('exam_grade')
							->join('score_grade', 'score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
							->where('class_id', $class_id)
							->orderBy('score_from', 'ASC')
							->get();
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(20, 5, 'Score Grade Keys', 0, 0, 'L');
					$this->pdf->Ln(5);
					foreach ($grades as $grade){
						$this->pdf->Cell(20, 5, $grade->score_grade, 1, 0, 'C');
					}
					$this->pdf->Ln(5);
					foreach ($grades as $grade){
						$this->pdf->Cell(20, 5, $grade->score_from. '-'.$grade->score_to, 1, 0, 'C');
					}
					
					$this->pdf->Ln(5);
					//////////////////no list the assessment
					$assessments = DB::table('student_assessment')->get();
					//loop through the student assessment table
					foreach ($assessments as $assessment){							
						$assessment_id = $assessment->assessment_id;
						//is there one for the semester and for the student?
						$remarks = DB::table('term_student_rating')
							->where('student_id', $student_id)
							->where('semester_id', $semester_id)
							->where('assessment_id', $assessment_id)
							->value('remarks');
						
						if( !empty($remarks)){
							//if the student has assessment	
							
							//creating the headings, which is the parameter
							//`assessment_param`(`param_id`, `parameter`, `assessment_id`, `class_id`
							$params = DB::table('assessment_param')
									->where('assessment_id', $assessment_id)
									->where('class_id', $class_id)
									->orderBy('param_id', 'ASC')
									->get();
							
							//this list the paramer accross the page: vertically
							if(count($params) > 0){
								$this->pdf->SetFont('Arial', 'B', 8); //set font
								$this->pdf->Ln(5);
								$this->pdf->SetX(10);
								//put the caption first
								$this->pdf->Cell(30, 5, $assessment->assessment, 0, 0, 'L');
								
								//then below is the heading: with maximum of 5 parameter
								$this->pdf->Ln(5);
								$this->pdf->SetX(10);
								
								$col_cnt = count($params);
								if($col_cnt > 5) $col_cnt = 5;
								
								//this sets the width of the columns
								if($col_cnt == 1) $this->pdf->SetWidths(array(180));
								if($col_cnt == 2) $this->pdf->SetWidths(array(90,90));
								if($col_cnt == 3) $this->pdf->SetWidths(array(60,60,60));
								if($col_cnt == 4) $this->pdf->SetWidths(array(46,46,46,46));
								if($col_cnt == 5) $this->pdf->SetWidths(array(35,35,35,35,35));
								
								//extract the parameter column for this assessment only
								$hdngs = DB::table('assessment_param')
									->where('assessment_id', $assessment_id)
									->where('class_id', $class_id)
									->orderBy('param_id', 'ASC')
									->pluck('parameter');
								//this populates the columns with headings
								if($col_cnt == 1) $this->pdf->Row(array($hdngs[0]));
								if($col_cnt == 2) $this->pdf->Row(array($hdngs[0],$hdngs[1]));
								if($col_cnt == 3) $this->pdf->Row(array($hdngs[0],$hdngs[1],$hdngs[2]));
								if($col_cnt == 4) $this->pdf->Row(array($hdngs[0],$hdngs[1],$hdngs[2],$hdngs[3]));
								if($col_cnt == 5) $this->pdf->Row(array($hdngs[0],$hdngs[1],$hdngs[2],$hdngs[3],$hdngs[4]));
								////this populates the table cells
								$cll_vl = DB::table('term_student_rating')
									->where('student_id', $student_id)
									->where('semester_id', $semester_id)
									->where('assessment_id', $assessment_id)
									->orderBy('param_id', 'ASC')
									->pluck('remarks');
									//->pluck('word_two')->toArray();
									
								$this->pdf->SetFont('Arial', '', 8); //set font
								if($col_cnt == 1) $this->pdf->Row(array($cll_vl[0]));
								if($col_cnt == 2) $this->pdf->Row(array($cll_vl[0],$cll_vl[1]));
								if($col_cnt == 3) $this->pdf->Row(array($cll_vl[0],$cll_vl[1],$cll_vl[2]));
								if($col_cnt == 4) $this->pdf->Row(array($cll_vl[0],$cll_vl[1],$cll_vl[2],$cll_vl[3]));
								if($col_cnt == 5) $this->pdf->Row(array($cll_vl[0],$cll_vl[1],$cll_vl[2],$cll_vl[3],$cll_vl[4]));
								//move to another row and continue if more than 5
								if( count($params) > 5){
									$col_cnt = count($params);
									if($col_cnt > 10) $col_cnt = 10;
									$this->pdf->SetFont('Arial', 'B', 8); //set font
									//the gap should not be much here
									$this->pdf->Ln(2);
									$this->pdf->SetX(10);
									
									if($col_cnt == 6) $this->pdf->SetWidths(array(35));
									if($col_cnt == 7) $this->pdf->SetWidths(array(35,35));
									if($col_cnt == 8) $this->pdf->SetWidths(array(35,35,35));
									if($col_cnt == 9) $this->pdf->SetWidths(array(35,35,35,35));
									if($col_cnt == 10) $this->pdf->SetWidths(array(35,35,35,35,35));
									
									if($col_cnt == 6) $this->pdf->Row(array($hdngs[5]));
									if($col_cnt == 7) $this->pdf->Row(array($hdngs[5],$hdngs[6]));
									if($col_cnt == 8) $this->pdf->Row(array($hdngs[5],$hdngs[6],$hdngs[7]));
									if($col_cnt == 9) $this->pdf->Row(array($hdngs[5],$hdngs[6],$hdngs[7],$hdngs[8]));
									if($col_cnt == 10) $this->pdf->Row(array($hdngs[5],$hdngs[6],$hdngs[7],$hdngs[8],$hdngs[9]));
									
									$this->pdf->SetFont('Arial', '', 8); //set font
									if($col_cnt == 6) $this->pdf->Row(array($cll_vl[5]));
									if($col_cnt == 7) $this->pdf->Row(array($cll_vl[5],$cll_vl[6]));
									if($col_cnt == 8) $this->pdf->Row(array($cll_vl[5],$cll_vl[6],$cll_vl[7]));
									if($col_cnt == 9) $this->pdf->Row(array($cll_vl[5],$cll_vl[6],$cll_vl[7],$cll_vl[8]));
									if($col_cnt == 10) $this->pdf->Row(array($cll_vl[5],$cll_vl[6],$cll_vl[7],$cll_vl[8],$cll_vl[9]));
									
								}
							}
						}
					}
					
					/*$txt="FPDF is a PHP class which allows to generate PDF files with pure PHP, that is to say ".
						"without using the PDFlib library. F from FPDF stands for Free: you may use it for any ".
						"kind of usage and modify it to suit your needs.\n\n";
					for($i=0;$i<25;$i++) 
						$pdf->MultiCell(0,5,$txt,0,'J');*/
					
					//NO NEED FOR TEACHERS COMMENT: It should be put as an assessment
					//next is the treachers comment: this may have been included in the assessment
					/*if( $comment !== NULL && $comment !== ""){
						//$this->pdf->Ln(5);
						$y = $this->pdf->GetY();
						$this->pdf->SetY($y + 15); //create enough gap between the assessment and this area
						$this->pdf->SetX(10);
						$this->pdf->SetFont('Arial', 'B', 10); //set font
						$this->pdf->Cell(40, 5, "Teacher's Comment: ", 0, 0, 'L');
						
						$this->pdf->SetFont('Arial', '', 10); //set font
						$this->pdf->Cell(150, 5, $comment, 0, 0, 'L');
					}*/
					
					$path = storage_path('reports/pdf/results').'/'.$pdf_file;
					$this->pdf->Output($path, 'F');
					
					$destination = $this->public_folder.'/reports/pdf/results/'.$pdf_file;
					\File::copy($path,$destination);
					
					return $pdf_file;
					
					//send email of the file here
				} catch (Exception $e) {
					$this->report_error($e, 'Exams', 'Semester-Result', 'Pdf');
				}
			}
		}
	}
	public function pdfAssessmentResult(Request $request){
		
		$reg_no = $request->reg_no;
		$semester_id = $request->semester_id;
		$semester_start = SetController::semester_start($semester_id);
		$semester_end = SetController::semester_end($semester_id);
		$semester_name = SetController::semester_name($semester_id);
		$semester_next = SetController::semester_next($semester_id); //this gets the next semester start date
		$student_id = StudentController::getStudentID($reg_no);
		//create a file
		$pdf_file = $semester_name.'_'.$reg_no.'_assessment_result.pdf';
		$line = DB::table('students')->where('student_id', $student_id)->first();
		$photo = $line->photo;
		$class_name = StudentController::getStudentClassName($student_id);
		$section = StudentController::getStudentClass($student_id);
		$class_id = StudentController::getStudentLevel($student_id);
		try{		
			$this->pdf->AddPage('L');
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 14); //set font
			$this->pdf->Cell(200, 5, 'TERM ASSESSMENT', 0, 0, 'C');
			$this->pdf->Ln(10);
			//image, x, y, widht, height, image type
			if(!empty($photo)) $this->pdf->Image(storage_path('app/photo/student/'.$photo),185, 50, 15, 15, ''); 
			
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->SetX(10);
			$this->pdf->Cell(20, 5, 'Reg. No:', 0, 0, 'L'); //width, height, text, next line, border, alignment
			$this->pdf->SetFont('Arial', '', 10); //set font
			$this->pdf->Cell(10, 5, $line->reg_no, 0, 0, 'L');
			$this->pdf->SetX(70);
			
			$name = $line->last_name.', '.$line->first_name;
			if( $line->other_name !== NULL && $line->other_name !== "-"){
				$name = $name.' '.$line->other_name;
			}
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(20, 5, 'Name:', 0, 0, 'L');
			$this->pdf->SetFont('Arial', '', 10); //set font
			$this->pdf->Cell(50, 5, $name, 0, 0, 'L');
			
			$this->pdf->SetX(135);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(20, 5, 'Class:', 0, 0, 'L'); //width, height, text, next line, border, alignment
			$this->pdf->SetFont('Arial', '', 10); //set font
			$this->pdf->Cell(25, 5, $class_name, 0, 0, 'L');
			/////////////////////////////
			$this->pdf->Ln(5);
			$this->pdf->SetX(10);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(20, 5, 'Term:', 0, 0, 'L'); //width, height, text, next line, border, alignment
			$this->pdf->SetFont('Arial', '', 10); //set font
			$this->pdf->Cell(10, 5, $semester_name, 0, 0, 'L');
			$this->pdf->SetX(70);
			
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(20, 5, 'Date:', 0, 0, 'L'); //width, height, text, next line, border, alignment
			$this->pdf->SetFont('Arial', '', 10); //set font
			$this->pdf->Cell(25, 5, date('d/m/Y'), 0, 0, 'L');
			$this->pdf->SetX(70);
			
			$assessments = DB::table('student_assessment')->get();
			foreach ($assessments as $assessment){							
				$assessment_id = $assessment->assessment_id;
				//is there one for the semester and for the student?
				$remarks = DB::table('term_student_rating')
					->where('student_id', $student_id)
					->where('semester_id', $semester_id)
					->where('assessment_id', $assessment_id)
					->value('remarks');
				
				if( !empty($remarks)){
					
					//creating the headings, which is the parameter
					//`assessment_param`(`param_id`, `parameter`, `assessment_id`, `class_id`
					$params = DB::table('assessment_param')
							->where('assessment_id', $assessment_id)
							->where('class_id', $class_id)
							->orderBy('param_id', 'ASC')
							->get();
					//this list the paramer accross the page: vertically
					if(count($params) > 0){
						$this->pdf->SetFont('Arial', 'B', 9); //set font
						$this->pdf->Ln(10);
						$this->pdf->SetX(10);
						$this->pdf->Cell(30, 5, $assessment->assessment, 0, 0, 'L');
						//the above is the caption
						//then below is the heading: with maximum of 5 parameter
						$this->pdf->Ln(5);
						$this->pdf->SetX(10);
					
						foreach ($params as $param){
							$this->pdf->Cell(40, 5, $param->parameter, 1, 0, 'L');
						}
						//now get content/variables for each parametr in similar manner
						$this->pdf->Ln(5);
						$this->pdf->SetX(10);
						$this->pdf->SetFont('Arial', '', 9); //set font
						
						$remarks = DB::table('term_student_rating')
							->where('student_id', $student_id)
							->where('semester_id', $semester_id)
							->where('assessment_id', $assessment_id)
							->orderBy('param_id', 'ASC')
							->get();
							
						foreach ($remarks as $remark){
							$this->pdf->Cell(40, 5, $remark->remarks, 1, 0, 'L');
						}
					}
				}
			}
			$path = storage_path('reports/pdf/results').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/results/'.$pdf_file;
			\File::copy($path,$destination);
			
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Exams', 'Semester-Assessment', 'Pdf');
		}
	}
	//this prints students score to the screen
	public function prtStudentScore(Request $request){
		
		$semester_id = $request->semester_id;
		$class_div_id = $request->class_div_id;
		try{
			$rows = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
				->join('students', 'students.student_id', '=', 'student_total_score.student_id')
				->select('reg_no', 'last_name', 'first_name', 'class_div', 
					'student_total_score.student_id AS student_reg')
				->where('student_total_score.class_div_id', $class_div_id)
				->where('student_total_score.semester_id', $semester_id)
				->distinct('student_total_score.student_id')
				->orderBy('student_total_score.student_id', 'ASC')
				->get();
			$records = array();
			$old_student = "";
			foreach ($rows as $row){
				
				$record = array();
				$student_id = $row->student_reg;
				if($old_student !== $student_id){
					$record['reg_no'] = $row->reg_no;
					$record['last_name'] = $row->last_name;
					$record['first_name'] = $row->first_name;
					//$record['class_div'] = $row->class_div;
					//get the number of subjects for this student
					$no_subject = DB::table('student_total_score as a')
								->select(DB::raw('COUNT(a.student_id) AS exams'))
								->where('a.student_id', $student_id)
								->where('a.semester_id', $semester_id)
								->where('a.class_div_id', $class_div_id)
								->value('exams');	
					$total_score = DB::table('student_total_score as a')
								->select(DB::raw('SUM(a.exam_score) AS scores'))
								->where('a.student_id', $student_id)
								->where('a.semester_id', $semester_id)
								->where('a.class_div_id', $class_div_id)
								->value('scores');
					
					$record['no_subject'] = $no_subject;
					$record['score_total'] = $no_subject * 100;
					//get the total score for this student
					$record['score_obtained'] = $total_score;
					$record['average_score'] = number_format($total_score/$no_subject, 2, '.', ',');
					//get the subject passed
					$subject_f = StudentTotalScore::join('exam_grade', 'exam_grade.exam_grade_id', '=', 
									'student_total_score.exam_grade_id')
								->join('score_grade', 'score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
								->where('student_total_score.semester_id', $semester_id)
								->where('student_total_score.student_id', $student_id)
								->where('exam_grade.remarks', 'Failure')
								->get();
					$subject_f = count($subject_f);
					//get students total score for the semester
					$student_total_score = DB::table('student_results')
										->where('student_id', $student_id)
										->where('semester_id', $semester_id)
										->where('class_div_id', $class_div_id)
										->value('total_score');
					//now get his position in the class
					$class_position = DB::table('student_results')
										->where('semester_id', $semester_id)
										->where('class_div_id', $class_div_id)
										->where('total_score', '>=' , $student_total_score)
										->count();
									
					$record['subject_failed'] = $subject_f;
					$record['subject_passed'] = $no_subject - $subject_f;
					$record['class_position'] = $class_position;
					
					$old_student = $student_id;
					array_push($records, $record);
				}
			}
			return $records;
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Print-Score', 'Get');}
		
	}
	public function getStudentResult(Request $request){
		$semester_id = $request->semester_id;
		$reg_no = $request->reg_no;
		$records = array();
		
		try{
			$rows = DB::table('student_total_score as a')
				->join('class_div as b','b.class_div_id', '=', 'a.class_div_id')
				->join('subjects as c', 'c.subject_id', '=', 'a.subject_id')
				->join('students as d', 'd.student_id', '=', 'a.student_id')
				->join('exam_grade as e', 'e.exam_grade_id', '=', 'a.exam_grade_id')
				->join('score_grade as f', 'f.score_grade_id', '=', 'e.score_grade_id')
				->select('a.class_div_id AS class_div_id', 'b.class_id', 
					'a.subject_id AS subject_id','e.remarks AS remarks', 'f.score_grade', 'a.exam_score','c.subject')
				->where('a.semester_id', $semester_id)
				->where('a.exam_score', '>', 0)
				->where('d.reg_no', $reg_no)
				->orderBy('c.subject', 'ASC')
				->get();
				
			foreach ($rows as $row){
				//get each subject
				$record = array();
				$subject = $row->subject;
				$subject_id = $row->subject_id;
				$exam_score = $row->exam_score;
				$score_grade = $row->score_grade;
				$remarks = $row->remarks;
				$class = $row->class_id;
				$section = $row->class_div_id;
				
				$class_highest = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
					->where('student_total_score.subject_id', $subject_id)
					->where('student_total_score.semester_id', $semester_id)
					->where('class_div.class_div_id', $section)
					->max('exam_score');
					
				$class_lowest = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
					->where('student_total_score.subject_id', $subject_id)
					->where('student_total_score.semester_id', $semester_id)
					->where('class_div.class_div_id', $section)
					->min('exam_score');
					
				$subject_highest = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
					->where('student_total_score.subject_id', $subject_id)
					->where('student_total_score.semester_id', $semester_id)
					->where('class_div.class_id', $class)
					->max('exam_score');
					
				$subject_lowest = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
					->where('student_total_score.subject_id', $subject_id)
					->where('student_total_score.semester_id', $semester_id)
					->where('class_div.class_id', $class)
					->min('exam_score');
				//the logic here is that to get the position, you must count the other scores after the desired score
				//e.g if total population is 20, and the score is at 10th position, that means that
				$class_position = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
					->where('student_total_score.subject_id', $subject_id)
					->where('student_total_score.semester_id', $semester_id)
					->where('class_div.class_div_id', $section)
					->where('student_total_score.exam_score', '>=' , $exam_score)
					->count();
					
				$subject_position = StudentTotalScore::join('class_div','class_div.class_div_id', '=', 'student_total_score.class_div_id')
					->where('student_total_score.subject_id', $subject_id)
					->where('student_total_score.semester_id', $semester_id)
					->where('class_div.class_id', $class)
					->where('student_total_score.exam_score', '>=' , $exam_score)
					->count();
				
				$record['subject'] = $subject;
				$record['exam_score'] = $exam_score;
				$record['score_grade'] = $score_grade;
				$record['remarks'] = $remarks;
				$record['subject_position'] = $subject_position;
				$record['class_position'] = $class_position;
				$record['class_highest'] = $class_highest;
				$record['class_lowest'] = $class_lowest;
				$record['subject_highest'] = $subject_highest;
				$record['subject_lowest'] = $subject_lowest;
				array_push($records, $record);
			}
			return $records;
		}catch (\Exception $e) {$this->report_error($e, 'Exams', 'Result', 'Get');}
	}
	//this has now being changed to determine those who passed: promoted and those who failed per term:
	//for ptomotion, just indicate the term to be used and those who passed will come up
	//also this should show on terminal result. Hence, check the promotion table to pick this
	public function semesterPromotion(Request $request){
		//this should generate promotion based on the result for the specified semester, according to the processing above
		$c = count($request->class_id);
		if($request->ajax() && $c > 0){
			$logRec = array();
			$operator = $request->operator;
			$promotion_date = $request->promotion_date;
			$semester_id = $request->semester_id1;
			
			try{
				for($i=0;$i<$c;$i++){
					//update attendance table class
					$class_id = $request->class_id[$i];
					$passed_subject = $request->subjects[$i]; 
					$cutoff_score = $request->score[$i]; 
					$cutoff_average = $request->average[$i]; 
					//first update the promotion table with the promotion parameters
					//check if promotion was earlier created, if yes, then delete it
					$existing =  ExamPromotion::where('class_id', $class_id)
								->where('semester_id', $semester_id)
								->get();
								
					if( count($existing) > 0){
						DB::table('exam_promotion')
							->where('class_id', $class_id)
							->where('semester_id', $semester_id)
							->delete();
					}
					//now update
					$promoRec = ExamPromotion::create(
							array(
							'passed_subject' => $passed_subject,
							'passed_score' => $cutoff_score,
							'average_score' => $cutoff_average,
							'class_id' => $class_id,
							'promotion_date' => $promotion_date, 
							'semester_id' => $semester_id,
							'operator' => $operator
						)
					);
					//then update student promotion table with those promoted by iterating through the total score table
					$rows = DB::table('student_total_score as a')
							->join('class_div as b','b.class_div_id', '=', 'a.class_div_id')
							->join('exam_grade as c', 'c.exam_grade_id', '=', 'a.exam_grade_id')
							->join('score_grade as d', 'd.score_grade_id', '=', 'c.score_grade_id')
							->select('a.class_div_id AS class_div_id', 'b.class_id',
								'exam_score', 'a.student_id AS student_id')
							->where('a.semester_id', $semester_id)
							->where('b.class_id',$class_id)
							->distinct()
							->get(['a.student_id']);
						
					foreach ($rows as $row){
						$student_id = $row->student_id;
						$class_div_id = $row->class_div_id;
						$promotion_id = $promoRec->promotion_id;
						
						//maintain just one record
						$existing =  DB::table('semester_promotion')
									->where('student_id', $student_id)
									->where('class_div_id', $class_div_id)
									->where('semester_id', $semester_id)
									->get();
						//delete any existing record
						if( count($existing) > 0){
							DB::table('semester_promotion')
								->where('student_id', $student_id)
								->where('class_div_id', $class_div_id)
								->where('semester_id', $semester_id)
								->delete();
						}
						//get total score
						$total_score = DB::table('student_total_score')
									->where('student_id', $student_id)
									->where('semester_id', $semester_id)
									->sum('exam_score');
						//get average score
						$average_score = DB::table('student_total_score')
									->where('student_id', $student_id)
									->where('semester_id', $semester_id)
									->average('exam_score');
						//get subjects passed
						$no_subject = DB::table('student_total_score')
								->join('exam_grade', 'exam_grade.exam_grade_id', '=', 'student_total_score.exam_grade_id')
								->join('score_grade', 'score_grade.score_grade_id', '=', 'exam_grade.score_grade_id')
								->where('student_total_score.semester_id', $semester_id)
								->where('student_total_score.student_id', $student_id)
								->where('exam_grade.remarks', '!=', 'Failure')    
								->get();
								//oR $query->where('delivered', '<>', 1)->where('invoiced', 1);
								
						//determine which one is defined: subject count, total score OR average score
						if( (count($no_subject) >= $passed_subject && $passed_subject > 0) || 
								($total_score >= $cutoff_score && $cutoff_score > 0) || 
								($average_score >= $cutoff_average && $cutoff_average > 0) ){
							
							$logRec = SemesterPromotion::create(
								array(
									'promotion_id' => $promotion_id,
									'student_id' => $student_id,
									'total_score' => $total_score,
									'average_score' => $average_score,
									'semester_id' => $semester_id,
									'subject_passed' => count($no_subject), 
									'class_div_id' => $class_div_id,
									'operator' => $operator
								)
							);
						}
					}
				}
			} catch (\Exception $e) {
				$this->report_error($e, 'Exams', 'Semester-Promotion', 'Update');
			}
			return $logRec;
		}
	}
	//this only list the passed students as the logic has been changed from prmoted to passed
	public function getPromotedStudents(Request $request){
		$semester_id = $request->semester_id;
		$class_id = $request->class_id;
		//file_put_contents('file_error.txt', $semester_id. PHP_EOL, FILE_APPEND);
		//file_put_contents('file_error.txt', $class_id. PHP_EOL, FILE_APPEND);
		//determine if it is the last term in the academic calendar as the last holiday in the semester should
		//have its last date equal to the academic calendar last date
		$rows = DB::table('semester_promotion')
				->join('class_div','class_div.class_div_id', '=', 'semester_promotion.class_div_id')
				->join('students', 'students.student_id', '=', 'semester_promotion.student_id')
				->join('exam_promotion', 'exam_promotion.promotion_id', '=', 'semester_promotion.promotion_id')
				->select('class_div', 'reg_no', 'first_name', 'last_name', 'total_score', 'subject_passed', 
					'semester_promotion.student_id AS student_id','class_div.class_div_id AS class_div_id')
				->where('semester_promotion.semester_id', $semester_id)
				->where('class_div.class_id',$class_id)
				->orderBy('class_div', 'ASC')
				->get();
		//file_put_contents('file_error.txt', count($rows). PHP_EOL, FILE_APPEND);
		return $rows;
	}
	public function semesterTeacher(Request $request){
		$logRec = array();
		$semester_id = $request->semester_id3;
		$class_div_id = $request->class_div_id;
		$operator = $request->operator;
		
		$c = count($request->reg_no);
		if($request->ajax() && $c > 0){
			try{
				for($i=0;$i<$c;$i++){
					//update the student report card with teachers comments
					$student_id = StudentController::getStudentID($request->reg_no[$i]);
					$comment = $request->remarks[$i];
					//delete any previous comment
					DB::table('teachers_comment')
						->where('semester_id', $semester_id)
						->where('student_id', $student_id)
						->delete();
					
					//update the transfer table
					$logRec = TeachersComment::create(
							array(
							'semester_id' => $semester_id,
							'student_id' => $student_id,
							'class_div_id' => $class_div_id,
							'comment' => $comment,
							'operator' => $operator
						)
					);
				}
				
			} catch (\Exception $e) {
				$this->report_error($e, 'Exams', 'Semester-Distribution', 'Update');
			}
		}
		return $logRec;
	}
	public function semesterDistribution(Request $request){
		$logRec = array();
		$operator = $request->operator;
		$distribute_date = $request->distribute_date;
		$semester_id = $request->semester_id6;
		
		$c = count($request->student_id);
		//you can only process promotion at the end of the term: check the last term in the academic year
		//get the academic_id for the selected semester
		$academic_id = DB::table('semester')->where('semester_id',$semester_id)->value('academic_id');
		//get the last semester defined for that academic_id 
		$semester_record = DB::table('semester')
							->where('academic_id',$academic_id)
							->orderBy('date_from', 'DESC')
							->first();
		$last_semester_id = $semester_record->semester_id;
		//process if it is the last semester in the academic year
		if($last_semester_id == $semester_id){
			if($request->ajax() && $c > 0){
				try{
					//iterate over all the records in the table
					for($i=0;$i<$c;$i++){
						//update the enrolment table as that contains the latest information on student class
						//reset the current enrolment class for the student being processed
						StudentEnrol::where('student_id', $request->student_id[$i])->update(array('active' => "0"));
						//now change the class, as specified, for the student
						$item = new StudentEnrol();
						$item->class_id = $request->new_class[$i]; 
						$item->student_id = $request->student_id[$i]; 
						$item->active = "1";
						$item->enrol_date = $distribute_date;
						$item->operator = $operator;
						$item->save();
						//update the student promotion table: now called movement technically
						$logRec = StudentPromotion::create(
								array(
								'class_from' => MasterController::getClassSectionID($request->old_class[$i]),      //get class ID
								'class_to' => $request->new_class[$i],
								'remarks' => 'Promotion',
								'student_id' => $request->student_id[$i],
								'promotion_date' => $distribute_date,
								'operator' => $operator
							)
						);
					}
				} catch (\Exception $e) {
					$this->report_error($e, 'Exams', 'Promotion', 'Update');
				}
			}
		}
		return $logRec;
	}
	public function report_error($e, $module, $form, $task){
		file_put_contents('file_error.txt', $e->getMessage(). '\n'. $module. '-'. $form. '-'. $task. PHP_EOL, FILE_APPEND);
		//Log::useFiles(storage_path().'/laravel.log');
		Log::info($e->getMessage());
	}
	public function toDbaseDate($value){
		//return STR_TO_DATE($value, '%d/%m/%Y');
		$_date = str_replace('/', '-', $value);
		$_date = date('Y-m-d', strtotime($_date));
		return $_date;
		//return DATE_FORMAT(STR_TO_DATE($value, '%d/%m/%Y'), '%Y-%m-%d');
	}
	function GenerateWord()
	{
		//Get a random word
		$nb=rand(3,10);
		$w='';
		for($i=1;$i<=$nb;$i++)
			$w.=chr(rand(ord('a'),ord('z')));
		return $w;
	}
	
	function GenerateSentence()
	{
		//Get a random sentence
		$nb=rand(1,10);
		$s='';
		for($i=1;$i<=$nb;$i++)
			$s.= $this->GenerateWord().' ';
		return substr($s,0,-1);
	}
	/*
	$pdf=new PDF_MC_Table();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
	//Table with 20 rows and 4 columns
	$pdf->SetWidths(array(30,50,30,40));
	srand(microtime()*1000000);
	for($i=0;$i<20;$i++)
		$pdf->Row(array(GenerateSentence(),GenerateSentence(),GenerateSentence(),GenerateSentence()));*/
	
}
?>