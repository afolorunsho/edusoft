<?php
namespace App\Http\Controllers;
use Alert;
use App\Http\Requests;
use Artisan;
use Log;
use Storage;
use Auth;

class BackupController extends Controller
{
    public function index()
    {
        //this is to populate the view
		$disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);
        $files = $disk->files(config('laravel-backup.backup.name'));
        $backups = [];
        // make an array of backup files, with their filesize and creation date
        foreach ($files as $k => $f) {
            // only take the zip files into account
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('laravel-backup.backup.name') . '/', '', $f),
                    'file_size' => $disk->size($f),
                    'last_modified' => $disk->lastModified($f),
                ];
            }
        }
        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);
        //poulate the view
		return view("sys.backups")->with(compact('backups'));
    }
    public function create()
    {
        try {
            ini_set('max_execution_time', 600);
            // start the backup process
            Artisan::call('db:backup');
			
			//Artisan::call('db:backup',['dump' => 'theara.sql']);
			//php artisan db:backup --database=mysql
			
			/*Artisan::call("db:backup", [
				"--filename"   => $filename,
				"--gzip"       => "",
				"--local-path" => $fileLocation,
				"--cleanup"    => ""
			]);*/
			//Artisan::call('backup:run', ['--only-db' => true]);
			/*
			$execValue = Artisan::call("db:backup", [
			 "--database" => "mysql", // This missed
			 "--destination" => "dropbox",
			 "--destinationPath" => "/db_",
			 "--timestamp" => "Y-m-d H:i:s",
			 "--compression" => "gzip",
			 ]);*/
        } catch (Exception $e) {
            Response::make($e->getMessage(), 500);
			$this->report_error($e, 'Backup', 'Backup', 'Add');
        }
        //return 'success';
    }
	public function restore()
    {
        try {
            ini_set('max_execution_time', 600);
            Artisan::call('db:restore',['test'=> $test]);
			//php artisan backup:mysql-restore --filename=backup_filename
			 
        } catch (Exception $e) {
            Response::make($e->getMessage(), 500);
			$this->report_error($e, 'Backup', 'Backup', 'Add');
        }
        //return 'success';
    }
    public function download($file_name)
    {
        $file = config('laravel-backup.backup.name') . '/' . $file_name;
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);
        if ($disk->exists($file)) {
            $fs = Storage::disk(config('laravel-backup.backup.destination.disks')[0])->getDriver();
            $stream = $fs->readStream($file);
            return \Response::stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $fs->getMimetype($file),
                "Content-Length" => $fs->getSize($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
    /**
     * Deletes a backup file.
     */
    public function delete($file_name)
    {
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);
        if ($disk->exists(config('laravel-backup.backup.name') . '/' . $file_name)) {
            $disk->delete(config('laravel-backup.backup.name') . '/' . $file_name);
            return redirect()->back();
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
	public function report_error($e, $module, $form, $task){
		file_put_contents('file_error.txt', $e->getMessage(). ' \n'. $module. '-'. $form. '-'. $task. PHP_EOL, FILE_APPEND);
		//Log::useFiles(storage_path().'/laravel.log');
		Log::info($e->getMessage());
	}
}
/*
namespace App\Http\Controllers;

use Alert;
use App\Http\Requests;
use Artisan;
use Log;
use Storage;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);

        $files = $disk->files(config('laravel-backup.backup.name'));
        $backups = [];
        // make an array of backup files, with their filesize and creation date
        foreach ($files as $k => $f) {
            // only take the zip files into account
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('laravel-backup.backup.name') . '/', '', $f),
                    'file_size' => $disk->size($f),
                    'last_modified' => $disk->lastModified($f),
                ];
            }
        }
        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);

        return view("backup.backups")->with(compact('backups'));
    }

    public function create()
    {
        try {
            // start the backup process
            Artisan::call('backup:run');
            $output = Artisan::output();
            // log the results
            Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);
            // return the results as a response to the ajax call
            Alert::success('New backup created');
            return redirect()->back();
        } catch (Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    //Downloads a backup zip file.
    public function download($file_name)
    {
        $file = config('laravel-backup.backup.name') . '/' . $file_name;
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);
        if ($disk->exists($file)) {
            $fs = Storage::disk(config('laravel-backup.backup.destination.disks')[0])->getDriver();
            $stream = $fs->readStream($file);

            return \Response::stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $fs->getMimetype($file),
                "Content-Length" => $fs->getSize($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
	//Deletes a backup file.
    public function delete($file_name)
    {
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);
        if ($disk->exists(config('laravel-backup.backup.name') . '/' . $file_name)) {
            $disk->delete(config('laravel-backup.backup.name') . '/' . $file_name);
            return redirect()->back();
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
}
*/
?>
