<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\models\Registration;
use App\models\Institution;
use App\models\SchClass;
use App\models\StudentEnrol;
use App\models\StudentAttendance;
use App\models\ClassTransfer;
use App\models\StudentPromotion; //this is presently Student Movement
use App\models\StudentExit;
use App\models\StudentDiscipline;
use App\models\StudentAchievement;

use Log; //the default Log file
use DB; //use the default Database
use Excel;
use Auth;

class StudentController extends Controller
{
    protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function showRegistration(){
		return view('student.registration');	
	}
	public function infoRegistration(Request $request){
		$from_date = $request->start_date;
		$to_date = $request->end_date;
			
		$records =  Registration::where('reg_date', '>=', $from_date)
						->where('reg_date', '<=', $to_date)
						->orderBy('reg_no', 'DESC')
						->get();
		return view('student.inforegistration', compact('records'));
	}
	public function updateStudentImage(Request $request){
		if($request->ajax()){
			$contents = File::get(storage_path().DIRECTORY_SEPARATOR.'public_folder.txt');
			//$this->public_folder = $contents;
			$logRec = array();
			$old_photo = '';
			$allowed_filetypes = array('.jpg','.jpeg','.png','.gif','.JPG','.JPEG','.PNG','.GIF');
			$max_filesize = 10485760;
			//if the record exist
			$row = DB::table('students')->where('reg_no', $request->reg_no)->first();
			if(count($row)>0){
				//edit the table
				$old_photo = $row->photo;
				//if there is a photo change
				$reg_no = $request->reg_no;
				if ($request->hasFile('photo_')) {
					try{
						if( $old_photo !== NULL && $old_photo !== ""){
							//Storage::delete('photo/student/'.$old_photo);
							//Storage::delete('photo/student/'.$old_photo);
							//Storage::disk('public')->delete($old_photo);
							Storage::disk('public')->delete('/photo/student/'.$old_photo);
						}
					}catch (\Exception $e) {}
					
					$file_photo = $request->file('photo_');
					$photo_size = $request->file('photo_')->getClientSize();
					$name_photo = time().$file_photo->getClientOriginalName();
					$ext2 = substr($name_photo, strpos($name_photo,'.'), strlen($name_photo)-1);
					$name_photo = $reg_no.$ext2;
					
					$request->photo_->storeAs('photo/student', $name_photo);
					File::put($this->public_folder.'/photo/student/'.$name_photo, File::get($file_photo));
					//$request->photo_->Storage::disk('public')->put('filename', $file_content);
					//update the table for file reference
					$logRec = Registration::where('reg_no', $request->reg_no)->update(array('photo' => $name_photo));
				}
			}
		}
		return $logRec;
	}
	public function updateMultiple(Request $request){
		
		$logRec = array();
		$c = count($request->reg_no);
		if($request->ajax() && $c > 0){
			try{
				$operator = $request->operator;
				$reg_date = $this->toDbaseDate($request->txn_date);
				
				for($i=0;$i<$c;$i++){
					$reg_no = $request->reg_no[$i];
					$row = DB::table('students')->where('reg_no', $reg_no)->first();
					if(count($row)< 1){
						$other_name = $request->other_name[$i];
						if( $request->other_name[$i] == '-') $other_name = "";
						$logRec = Registration::create(
							array(
								'reg_no' => $request->reg_no[$i], 
								'reg_date' => $reg_date, 
								'first_name' => $request->first_name[$i], 
								'last_name' => $request->last_name[$i], 
								'other_name' => $other_name, 
								'dob' => $this->toDbaseDate($request->dob[$i]), 
								'tribe' => '',
								'height' => '0', 
								'weight' => '0',
								'blood' => '0',
								'gender' => $request->gender[$i],
								'district' => '-', 
								'region' => '-',
								'town' => '-',
								'lga' => '-',
								'state_origin' => '-',
								'nationality' => '-', 
								'religion' => $request->religion[$i], 
								'address' => $request->home_add[$i],
								'email' => $request->email[$i], 
								'phone' => $request->phone[$i], 
								'guardian' => $request->guardian[$i], 
								'relationship' => '-', 
								'guard_office' => $request->office_add[$i],  
								'guard_home' => $request->home_add[$i], 
								'guard_email' => $request->email[$i],  
								'guard_phone' => $request->phone[$i],
								'photo' => ""
							)
						);
					}
				}
			} catch (\Exception $e) {
				$this->report_error($e, 'Student', 'Registration', 'Multiple');
			}
		}
		return $logRec;
	}
	public function updateRegistration(Request $request){
		//this will only allow one record. if record is found then update with current data
		if($request->ajax()){
			//$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
			$contents = File::get(storage_path().DIRECTORY_SEPARATOR.'public_folder.txt');
			//$this->public_folder = $contents;
			$logRec = array();
			$old_photo = '';
			$allowed_filetypes = array('.jpg','.jpeg','.png','.gif','.JPG','.JPEG','.PNG','.GIF');
			$max_filesize = 10485760;
			//if the record exist
			$row = DB::table('students')->where('reg_no', $request->reg_no)->first();
			if(count($row)>0){
				try{
					//edit the table, as it exists
					$old_photo = $row->photo;
					$reg_no = $request->reg_no;
					$other_name = $request->other_name;
					if( $request->other_name == '-') $other_name = "";
						
					$logRec = Registration::where('reg_no', $reg_no)->update(
						array(
							'reg_date' => $request->reg_date, 
							'first_name' => $request->first_name, 
							'last_name' => $request->last_name, 
							'other_name' => $other_name, 
							'dob' => $request->dob, 
							'tribe' => $request->tribe,
							'height' => $request->height, 
							'weight' => $request->weight, 
							'blood' => $request->blood, 
							'gender' => $request->gender, 
							'tribe' => $request->tribe, 
							'district' => '-', 
							'region' => '-', 
							'town' => $request->town, 
							'lga' => $request->lga, 
							'state_origin' => $request->state_origin, 
							'nationality' => $request->nationality, 
							'religion' => $request->religion, 
							'address' => $request->guard_home, 
							'email' => $request->guard_email,
							'phone' => $request->guard_phone, 
							'guardian' => $request->guardian, 
							'relationship' => $request->relationship, 
							'guard_office' => $request->guard_office, 
							'guard_home' => $request->guard_home, 
							'guard_email' => $request->guard_email, 
							'guard_phone' => $request->guard_phone
						)
					);
					//if there is a photo change
					if ($request->hasFile('photo_')) {
						try{
							if( $old_photo !== NULL && $old_photo !== ""){
								//Storage::delete('photo/student/'.$old_photo);
								Storage::disk('public')->delete('/photo/student/'.$old_photo);
							}
						}catch (\Exception $e) {}
						
						$file_photo = $request->file('photo_');
						$photo_size = $request->file('photo_')->getClientSize();
						$name_photo = time().$file_photo->getClientOriginalName();
						$ext2 = substr($name_photo, strpos($name_photo,'.'), strlen($name_photo)-1);
						$name_photo = $reg_no.$ext2;
						$request->photo_->storeAs('photo/student', $name_photo);
						File::put($this->public_folder.'/photo/student/'.$name_photo, File::get($file_photo));
						//Registration::where('student_id', $request->student_id)->update(array('photo' => $name_photo));
						Registration::where('reg_no', $reg_no)->update(array('photo' => $name_photo));
					}
					return $logRec;
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Registration', 'Edit');
				}
			}else{
				//student is new
				//student image is optional
				try{
					$data = $request->all();
					if ($request->hasFile('photo_') ) {
						$reg_no = $request->reg_no;
						$file_photo = $request->file('photo_');
						$photo_size = $request->file('photo_')->getClientSize();
						//get the names of the photos. you may have to use your own name here
						$name_photo = time().$file_photo->getClientOriginalName();
						//move to the specified folder
						$ext2 = substr($name_photo, strpos($name_photo,'.'), strlen($name_photo)-1);
						//ensure that you retain the extension of the image files
						$name_photo = $reg_no.$ext2;
						
						if(!in_array($ext2,$allowed_filetypes))die('The file you attempted to upload is not allowed:'.$name_photo);
						
						if($photo_size > $max_filesize) die('The file you attempted to upload is too large:'.$name_photo);
							
						//delete the files, if they exist
						if(count($row)>0){
							try
							{
								if( $old_photo !== NULL && $old_photo !== ""){
									//Storage::delete('photo/student/'.$old_photo);
									Storage::disk('public')->delete('/photo/student/'.$old_photo);
								}
							}catch (\Exception $e) {}
						}
						//store files
						$request->photo_->storeAs('photo/student', $name_photo); 
						//$file_photo->move(public_path('photo/student'), $name_photo);
						File::put($this->public_folder.'/photo/student/'.$name_photo, File::get($file_photo));
						
						//merge the photo aray with the main array(request)
						$data = array_merge(['photo' => "{$name_photo}"], $request->all());
					}
					$logRec = Registration::create($data);
					return $logRec;
						
				} catch (\Exception $e) {
					$this->report_error($e, 'Student', 'Registration', 'Update');
				}
			}
		}
	}
	public function getFullName(Request $request){
		$reg_no = $request->reg_no;
		$item = DB::table('students')
			->where('reg_no', $reg_no)
			->first();
		$full_name =  "";
		if( count($item) > 0 ){
			$full_name = $item->last_name. ', '. $item->first_name;
			if( $item->other_name !== NULL && $item->other_name !== '-' && $item->other_name !== ""){
				$full_name = $full_name . ' ' . $item->other_name;
			}
		}
		return $full_name;
	}
	public function getViewStudentID(Request $request){
		
		$student_id = DB::table('students')->where('reg_no', $request->reg_no)->value('student_id');
		return $student_id;
	}
	public function listEnrolStudents(){
		try{ 
			$students =  DB::table('student_enrol as a')
				->join('students as d','d.student_id', '=', 'a.student_id')
				->where('d.active', "1")
				->where('a.active', "1")
				->select('d.reg_no', 'a.student_id')
				->orderBy('d.reg_no', 'DESC')
				->get();
			
			return $students;
		} catch (Exception $e) {$this->report_error($e, 'Students', 'Enrol', 'List');}
	}
	public function listClassStudents(Request $request){
		$class_id = $request->class_id;
		try{ 
			$students =  DB::table('student_enrol as a')
				->join('students as b','b.student_id', '=', 'a.student_id')
				->join('class_div as c','c.class_div_id', '=', 'a.class_id')
				->where('c.class_id', $class_id)
				->where('b.active', "1")
				->where('a.active', "1")
				->select('b.reg_no', 'a.student_id')
				->orderBy('b.reg_no', 'DESC')
				->get();
			
			return $students;
		} catch (Exception $e) {$this->report_error($e, 'Students', 'Enrol', 'List');}
	}
	public function listActiveStudents(){
		try{
			$items = DB::table('students')
				->where('active', "1")
				->where('enrol', "1")
				->get();
				
			return $items;
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'List');
		}
	}
	public function getStudent(Request $request){
		//this displays the record for display for EDIT or REVIEW
		$item = DB::table('students')
			->where('student_id', $request->student_id)
			//->where('active', "1")
			//->where('enrol', "1")
			->first();
		$record =  array();
		try{
			if( count($item) > 0 ){
				$record['student_id'] = $item->student_id;
				$record['reg_no'] = $item->reg_no;
				$record['reg_date'] = $item->reg_date;
				$record['first_name'] = $item->first_name;
				$record['last_name'] = $item->last_name;
				$record['other_name'] = $item->other_name;
				$record['dob'] = $item->dob;
				$record['tribe'] = $item->tribe;
				$record['height'] = $item->height;
				$record['weight'] = $item->weight;
				$record['blood'] = $item->blood;
				$record['gender'] = $item->gender;
				$record['district'] = $item->district;
				$record['region'] = $item->region;
				$record['town'] = $item->town;
				$record['lga'] = $item->lga;
				$record['state_origin'] = $item->state_origin;
				$record['nationality'] = $item->nationality;
				$record['religion'] = $item->religion;
				$record['address'] = $item->address;
				$record['email'] = $item->email;
				$record['phone'] = $item->phone;
				$record['photo'] = $item->photo;
				$record['guardian'] = $item->guardian;
				$record['relationship'] = $item->relationship;
				$record['guard_office'] = $item->guard_office;
				$record['guard_home'] = $item->guard_home;
				$record['guard_email'] = $item->guard_email;
				$record['guard_phone'] = $item->guard_phone;
				$record['enrol_date'] = $item->enrol_date;
				return $record;
			}
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'Get');
		}
	}
	public function classStudent(){
		//this displays the record for display for EDIT or REVIEW
		$items = DB::table('students')
			->where('active', "1")
			->get();
		$global_array =  array();
		if( count($items) > 0 ){
			foreach ($items as $item)
			{
				$record =  array();
				$record['student_id'] = $item->student_id;
				$record['reg_no'] = $item->reg_no;
				$record['reg_date'] = $item->reg_date;
				$record['first_name'] = $item->first_name;
				$record['last_name'] = $item->last_name;
				$record['other_name'] = $item->other_name;
				$record['photo'] = $item->photo;
				//get current class
				$class_info =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
											->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
											->where('student_enrol.student_id', $item->student_id)
											->where('active', "1")
											->orderBy('enrol_date', 'DESC')
											->orderBy('enrol_id', 'DESC')->first();
											
				if( count($class_info)>0){
					$record['section'] = $class_info->class_div;
					$record['class_name'] = $class_info->class_name;		
				}else{
					$record['section'] = "";
					$record['class_name'] = "";	
				}
				array_push($global_array, $record);
			}
			return $global_array;
		}
	}
	public function getActiveStudent(Request $request){
		//this displays the record for display for EDIT or REVIEW
		$item = DB::table('students')
			->where('reg_no', $request->reg_no)
			//->where('active', "1")
			//->where('enrol', "1")
			->first();
		$record =  array();
		try{
			if( count($item) > 0 ){
				$record['student_id'] = $item->student_id;
				$record['reg_no'] = $item->reg_no;
				$record['reg_date'] = $item->reg_date;
				$record['first_name'] = $item->first_name;
				$record['last_name'] = $item->last_name;
				$record['other_name'] = $item->other_name;
				$record['dob'] = $item->dob;
				$record['tribe'] = $item->tribe;
				$record['height'] = $item->height;
				$record['weight'] = $item->weight;
				$record['blood'] = $item->blood;
				$record['gender'] = $item->gender;
				$record['district'] = $item->district;
				$record['region'] = $item->region;
				$record['town'] = $item->town;
				$record['lga'] = $item->lga;
				$record['state_origin'] = $item->state_origin;
				$record['nationality'] = $item->nationality;
				$record['religion'] = $item->religion;
				$record['address'] = $item->address;
				$record['email'] = $item->email;
				$record['phone'] = $item->phone;
				$record['photo'] = $item->photo;
				$record['guardian'] = $item->guardian;
				$record['relationship'] = $item->relationship;
				$record['guard_office'] = $item->guard_office;
				$record['guard_home'] = $item->guard_home;
				$record['guard_email'] = $item->guard_email;
				$record['guard_phone'] = $item->guard_phone;
				$record['enrol_date'] = $item->enrol_date;
				//get current class
				$class_info =  StudentEnrol::join('class_div', 'class_div.class_div_id', '=','student_enrol.class_id')
											->where('student_enrol.student_id', $item->student_id)
											->where('active', "1")
											->orderBy('enrol_date', 'DESC')
											->orderBy('enrol_id', 'DESC')
											->first();
				if( count($class_info)>0){
					$record['class_id'] = $class_info->class_div_id;
					$record['class_name'] = $class_info->class_div;		
				}else{
					$record['class_id'] = "";
					$record['class_name'] = "";	
				}
				return $record;
			}
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'Get');
		}
	}
	public function getActiveStudents(){
		//this displays the record for display for EDIT or REVIEW
		$items = DB::table('students')
			->where('active', "1")
			->get();
		$global_array =  array();
		if( count($items) > 0 ){
			foreach ($items as $item)
			{
				$record =  array();
				$record['student_id'] = $item->student_id;
				$record['reg_no'] = $item->reg_no;
				$record['reg_date'] = $item->reg_date;
				$record['first_name'] = $item->first_name;
				$record['last_name'] = $item->last_name;
				$record['other_name'] = $item->other_name;
				$record['dob'] = $item->dob;
				$record['tribe'] = $item->tribe;
				$record['height'] = $item->height;
				$record['weight'] = $item->weight;
				$record['blood'] = $item->blood;
				$record['gender'] = $item->gender;
				$record['district'] = $item->district;
				$record['region'] = $item->region;
				$record['town'] = $item->town;
				$record['lga'] = $item->lga;
				$record['state_origin'] = $item->state_origin;
				$record['nationality'] = $item->nationality;
				$record['religion'] = $item->religion;
				$record['address'] = $item->address;
				$record['email'] = $item->email;
				$record['phone'] = $item->phone;
				$record['photo'] = $item->photo;
				$record['guardian'] = $item->guardian;
				$record['relationship'] = $item->relationship;
				$record['guard_office'] = $item->guard_office;
				$record['guard_home'] = $item->guard_home;
				$record['guard_email'] = $item->guard_email;
				$record['guard_phone'] = $item->guard_phone;
				$record['enrol_date'] = $item->enrol_date;
				//get current class
				$class_info =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
											->where('student_enrol.student_id', $item->student_id)
											->where('active', "1")
											->orderBy('enrol_date', 'DESC')
											->orderBy('enrol_id', 'DESC')->first();
											
				if( count($class_info)>0){
					$record['class_id'] = $class_info->class_div_id;
					$record['class_name'] = $class_info->class_div;		
				}else{
					$record['class_id'] = "";
					$record['class_name'] = "";	
				}
				array_push($global_array, $record);
			}
			return $global_array;
		}
	}
	public function getImage(Request $request){
		//this displays the record for display for EDIT or REVIEW
		$row = DB::table('students')->where('reg_no', $request->reg_no)->first();
		$photo = $row->photo;
		$path = storage_path('app/photo/student/');
		$path = "{$path}/{$photo}";
		return $path;
	}
	public static function getStudentEmail($reg_no){
		//this displays the record for display for EDIT or REVIEW
		$guard_email = DB::table('students')->where('reg_no', $reg_no)->value('guard_email');
		return $guard_email;
	}
	public static function getGuardian($reg_no){
		//this displays the record for display for EDIT or REVIEW
		$guard_email = DB::table('students')->where('reg_no', $reg_no)->value('guardian');
		return $guard_email;
	}
	public static function getStudentID($reg_no){
		//this displays the record for display for EDIT or REVIEW
		$student_id = DB::table('students')->where('reg_no', $reg_no)->value('student_id');
		return $student_id;
	}
	public static function getStudentRegNo($student_id){
		//this displays the record for display for EDIT or REVIEW
		$reg_no = DB::table('students')->where('student_id', $student_id)->value('reg_no');
		return $reg_no;
	}
	public static function getLastName($reg_no){
		$last_name = DB::table('students')->where('reg_no', $reg_no)->value('last_name');
		return $last_name;
	}
	public static function getFirstName($reg_no){
		$first_name = DB::table('students')->where('reg_no', $reg_no)->value('first_name');
		return $first_name;
	}
	public function editRegistration(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				return Registration::where('student_id', '=', $request->student_id)->first();
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Student', 'Registration', 'Edit');
			}
		}	
	}
	public function delRegistration(Request $request){
		try{
			$logRec = array();
			$posted_by =  DB::table('students')->where('student_id', $request->student_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$row = DB::table('students')->where('student_id', $request->student_id)->first();
				
				//alternatively, make the stunde inactive: active = 0	
				//use empty instead
				if(count($row)>0){
					$old_photo = $row->photo;
					if( $old_photo !== NULL && $old_photo !== ""){
						//Storage::delete('photo/student/'.$old_photo);
						Storage::disk('public')->delete('/photo/student/'.$old_photo);
					}
					//now delete OR mark as INACTIVE
					$logRec = Registration::where('student_id',$request->student_id)->delete();
					
				}
				if(count($logRec) > 0){
					SysController::postLog('Student', 'Registration', 'Delete', $request->student_id, $request->student_id, 
						'-');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'Delete');
			
		}
	}
	//////////////PDF generate
	public function pdfRegistration(Request $request){
		//name of school, obtain the logo on the left
		
		$row = DB::table('students')->where('student_id', $request->student_id)->first();
		if (count($row)> 0){
			try{ //A4: 210 × 297 millimeters
				$photo = $row->photo;
				$pdf_file = 'student.pdf';
				$this->pdf->AddPage('P'); 
				$this->pdf->Ln(5);
				$this->pdf->Image(storage_path('app/photo/student/'.$row->photo),150, 25, 35, 35, ''); 
				//image, x, y, widht, height, image type
				$this->pdf->Cell(50, 10, 'Reg. No:', 0, 0, 'L'); //width, height, text, next line, border, alignment
				$this->pdf->Cell(50, 10, $row->reg_no, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Reg. Date:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, date("d/m/Y", strtotime($row->reg_date)), 0, 0, 'L');
				if( $row->enrol_date !== NULL && $row->enrol_date !== '0000-00-00'){
					$this->pdf->Ln(5);
					$this->pdf->Cell(50, 10, 'Enrolment Date:', 0, 0, 'L');
					$this->pdf->Cell(50, 10, date("d/m/Y", strtotime($row->enrol_date)), 0, 0, 'L');
				}
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'First Name:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->first_name, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Last Name:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->last_name, 0, 0, 'L');
				if( $row->other_name !== NULL){
					$this->pdf->Ln(5);
					$this->pdf->Cell(50, 10, 'Other Name:', 0, 0, 'L');
					$this->pdf->Cell(50, 10, $row->other_name, 0, 0, 'L');
				}
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Date of Birth:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, date("d/m/Y", strtotime($row->dob)), 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Height(m):', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->height, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Weight(kg):', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->weight, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Gender:', 0, 0, 'L');
				$gender = "";
				if($row->gender == "0") $gender = "Female";
				if($row->gender == "1") $gender = "Male";
				$this->pdf->Cell(50, 10, $gender, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Blood:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->blood, 0, 0, 'L');
				//
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Home Town:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->town, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'LGA:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->lga, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'State of Origin:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->state_origin, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Nationality:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->nationality, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Religion:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->religion, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Guardian:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->guardian, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Relationship:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->relationship, 0, 0, 'L');
				$this->pdf->Ln(7);
				$this->pdf->Cell(50, 5, 'Office Address:', 0, 0, 'L');
				//MultiCell(float w, float h, string txt [, mixed border [, string align [, boolean fill]]])
				$this->pdf->MultiCell(50, 5, $row->guard_office); 
				$this->pdf->Ln(2);
				//Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]
				$this->pdf->Cell(50, 5, 'Home Address:', 0, 0, 'L'); 
				$this->pdf->MultiCell(50, 5, $row->guard_home);
				$this->pdf->Ln(2);
				$this->pdf->Cell(50, 10, 'Email:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->guard_email, 0, 0, 'L');
				$this->pdf->Ln(5);
				$this->pdf->Cell(50, 10, 'Phone:', 0, 0, 'L');
				$this->pdf->Cell(50, 10, $row->guard_phone, 0, 0, 'L');
				$this->pdf->Ln(5);
				
				$path = storage_path('reports/pdf').'/'.$pdf_file;
				$this->pdf->Output($path, 'F');
				
				$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
				\File::copy($path,$destination);
				return $pdf_file;
				
			} catch (Exception $e) {
				$this->report_error($e, 'Student', 'Registration', 'PDF');
				return redirect()->back();
			}
		}
	}
	public function excelRegistration(){
		try{
			$csv = Registration::select('reg_no', 'reg_date', 'first_name', 'last_name', 
					'other_name', 'dob', 'tribe',
					'height', 'weight', 'blood', 'gender', 'district', 'region', 'town', 'lga', 
					'state_origin', 'nationality', 'religion', 
					'address', 'email', 'phone', 'enrol_date', 'active', 'guardian', 
					'relationship', 'guard_office', 'guard_home', 
					'guard_email', 'guard_phone', 'operator', 'reviewer', 'created_at')
					->orderBy('reg_no', 'ASC')
					->get();
			
			return \Excel::create('registration-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('reg_no', 'reg_date', 'first_name', 'last_name', 'other_name', 'dob', 'tribe',
						'height', 'weight', 'blood', 'gender', 'district', 'region', 'town', 'lga', 
						'state_origin', 'nationality', 'religion', 
						'address', 'email', 'phone', 'enrol_date', 'active', 'guardian', 'relationship', 
						'guard_office', 'guard_home', 
						'guard_email', 'guard_phone', 'operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
				
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'Excel');
			return redirect()->back();
		}
	}
	/////////////////////////////////////////////////////ENROLMENT
	////////////////////////////////////////////////////
	public function showEnrolment(){
		//get class inside
		//get unenrolled students inside
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		$records =  Registration::where('enrol', '0')->orderBy('reg_no', 'DESC')->get();
		return view('student.enrolment', compact('class', 'records'));
	}
	public function updateEnrolment(Request $request){
		$c = count($request->students);
		//at least a student should be selected
		//update the student record
		//update student class information
		if($request->ajax() && $c > 0){
			$logRec = array();
			$operator = $request->operator;
			$enrol_date = $request->enrol_date;
			$class_div = $request->class_div_id;
			try{
				
				for($i=0;$i<$c;$i++){
					//update student record
					Registration::where('student_id',  $request->students[$i])
						->update(array(
							'enrol' => "1",
							'enrol_date' => $enrol_date
							)
						);
					//update student class
					$logRec = StudentEnrol::create(array(
							'class_id' => $class_div,
							'active' => '1',
							'student_id' => $request->students[$i], 
							'enrol_date' => $enrol_date,
							'operator' => $request->operator
						)
					);
				}
			} catch (\Exception $e) {
				$this->report_error($e, 'Student', 'Enrolment', 'Update');
			}
			return $logRec;
		}
	}
	public function classOccupancy(){
		
		try{
			$items =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
				->join('students','students.student_id', '=', 'student_enrol.student_id')
				->where('students.active', "1")
				->where('student_enrol.active', "1")
				->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name')
				->orderBy('student_enrol.class_id')
				->get();
			$global_array =  array();
			if( count($items) > 0 ){
				foreach ($items as $item)
				{
					$record =  array();
					$record['student_id'] = $item->student_id;
					$record['reg_no'] = $item->reg_no;
					$record['reg_date'] = $item->reg_date;
					$record['enrol_date'] = $item->enrol_date;
					$record['first_name'] = $item->first_name;
					$record['last_name'] = $item->last_name;
					$record['other_name'] = $item->other_name;
					$record['photo'] = $item->photo;
												
					$record['section'] = $item->section;
					$record['class_name'] = $item->class_name;
					array_push($global_array, $record);
				}
				$records = $global_array;
				return view('master.infoclassoccupancy', compact('records'));
			}
		} catch (Exception $e) {
			$this->report_error($e, 'Student', 'Enrolment', 'Class');
		}
	}
	public static function getClassEnrolment($class_div_id){
		
		try{
			$students =  StudentEnrol::join('students','students.student_id', '=', 'student_enrol.student_id')
				->join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
				->where('students.active', "1")
				->where('student_enrol.active', "1")
				->where('student_enrol.class_id', $class_div_id)
				->select('student_enrol.student_id AS student_id','reg_no','reg_date','student_enrol.enrol_date AS enrol_date', 'first_name', 'last_name', 'other_name', 'photo', 'class_div AS section', 'class_name')
				->orderBy('students.reg_no')
				->get();
			return $students;
			
		} catch (Exception $e) {}
	}
	public static function getStudentClass($value){
		try{
			$class_id = StudentEnrol::where('student_id', $value)
				->where('active', "1")
				->value('class_id');
			
			return $class_id;
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'Class');
		}
	}
	public static function getStudentClassName($value){
		try{
			$class_name = StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->where('student_id', $value)
				->where('active', "1")
				->value('class_div');
			return $class_name;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Enrolment', 'Class Name');
		}
	}
	public static function getStudentLevel($value){
		try{
			$class_id = StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->where('student_id', $value)
				->where('active', "1")
				->value('class_div.class_id');
			return $class_id;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Enrolment', 'Class ID');
		}
	}
	public function excelEnrolment(){
		try{
			$csv = StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
					->join('students', 'students.student_id', '=', 'student_enrol.student_id')
					->where('students.active', "1")
					->where('student_enrol.active', "1")
					->select('students.reg_no', 'students.reg_date', 'students.first_name', 'last_name','other_name', 'student_enrol.enrol_date', 
						'class_div.class_div', 'student_enrol.operator', 'student_enrol.created_at')
					->get();
			
			return \Excel::create('enrolment-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('reg_no', 'reg_date', 'first_name', 'last_name','other_name', 'student_enrol.enrol_date', 
						'class_div', 'student_enrol.operator', 'created_at');
					$sheet->prependRow(1, $headings);
				});
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (Exception $e) {
			$this->report_error($e, 'Student', 'Enrolment', 'Excel');
			//return redirect()->back();
		}
	}
	////////////////////////////////////////////////////////////////
	////////////////////////////////ATTENDANCE
	public function showAttendance(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		//include only those who have enrolled
		$records =  Registration::where('enrol', '1')->orderBy('reg_no', 'DESC')->get();
		return view('student.attendance', compact('class', 'records'));
	}
	public function getDivStudents(Request $request){
		try{
			//iterate through students with enrolment = 1
			$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->join('sch_classes','sch_classes.class_id', '=', 'class_div.class_id')
				->join('students','students.student_id', '=', 'student_enrol.student_id')
				->where('students.active', "1")
				->where('student_enrol.active', "1")
				->where('student_enrol.class_id', $request->class_div_id)
				->select('student_enrol.student_id AS student_id','reg_no','student_enrol.enrol_date AS enrol_date', 'first_name', 'last_name', 'other_name', 'photo')
				->orderBy('student_enrol.student_id')
				->get();
			$global_array =  array();
			foreach ($students as $student)
			{
				
				$record['student_id'] = $student->student_id;
				$record['reg_no'] = $student->reg_no;
				$record['last_name'] = $student->last_name;
				$record['first_name'] = $student->first_name;
				$record['other_name'] = $student->other_name;
				$record['photo'] = $student->photo;
				$record['enrol_date'] = $student->enrol_date;
				//add this to the mother array
				array_push($global_array, $record);
				//$global_array[] = $record;
			}
			return $global_array;
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Setion', 'Student');
		}
	}
	public function getStudentClassForm(Request $request){
		try{
			$class_info =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->where('student_enrol.student_id', $request->student_id)
				->where('student_enrol.active', "1")
				->orderBy('enrol_date', 'DESC')
				->orderBy('enrol_id', 'DESC')->first();
			
			return $class_info;
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Class', 'Form');
		}
	}
	public function classForms(){
		//this is to generate pdf files containing current students in the various classes
		$students =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->join('sch_classes','sch_classes.class_id', '=', 'student_enrol.class_id')
				->join('students','students.student_id', '=', 'student_enrol.student_id')
				//->where('students.active', "1")
				->where('student_enrol.active', "1")
				->select('reg_no', 'first_name', 'last_name', 'other_name', 'class_div.class_div')
				->orderBy('sequence', 'ASC')
				->orderBy('reg_no', 'ASC')
				->get();
		$old_class = '';
		$pdf_file = 'Class Forms.pdf';
		try{			
			foreach ($students as $student)
			{
				//check if the class is the same. IF NOT, then add a page
				$class = $student->class_div;
				if( $old_class !== $class){
					$this->pdf->AddPage();
					$this->pdf->Ln(2);
					$this->pdf->SetFont('Arial', 'B', 10); //set font
					$this->pdf->Cell(200, 5, $class, 0, 0, 'C');
					$this->pdf->Ln(10);
					
					$this->pdf->Cell(20, 5, 'RegNo', 1, 0, 'L');
					$this->pdf->Cell(25, 5, 'Last Name', 1, 0, 'L');
					$this->pdf->Cell(25, 5, 'First Name', 1, 0, 'L');
					$this->pdf->Cell(120, 5, 'Remarks', 1, 0, 'L');
					
					$this->pdf->Ln(5);
					$this->pdf->SetFont('Arial', '', 10); //se
					
				}
				$old_class = $class;
				$this->pdf->Cell(20, 5, $student->reg_no, 1, 0, 'L');
				$this->pdf->Cell(25, 5, $student->last_name, 1, 0, 'L');
				$this->pdf->Cell(25, 5, $student->first_name, 1, 0, 'L');
				$this->pdf->Cell(120, 5, '', 1, 0, 'L');
				$this->pdf->Ln(5);
			}
			
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Class', 'Student Form');
		}	
	}
	public static function getStudentSection($student_id){
		try{
			$class_section =  StudentEnrol::join('class_div','student_enrol.class_id', '=', 'class_div.class_div_id')
				->where('student_enrol.student_id', $student_id)
				->where('student_enrol.active', "1")
				->orderBy('enrol_date', 'DESC')
				->orderBy('enrol_id', 'DESC')
				->value('class_div');
			
			return $class_section;
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Class', 'Section');
		}
	}
	public function updateAttendance(Request $request){
		$c = count($request->students);
		if($request->ajax() && $c > 0){
			$logRec = array();
			$operator = $request->operator;
			$attendance_date = $request->attendance_date;
			$class_id = $request->class_div_id;
			
			//check if attendance was earlier created, if yes, then delete it
			$existing =  StudentAttendance::where('class_id', $class_id)
						->where('attendance_date', $attendance_date)
						->get();
						
			if( count($existing) > 0){
				DB::table('student_attendance')
					->where('class_id', $class_id)
					->where('attendance_date', $attendance_date)
					->delete();
			}
			try{
				for($i=0;$i<$c;$i++){
					//update attendance table class
					$logRec = StudentAttendance::create(
							array(
							'student_id' => $request->students[$i],
							'attendance_date' => $request->attendance_date,
							'class_id' => $request->class_div_id,
							'arrival_time' => $request->arrival_time[$i], 
							'remarks' => $request->remarks[$i],
							'operator' => $request->operator
						)
					);
				}
			} catch (\Exception $e) {
				$this->report_error($e, 'Student', 'Attendance', 'Update');
			}
			return $logRec;
		}
	}
	
	public function getClassAttendance(Request $request){
		$date = $request->attendance_date;
		$class_id = $request->class_div_id;
		$records = StudentAttendance::join('students', 'students.student_id', '=', 'student_attendance.student_id')
					->where('class_id', $class_id)
					->where('attendance_date', $date)
					->orderBy('students.last_name', 'DESC')
					->get();
		return $records;
	}
	
	/////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////
	public function showTransfer(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		return view('student.transfer', compact('class'));
	}
	public function updateTransfer(Request $request){
		
		$c = count($request->students);
		if($request->ajax() && $c > 0){
			$logRec = array();
			$operator = $request->operator;
			$transfer_date = $request->transfer_date;
			$class_from = $request->class_from;
			$class_to = $request->class_to;
			try{
				
				for($i=0;$i<$c;$i++){
					//update the enrolment table as that contains the latest information on student class
					//ensure that the other enrolments for this student is active = 0
					StudentEnrol::where('student_id', $request->students[$i])->update(array('active' => "0"));
					//now update
					$item = new StudentEnrol();
					$item->class_id = $class_to; 
					$item->student_id = $request->students[$i]; 
					$item->enrol_date = $transfer_date;
					$item->active = "1";
					$item->operator = $operator;
					$item->save();
					
					//update the transfer table
					$logRec = ClassTransfer::create(
							array(
							'class_from' => $class_from, 
							'class_to' => $class_to,
							'student_id' => $request->students[$i], 
							'transfer_date' => $transfer_date,
							'operator' => $request->operator
						)
					);
				}
			} catch (\Exception $e) {
				$this->report_error($e, 'Student', 'Transfer', 'Update');
			}
			return $logRec;
		}
	}
	public function searchTransfer(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//$class_id = $request->class_div_id;
			
			//display the records here
			$records = DB::table('class_transfer as a')
				->join('students as d', 'd.student_id', '=', 'a.student_id')
				->join('class_div as b','a.class_from', '=', 'b.class_div_id')
				->join('class_div as c','a.class_to', '=', 'c.class_div_id')
				->select('d.reg_no', 'd.last_name', 'd.first_name', 'd.other_name', 
					'a.transfer_date','b.class_div as div_from','c.class_div as div_to')
				->where('a.transfer_date', '>=', $from_date)
				->where('a.transfer_date', '<=', $to_date)
				->orderBy('a.transfer_date', 'DESC')
				->get();
				
			return view('student.infotransfer', compact('records'));
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Transfer', 'Search');
		}
	}
	
	////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////DISCIPLINE
	public function showDiscipline(){
		return view('student.discipline');	
	}
	public function updateDiscipline(Request $request){
		
		try{
			$logRec = array();
			//get the student class
			$student_id = $request->student_id;
			$class_id = $this->getStudentClass($student_id);
			
			$logRec = StudentDiscipline::create(
					array(
					'class_id' => $class_id,
					'remarks' => $request->remarks,
					'infraction' =>  $request->infraction,
					'sanction' =>  $request->sanction,
					'student_id' => $student_id,
					'discipline_date' => $request->discipline_date,
					'operator' => $request->operator
				)
			);
			return $logRec;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Discipline', 'Update');
			Log::info($e->getMessage());
		}
	}
	public function searchDiscipline(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('student_discipline as a')
				->join('students as b', 'b.student_id', '=', 'a.student_id')
				->join('class_div as c','a.class_id', '=', 'c.class_div_id')
				->select('b.reg_no', 'b.last_name', 'b.first_name', 'b.other_name', 'a.infraction', 'a.remarks AS reasons',
					'a.discipline_date','c.class_div AS class_name', 'a.sanction', 'a.discipline_id')
				->where('a.discipline_date', '>=', $from_date)
				->where('a.discipline_date', '<=', $to_date)
				->orderBy('a.discipline_date', 'DESC')
				->get();
				
			return view('student.infodiscipline', compact('records'));
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Discipline', 'Search');
		}
	}
	public function delDiscipline(Request $request){
		try{
			$posted_by =  DB::table('student_discipline')->where('discipline_id',$request->discipline_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('student_discipline')->where('discipline_id',$request->discipline_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Student', 'Discipline', 'Delete', $request->discipline_id, $request->discipline_id, 
						'-');
				}
				return $logRec;
			}
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Discipline', 'Delete');
		}
	}
	////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////ACHIEVEMENT
	public function showAchievement(){
		return view('student.achievement');	
	}
	public function updateAchievement(Request $request){
		
		try{
			//get the student class
			$logRec = array();
			$student_id = $request->student_id;
			$class_id = $this->getStudentClass($student_id);
			$logRec = StudentAchievement::create(
					array(
					'class_id' => $class_id,
					'remarks' => $request->remarks,
					'award' => $request->award, 
					'achievement' =>  $request->achievement, 
					'student_id' => $student_id, 
					'achievement_date' => $request->achievement_date, 
					'operator' => $request->operator
				)
			);
			return $logRec;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Achievement', 'Update');
		}
	}
	public function searchAchievement(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('student_achievement as a')
				->join('students as b', 'b.student_id', '=', 'a.student_id')
				->join('class_div as c','a.class_id', '=', 'c.class_div_id')
				->select('b.reg_no', 'b.last_name', 'b.first_name', 'b.other_name', 'a.achievement', 'a.remarks',
					'a.achievement_date','c.class_div AS class_name', 'a.award', 'a.achievement_id')
				->where('a.achievement_date', '>=', $from_date)
				->where('a.achievement_date', '<=', $to_date)
				->orderBy('a.achievement_date', 'DESC')
				->get();
				
			return view('student.infoachievement', compact('records'));
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Achievement', 'Info');
		}
	}
	public function delAchievement(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('student_achievement')->where('achievement_id',$request->achievement_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('student_achievement')->where('achievement_id',$request->achievement_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Student', 'Achievement', 'Delete', $request->achievement_id, $request->achievement_id, 
						'-');
				}
				return $logRec;
			}
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Achievement', 'Delete');
		}
	}
	////////////////////////////////////////////////////////////////EXIT
	//////////////////////////////////////////////ACHIEVEMENT
	public function showExit(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		return view('student.exit', compact('class'));
	}
	public function updateExit(Request $request){
		
		try{
			$logRec = array();
			$c = count($request->students);
			$logOutcome = array();
			for($i=0; $i<$c; $i++){
				$student_id = $request->students[$i];
				$remarks = $request->remarks[$i];
				$reason = $request->reasons[$i];
				if( empty($remarks) ) $remarks = "None";
				if( !empty($reason) ){
					
					$logRec = StudentExit::create(
							array(
								'class_id' => $request->class_div_id,
								'exit_date' => $request->exit_date, 
								'remarks' => $remarks,
								'reason' => $reason,
								'student_id' => $student_id,
								'operator' => $request->operator
							)
						);
					if(count($logRec) > 0){
						$logOutcome = $logRec;
						//remove the student from the active list
						Registration::where('student_id',$student_id)->update(array('active' => "0"));
						StudentEnrol::where('student_id',$student_id)->update(array('active' => "0"));
						SysController::postLog('Student', 'Exit', 'Add', $logRec->exit_id, $student_id,'-');
					}
				}
			}
			return $logOutcome;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Exit', 'Update');
		}
	}
	public function searchExit(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('student_exit as a')
				->join('students as b', 'b.student_id', '=', 'a.student_id')
				->join('class_div as c','a.class_id', '=', 'c.class_div_id')
				->select('b.reg_no', 'b.last_name', 'b.first_name', 'b.other_name', 'a.remarks', 'a.reason', 'a.student_id',
					'a.exit_date','c.class_div AS class_name')
				->where('a.exit_date', '>=', $from_date)
				->where('a.exit_date', '<=', $to_date)
				->orderBy('a.exit_date', 'DESC')
				->get();
				
			return view('student.infotermination', compact('records'));
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Termination', 'Info');
		}
	}
	public function delExit(Request $request){
		try{
			$logRec = array();
			$posted_by = DB::table('student_exit')->where('exit_id',$request->exit_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('student_exit')->where('exit_id',$request->exit_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Student', 'Exit', 'Delete', $request->exit_id, $request->exit_id, 
						'-');
				}
				return $logRec;
			}
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Exit', 'Delete');
		}
	}
	
	///////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////PROMOTION
	public function showPromotion(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		return view('student.movement', compact('class'));
	}
	public function updatePromotion(Request $request){
		
		$c = count($request->students);
		if($request->ajax() && $c > 0){
			$logRec = array();
			$operator = $request->operator;
			$promotion_date = $request->promotion_date;
			$class_from = $request->class_from;
			$class_to = $request->class_to;
			$remarks = $request->remarks;
			try{
				for($i=0;$i<$c;$i++){
					//update the enrolment table as that contains the latest information on student class
					StudentEnrol::where('student_id', $request->students[$i])->update(array('active' => "0"));
					//now update
					$item = new StudentEnrol();
					$item->class_id = $class_to; 
					$item->student_id = $request->students[$i]; 
					$item->enrol_date = $promotion_date;
					$item->active = "1";
					$item->operator = $operator;
					$item->save();
					//update the transfer table
					$logRec = StudentPromotion::create(
							array(
							'class_from' => $class_from, 
							'class_to' => $class_to,
							'remarks' => $remarks,
							'student_id' => $request->students[$i], 
							'promotion_date' => $promotion_date, 
							'operator' => $operator
						)
					);
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Student', 'Promotion', 'Update');
			}
		}
	}
	public function searchPromotion(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('student_promotion as a')
				->join('students as d', 'd.student_id', '=', 'a.student_id')
				->join('class_div as b','a.class_from', '=', 'b.class_div_id')
				->join('class_div as c','a.class_to', '=', 'c.class_div_id')
				->select('d.reg_no', 'd.last_name', 'd.first_name', 'd.other_name', 'a.remarks',
					'a.promotion_date','b.class_div as div_from','c.class_div as div_to')
				->where('a.promotion_date', '>=', $from_date)
				->where('a.promotion_date', '<=', $to_date)
				->orderBy('a.promotion_date', 'DESC')
				->get();
			return view('student.infopromotion', compact('records'));
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Promotion', 'Info');
		}
	}
	//////////////////////////////////////////////////////////
	///////////////////////////////////////////TERMINATION
	public function showTermination(){
		return view('student.termination');	
	}
	public function updateTermination(Request $request){
		
		try{
			$logRec = array();
			//get the student class
			$student_id = $request->student_id;
			$class_id = $this->getStudentClass($student_id);
			
			$item = new StudentExit();
			$item->class_id = $class_id; 
			$item->remarks = $request->remarks; 
			$item->reason = "Terminate"; 
			$item->student_id = $student_id; 
			$item->exit_date = $request->terminate_date; 
			$item->operator = $request->operator; 
			$item->save();
			
			//update the registration and change active to zero
			StudentEnrol::where('student_id', $request->student_id)->update(array('active' => "0"));
			$logRec = Registration::where('student_id', $request->student_id)->update(array('active' => "0"));
			
			return $logRec;
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Termination', 'Update');
		}
	}
	public function searchTermination(Request $request){
		try{
			$from_date = $request->start_date;
			$to_date = $request->end_date;
			//display the records here
			$records = DB::table('student_exit as a')
				->join('students as b', 'b.student_id', '=', 'a.student_id')
				->join('class_div as c','a.class_id', '=', 'c.class_div_id')
				->select('b.reg_no', 'b.last_name', 'b.first_name', 'b.other_name', 'a.remarks AS reasons',
					'a.exit_date','c.class_div AS class_name')
				->where('a.exit_date', '>=', $from_date)
				->where('a.exit_date', '<=', $to_date)
				->where('a.reason', '=', "Terminate")
				->orderBy('a.exit_date', 'DESC')
				->get();
				
			return view('student.infotermination', compact('records'));
			
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Search', 'Termination');
		}
	}
	public function registration_import(Request $request){
		try{
			ini_set("auto_detect_line_endings", true);
			$global_array = array();
			$file_load = $request->file('table_file');
			
			//if a file is present on the request using the hasFile method
			if ($request->hasFile('table_file')) {
				//may verify that there were no problems uploading the file via the isValid method
				if ($file_load->isValid()) {
					$temp = $_FILES['table_file']['tmp_name'];
					//$file_contents = file_get_contents($temp);
					$handle = fopen($temp, 'r');
					$content = '<table class="table table-hover table-striped table-condensed" id="import_input" style="font-size:100%">';
					//$read = file_get_contents($temp); //read
					//$lines = explode("\n", $read);//get
					$i = 0;//initialize
					while (!feof($handle)) {
						$value[] = fgets($handle, 1024);
						$lineArr = explode("\t", $value[$i]);
							
						list($reg_no, $last_name, $first_name, $other_name, $dob, $gender, $religion, 
							$guardian, $office_add, $home_add, $phone, $email) = $lineArr;
						
						if( $i == 0){
							$content .='<thead>';
							$content .='<tr>';
							$content .='<th>'.$reg_no.'</th>';
							$content .='<th>'.$last_name.'</th>';
							$content .='<th>'.$first_name.'</th>';
							$content .='<th>'.$other_name.'</th>';
							$content .='<th>'.$dob.'</th>';
							$content .='<th>'.$gender.'</th>';
							$content .='<th>'.$religion.'</th>';
							$content .='<th>'.$guardian.'</th>';
							$content .='<th>'.$office_add.'</th>';
							$content .='<th>'.$home_add.'</th>';
							$content .='<th>'.$phone.'</th>';
							$content .='<th>'.$email.'</th>';
							$content .='</tr>';
							$content .='</thead>';
							$content .='<tbody>';
						}
						if( $i > 0){
							$content .='<tr>';
							$content .='<td name="reg_no[]">'.str_replace('"', '',$reg_no).'</td>';
							$content .='<td name="last_name[]">'.str_replace('"', '',$last_name).'</td>';
							$content .='<td name="first_name[]">'.str_replace('"', '',$first_name).'</td>';
							$content .='<td name="other_name[]">'.str_replace('"', '',$other_name).'</td>';
							$content .='<td name="dob[]">'.str_replace('"', '',$dob).'</td>';
							$content .='<td name="gender[]">'.str_replace('"', '',$gender).'</td>';
							$content .='<td name="religion[]">'.str_replace('"', '',$religion).'</td>';
							$content .='<td name="guardian[]">'.str_replace('"', '',$guardian).'</td>';
							$content .='<td name="office_add[]">'.str_replace('"', '',$office_add).'</td>';
							$content .='<td name="home_add[]">'.str_replace('"', '',$home_add).'</td>';
							$content .='<td name="phone[]">'.str_replace('"', '',$phone).'</td>';
							$content .='<td name="email[]">'.str_replace('"', '',$email).'</td>';
							$content .='</tr>';
						}
						$i++;
					}
					$content .='</tbody></table>';
					//file_put_contents('file_error.txt', $content. PHP_EOL, FILE_APPEND);
					fclose($handle);
					return $content;
				}
			}
		}
		catch (Exception $e) { 
			$this->report_error($e, 'Student', 'Registration', 'Upload'); 
		}
	}
	
	public function importUpdate(Request $request){
		//check the number of count
		$logRec = array();
		try{
			$reg_no = $request->reg_no;
			$operator = $request->operator;
			$row = DB::table('students')->where('reg_no', $reg_no)->first();
			if(count($row)< 1){
		
				$logRec = Registration::create(
					array(
						'reg_no' => $reg_no, 
						'reg_date' => $request->import_date, 
						'first_name' => $request->first_name, 
						'last_name' => $request->last_name, 
						'other_name' => $request->other_name, 
						'dob' => $request->dob, 
						'height' => '0', 
						'weight' => '0',
						'blood' => '0',
						'gender' => $request->gender,
						'district' => '-', 
						'region' => '-',
						'town' => '-',
						'lga' => '-',
						'state_origin' => '-',
						'nationality' => '-', 
						'religion' => $request->religion, 
						'address' => $request->home_add,
						'email' => $request->email, 
						'phone' => $request->phone, 
						'guardian' => $request->guardian, 
						'relationship' => '-', 
						'guard_office' => $request->office_add,  
						'guard_home' => $request->home_add, 
						'guard_email' => $request->email,  
						'guard_phone' => $request->phone,
						'photo' => "",
						'operator' => $request->operator
					)
				);
			}
		} catch (\Exception $e) {
			$this->report_error($e, 'Student', 'Registration', 'Import-Update');
		}
		return $logRec;
	}
	
	///////////////////////////////////////////////////////assessment setup
	public function showAssessSetup(){
		$class =  SchClass::orderBy('sequence', 'ASC')->get();
		$assessments =  DB::table('student_assessment')->orderBy('assessment', 'ASC')->get();
		return view('student.student_assessment', compact('class', 'assessments'));
	}
	public function updateAssessHead(Request $request){
		try{
			$records = array();
			if($request->ajax()){
				$record = DB::table('student_assessment')->where('assessment', '=', $request->assessment)->get();
				if(count($record) < 1)							
				{
					DB::table('student_assessment')->insert(
						 array(
							'assessment' =>  $request->assessment,
							'created_at' =>  date('Y-m-d H:i:s'),
							'updated_at' =>  date('Y-m-d H:i:s'),
							'operator' =>  $request->operator
						 )
					);
				}
			}
			//now get all the record for the drop-down list
			$assessment =  DB::table('student_assessment')->orderBy('assessment', 'ASC')->get();
			return $assessment;
		} catch (Exception $e) {$this->report_error($e, 'Student', 'Assessment', 'Update');}
	}
	public function getAssessmentHead(){
		$assessment =  DB::table('student_assessment')->orderBy('assessment', 'ASC')->get();
		return $assessment;
	}
	//have the class, the head and the parameter in the table here
	public function updateAssessPara(Request $request){
		try{
			$logRec = array();
			$c = count($request->assess_id);
			
			if($request->ajax() && $c > 0){
				//first remove any parameter already defined for this class
				DB::table('assessment_param')->where('class_id', '=', $request->class_id)->delete();
					
				for($i=0;$i<$c;$i++){
					
					$logRec = DB::table('assessment_param')->insert(
						 array(
							'assessment_id' =>  $request->assess_id[$i],
							'parameter' =>  $request->parameter[$i],
							'class_id' =>  $request->class_id,
							'created_at' =>  date('Y-m-d H:i:s'),
							'updated_at' =>  date('Y-m-d H:i:s'),
							'operator' =>  $request->operator
						 )
					);
				}
			}	
		} catch (Exception $e) {$this->report_error($e, 'Student', 'Assessment', 'Update');}
	}
	//this function populates the table with assessment already defined for the class
	public function editClassAssessment(Request $request){
		try{
			$class_id = $request->class_id;
			
			$records = DB::table('assessment_param as a')
					->join('student_assessment as b', 'b.assessment_id', '=', 'a.assessment_id')
					->select('a.assessment_id','assessment', 'parameter')
					->where('a.class_id', $class_id)
					->orderBy('assessment', 'ASC')
					->get();
					
			return $records;
		}catch (\Exception $e) {$this->report_error($e, 'Students', 'Assessment', 'Edit');}
	}
	public function getStudentAssessment(Request $request){
		//check if the parameter is defined for this class and extract
		$class_id = $request->class_id;
		$class_div_id = $request->class_div_id;
		$assessment_id = $request->assessment_id;
		$semester_id = $request->semester_id;
		//retrieve all the students in the class section
		//append the parameter, if any, define for that class
		//have this also in the heading, RegNo, Name, then the parameters
		$params = DB::table('assessment_param')
					->select('parameter', 'param_id')
					->where('class_id', $class_id)
					->where('assessment_id', $assessment_id)
					->distinct('param_id')
					->orderBy('parameter', 'ASC')
					->get();
		//create heading
		$content ='<table class="table table-borderless table-condensed table-hover table-fixed" id="student_assessment" style="font-size:100%; margin:5px;">';
		$content .='<thead class="thead-dark">';
		$content .='<tr>';
		$content .='<th width="15px" class="fixed">Reg No</th>';
		$content .='<th width="30px">Name</th>';
	
		foreach ($params as $param){
			$content .='<th width="50px">'.$param->parameter.'</th>';
		}
		$content .='</tr>';
		$content .='</thead>';
		$content .='<tbody>';
		//if there is content, then display it
		
		//NB: remember that when updaing, the column should be in the sequence as outlined above
		//now go through all the students and add for each
		$students = DB::table('student_enrol as a')
					->join('students as b','b.student_id', '=', 'a.student_id')
					->where('a.active', "1")
					->where('a.class_id', $class_div_id)
					->distinct('a.student_id')
					->orderBy('b.reg_no', 'ASC')
					->get();
		foreach ($students as $student){
			$content .='<tr>';
				
				$content .='<td width="15px"><input type="text" name="regno[]" class="regno" value="'.$student->reg_no.'" readonly style="width: 100%; border:none;background-color:transparent;"></td>';
				$content .='<td width="30px">'.$student->last_name. ', '.$student->first_name.'</td>';
				
				foreach ($params as $param){
					
					$student_id = $student->student_id;
					//check if rating exist for this student, then display it
					$remarks = DB::table('term_student_rating')
							->where('student_id', $student_id)
							->where('semester_id', $semester_id)
							->where('assessment_id', $assessment_id)
							->where('param_id', $param->param_id)
							->value('remarks');
					//file_put_contents('file_error.txt', 'remarks '. $remarks. PHP_EOL, FILE_APPEND);
					$content .= '<td width="50px"><textarea rows="2" class="form-control" name="'.$param->param_id.'[]">'.$remarks.'</textarea></td>';
				}
			$content .='</tr>';
		}
		$content .='</tbody>';
		return $content;
	}
	public function getClassAssessment(Request $request){
		$records = DB::table('assessment_param as a')
					->join('student_assessment as b', 'b.assessment_id', '=', 'a.assessment_id')
					->join('sch_classes as c','c.class_id', '=', 'a.class_id')
					->select('a.assessment_id','assessment')
					->where('a.class_id', $request->class_id)
					->distinct('a.assessment_id')
					->orderBy('assessment', 'ASC')
					->get();
		return $records;
	}
	public function updateStudentAssessment(Request $request){
		//get the whole form inside and iterate to record the records
		$class_id = $request->class_id_comment;
		$class_div_id = $request->class_div_id_comment;
		$assessment_id = $request->assessment_id;
		$semester_id = $request->semester_id_comment;
			
		try{
			$logRec = array();
			$c = count($request->regno);
			
			if($request->ajax() && $c > 0){
				
				$params = DB::table('assessment_param')
					->select('parameter', 'param_id')
					->where('class_id', $class_id)
					->where('assessment_id', $assessment_id)
					->distinct('param_id')
					->orderBy('parameter', 'ASC')
					->get();
					
				foreach ($params as $param){	
					$param_id = $param->param_id;
					//create the different records accordingly
					$userInput = $request->all();
					$i = 0;
					foreach( $userInput[$param_id] as $key=>$item){
						$reg_no = $request->regno[$i];
						$student_id = StudentController::getStudentID($reg_no);
						
						//delete the student record if exist
						
						DB::table('term_student_rating')
							->where('student_id', $student_id)
							->where('semester_id', $semester_id)
							->where('assessment_id', $assessment_id)
							->where('param_id', $param_id)
							->delete();
							
						/*file_put_contents('file_error.txt', 'student '.$reg_no. PHP_EOL, FILE_APPEND);
						file_put_contents('file_error.txt', 'item '. $item. PHP_EOL, FILE_APPEND);*/
						if(empty($item) || $item == NULL) $item = 'N/A';
						$logRec = DB::table('term_student_rating')->insert(
							 array(
								'assessment_id' =>  $assessment_id,
								'semester_id' =>  $semester_id,
								'param_id' =>  $param_id,
								'remarks' =>  $item,
								'student_id' =>  $student_id,
								'created_at' =>  date('Y-m-d H:i:s'),
								'updated_at' =>  date('Y-m-d H:i:s'),
								'operator' =>  $request->operator
							 )
						);
						$i = $i + 1;
					}
				}
				//return $logRec;
			}	
		} catch (Exception $e) {$this->report_error($e, 'Student', 'Assessment', 'Update');}
		
	}
	public function getAssessmentList(){
		$records = DB::table('assessment_param as a')
					->join('student_assessment as b', 'b.assessment_id', '=', 'a.assessment_id')
					->join('sch_classes as c','c.class_id', '=', 'a.class_id')
					->select('class_name', 'assessment', 'parameter', 'a.operator', 'a.reviewer', 'a.created_at')
					->orderBy('assessment', 'ASC')
					->get();
		return view('student.infoassessment', compact('records'));
	}
	public function excelAssessment(){
		try{
			$csv = DB::table('assessment_param as a')
					->join('student_assessment as b', 'b.assessment_id', '=', 'a.assessment_id')
					->join('sch_classes as c','c.class_id', '=', 'a.class_id')
					->select('class_name', 'assessment', 'parameter', 'a.operator', 'a.reviewer', 'a.created_at')
					->orderBy('assessment', 'ASC')
					->get();
			
			return \Excel::create('assessment-csvfile', function ($excel) use ($csv) {
					$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('class_name', 'assessment', 'parameter', 'operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
				
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (Exception $e) {$this->report_error($e, 'Student', 'Assessment', 'Excel');}
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
/*public function upload()
    { 
        if (isset($_POST['btn-upload'])) {

        $file = rand(1000, 100000) . "-" . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder = "uploads/";
        $location = $_FILES['file'];


        $new_size = $file_size / 1024; // new file size in KB
        $new_file_name = strtolower($file);
        $final_file = str_replace(' ', '-', $new_file_name); // make file name in lower case

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            //Prepare upload data
            $upload_data = Array(
                'file'  => $final_file,
                'type'  => $file_type,
                'size'  => $new_size
            );
            //Insert into tbl_uploads
            $this->db->insert('daily_data2', $upload_data);

            $handle = fopen("c:/wamp/www/codeigniter/uploads/$file", "r");

            if ($handle) {
                while (($line = fgets($handle)) !== false) {

                    $lineArr = explode("\t", "$line");
                    // instead assigning one by onb use php list -> http://php.net/manual/en/function.list.php
                    list($emp_id, $date_data, $abc, $def, $entry, $ghi) = $lineArr;

                    $daily_data = Array(
                        'emp_id'    => $emp_id,
                        'date_data' => $date_data,
                        'abc'       => $abc,
                        'def'       => $def,
                        'entry'     => $entry,
                        'ghi'       => $ghi
                    );

                    //Insert data
                    $this->db->insert('daily_data2', $daily_data);
                }
                fclose($handle);
            }
            //Alert success redirect to ?success
            $this->alert('successfully uploaded', 'index.php?success');
            } else {
                //Alert error
                $this->alert('error while uploading file', 'index.php?fail');
            }
        }
    }

    protected function alert($text, $location) {
        return "<script> alert('".$text."'); window.location.href='".$location."'; </script>";
    }
	
	In config/filesystems.php, you could do this... change the root element in public
	'disks' => [
	   'public' => [
		   'driver' => 'local',
		   'root'   => public_path() . '/uploads',
		   'url' => env('APP_URL').'/public',
		   'visibility' => 'public',
		]
	]
	and you can access it by
	
	Storage::disk('public')->put('filename', $file_content);
	
	you can use public_path(); function in laravel 
	or
	<img src="{{ public_path().'/website/'.$file->path.'/'.$file->file;}}"  />
	or
	<img src="/website/{{$file->path.$file->file}}"  />
	if (is_file('/path/to/foo.txt')) {
    //The path '/path/to/foo.txt' exists and is a file 
	} else {
	//The path '/path/to/foo.txt' does not exist or is not a file 
	}
	
	Storage::disk('public')->put($filename, $image)
	Storage::disk('public')->exists('file.jpg'); // bool
	
	The way I see it you can't use Storage::delete here because you are trying to delete a file in your public folder.
	
	Try one of these that I found on Laravel Recipies:
	
	// Delete a single file
	\File::delete($filename);
	You can use Illuminate\Filesystem\Filesystem for this. Laravel provides the File facade for easy access:
	
	File::deleteDirectory(public_path('path/to/folder'));
}*/
?>
