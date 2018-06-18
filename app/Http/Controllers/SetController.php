<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\models\Institution;
use App\models\Academic;
use App\models\Semester;
use App\models\LogCourse;
use App\models\SchEvents;
use App\models\EventType;
use Log; //the default Log file
use DB; //use the default Database
use Excel;
use Auth;

class SetController extends Controller
{
    protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		//$this->public_folder = $_SERVER['DOCUMENT_ROOT'];
		$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function showInstitute(Request $request){
		return view('settings.institute');	
	}
	public function infoInstitute(Request $request){
		
		//$records =  Institution::all();
		$records =  Institution::orderBy('sch_name', 'DESC')->get();
		return view('settings.infoinstitute', compact('records'));
	}
	public function updateInstitute(Request $request){
		ini_set('max_execution_time', 600);
		
		$contents = File::get(storage_path().DIRECTORY_SEPARATOR.'public_folder.txt');
		//$this->public_folder = $contents;
		//$contents = Storage::disk('local')->get('public_folder.txt');
		//file_put_contents('file_error.txt', $contents. PHP_EOL, FILE_APPEND);
		
		
		//at the server end, this is just: $this->public_folder = $_SERVER['DOCUMENT_ROOT'];
			
		//this will only allow one record. if record is found then update with current data
		if($request->ajax()){
			$logRec = array();

			//is there a record, then obtain the image names in the file and delete
			$old_logo = '';
			$old_photo = '';
			$allowed_filetypes = array('.jpg','.jpeg','.png','.gif','.JPG','.JPEG','.PNG','.GIF');
			$max_filesize = 10485760;
			
			$row = DB::table('institution')->first();
			//if there is a record: EDIT
			if(count($row)>0){
				//edit the table, where it exists
				$old_logo = $row->logo_image;
				$old_photo = $row->photo_image;
				
				$logRec = Institution::where('institute_id', $request->institute_id)->update(
					array(
						'sch_name' => $request->sch_name,
						'motto' => $request->motto,
						'reg_no' => $request->reg_no,
						'phone' => $request->phone,
						'email' => $request->email,
						'website' => $request->website,
						'country' => $request->country,
						'region' => $request->region,
						'address' => $request->address,
						'reg_date' => $request->reg_date
					)
				);
				if ($request->hasFile('photo_')) {
					try{
						//delete form storage path
						if( $old_photo !== NULL && $old_photo !== ""){
							//Storage::delete('photo/institute/'.$old_photo);
							//Storage::delete(public_path('photo/institute').$old_photo);
							Storage::disk('public')->delete('/photo/institute/'.$old_photo);
							//File::delete(public_path('photo/institute').$old_photo);
						}
						//delete from public path
						//$this->public_folder.'/reports/pdf'
					}catch (\Exception $e) {}
					
					$file_photo = $request->file('photo_');
					$photo_size = $request->file('photo_')->getClientSize();
					$name_photo = time().$file_photo->getClientOriginalName();
					$ext = substr($name_photo, strpos($name_photo,'.'), strlen($name_photo)-1);
					$name_photo = 'photo_pict'.$ext;
					//store in storage
					$request->photo_->storeAs('photo/institute', $name_photo);
					//note that the below code does not work in shared hosting
					//Storage::disk('public')->put('/photo/institute/'.$name_photo, File::get($file_photo));
					File::put($this->public_folder.'/photo/institute/'.$name_photo, File::get($file_photo));
					
					File::put($this->public_folder.'/img/'.'bg-1.jpg', File::get($file_photo));
					
					//$file_photo->move(public_path("/img"), "bg-1.jpg");
					
					$logRec = Institution::where('institute_id', $request->institute_id)->update(
						array('photo_image' => $name_photo)
					);
				}
				if ($request->hasFile('logo_')) {
					try{
						if( $old_logo !== NULL && $old_logo !== ""){
							//Storage::delete('photo/institute/'.$old_logo);
							Storage::disk('public')->delete('/photo/institute/'.$old_logo);
						}
					}catch (\Exception $e) {}
					
					$file_logo = $request->file('logo_');
					$logo_size = $request->file('logo_')->getClientSize();
					$name_logo = time().$file_logo->getClientOriginalName();
					$ext = substr($name_logo, strpos($name_logo,'.'), strlen($name_logo)-1);
					$name_logo = 'logo_pict'.$ext;
					
					$request->logo_->storeAs('photo/institute', $name_logo); //to store to the default storage
					File::put($this->public_folder.'/photo/institute/'.$name_logo, File::get($file_logo));
					
					$logRec = Institution::where('institute_id', $request->institute_id)->update(
						array('logo_image' => $name_logo)
					);
				}
				if ($request->hasFile('header_')) {
						
					$file_photo = $request->file('header_');
					$photo_size = $request->file('header_')->getClientSize();
					$name_photo = time().$file_photo->getClientOriginalName();
					$ext = substr($name_photo, strpos($name_photo,'.'), strlen($name_photo)-1);
					$name_photo = 'header_pict'.$ext;
					//store in storage
					$request->header_->storeAs('photo/institute', $name_photo);
					File::put($this->public_folder.'/photo/institute/'.$name_photo, File::get($file_photo));
					
					$logRec = Institution::where('institute_id', $request->institute_id)->update(
						array('header_image' => $name_photo)
					);
				}
			}else{
			//if a new file, then logo must be attached. if not, if picture is not attached, jus edit other inform
				try{
					
					$file_logo = $request->file('logo_');
					$file_photo = $request->file('photo_');
					$file_header = $request->file('header_');
					$name_logo = time().$file_logo->getClientOriginalName();
					$name_photo = time().$file_photo->getClientOriginalName();
					$name_header = time().$file_header->getClientOriginalName();
					$data = array_merge(['logo_image' => "{$name_logo}"],['photo_image' => "{$name_photo}"],['header_image' => "{$name_header}"], 
						$request->all());
					
					file_put_contents('file_error.txt', $this->public_folder.'/photo/institute/'.$name_photo. PHP_EOL, FILE_APPEND);
					//DB::table('institution')->delete();   //delete the table since only one record is allowed
					//now update the file
					$logRec = Institution::create($data);
					//update the log file
					if ($request->hasFile('photo_')) {
						try{
							//delete form storage path
							if( $old_photo !== NULL && $old_photo !== ""){
								Storage::disk('public')->delete('/photo/institute/'.$old_photo);
							}
						}catch (\Exception $e) {}
						
						$file_photo = $request->file('photo_');
						$photo_size = $request->file('photo_')->getClientSize();
						$name_photo = time().$file_photo->getClientOriginalName();
						$ext2 = substr($name_photo, strpos($name_photo,'.'), strlen($name_photo)-1);
						$name_photo = 'photo_pict'.$ext2;
						//store in storage
						$request->photo_->storeAs('photo/institute', $name_photo);
						File::put($this->public_folder.'/photo/institute/'.$name_photo, File::get($file_photo));
						File::put($this->public_folder.'/img/'.'bg-1.jpg', File::get($file_photo));
						
						$logRec = Institution::where('reg_no', $request->reg_no)->update(
							array('photo_image' => $name_photo)
						);
					}
					if ($request->hasFile('logo_')) {
						try{
							if( $old_logo !== NULL && $old_logo !== ""){
								//Storage::delete('photo/institute/'.$old_logo);
								Storage::disk('public')->delete('/photo/institute/'.$old_logo);
							}
						}catch (\Exception $e) {}
						
						$file_logo = $request->file('logo_');
						$logo_size = $request->file('logo_')->getClientSize();
						$name_logo = time().$file_logo->getClientOriginalName();
						$ext1 = substr($name_logo, strpos($name_logo,'.'), strlen($name_logo)-1);
						$name_logo = 'logo_pict'.$ext1;
						
						$request->logo_->storeAs('photo/institute', $name_logo); //to store to the default storage
						File::put($this->public_folder.'/photo/institute/'.$name_logo, File::get($file_logo));
						
						$logRec = Institution::where('reg_no', $request->reg_no)->update(
							array('logo_image' => $name_logo)
						);
					}
					if ($request->hasFile('header_')) {
						
						$file_photo = $request->file('header_');
						$photo_size = $request->file('header_')->getClientSize();
						$name_photo = time().$file_photo->getClientOriginalName();
						$ext2 = substr($name_photo, strpos($name_photo,'.'), strlen($name_photo)-1);
						$name_photo = 'header_pict'.$ext2;
						//store in storage
						$request->header_->storeAs('photo/institute', $name_photo);
						File::put($this->public_folder.DIRECTORY_SEPARATOR.'photo'.DIRECTORY_SEPARATOR.'institute'.DIRECTORY_SEPARATOR.$name_photo, 
							File::get($file_photo));
						
						$logRec = Institution::where('reg_no', $request->reg_no)->update(
							array('header_image' => $name_photo)
						);
					}
					return $logRec;
					//at the view level, if count > 0, then successful. if not failure
				} catch (\Exception $e) {
					$this->report_error($e, 'Setting', 'Institute', 'Update');
				}
			}
			return $logRec;
		}
	}
	public function getInstituteLogo(){
		//this displays the record for display for EDIT or REVIEW
		$name_logo = DB::table('institution')->value('logo_image');
		//$path = storage_path('photo/institute/');
		//$path = "{$path}/{$name_logo}";
		return $name_logo;
	}
	public function getInstituteName(){
		//get the name of the school
		$school_name = Institution::value('sch_name');
		if($school_name === NULL) $school_name = "";
		return $school_name;
	}
	public function editInstitute(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$item = Institution::where('institute_id', '=', $request->institute_id)->first();
				$record =  array();
				
				$record['institute_id'] = $item->institute_id;
				$record['sch_name'] = $item->sch_name;
				$record['motto'] = $item->motto;
				$record['reg_no'] = $item->reg_no;
				$record['phone'] = $item->phone;
				$record['email'] = $item->email;
				$record['website'] = $item->website;
				$record['country'] = $item->country;
				$record['region'] = $item->region;
				$record['address'] = $item->address;
				$record['reg_date'] = $item->reg_date;
				$record['logo_image'] = $item->logo_image;
				$record['photo_image'] = $item->photo_image;
				$record['header_image'] = $item->header_image;
				
				$path = storage_path('photo/institute/');
				
				$record['storage_path'] = $path;
				
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Setting', 'Institute', 'Edit');
			}
		}	
	}
	public static function getSchEmail(){
		$email = DB::table('institution')->value('email');
		return $email;
	}
	public static function getSchName(){
		$name = DB::table('institution')->value('sch_name');
		return $name;
	}
	public function delInstitute(Request $request){
		//delete a particular insitute record
		/*
		$logRec = array();
		try{
			$row = DB::table('institution')->first();
			
			if(count($row)>0){
				
				$old_logo = $row->logo_image;
				$old_photo = $row->photo_image;
								
				if( $old_photo !== NULL && $old_photo !== ""){
					Storage::delete('photo/institute/'.$old_logo, 'photo/institute/'.$old_photo);
				}
				//now delete
				$logRec = DB::table('institution')->where('institute_id',$request->institute_id)->delete();
				
				return $logRec;
			}else{
				return $logRec;
			}
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Setting', 'Institute', 'Delete');
		}*/
	}
	//////////////PDF generate
	public function pdfInstitute() {
		$pdf_file = 'school_details.pdf';
		$path = storage_path('reports/pdf').'/'.$pdf_file;
		
		try{ //A4: 210 × 297 millimeters
			$this->pdf->AddPage();
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			//File::put($this->public_folder.'/photo/institute/'.$name_photo, File::get($file_photo));
					
			/*
			$storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            The Path to your Storage disk would be :
			$storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix()
			I don't know any shorter solutions to that...
			You could share the $storagePath to your Views and then just call
			$storagePath."/myImg.jpg";*/
			
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Setting', 'Institute', 'Pdf');
			return redirect()->back();
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////ACADEMIC
	public function showAcademic(){
		return view('settings.academics');	
	}
	public function infoAcademic(Request $request){
		
		$records =  Academic::orderBy('date_from', 'DESC')->get();
		return view('settings.infoacademic', compact('records'));
	}
	public function updateAcademic(Request $request){
		//if institute id is blank, then it is a new record, else edit
		$logRec = array();
		if($request->ajax()){
			try{
				$academic_id = $request->academic_id;
				//get the date just before this new from date to ensure harmonisation with the previous last date
				$cur_no = DB::table('academics')
						->where('date_to', '<', $request->date_to)
						->orderBy('date_to', 'DESC')
						->first();
				//if there is a record, then ensure date harmonisation
				if(count($cur_no) > 0 && empty($academic_id)){
					//if there is a record, ensure that there is no gap in dates, if new
					$next_start = date("Y-m-d", strtotime("+1 day", strtotime($cur_no->date_to)) );
					$curr_start = $request->date_from;
					//the two should be the same
					if ($next_start == $curr_start){
						$logRec = Academic::create($request->all());
					}
				}
				//if there is no record, then just take anything
				else if(count($cur_no) < 1 && empty($academic_id)){
					$logRec = Academic::create($request->all());
				}
				else{
					$logRec = DB::table('academics')
					->where('date_from', $request->date_from)
					->update(array(
						'academic' => $request->academic, 
						'date_to' => $request->date_to, 
						'operator' => $request->operator
					));
				}
				
			} catch (\Exception $e) {$this->report_error($e, 'Setting', 'Academic', 'Update');}
		}
		return $logRec;
	}
	
	public function editAcademic(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = Academic::where('academic_id', '=', $request->academic_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Setting', 'Academic', 'Edit');
			}
		}	
	}
	public function getAcademicDates(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = Academic::where('academic_id', '=', $request->academic_id)->first();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Settings', 'Academic', 'Get');
			}
		}	
	}
	
	public function delAcademic(Request $request){
		try{
			$logRec = array();
			$posted_by =  DB::table('academics')->where('academic_id',$request->academic_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('academics')->where('academic_id',$request->academic_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Settings', 'Academics', 'Delete', $request->academic_id, $request->academic_id, 
						'-');
				}
				return $logRec;
			}
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Setting', 'Academic', 'Delete');
		}
	}
	//////////////PDF generate
	
	public function pdfAcademic(){
		
		try{ //A4: 210 × 297 millimeters
			$pdf_file = 'school_terms.pdf';
			$this->pdf->AddPage();
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'ACADEMIC YEARS', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records =  Academic::orderBy('date_from', 'DESC')->get();
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(20, 10, 'ID', 0, 0, 'L');
			$this->pdf->Cell(100, 10, 'Academic Year', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'Start Date', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'End Date', 0, 0, 'L');
			$this->pdf->Ln(5);
				
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(20, 10, $record->academic_id, 0, 0, 'L');
				$this->pdf->Cell(100, 10, $record->academic, 0, 0, 'L');
				$this->pdf->Cell(25, 10, $record->date_from, 0, 0, 'L');
				$this->pdf->Cell(25, 10, $record->date_to, 0, 0, 'L');
				$this->pdf->Ln(5);
			}
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Setting', 'Academic', 'Pdf');
			return redirect()->back();
		}
	}
	public function excelAcademic(){
		
		$records =  Academic::select('academic_id','academic','date_from','date_to', 'operator', 'reviewer', 'created_at')
					->orderBy('date_from', 'DESC')->get();
		
		//$sheet->fromArray($data);
		$csv = $records;  // stored the data in a array
		
		return Excel::create('academic-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('academic_id','academic','date_from','date_to', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
			
		})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
	}
	/////////////////////////////////////////////////////////////////////////////////////////////SESSION START
	public function showSemester(){
		//show all the semesters
		$records =  Academic::orderBy('date_from', 'DESC')->get();
		return view('settings.semester', compact('records'));
		
	}
	public function infoSemester(Request $request){
		
		$records = Semester::join('academics', 'academics.academic_id', '=', 'semester.academic_id')
					->select('semester_id', 'semester', 'academic','semester.date_from AS from_date','semester.date_to AS to_date')
					->orderBy('semester.date_from', 'DESC')
					->get();
			
		return view('settings.infosemester', compact('records'));
	}
	public function updateSemester(Request $request){
		//if institute id is blank, then it is a new record, else edit
		$logRec = array();
		if($request->ajax()){
			try{
				$semester_id = $request->semester_id;
				if($semester_id === NULL || $semester_id ==""){
					$logRec = Semester::create($request->all());
				
				}else{
					$logRec = Semester::updateOrCreate(['semester_id'=>$request->semester_id], $request->all());
				}	
			} catch (\Exception $e) {
				$this->report_error($e, 'Setting', 'Semester', 'Update');
			}
			return $logRec;
		}
	}
	
	public function editSemester(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = Semester::where('semester_id', '=', $request->semester_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Setting', 'Semester', 'Edit');
			}
		}	
	}
	public static function semester_start($semester_id){
		return DB::table('semester')->where('semester_id', '=', $semester_id)->value('date_from');
	}
	public static function semester_end($semester_id){
		return DB::table('semester')->where('semester_id', '=', $semester_id)->value('date_to');
	}
	public static function semester_name($semester_id){
		return DB::table('semester')->where('semester_id', '=', $semester_id)->value('semester');
	}
	public static function semester_next($semester_id){
		return DB::table('semester')->where('semester_id', '=', $semester_id + 1)->value('date_from');
	}
	public function delSemester(Request $request){
		try{
			$logRec = array();
			$posted_by =  DB::table('semester')->where('semester_id',$request->semester_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('semester')->where('semester_id',$request->semester_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Settings', 'Semester', 'Delete', $request->semester_id,$request->semester_id,'-');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Setting', 'Semester', 'Delete');
		}
	}
	//////////////PDF generate
	
	public function pdfSemester(){
		
		try{ //A4: 210 × 297 millimeters
			$pdf_file = 'school_terms.pdf';
			$this->pdf->AddPage();
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'SCHOOL SEMESTERS', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records = Semester::join('academics', 'academics.academic_id', '=', 'semester.academic_id')
			->select('semester_id', 'semester', 'academic','semester.date_from AS from_date','semester.date_to AS to_date')
			->orderBy('semester.date_from', 'DESC')
			->get();
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(20, 10, 'ID', 0, 0, 'L');
			$this->pdf->Cell(50, 10, 'Semester', 0, 0, 'L');
			$this->pdf->Cell(75, 10, 'Academic Year', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'Start Date', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'End Date', 0, 0, 'L');
			$this->pdf->Ln(5);
				
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(20, 10, $record->semester_id, 0, 0, 'L');
				$this->pdf->Cell(50, 10, $record->semester, 0, 0, 'L');
				$this->pdf->Cell(75, 10, $record->academic, 0, 0, 'L');
				$this->pdf->Cell(25, 10, date('d/m/Y', strtotime($record->from_date)), 0, 0, 'L');
				$this->pdf->Cell(25, 10, date('d/m/Y', strtotime($record->to_date)), 0, 0, 'L');
				$this->pdf->Ln(5);
			}
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Setting', 'Semester', 'Pdf');
			return redirect()->back();
		}
	}
	public function excelSemester(Request $request){
		
		$records = Semester::join('academics', 'academics.academic_id', '=', 'semester.academic_id')
			->select('semester_id', 'semester', 'academic',
				'semester.date_from AS from_date',
				'semester.date_to AS to_date', 
				'semester.operator AS user', 
				'semester.reviewer AS supervisor', 
				'semester.created_at AS date_created')
			->orderBy('semester.date_from', 'DESC')
			->get();
		
		$csv = $records;  // stored the data in a array
		
		return Excel::create('semester-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
				$sheet->fromArray($csv, null, 'A1', false, false);
				//to create heading
				$headings = array('semester_id', 'semester', 'academic','date_from','date_to', 'operator', 'reviewer', 'created_at');
				$sheet->prependRow(1, $headings);
			});
			
		})->save('xlsx', $this->public_folder.'/reports/excel', true);
		
	}
	
	///////////////////////////////////////////////////YEARLY EVENTS
	public function createEventType(Request $request){
		//ensure that the code is not duplicated
		try{
			$logRec = array();
			if($request->ajax()){
				$record = EventType::where('event_type', '=', $request->event_type)->get();
				if(count($record) == 0)		////if(count($group) > 0) OR if( is_null($group) )	
				{
					$logRec = EventType::create($request->all());
					
				}
			}
			return $logRec;
		} catch (Exception $e) {
			$this->report_error($e, 'Setting', 'Event-Type', 'Update');
			return redirect()->back();
		}
	}
	
	public function showEvents(){
		//show all the events
		$records =  Academic::orderBy('date_from', 'DESC')->get();
		$event_types = EventType::orderBy('event_type', 'DESC')->get();
		return view('settings.events', compact('event_types', 'records'));
		
	}
	public function infoEvents(Request $request){
		
		$records = SchEvents::join('event_type', 'event_type.event_type_id', '=', 'sch_events.event_type_id')
					->select('event_id', 'event_name', 'event_type','sch_events.date_from AS from_date','sch_events.date_to AS to_date')
					->orderBy('sch_events.date_from', 'ASC')
					->get();
			
		return view('settings.infoevent', compact('records'));
	}
	public function updateCoreEvents(Request $request){
		
		if($request->ajax()){
			$logRec = array();
			$c = count($request->semester);
			try{
				//update where the record exist
				$records = DB::table('semester')
					->where('academic_id',$request->academic_id)
					->orderBy('semester_id')
					->get();
					
				if(count($records)>1){
					$i = 0;
					foreach($records as $record){
						$semester_id = $record->semester_id;
						$logRec =  DB::table('semester')
							->where('semester_id',$semester_id)
							->update(array(
								'semester' => $request->semester[$i], 
								'date_from' => SetController::toDbaseDate($request->semester_start[$i]), 
								'date_to' => SetController::toDbaseDate($request->semester_end[$i]), 
								'operator' => $request->operator
							));
						$i = $i + 1;
					}
					//now update the events of it
					$records = DB::table('sch_events')
						->where('academic_id',$request->academic_id)
						->where('event_name', 'like', '%Holiday%')
						->orderBy('event_id')
						->get();
					
					$i = 0;
					foreach($records as $record){
						$event_id = $record->event_id;
						
						DB::table('sch_events')
							->where('event_id',$event_id)
							->update(array(
								'event_name' => $request->semester[$i].' - Holiday', 
								'date_from' => SetController::toDbaseDate($request->holiday_start[$i]), 
								'date_to' => SetController::toDbaseDate($request->holiday_end[$i]), 
								'operator' => $request->operator
							));
						$i = $i + 1;
					}
				}
				else{
					//check whether evety type of holiday exist in the event table
					$event = EventType::where('event_type', '=', 'Holiday')->first();
					if(empty($event)){
						//if empty, then create it
						 EventType::create(
								array(
								'event_type' => 'Holiday',
								'operator' => $request->operator
							)
						);
					}
					$logRec = EventType::where('event_type', '=', 'Holiday')->first();
					$event_type = $logRec->event_type_id;
					
					$c = count($request->semester);
					$logRec = array();
					for($i=0;$i<$c;$i++){
						//create semester
						$logRec = Semester::create(
							array(
								'semester' => $request->semester[$i], 
								'date_from' => SetController::toDbaseDate($request->semester_start[$i]), 
								'date_to' => SetController::toDbaseDate($request->semester_end[$i]), 
								'academic_id' => $request->academic_id, 
								'operator' => $request->operator
							)
						);
						//create the session holidays
						$logRec = SchEvents::create(
							array(
								'event_name' => $request->semester[$i].' - Holiday', 
								'date_from' => SetController::toDbaseDate($request->holiday_start[$i]), 
								'date_to' => SetController::toDbaseDate($request->holiday_end[$i]), 
								'academic_id' => $request->academic_id, 
								'event_type_id' => $event_type, 
								'operator' => $request->operator
							)
						);
					}
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Setting', 'Setting.Core-Events', 'Update');
			}
		}
	}
	public function updateEvents(Request $request){
		//if institute id is blank, then it is a new record, else edit
		if($request->ajax()){
			$logRec = array();
			try{
				$event_id = $request->event_id;
				if($event_id === NULL || $event_id ==""){
					$logRec = SchEvents::create($request->all());
					
				}else{
					$logRec = SchEvents::updateOrCreate(
						['event_id'=>$request->event_id], $request->all() );
					
				}
				return $logRec;
			} catch (\Exception $e) {
				$this->report_error($e, 'Setting', 'Events', 'Update');
			}
		}
	}
	
	public function editEvents(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = SchEvents::where('event_id', '=', $request->event_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'Setting', 'Events', 'Edit');
			}
		}	
	}
	public function delEvents(Request $request){
		try{
			$logRec = array();
			$posted_by =  DB::table('sch_events')->where('event_id',$request->event_id)->value('operator');
			if(Auth::user()->username == $posted_by || Auth::user()->role_id == '1'){
				$logRec = DB::table('sch_events')->where('event_id',$request->event_id)->delete();
				if(count($logRec) > 0){
					SysController::postLog('Settings', 'School Events', 'Delete', 
						$request->event_id, $request->event_id, '-');
				}
			}
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'Setting', 'Events', 'Delete');
		}
	}
	
	public function getAcademicCalendar(Request $request){
		$content ='';
		if($request->ajax()){
			//go through the semester record line by line and get the related holidays 
			//using the next date at the end of the term
			$terms = DB::table('semester')
							->where('academic_id',$request->academic_id)
							->orderBy('date_from', 'ASC')
							->get();
			foreach ($terms as $term){
				//get the first record with enddate greater than in event to get the holdays
				$end_date = $term->date_to;
				$holiday = DB::table('sch_events')
							->where('academic_id',$request->academic_id)
							->where('date_from','>', $end_date)
							->first();
				
				
				$content .='<tr>';
				$content .='<td><input type="text" class="form-control semester" name="semester[]" value="'.
							$term->semester.'"></td>';
				$content .='<td><input type="text" placeholder="dd/mm/yyyy" 
							class="form-control semester_start text-right" name="semester_start[]" value="'.
							date("d/m/Y", strtotime($term->date_from)).'" readonly></td>';
				$content .='<td><input type="text" placeholder="dd/mm/yyyy" 
							class="form-control semester_end text-right" name="semester_end[]" value="'.
							date("d/m/Y", strtotime($term->date_to)).'"></td>';
				$content .='<td>Holiday Dates =></td>';
				
				$content .='<td><input type="text" placeholder="dd/mm/yyyy" 
							class="form-control holiday_start text-right" name="holiday_start[]" value="'.
							date("d/m/Y", strtotime($holiday->date_from)).'" readonly></td>';
				$content .='<td><input type="text" placeholder="dd/mm/yyyy" 
							class="form-control holiday_end text-right" name="holiday_end[]"value="'.
							date("d/m/Y", strtotime($holiday->date_to)).'"></td>';
				$content .='<td><button class="btn btn-md remove-this"><i class="fa fa-trash-o fa-lg"></i></button></td>';
				$content .='</tr>';
				
			}
			return $content;
		}
	}
	//////////////PDF generate
	
	public function pdfEvents(){
		
		try{ //A4: 210 × 297 millimeters
			$pdf_file = 'school_terms.pdf';
			$this->pdf->AddPage();
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 10); //set font
			$this->pdf->Cell(200, 5, 'SCHOOL EVENTS', 0, 0, 'C');
			$this->pdf->Ln(10);
			//put the table here
			$records = SchEvents::join('event_type', 'event_type.event_type_id', '=', 'sch_events.event_type_id')
				->select('event_id', 'event_name', 'event_type',
					'sch_events.date_from AS from_date',
					'sch_events.date_to AS to_date')
				->orderBy('sch_events.date_from', 'ASC')
				->get();
			$this->pdf->SetX(10);
			
			$this->pdf->Cell(20, 10, 'ID', 0, 0, 'L');
			$this->pdf->Cell(100, 10, 'Event', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'Event Type', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'Start Date', 0, 0, 'L');
			$this->pdf->Cell(25, 10, 'End Date', 0, 0, 'L');
			$this->pdf->Ln(5);
				
			$this->pdf->SetFont('Arial', '', 10); //set font
			foreach ($records as $record){
				$this->pdf->SetX(10);
				$this->pdf->Cell(20, 10, $record->event_id, 0, 0, 'L');
				$this->pdf->Cell(100, 10, $record->event_name, 0, 0, 'L');
				$this->pdf->Cell(25, 10, $record->event_type, 0, 0, 'L');
				$this->pdf->Cell(25, 10, date('d/m/Y', strtotime($record->from_date)), 0, 0, 'L');
				$this->pdf->Cell(25, 10, date('d/m/Y', strtotime($record->to_date)), 0, 0, 'L');
				$this->pdf->Ln(5);
			}
			$path = storage_path('reports/pdf').'/'.$pdf_file;
			$this->pdf->Output($path, 'F');
			
			$destination = $this->public_folder.'/reports/pdf/'.$pdf_file;
			\File::copy($path,$destination);
			return $pdf_file;
			
		} catch (Exception $e) {
			$this->report_error($e, 'Setting', 'Events', 'Pdf');
			return redirect()->back();
		}
	}
	public function excelEvents(Request $request){
		try{
			$records = SchEvents::join('event_type', 'event_type.event_type_id', '=', 'sch_events.event_type_id')
				->select('event_id', 'event_name', 'event_type',
					'sch_events.date_from AS from_date',
					'sch_events.date_to AS to_date', 
					'sch_events.operator AS user', 
					'sch_events.reviewer AS supervisor', 
					'sch_events.created_at AS date_created')
				->orderBy('sch_events.date_from', 'ASC')
				->get();
			
			$csv = $records;  // stored the data in a array
			
			return Excel::create('sch_events-csvfile', function ($excel) use ($csv) {
				$excel->sheet('mySheet', function ($sheet) use ($csv) {
					$sheet->fromArray($csv, null, 'A1', false, false);
					//to create heading
					$headings = array('event_id', 'event ', 'event-type','date_from','date_to', 'operator', 'reviewer', 'created_at');
					$sheet->prependRow(1, $headings);
				});
				
			})->save('xlsx', $this->public_folder.'/reports/excel', true);
			
		} catch (Exception $e) {
			$this->report_error($e, 'Setting', 'Event', 'Excel');
			return redirect()->back();
		}
		return redirect()->back();
	}
	
	////////////////////////////////////////////////
	//////////////////////////////////UTILITY
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