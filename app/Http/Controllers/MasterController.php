<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\models\SchTimetable; //sch_timetable
use App\models\ClassTimetable;//class_timetable
use App\models\ClassSubject;//class_subject
use App\models\SchClass; //sch_classes
use App\models\ClassDiv;
use App\models\Syllabus;//class_syllabus
use App\models\ClassSyllabus;//class_syllabus
use App\models\School;
use App\models\Subject;
use App\models\Institution;
use Log; //the default Log file
use DB; //use the default Database
use Excel;
use Auth;

class MasterController extends Controller
{
    protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function showSchool(){
		return view('master.school');	
	}
	public function infoSchool(Request $request){
		
		$records =  School::orderBy('sequence', 'ASC')->get();
		return view('master.infoschool', compact('records'));
	}
	public function updateSchool(Request $request){
		//if institute id is blank, then it is a new record, else edit
		if($request->ajax()){
			$logRec = array();
			try{
				$school_id = $request->school_id;
				if(empty($school_id)){
					//check if school name exists, since it is new
					$sch_name = DB::table('schools')->where('school_name',$request->school_name)->value('school_name');
					if(empty($sch_name)) $logRec = School::create($request->all());
				}else{
					$logRec = School::updateOrCreate(['school_id'=>$request->school_id], $request->all());
				}	
				
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Master', 'School', 'Update');
			}
		}
	}
	
