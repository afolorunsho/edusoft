<?php

namespace App\Http\Controllers;

use Illuminate\Console\Scheduling\Schedule;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use Artisan;
use Exception;
use Illuminate\Routing\Controller;
use League\Flysystem\Adapter\Local;
use Response;


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

use Log; //the default Log file
use DB; //use the default Database
use Excel;
use App\User;
use App\Role;
use App\models\LogCourse;
use Backpack;
use Backup;
use BackupManager;
use Mail;
use ZipArchive;
use Auth;

class SysController extends Controller
{
    public $request;
	
	protected $pdf;
	private $public_folder;

    public function __construct(\App\Http\Controllers\extend\PDFA4 $pdf)
    {
        $this->pdf = $pdf;
		$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
    }
	public function showUsers(){
		$roles = Role::orderBy('role', 'ASC')->get();
		return view('sys.user_reg', compact('roles'));
	}
	public function showBackup(){
		return view('sys.mng_bkup');
	}
	public function noRegister(){
		$record = DB::table('users')
			->select('users.user_id', 'users.email','users.username','users.name','users.phone','roles.role','users.created_at','users.last_signon','users.photo', 'signon_cnt')
			->join('roles', 'roles.role_id', '=', 'users.role_id')
			->orderBy('users.username', 'ASC')
			->get();
		return view('sys.info_noregister', compact('record'));
		
	}
	public function editUser(Request $request){
		//this displays the record for display for EDIT or REVIEW
		if($request->ajax()){
			try{
				$record = User::where('user_id', $request->user_id)->get();
				return $record;
			}
			catch (\Exception $e) {
				$this->report_error($e, 'System', 'User', 'Edit');
			}
		}	
	}
	public function delUser(Request $request){
		
		//USERS cannnot be deleted. 
		try{
			$logRec = User::where('user_id', $user_id)->update(
				array(
					'active' => '0',
					'role_id' => '50'
				)
			);
			
			/*$username = $request->operator;
			$modulename = 'System';
			$formname = 'User';
			$operation = 'Delete';
			$record_id = $request->user_id;
			$record_code = $request->user_id;
			$activity = 'Delete a user';
			$this->postLog($modulename, $formname, $operation, $record_id, $record_code, 
					$activity, $username);
			*/			
			return $logRec;
		}
		catch (\Exception $e) {
			$this->report_error($e, 'System', 'User', 'Delete');
		}
	}
	public function updateUser(Request $request){
		//
		$allowed_filetypes = array('.jpg','.jpeg','.png','.gif','.JPG','.JPEG','.PNG','.GIF');
		$max_filesize = 10485760;
		$logRec = array();
		if($request->ajax()){
			//$this->public_folder = $_SERVER['DOCUMENT_ROOT'].'/edu_soft/public';
			$contents = File::get(storage_path().DIRECTORY_SEPARATOR.'public_folder.txt');
			//$this->public_folder = $contents;
			try{
				/////////////////////////////
				$name_photo = "";
				$user_id = $request->user_id;
				
				if ($request->hasFile('photo_')) {
					$file_photo = $request->file('photo_');
					$photo_size = $file_photo->getClientSize();
					$name_photo = time().$file_photo->getClientOriginalName();
					
					$ext2 = substr($name_photo, strpos($name_photo,'.'), strlen($name_photo)-1);
					$name_photo = $user_id.$ext2;
					
					if(!in_array($ext2,$allowed_filetypes))die('The file you attempted to upload is not allowed:'.$name_photo);
					if($photo_size > $max_filesize) die('The file you attempted to upload is too large:'.$name_photo);
					
					//if(file_exists( Storage::disk('public')->has('photo/user/'.$user_id.$ext2) )) {
    					//Storage::delete('photo/user/'.$user_id.'*');
						Storage::disk('public')->delete('/photo/user/'.$user_id.'*');
					//}
					
					$request->photo_->storeAs('photo/user', $user_id.$ext2);
					//$file_photo->move(public_path('photo/user'), $name_photo);
					File::put($this->public_folder.'/photo/user/'.$name_photo, File::get($file_photo));
					
					//update the photo where it was uploaded
					User::where('user_id', $user_id)->update(
						array(
							'photo' => $name_photo
						)
					);
				}
				///////////////////////////////
				//file_put_contents('file_error.txt', $name_photo. PHP_EOL, FILE_APPEND);
				//update the other mandatory information
				$logRec = User::where('user_id', $user_id)->update(
					array(
						//'username' => $request->operator,
						//'name' => $request->name,
						'role_id' => $request->role_id
					)
				);
				$username = $request->operator;
				$modulename = 'System';
				$formname = 'User';
				$operation = 'Edit';
				$record_id = $user_id;
				$record_code = $request->name;
				$activity = 'Add A Role';
				$this->postLog($modulename, $formname, $operation, $record_id, $record_code, 
						$activity);
				
			} catch (\Exception $e) {
				$this->report_error($e, 'System', 'User', 'Update');
			}
			return $logRec;
		}
	}
	public function table_export(){
		$now = date("Y-m-d.H.m"); 
		$now = $now.'-backup.sql';
		$tables = DB::select('SHOW TABLES');
		
		foreach ($tables as $table) {
			foreach ($table as $key => $db_table){
				
				$path = storage_path('app/public/export');
				$file = $path. '/'. $db_table.'-'.$now;
				//file_put_contents('file_error.txt', $file. PHP_EOL, FILE_APPEND);
				try{
					//DB::statement("SELECT * INTO OUTFILE '$file' FROM '$db_table'");
					//DB::statement("SELECT * INTO OUTFILE '".addslashes($file)."' FROM ". $db_table. " FIELDS TERMINATED BY '#' ".
					//" ENCLOSED BY '' LINES TERMINATED BY '\n'");
					//DB::statement("SELECT * INTO OUTFILE '".addslashes($file)."' FROM ". $db_table);
					$sql = "SELECT * INTO OUTFILE '".addslashes($file)."' FROM ". $db_table;
					//$sql = "SELECT * INTO OUTFILE '".addslashes($file)."' FROM ". $db_table. 
					//	" FIELDS TERMINATED BY '|' ENCLOSED BY '\"'  LINES TERMINATED BY '\n'";
					DB::statement($sql);
				
				} catch (Exception $e) {
					$this->report_error($e, 'System', 'Table', 'Export');
				}
			}
		}
	}
	////////////////////////////////////////////////////BACKUP
	public function viewBackup()
    {
		return view("sys.backups");
    }
	public function infoBackup()
    {
		if (!count(config('laravel-backup.backup.destination.disks'))) {
			
            dd(trans('backpack::backup.no_disks_configured'));
        }
        $this->data['backups'] = [];
		
        foreach (config('laravel-backup.backup.destination.disks') as $disk_name) {
            $disk = Storage::disk($disk_name);
            $adapter = $disk->getDriver()->getAdapter();
            $files = $disk->allFiles();
            // make an array of backup files, with their filesize and creation date
            foreach ($files as $k => $f) {
                // only take the zip files into account
                if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                    $this->data['backups'][] = [
                        'file_path'     => $f,
                        'file_name'     => str_replace('backups/', '', $f),
                        'file_size'     => $this->formatSizeUnits($disk->size($f)),
                        'last_modified' => date("F d Y H:i:s.", $disk->lastModified($f)),
						'file_age' 		=> $this->humanTiming($disk->lastModified($f)),
                        'disk'          => $disk_name,
                        'download'      => ($adapter instanceof Local) ? true : false,
                        ];
                }
            }
        }
        // reverse the backups, so the newest one would be on top
        $this->data['backups'] = array_reverse($this->data['backups']);
        $this->data['title'] = 'Backups';
		
