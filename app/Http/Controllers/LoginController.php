<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Log;
use Mail;
use DB; //use the default Database

class LoginController extends Controller
{
   	protected $username = 'username';
	protected $redirectTo = '/dashboard';
	protected $guard = 'web';
	
	public function getMainMenu(){
		return view('index');	
	}
	public function getConsult(){
		return view('sconsult');	
	}
	public function postCaptchaForm(Request $request){
		
		try{
			 $token = $request->input('g-recaptcha-response');
			 $message = $request->message;
			 $phone = $request->phone;
			 $email = $request->email;
			 $surname = $request->surname;
			 $name = $surname. ', '. $request->name;
			 $content =  $message . PHP_EOL . 'From: '.$name.PHP_EOL.'Tel: '.$phone.PHP_EOL.'Email: '.$email.PHP_EOL.'www.pubtechltd.com';  	 
			 if($token){
				$client = new Client();
				$response = $client->post(
					'https://www.google.com/recaptcha/api/siteverify',
					['form_params'=>
						[
							'secret'=> '6LcJzDoUAAAAAIg0wz5qr_jH6n2uuTjB7By7Z1bJ',
							'response'=>$token
						 ]
					]
				);
				$results = json_decode($response->getBody()->getContents());
				if($results->success){
					
					\Mail::send('infoemail', ['name' => $name, 'email' => $email, 'title' => 'Contact', 'content'  => $content ], 
						function($message) use ($email){
						$message->to('afolorunsho@gmail.com', 'Abayomi Folorunsho');
						$message->to('infopubtech@gmail.com', 'PubTech Business Center');
						$message->to('direct_tous@pubtechltd.com', 'PubTech Business Center');
						$message->to($email, '');
						$message->subject('Contact Us');
						$message->from($email);
					});
					// check for failures
                    if (\Mail::failures()) {
                        return "Failure";
                    }
                    if( count(\Mail::failures()) > 0 ) {
                        return "Failure";
                    }
					return "Success";
				}else{
					return "Failure";
				}
			 }
		} catch (\Exception $e) {
			 file_put_contents('file_error.txt', $e->getMessage(). PHP_EOL, FILE_APPEND);
		}
	 }
	public function getLogin(){
		if( Auth::guard('web')->check()){
			return redirect()->route('dashboard');	
			$this->currentUser = Auth::User();
		}
		return view('login')->with('status', 'Sorry, access to this application is restricted. Please use the Signup button to register');	
	}
	public function postLogin(Request $request){
		// create our user data for the authentication
		$userdata = array(
			'username'=>$request->username,
			'password'=>$request->password,
			'active'=>1
		);
		// attempt to do the login
		//if (Auth::attempt($userdata))
		if ( Auth::guard('web')->attempt($userdata) )
		{
			//move the last login to previous login
			//get previous login
			$previous = DB::table('users as a')
					->where('a.username', '=',  $request->username)
					->value('last_signon');	
			if(empty($previous)) $previous = date("Y-m-d H:i:s");
			//update last login
			$count = DB::table('users as a')
					->where('a.username', '=',  $request->username)
					->value('signon_cnt');	
			if(empty($count)) $count = 0;
						
			User::where('username', '=',  $request->username)
				->update(array(
					'previous_signon'  => $previous,
					'last_signon'  => date("Y-m-d H:i:s"),
					'signon_cnt' => $count + 1
					)
				);
			// validation successful
			return redirect()->intended('dashboard');
		}
		else
		{
			return redirect()->route('signon')->with('status', 'Sorry, access to this application is restricted. Please use the Signup button to register');	
		}
	}
	public function changePassword(Request $request){
		// create our user data for the authentication
		$logRec = array();
		//put this in array
		$userdata = array(
			'username'=>$request->operator,
			'password'=>$request->old_password,
			'active'=>1
		);
		if ( Auth::guard('web')->attempt($userdata) )
		{
			//if validated, then change the password
			$logRec = User::where('username', '=',  $request->operator)
					->update(array('password'  => bcrypt($request->password1)));
		}
		return $logRec;
	}
	public function signup(Request $request){
		try{
			$logRec = array();
			//check if the email and the secret word exist, if yes, then just edit, leaving out the role
			$record = User::where('username', '=',  strtolower($request->username))
			        ->orWhere('email', '=',  strtolower($request->email))
					->first();
			if( count($record)>0 ){
				$email = $record->email;
				$secret = $record->secret;
				
				if( $email == trim(strtolower($request->email)) && 
								$secret == bcrypt(trim($request->secret)) ){
					//edit me based on my email and secret word
					$logRec = User::where('username', '=',  strtolower($request->username))->update(
						array(
							'name'  => ucwords(strtolower($request->name)),
							'phone'  => $request->phone,
							'password'  => bcrypt($request->password),
							'active'  => 1,
							'operator'  => strtolower($request->username)
						)
					);
				}
						
			}else{
				//add
				$logRec = DB::table('users')->insert(
					 array(
						'name'  => ucwords(strtolower($request->name)),
						'username'  => strtolower($request->username),
						'email'  => strtolower($request->email),
						'phone'  => $request->phone,
						'secret'  => bcrypt($request->secret),
						'password'  => bcrypt($request->password),
						'last_signon' => date('Y-m-d H:i:s'),
						'previous_signon' => date('Y-m-d H:i:s'),
						'created_at'  => date('Y-m-d H:i:s'),
						'updated_at'  => date('Y-m-d H:i:s'),
						'signon_cnt' => 0,
						'active'  => 1,
						'role_id'  => 50,
						'operator'  => strtolower($request->username)
					 )
				);
				//generate a mail to the user that he has setup on Edu_Soft
			}
			return "Success";
			
		} catch (\Exception $e) {
			 $this->report_error($e, 'Login', 'User Registration', 'Add');
		}
	}
	public function getLogout(){
		Auth::guard('web')->Logout();
		return redirect()->route('signon');	
	}
	public function report_error($e, $module, $form, $task){
		file_put_contents('file_error.txt', $e->getMessage(). '\n'. $module. '-'. $form. '-'. $task. PHP_EOL, FILE_APPEND);
		//Log::useFiles(storage_path().'/laravel.log');
		Log::info($e->getMessage());
	}
}
/*
<?php
if(isset($_POST['g-recaptcha-response'])) {
    $result = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=[YOUR_SECRET_KEY]&response=$_POST["g-recaptcha-response"]&remoteip=$_SERVER["REMOTE_ADDR"]'), TRUE);

    if($result['success'] == 1) {
        // Captcha ok
    } else {
        // Captcha failed
    }
}
?>*/