	public function editSchool(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = School::where('school_id', '=', $request->school_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Master', 'School', 'Edit');
			}
		}	
	}
	public function delSchool(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('schools')->where('school_id',$request->school_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('schools')->where('school_id',$request->school_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Master', 'School', 'Delete', $request->school_id, $request->school_id, 
						'-');
				}
				return $logRec;
			}
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Master', 'School', 'Delete');
		}
	}
	//////////////PDF generate
	
	public function pdfSchool(){
		//get the htmlToPTDF blade well designed in the normal blade with php code
		
		try{		
			//A4: 210 × 297 millimeters
			$pdf_file = 'schools.pdf';
			$this->pdf->AddPage();
			//////////////////////////////////////////////////////////////END OF HEADING
			//$pdf = new PDF();
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'SCHOOLS', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records =  School::orderBy('sequence', 'ASC')->get();
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(20, 10, 'Sequence', 0, 0, 'L');
			$this->pdf->Cell(100, 10, 'School Name', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'Location', 0, 0, 'L');
			$this->pdf->Ln(5);
				
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(20, 10, $record->sequence, 0, 0, 'L');
				$this->pdf->Cell(100, 10, $record->school_name, 0, 0, 'L');
				$this->pdf->Cell(25, 10, $record->address, 0, 0, 'L');
				$this->pdf->Ln(5);
			}
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Master', 'School', 'Pdf');
			return redirect()->back();
		}
	}
	public function excelSchool(){
		$records =  School::select('school_id','school_name','address','sequence', 'operator', 'reviewer', 'created_at')
					->orderBy('sequence', 'ASC')->get();
		
		//$sheet->fromArray($data);
		$csv = $records;  // stored the data in a array
		
		return Excel::create('school-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('school_id','school_name','address','sequence', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
			
		})->save('xlsx', $this->public_folder.'/reports/excel', true);
	}
	////////////////////////////////////////////////////////////////////////////SCHEDULE
	public function showSchedule(){
		//get the schools and populate the dropdown in the timetable
		$records =  School::orderBy('sequence', 'DESC')->get();
		return view('master.schedule', compact('records'));
	}
	public function infoSchedule(Request $request){
		
		$records = SchTimetable::join('schools', 'schools.school_id', '=', 'sch_timetable.school_id')
					->select('timetable_id','school_name', 'period_name', 'time_from', 'time_to')
					->orderBy('timetable_id', 'ASC')
					->get();
		return view('master.infoschedule', compact('records'));
	}
	public function updateSchedule(Request $request){
		//if institute id is blank, then it is a new record, else edit
		if($request->ajax()){
			$logRec = array();
			
			try{
				$timetable_id = $request->timetable_id;
				if($timetable_id === NULL || $timetable_id ==""){
					$logRec = SchTimetable::create($request->all());
					
				}else{
					$logRec = SchTimetable::updateOrCreate(
							['timetable_id'=>$request->timetable_id], $request->all()
					);
				}
				
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Schedule', 'Update');
			}
		}
	}
	
	public function editSchedule(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = SchTimetable::where('timetable_id', '=', $request->timetable_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Schedule', 'Edit');
			}
		}	
	}
	public function delSchedule(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('sch_timetable')->where('timetable_id',$request->timetable_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('sch_timetable')->where('timetable_id',$request->timetable_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Master', 'Timetable', 'Delete', $request->timetable_id, $request->timetable_id, 
						'-');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Master', 'Schedule', 'Delete');
		}
	}
	//////////////PDF generate
	
	public function pdfSchedule(){
		
		try{ //A4: 210 × 297 millimeters
			$pdf_file = 'schools.pdf';
			$this->pdf->AddPage();
			//////////////////////////////////////////////////////////////END OF HEADING
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'SCHOOL PERIODS', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records = SchTimetable::join('schools', 'schools.school_id', '=', 'sch_timetable.school_id')
				->select('timetable_id','school_name', 'period_name', 'time_from', 'time_to')
				->orderBy('timetable_id', 'ASC')
				->get();
				
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(75, 10, 'School Name', 0, 0, 'L');
			$this->pdf->Cell(75, 10, 'Period Name', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'Time From', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'Time To', 0, 0, 'L');
			$this->pdf->Ln(5);
				
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(75, 10, $record->school_name, 0, 0, 'L');
				$this->pdf->Cell(75, 10, $record->period_name, 0, 0, 'L');
				$this->pdf->Cell(25, 10, $record->time_from, 0, 0, 'L');
				$this->pdf->Cell(25, 10, $record->time_to, 0, 0, 'L');
				$this->pdf->Ln(5);
			}
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Master', 'Schedule', 'Pdf');
			return redirect()->back();
		}
	}
	public function excelSchedule(){
		$records = SchTimetable::join('schools', 'schools.school_id', '=', 'sch_timetable.school_id')
					->select('timetable_id','school_name', 'period_name', 'time_from', 'time_to', 
						'sch_timetable.operator', 'sch_timetable.reviewer', 'sch_timetable.created_at')
					->orderBy('timetable_id', 'ASC')
					->get();
					
		//$sheet->fromArray($data);
		$csv = $records;  // stored the data in a array
		
		return Excel::create('sch-timetable-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('timetable_id','school_name', 'period_name', 'time_from', 'time_to', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
			
		})->save('xlsx', $this->public_folder.'/reports/excel', true);
	}
	
	//////////////////////////////////////////////////////SUBJECT
	public function showSubject(){
		return view('master.subject');	
	}
	public function infoSubject(Request $request){
		
		$records =  Subject::orderBy('subject', 'ASC')->get();
		return view('master.infosubject', compact('records'));
	}
	public function updateSubject(Request $request){
		//if institute id is blank, then it is a new record, else edit
		if($request->ajax()){
			$logRec = array();
			try{
				$subject_id = $request->subject_id;
				
				if(empty($subject_id)){
					$subject_name = DB::table('subjects')->where('subject',$request->subject)->value('subject');
					if(empty($subject_name)) $logRec = Subject::create($request->all());
					
				}else{
					$logRec = Subject::updateOrCreate(
							['subject_id'=>$request->subject_id], $request->all()
					);
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Subject', 'Update');
			}
		}
	}
	public static function getSubjectID($subject_name){
		$subject_id = Subject::where('subject', '=', $subject_name)->value('subject_id');
		return $subject_id;
	}
	public function editSubject(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = Subject::where('subject_id', '=', $request->subject_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Subject', 'Edit');
			}
		}	
	}
	public function delSubject(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('subjects')->where('subject_id',$request->subject_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('subjects')->where('subject_id',$request->subject_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Master', 'Subject', 'Delete', $request->subject_id, $request->subject_id, 
						'-');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Master', 'Subject', 'Delete');
		}
	}
	//////////////PDF generate
	
	public function pdfSubject(){
		
		try{ //A4: 210 × 297 millimeters
			$pdf_file = 'subjects.pdf';
			$this->pdf->AddPage();
			//////////////////////////////////////////////////////////////END OF HEADING
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'SUBJECTS', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records =  Subject::orderBy('subject', 'ASC')->get();
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(20, 5, 'S/N', 1, 0, 'L');
			$this->pdf->Cell(75, 5, 'Subject Name', 1, 0, 'L');
			$this->pdf->Cell(100, 5, 'Short Name', 1, 0, 'L');
			$this->pdf->Ln(5);
			$serial = 1;
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(20, 5, $serial, 1, 0, 'L');
				$this->pdf->Cell(75, 5, $record->subject, 1, 0, 'L');
				$this->pdf->Cell(100, 5, $record->short_name, 1, 0, 'L');
				$this->pdf->Ln(5);
				$serial = $serial + 1;
			}
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Master', 'Subject', 'Pdf');
			return redirect()->back();
		}
	}
	public function excelSubject(){
		$records =  Subject::select('subject_id','subject','short_name', 'operator', 'reviewer', 'created_at')
					->orderBy('subject', 'ASC')->get();
		
		//$sheet->fromArray($data);
		$csv = $records;  // stored the data in a array
		
		return Excel::create('subject-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('subject_id','subject','short_name', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
			
		})->save('xlsx', $this->public_folder.'/reports/excel', true);
	}
	////////////////////////////////////////GET CLASS/SECTION SUBJECTS based on the syllabus table
	
	public function getClassSubject(Request $request){
		//extract all the subjects associated with this class in the syllabus table
		//since subjects are attached to sections in the syllabus table
		//iterate through the class_div table to search the class_id and bring out the related class_div_id
		//to be used in searching in the syllabus table
		$class_id = $request->class_id;
		$record = Syllabus::join('sch_classes', 'sch_classes.class_id', '=', 'syllabus.class_id')
					->join('subjects', 'subjects.subject_id', '=', 'syllabus.subject_id')
					->select('syllabus.class_id AS class_id','syllabus.subject_id AS subject_id',
						'class_name', 'subject')
					->where('syllabus.class_id', '=', $class_id)
					->get();
					
		return $record;
	}
	public function getSectionSubject(Request $request){
		//extract all the subjects associated with this section in the syllabus table
		$class_div_id = $request->class_div_id;
		//display all the details about the class and the subject
		//note that subject is defined at the syllabus table and NOT at the class_syllabus table
		//hence that should be the route here
		$record = Syllabus::join('sch_classes', 'sch_classes.class_id', '=', 'syllabus.class_id')
					->join('subjects', 'subjects.subject_id', '=', 'syllabus.subject_id')
					->join('class_syllabus', 'class_syllabus.syllabus_id', '=', 'syllabus.syllabus_id')
					->join('class_div', 'class_div.class_div_id', '=', 'class_syllabus.class_div_id')
					->select('syllabus.class_id AS class_id','syllabus.subject_id AS subject_id',
						'class_div.class_div_id AS class_div_id', 'class_div', 'subject')
					->where('class_syllabus.class_div_id', '=', $class_div_id)
					->get();
		return $record;
	}
	
	///////////////////////////////////////////////////////CLASS
	public function showSchClass(){
		$records =  School::orderBy('sequence', 'DESC')->get();
		return view('master.sch_class', compact('records'));
	}
	public function infoSchClass(Request $request){
		
		$records = SchClass::join('schools', 'schools.school_id', '=', 'sch_classes.school_id')
					->select('class_id', 'school_name','class_name','description', 'capacity', 
						'sch_classes.sequence AS order', 'div_type', 'div_no')
					->orderBy('sch_classes.sequence', 'ASC')
					->get();
		return view('master.infosch_class', compact('records'));
	}
	public function listSchClass(){
		$records =  SchClass::orderBy('sequence', 'ASC')->get();
		return $records;
	}
	public function getClassSection(Request $request){
		$class_id = $request->class_id;
		
		$record = ClassDiv::select('class_div_id', 'class_div')
			->where('class_id', '=', $class_id)
			->orderBy('class_div', 'ASC')
			->get();
			
		return $record;
	}
	public static function getClassSectionID($section_name){
		
		$record = DB::table('class_div')->where('class_div', '=', $section_name)->value('class_div_id');
		
		return $record;
	}
	public static function getClassID($class_div_id){
		
		$class_id = DB::table('class_div')->where('class_div_id', '=', $class_div_id)->value('class_id');
		
		return $class_id;
	}
	public function getClassSequence(Request $request){
		$class_id = $request->class_id;
		
		$record = SchClass::where('class_id', '=', $class_id)
				->value('sequence');
			
		return $record;
	}
	public function updateSchClass(Request $request){
		//creating classes goes concurrently with the sections as the various parameters have toe be defined at this point
		$c = count($request->sections);
		
		if($request->ajax() && $c > 0){
			$logRec = array();
			$operator = "";
			try{
				$class_id = $request->class_id;
				//if not an EDIT operation: class_id is empty if it is a NEW operation
				if(empty($class_id)){
					//if it is new, then check whether the class name exist as duplicates will not be allowed
					$class_name = DB::table('sch_classes')->where('class_name',$request->class_name)->value('class_name');
					//if the class name does not exist, then proceed
					if(empty($class_name)){
						$logRec = SchClass::create($request->all());
						
						for($i=0;$i<$c;$i++){
							//create the sections for the class
							$item = new ClassDiv();
							$item->description = $request->sections[$i];
							$item->class_div = $request->sections[$i]; //the same info in class div and description fields
							$item->class_id = $logRec->class_id;
							$item->operator = $logRec->operator;
							$item->save();
						}
					}
				}else{
					//if the class_id is found then it is an EDIT operation, then just change the class name and other details
					$logRec = SchClass::updateOrCreate(['class_id'=>$request->class_id], $request->all());
					//extract all the ids for the sections havning the class_id in the class_div table
					//note than this also counts the number of sections that was previously defined
					$divs = ClassDiv::where('class_id', '=', $class_id)
									->orderBy('class_div_id', 'ASC')
									->get();
					$j = count($divs);
					$i = 0;
					//go through each of the sections one by one and change the name to the new one: NO addition
					foreach ($divs as $div){
						//it should not update more than previously defined
						if($i <= $j){
							$div_id = $div->class_div_id;
							//change the name for this id
							ClassDiv::where('class_div_id', $div_id)->update(array('class_div' => $request->sections[$i]));
						}
						$i++;
					}
					//if there are more sections than previously defined, then create it
					//$j is the number of records in the table while $c is the number of records in the form
					if( $c > $j){
						for($i=$j;$i<$c;$i++){
							 
							$item = new ClassDiv();
							$item->description = $request->sections[$i];
							$item->class_div = $request->sections[$i]; //the same info in class div and description fields
							$item->class_id = $request->class_id;
							$item->operator = $request->operator;
							$item->save();
						}
					}
				}
				return $logRec;	
			} catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Class', 'Update');
			}
		}
	}
	
	public function editSchClass(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = SchClass::join('schools', 'schools.school_id', '=', 'sch_classes.school_id')
					->select('class_id','sch_classes.school_id AS school','class_name','description', 
						'capacity', 'sch_classes.sequence AS order', 'div_type', 'div_no')
					->where('class_id', '=', $request->class_id)
					->orderBy('sch_classes.sequence', 'ASC')
					->get();
					
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Class', 'Edit');
			}
		}	
	}
	public function editSchClassDiv(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = ClassDiv::where('class_id', '=', $request->class_id)
					->orderBy('class_div', 'ASC')
					->get();
					
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Section', 'Edit');
			}
		}	
	}
	public function delSchClass(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('sch_classes')->where('class_id',$request->class_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				//check whether class has been used. if yes, then you cannot delete
				DB::table('class_div')->where('class_id',$request->class_id)->delete();
				$logRec = DB::table('sch_classes')->where('class_id',$request->class_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Master', 'Class', 'Delete', $request->class_id, $request->class_id, 
						'-');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Master', 'Class', 'Delete');
		}
	}
	//////////////PDF generate
	
	public function pdfSchClass(){
		
		try{ //A4: 210 × 297 millimeters
			$pdf_file = 'schools.pdf';
			$this->pdf->AddPage();
			//////////////////////////////////////////////////////////////END OF HEADING
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'SCHOOL CLASSES', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records = SchClass::join('schools', 'schools.school_id', '=', 'sch_classes.school_id')
				->select('school_name','class_name','description', 'capacity', 'sch_classes.sequence AS order', 'div_type', 'div_no',
					'sch_classes.operator AS user', 'sch_classes.reviewer AS supervisor', 'sch_classes.created_at AS created')
				->orderBy('sch_classes.sequence', 'ASC')
				->get();
				
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(50, 5, 'School Name', 1, 0, 'L');
			$this->pdf->Cell(50, 5, 'Class Name', 1, 0, 'L');
			$this->pdf->Cell(25, 5, 'Div Type', 1, 0, 'L');
			$this->pdf->Cell(20, 5, 'DIV No', 1, 0, 'L');
			$this->pdf->Cell(20, 5, 'Capacity', 1, 0, 'L');
			$this->pdf->Cell(20, 5, 'Order', 1, 0, 'L');
			$this->pdf->Ln(5);
				
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(50, 5, $record->school_name, 1, 0, 'L');
				$this->pdf->Cell(50, 5, $record->class_name, 1, 0, 'L');
				$this->pdf->Cell(25, 5, $record->div_type, 1, 0, 'L');
				$this->pdf->Cell(20, 5, $record->div_no, 1, 0, 'L');
				$this->pdf->Cell(20, 5, $record->capacity, 1, 0, 'L');
				$this->pdf->Cell(20, 5, $record->order, 1, 0, 'L');
				$this->pdf->Ln(5);
			}
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Master', 'Class', 'Pdf');
			return redirect()->back();
		}
	}
	
	public function excelSchClass(){
		$records = SchClass::join('schools', 'schools.school_id', '=', 'sch_classes.school_id')
					->select('school_name','class_name','description', 'capacity', 'sch_classes.sequence', 'div_type', 'div_no',
						'sch_classes.operator', 'sch_classes.reviewer', 'sch_classes.created_at')
					->orderBy('sch_classes.sequence', 'ASC')
					->get();
					
		$csv = $records;  // stored the data in a array
		
		return Excel::create('sch-class-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('school_name','class_name','description', 'capacity', 'sch_classes.sequence', 'div_type', 'div_no',
						'sch_classes.operator', 'sch_classes.reviewer', 'sch_classes.created_at');
				$sheet->prependRow(1, $headings);
			});
			
		})->save('xlsx', $this->public_folder.'/reports/excel', true);
	}
	////////////////////////////////////////////////////////////////SYLLABUS
	public function showSyllabus(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		$class_subjects = DB::table('subjects')->select('subject_id', 'subject')->orderBy('subject', 'ASC')->get();
		//the above are necessary to populate the drop-down list in view
		return view('master.syllabus', compact('class', 'class_subjects'));
	}
	public function infoSyllabus(Request $request){
		try{
			$records = Syllabus::join('sch_classes', 'sch_classes.class_id', '=', 'syllabus.class_id')
				->join('subjects', 'subjects.subject_id', '=', 'syllabus.subject_id')
				->join('class_syllabus', 'class_syllabus.syllabus_id', '=', 'syllabus.syllabus_id')
				->join('class_div', 'class_div.class_div_id', '=', 'class_syllabus.class_div_id')
				->select('syllabus.syllabus_id', 'class_name','subject', 'class_div', 'syllabus')
				->orderBy('sch_classes.sequence', 'ASC')
				->get();
			return view('master.infosyllabus', compact('records'));
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Master', 'Syllabus', 'Info');
		}
	}
	public function updateSyllabus(Request $request){
		
		$c = count($request->sections);
		//at least a section should be selected
		if($request->ajax() && $c > 0){
			$logRec = array();
			$operator = "";
			try{
				$syllabus_id = $request->syllabus_id;
				if($syllabus_id === NULL || $syllabus_id ==""){
					//check if the subject_id exist for the class_id in syllabus, 
					//if yes, then delete what was created earlier and create a new one
					$count = Syllabus::where([
						['class_id', '=', $request->class_id],
						['subject_id', '=', $request->subject_id]
					])->get();
					$syllabus_old = '';
					if(count($count) > 0){
						//get the syllabus id
						$syllabus_old = DB::table('syllabus')->where([
							['class_id', '=', $request->class_id],
							['subject_id', '=', $request->subject_id]
						])->value('syllabus_id');
						//delete in syllabus
						DB::table('class_syllabus')
							->where('syllabus_id',$syllabus_old)
							->delete();
							
						DB::table('syllabus')->where([
							['class_id', '=', $request->class_id],
							['subject_id', '=', $request->subject_id]
						])->delete();
					}
					$logRec = Syllabus::create($request->all());
					for($i=0;$i<$c;$i++){
						//delete in class syllabus
						$class_section = $request->sections[$i];
						$item = new ClassSyllabus();
						$item->class_div_id = $class_section; //the same info in class div and description fields
						$item->syllabus_id = $logRec->syllabus_id;
						$item->operator = $logRec->operator;
						$item->save();
					}
				}else{
					$logRec = Syllabus::updateOrCreate(['syllabus_id'=>$request->syllabus_id], $request->all());
					//delete the old syllabus in class sections and create another in class syllabus
					DB::table('class_syllabus')->where('syllabus_id',$request->syllabus_id)->delete();
					for($i=0;$i<$c;$i++){
						$item = new ClassSyllabus();
						$item->class_div_id = $request->sections[$i]; //the same info in class div and description fields
						$item->syllabus_id = $logRec->syllabus_id;
						$item->operator = $logRec->operator;
						$item->save();
					}
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Syllabus', 'Update');
			}
		}
	}
	public function getClassSyllabus(Request $request){
		if($request->ajax()){
			try{
				$record = DB::table('syllabus')
					->where('class_id',$request->class_id)
					->where('subject_id',$request->subject_id)
					->value('syllabus');
				return $record;
				
			} catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Syllabus', 'Get');
			}
		}
	}
	
	public function editSyllabus(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				
				$record = Syllabus::join('sch_classes', 'sch_classes.class_id', '=', 'syllabus.class_id')
					->join('subjects', 'subjects.subject_id', '=', 'syllabus.subject_id')
					->join('class_syllabus', 'class_syllabus.syllabus_id', '=', 'syllabus.syllabus_id')
					->join('class_div', 'class_div.class_div_id', '=', 'class_syllabus.class_div_id')
					->select('syllabus.class_id AS class','syllabus.subject_id AS subject', 'syllabus',
						'class_div.class_div_id AS section_id', 'class_div')
					->where('syllabus.syllabus_id', '=', $request->syllabus_id)
					->get();
					
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Master', 'Syllabus', 'Edit');
			}
		}	
	}
	
	public function delSyllabus(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('syllabus')->where('syllabus_id',$request->syllabus_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				//check whether class has been used. if yes, then you cannot delete
				$logRec = DB::table('class_syllabus')->where('syllabus_id',$request->syllabus_id)->delete();
				DB::table('syllabus')->where('syllabus_id',$request->syllabus_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Master', 'Syllabus', 'Delete', $request->syllabus_id, $request->syllabus_id, 
						'-');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Master', 'Syllabus', 'Delete');
		}
	}
	////////only excel extract is available for syllabus
	public function excelSyllabus(){
		try{
			$records = Syllabus::join('sch_classes', 'sch_classes.class_id', '=', 'syllabus.class_id')
				->join('subjects', 'subjects.subject_id', '=', 'syllabus.subject_id')
				->join('class_syllabus', 'class_syllabus.syllabus_id', '=', 'syllabus.syllabus_id')
				->join('class_div', 'class_div.class_div_id', '=', 'class_syllabus.class_div_id')
				->select('class_name','subject', 'class_div', 'syllabus', 'syllabus.operator', 'syllabus.reviewer', 'syllabus.created_at')
				->orderBy('sch_classes.sequence', 'ASC')
				->get();
						
			$csv = $records;  // stored the data in a array
			
			return Excel::create('syllabus-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('class_name','subject', 'class_div', 'syllabus', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
				
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (\Exception $e) {$this->report_error($e, 'Master', 'Syllabus', 'Excel');}
	}
	////////////////////////////////////////IMPORTS
	public function syllabusImport(Request $request){
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
					$content = '<table class="table table-hover table-striped table-condensed" id="import_syllabus" style="font-size:100%">';
					$i = 0;//initialize
					while (!feof($handle)) {
						$value[] = fgets($handle, 1024);
						$lineArr = explode("\t", $value[$i]);
						
						list($sch_class, $subject, $syllabus, $section) = $lineArr;
						//get the last name o the student and the first name based on the reg no
						if( $i == 0){
							$content .='<thead>';
							$content .='<tr>';
							$content .='<th>'.$sch_class.'</th>';
							$content .='<th>'.$subject.'</th>';
							$content .='<th>'.$syllabus.'</th>';
							$content .='<th>'.$section.'</th>';
							$content .='</tr>';
							$content .='</thead>';
							$content .='<tbody>';
						}
						if( $i > 0){
							$content .='<tr>';
							$content .='<td>'.$sch_class.'</td>';
							$content .='<td>'.$subject.'</td>';
							$content .='<td>'.$syllabus.'</td>';
							$content .='<td>'.$section.'</td>';
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
			$this->report_error($e, 'Master', 'Syllabus', 'Import'); 
		}
	}
	public function updateSyllabusImport(Request $request){
		
		$logRec = array();
		try{
			$subject_id = $this->getSubjectID($request->subject);
			$class_div_id = $this->getClassSectionID($request->section);
			$class_id = $this->getClassID($class_div_id);
			$syllabus = $request->syllabus;
			
			//check if the subject_id exist for the class_id in syllabus, 
			//if yes, then delete what was created earlier and create a new one
			$count = Syllabus::where([
				['class_id', '=', $class_id],
				['subject_id', '=', $subject_id]
			])->get();
			
			$syllabus_old = '';
			if(count($count) > 0){
				//get the syllabus_id
				$syllabus_old = DB::table('syllabus')->where([
					['class_id', '=', $class_id],
					['subject_id', '=', $subject_id]
				])->value('syllabus_id');
				//delete in class syllabus, if found
				try{
					DB::table('class_syllabus')
					->where('syllabus_id',$syllabus_old)
					->where('class_div_id',$class_div_id)
					->delete();
				} catch (\Exception $e) {}
				//delete in syllabus: proceed if there is error as other section may be using the record, hence cannot be deleted
				try{
					DB::table('syllabus')->where([
						['class_id', '=', $class_id],
						['subject_id', '=', $subject_id]
					])->delete();
				} catch (\Exception $e) {}
			}
			$count = Syllabus::where([
				['class_id', '=', $class_id],
				['subject_id', '=', $subject_id]
			])->get();
			//update only if record is not found
			$syllabus_id = '';
			if(count($count) > 0){
				
				$syllabus_id = DB::table('syllabus')->where([
					['class_id', '=', $class_id],
					['subject_id', '=', $subject_id]
				])->value('syllabus_id');
				
			}else{
				$logRec = Syllabus::create(
					array(
					'syllabus' => $syllabus, 
					'class_id' => $class_id,
					'subject_id' => $subject_id, 
					'operator' => $request->operator)
				);
				$syllabus_id = $logRec->syllabus_id;
			}
			//then update class syllabus
			$item = new ClassSyllabus();
			$item->class_div_id = $class_div_id; //the same info in class div and description fields
			$item->syllabus_id = $syllabus_id;
			$item->operator = $request->operator;
			$item->save();
			
			return $item;
		} catch (\Exception $e) {
			$this->report_error($e, 'Master', 'Syllabus', 'Update');
		}
	}
	public function report_error($e, $module, $form, $task){
		file_put_contents('file_error.txt', $e->getMessage(). '\n'. $module. '-'. $form. '-'. $task. PHP_EOL, FILE_APPEND);
		Log::info($e->getMessage());
	}
}
?>