        return view("sys.infobackup", $this->data);
    }
	public function win_backup()
    {
        $ds = DIRECTORY_SEPARATOR;
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');
        
        //$ts = time();
		$now = date("Y-m-d-H-m").'-backup.sql';
		$ts = time();
		
		$path = realpath('storage'.$ds. 'backups' . $ds);
        $file = date('Y-m-d-H-m', $ts) . '-dump-' . $database . '.sql';
        $command = sprintf('mysqldump -h %s -u %s -p\'%s\' %s > %s', $host, $username, $password, $database, $path . $file);
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        exec($command);
    }
	//this is the active functio 
	public function createBackup()
    {
		//created in storage/app/public/backups/http---localhost
        try {
            ini_set('max_execution_time', 600);
            // start the backup process
            //Artisan::call('backup:run');
			Artisan::call('backup:run', ['--only-db' => true, '--disable-notifications' => true]);
            return redirect()->back();
			
        } catch (Exception $e) {
            $this->report_error($e, 'System', 'Backup', 'Add');
			return redirect()->back();
        }
    }
	public function downloadBackup(Request $request)
    {
		try {
            ini_set('max_execution_time', 600);
            $file = $request->record_id;
			//replace slashes, front and back with file separator
			$file = str_replace('/', DIRECTORY_SEPARATOR , $file);
			$path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'backups'.DIRECTORY_SEPARATOR.$file);
			//file_put_contents('file_error.txt', $path. PHP_EOL, FILE_APPEND);
			
			$destination = $request->destination.DIRECTORY_SEPARATOR.'edusoft_backup'.DIRECTORY_SEPARATOR;
			//file_put_contents('file_error.txt', $destination. PHP_EOL, FILE_APPEND);
			
			//if directory is not found, create it: http---localhost
			if (!file_exists($destination)) {
				//you need to add http---localhost because this is part of the file name
				File::makeDirectory($destination.'http---localhost'.DIRECTORY_SEPARATOR, 0755, true);
			}
			
			if(!copy($path, $destination.$file))
			 {
				 return "failed to copy $file";
			 }
			 else
			 {
				return "copied file into $destination.$file\n";
			 }
		}catch (Exception $e) {
            $this->report_error($e, 'System', 'Backup', 'Download');
        }
    }
	
	public function restoreExtBackup(Request $request)
    {
		//file_put_contents('file_error.txt', 'start 0'. PHP_EOL, FILE_APPEND);
		try {
            ini_set('max_execution_time', 600);
			
           	//file_put_contents('file_error.txt', 'start 1'. PHP_EOL, FILE_APPEND);
			//extract the file from wherever to restore-backup file
			if ($request->hasFile('backup_file')) {
				
				$backup_file = $request->file('backup_file');
				
				$zip = new ZipArchive;
				if($zip->open($backup_file) === TRUE) {
				   $zip->extractTo('restore-backup');
				   $zip->close();
				}
				$location = public_path('restore-backup'.DIRECTORY_SEPARATOR.'db-dumps'.DIRECTORY_SEPARATOR.'mysql-collegeDB.sql');
				
				$sql_dump = File::get($location);
				DB::connection()->getPdo()->exec($sql_dump);
			}
			return redirect()->back();
		}catch (Exception $e) {
            $this->report_error($e, 'System', 'Backup', 'Download');
        }
    }
	public function restoreIntBackup(Request $request)
    {
		try {
            ini_set('max_execution_time', 600);
           
			$path = realpath('storage'.DIRECTORY_SEPARATOR.'backups'.DIRECTORY_SEPARATOR.$request->record_id);
			
			$zip = new ZipArchive;
			if($zip->open($path) === TRUE) {
			   $zip->extractTo('restore-backup');
			   $zip->close();
			}
			$location = public_path('restore-backup'.DIRECTORY_SEPARATOR.'db-dumps'.DIRECTORY_SEPARATOR.'mysql-collegeDB.sql');
			
			$sql_dump = File::get($location);
			DB::connection()->getPdo()->exec($sql_dump);
			//DB::unprepared(file_get_contents($location));
			
			return redirect()->back();
		}catch (Exception $e) {
            $this->report_error($e, 'System', 'Backup', 'Restore');
        }
    }
	/**
     * Downloads a backup zip file.
     */
    public function download(Request $request)
    {
		try{
			$this->request = $request;
			$path = realpath('storage/backups/'.$this->request->record_id);
			$sch_mail = SetController::getSchEmail();
			/*$zip = new ZipArchive;
			if($zip->open($path) === TRUE) {
			   $zip->extractTo('email-backup');
			   $zip->close();
			}*/
			$sch_mail = SetController::getSchEmail();
			$sch_name = SetController::getSchName();
			
			$content = "Please find attached Backup";
			$data = ['content' => $content,
					'sender' => $sch_mail,
					'path' => $path ];
			
			\Mail::send('sys.infoemail', $data, function ($message) use ($sch_mail, $sch_name, $path){
				$message->from($sch_mail, $sch_name);
				$message->to($sch_mail, $sch_name);
				$message->subject('Backup From '.$sch_name);
				$message->attach($path);
			});
			//delete the backup 
			//File::deletedirectory(realpath('email-backup'));
			return redirect()->back();
		}catch (Exception $e) {
            $this->report_error($e, 'System', 'Backup', 'Download');
        }/*
			$email = StudentController::getStudentEmail($reg_no);
			$guardian = StudentController::getGuardian($reg_no);
			$student = StudentController::getFirstName($reg_no);
			$sch_mail = SetController::getSchEmail();
			$sch_name = SetController::getSchName();
			
			$content = "Please find attached ". $subject. " For ". $student;
			$data = ['content' => $content,
					'subject' => $subject,
					'sender' => $sch_mail,
					'guardian' => $guardian,
					'receiver' => $email,
					'path' => $pdf_name ];
			
			\Mail::send('sys.infoemail', $data, function ($message) use ($sch_mail, $sch_name, $sch_name, $guardian, $email, $file){
				$message->from($sch_mail, $sch_name);
				$message->to($email, $guardian);
				$message->subject('Message From '.$sch_name);
				$message->attach($file);
			});*/
    }
	public function getDownload()
	{
		//PDF file is stored under project/public/download/info.pdf
		$file= public_path(). "/download/info.pdf";
	
		$headers = array('Content-Type: application/pdf',);
	
		return Response::download($file, 'filename.pdf', $headers);
		//return response()->download($myFile, $newName, $headers);
	}
    /**
     * Deletes a backup file.
     */
    public function delBackup(Request $request){
    	$this->request = $request;
		
		$backup_date = strtotime(substr($this->request->record_id, 17, 10));
		$current_date = time();
		$day_interval = ($current_date-$backup_date)/ (60 * 60 * 24);
		
		$path = realpath('storage/backups/'.$this->request->record_id);
		
		if( $day_interval > 90){
			try{
				if (!unlink($path)){
				  return "Error deleting File";
				}else{
				  return "File Deleted";
				}
			} catch (Exception $e) {
			   $this->report_error($e, 'System', 'Backup', 'Delete');
				return redirect()->back();
			}
		}else{
			return "You cannot delete a file that is less than 90 days";
		}
    }

	public function create_bkup(){
		$path = storage_path('app/public/backups');
		$now = date("Y-m-d-H-m").'-backup.sql';
		$destination =  $path. '/-'. $now.'-backup.sql';
		//file_put_contents('file_error.txt', $path. PHP_EOL, FILE_APPEND);
		
		try {
            ini_set('max_execution_time', 300);
			Artisan::call('db:backup',
				[
					'--database' => 'mysql',
					'--destination' => 'local',
					'--destinationPath' => $destination,
					'--compression' => 'gzip'
				]
			);
			$output = Artisan::output();
            Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n".$output);
            
        } catch (Exception $e) {
           $this->report_error($e, 'System', 'Backup', 'Add');
        }
        return 'success';
	}
	protected function schedule(Schedule $schedule)
	{
	   $schedule->command('backup:clean')->daily()->at('04:00');
	   $schedule->command('backup:run')->daily()->at('05:00');
	}
	public function postDbBackUp() {
		$path = storage_path('backups');
		$now = date("Y-m-d.H.m").'-backup.sql';
		//file_put_contents('file_error.txt', $path. PHP_EOL, FILE_APPEND);
		
		try {
            ini_set('max_execution_time', 300);
			Artisan::call('db:backup',
				[
					'--database' => 'mysql',
					'--destination' => 'local',
					'--destinationPath' =>$path,
					'--compression' => 'gzip',
					'--filename' => $now,
					'--timestamp" => "Y-m-d H:i:s'
				]
			);
		}
		catch(Exception $e) {
			$this->report_error($e, 'System', 'Backup', 'Post');
		}
	}
	
	function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
	}
	function humanTiming($time)
	{
		// to get the time since that moment
		$time = time() - $time;
	
		// time unit constants
		$timeUnits = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);
	
		// iterate over time contants to build a human 
		$humanTiming = '';
		foreach ($timeUnits as $unit => $text)
		{
			if ($time < $unit)
				continue;
			$numberOfUnits = floor($time / $unit);
	
			// human readable token for current time unit
			$humanTiming = $humanTiming.' '.$numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	
			// compute remaining time for next loop iteration
			$time -= $unit*$numberOfUnits;
		}
		return $humanTiming;
	}
	///send mail to parents
	public static function sendStudentMail($reg_no, $subject, $pdf_name, $storage){
		//save the file
		//$path = storage_path('reports/pdf').'/'.$pdf_name;
		$file = $storage;
		//file must be found first
		//$file = storage_path('reports/pdf/bills').'/'.$pdf_name;
		if (file_exists($file)){
			$email = StudentController::getStudentEmail($reg_no);
			$guardian = StudentController::getGuardian($reg_no);
			$student = StudentController::getFirstName($reg_no);
			$sch_mail = SetController::getSchEmail();
			$sch_name = SetController::getSchName();
			$school = SetController::getSchName();
			//email can only be sent where there are addresses
			if(!empty($email) && !empty($sch_mail)){ 
				$content = "Please find attached ". $subject. " For ". $student;
				$data = ['content' => $content,
						'subject' => $subject,
						'sender' => $sch_mail,
						'guardian' => $guardian,
						'receiver' => $email,
						'path' => $pdf_name ];
				
				\Mail::send('sys.infoemail', $data, function ($message) use ($sch_mail, $sch_name, $guardian, $email, $file){
					$message->from($sch_mail, $sch_name);
					$message->to($email, $guardian);
					$message->subject('Message From '.$sch_name);
					$message->attach($file);
				});
			}
		}
	}
	////////////////////////////////////////////LOG File
	public static function postLog($modulename, $formname, $operation, $record_id, $record_code, $activity){
			
		$item = new LogCourse();
		$item->username = Auth::user()->username; 
		$item->modulename = $modulename; 
		$item->formname = $formname;
		$item->operation = $operation;
		$item->record_id = $record_id;
		$item->record_code = $record_code;
		$item->activity = $activity;
		$item->save();
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