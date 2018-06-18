
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Education Software for schools">
    <meta name="author" content="Abayomi Folorunsho[+2348053281398]">
    <meta name="keyword" content="edu_soft, education, school, application, software, accounting, business">
    <title>Login</title>
    <link href="{!! asset('css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/bootstrap-theme.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/bootstrap-dialog.min.css') !!}" rel="stylesheet">
    
    <link href="{!! asset('css/elegant-icons-style.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/font-awesome.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/style.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/style-responsive.css') !!}" rel="stylesheet">
</head>
	<style>
		.div{
		  height: 100px;
		  line-height: 100px;
		  text-align: center;
		  border: 2px dashed #f69c55;
		}
		.span {
		  display: inline-block;
		  vertical-align: middle;
		  line-height: normal;
		}
	</style>
  <body class="login-img3-body">
	<div class="container">

      <form class="login-form" action="{{ route('loginPost') }}" method="POST">
      	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		{!! csrf_field() !!}
        <div class="div">
          	<span class="span">
          		<p align="center"><h1>EduSoft</h1> <i>the software of choice for educational institutions</i></p>
       		</span>
        </div>
        
        <div class="login-wrap">
            <p class="login-img"><i class="icon_lock_alt"></i></p>
            <div class="input-group">
              <span class="input-group-addon"><i class="icon_profile"></i></span>
              <input type="text" name="username" class="form-control" placeholder="Username" autofocus>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right"> <a onClick="forgotPassword(); return false;" href="#"> Forgot Password?</a></span>
            </label>
            <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
            <button class="btn btn-info btn-lg btn-block" type="button" id="call_signup">Signup</button>
        </div>
        @if(session('status'))
			<div class="alert alert-info">
				<a class="close" data-dismiss="alert">×</a>
				<strong>{{ session('status') }}</strong> 
			</div>
		@endif 
      </form>
    </div>
    
  	@include('popup.sign_up')
    @include('popup.password_reset')
    </body>
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/jquery-ui-1.10.4.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/jquery-ui-1.9.2.custom.min.js') !!}"></script>
    <!-- bootstrap -->
    <script src="{!! asset('js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap-dialog.min.js') !!}"></script>
    <!-- nice scroll -->
    <script src="{!! asset('js/jquery.scrollTo.min.js') !!}"></script>
    <script src="{!! asset('js/jquery.nicescroll.js" type="text/javascript') !!}"></script>
    <!-- charts scripts -->
    <script src="{!! asset('assets/chart-master/Chart.js') !!}"></script>
    <script src="{!! asset('assets/jquery-knob/js/jquery.knob.js') !!}"></script>
    <script src="{!! asset('js/jquery.sparkline.js" type="text/javascript') !!}"></script>
    <script src="{!! asset('assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js') !!}"></script>
    <script src="{!! asset('js/owl.carousel.js') !!}" ></script>
    <!-- jQuery full calendar -->
    <script src="{!! asset('js/fullcalendar.min.js') !!}"></script> <!-- Full Google Calendar - Calendar -->
	<script src="{!! asset('assets/fullcalendar/fullcalendar/fullcalendar.js') !!}"></script>
    <!--script for this page only-->
    <script src="{!! asset('js/calendar-custom.js') !!}"></script>
	<script src="{!! asset('js/jquery.rateit.min.js') !!}"></script>
    <!-- custom select -->
    <script src="{!! asset('js/jquery.customSelect.min.js') !!}" ></script>
	<script>
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		  });

		$(function(){
			$('#call_signup').on('click', function(e){
				e.preventDefault();
				try{
					$('#signup_show').modal();
				}catch(err){ alert(err.message); }
			})
		});
		function forgotPassword(){
			try{
				$('#pass_reset_show').modal();
			}catch(err){ alert(err.message); }
		}
		//////////////////////////////////////////////////////////////////////
		$(function(){
			$('#btn-signup').on('click', function(e){
				e.preventDefault();
				BootstrapDialog.confirm({
					title: 'ADD A USER',
					message: 'Are you sure you want to add a user?',
					type: BootstrapDialog.TYPE_WARNING,
					closable: true,
					callback: function(result) {
						
						if(result) {
							var is_valid = validateSignUP();
							if (is_valid){
								document.getElementById("btn-signup").innerHTML = 
									'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
			
								var last_name = $('#signupform #last-name').val();
								var other_names = $('#signupform #other-names').val();
								var username = $('#signupform #login-name').val();
								var email = $('#signupform #email').val();
								var phone = $('#signupform #phone').val();
								var secret = $('#signupform #secret').val();
								var password = $('#signupform #login-password').val();
								var active = "1";
								var role_id = "50";
								var operator = username;
								var name = last_name + ' ' + other_names; 
								
								$.post("{{ route('signup') }}", {
									"_token": "{{ csrf_token() }}",
									name: name,
									username: username,
									email: email,
									phone: phone,
									secret: secret,
									password: password,
									active: active,
									role_id: role_id,
									operator: operator
								})
								.done(function(data){ 
									if(data == "Success"){
										//reset the form
										$('#signupform')[0].reset();
										alert('Update successful');
										alert('Please contact the System Administrator for Role Assignment');
										
									}else{
										alert('Update NOT successful');
									}
									document.getElementById("btn-signup").innerHTML = 'Register';
								})
								.fail(function (jqXHR, exception) {
									// error handling
									var msg = "";
									if (jqXHR.status === 0) {
										msg = 'Not connect.\n Verify Network.';
									} else if (jqXHR.status == 404) {
										msg = 'Requested page not found. [404]';
									} else if (jqXHR.status == 500) {
										msg = 'Internal Server Error [500]';
									} else if (exception === 'parsererror') {
										msg = 'Requested JSON parse failed.';
									} else if (exception === 'timeout') {
										msg = 'Time out error.';
									} else if (exception === 'abort') {
										msg = 'Ajax request aborted.';
									} else {
										msg = 'Uncaught Error.\n' + jqXHR.responseText;
									}
									alert(msg);
									//$('#error-container-text').html(jqXHR.responseText);
									// $('#error-container').modal('show');
									document.getElementById("btn-signup").innerHTML = 'Register';
								});
							}
						}
					}
				});
			})
		});
		function validateSignUP(){
			try{
				var last_name = $('#signupform #last-name').val();
				var other_names = $('#signupform #other-names').val();
				var login_name = $('#signupform #login-name').val();
				var email = $('#signupform #email').val();
				var repeat_email = $('#signupform #repeat-email').val();
				var phone = $('#signupform #phone').val();
				var secret = $('#signupform #secret').val();
				var password = $('#signupform #login-password').val();
				var repeat_password = $('#signupform #repeat-password').val();
				var active = 1;
				var role_id = 0;
				var operator = login_name;
				
				var reason = "";
				reason += validate_empty(document.getElementById("last-name"));
				reason += validate_empty(document.getElementById("other-names"));
				reason += validate_empty(document.getElementById("secret"));
				reason += validate_code(document.getElementById("login-name"));
				reason += validate_email(document.getElementById("email"));
				reason += validate_phone(document.getElementById("phone"));
				reason += validate_pass_complex(document.getElementById("login-password"));
				if( password !== repeat_password) reason += "Inconsistent password \n";
				if( email !== repeat_email) reason += "Inconsistent email \n";
				if( login_name == last_name || login_name == other_names) reason += "Login name cannot be Last Name or Other Name \n";
				
				if (reason != "") {
					alert("Some fields need correction:\n\n" + reason);
					return false;
				}
			}catch(err){ alert(err.message); }
			return true;	
		}
		function validate_pass_complex(fld) {
			var error = "";
			fld.style.background = 'White';
			
			var hasUpperCase = /[A-Z]/.test(fld.value);
			var hasLowerCase = /[a-z]/.test(fld.value);
			var hasNumbers = /\d/.test(fld.value);
			var hasNonalphas = /\W/.test(fld.value);
			
			if ((fld.value.length < 8) || (fld.value.length > 15)) {
				error += "The password is the wrong length. \n";
				fld.style.background = 'Yellow';
			}
			if ( hasUpperCase == false) {
				error += "Your password should contain an uppercase. \n";
				fld.style.background = 'Yellow';
			}
			if ( hasLowerCase == false) {
				error += "Your password should contain a lowercase. \n";
				fld.style.background = 'Yellow';
			}
			if ( hasNumbers == false) {
				error += "Your password should contain a digit. \n";
				fld.style.background = 'Yellow';
			}
			if ( hasNonalphas == false) {
				error += "Your password should contain non-character. \n";
				fld.style.background = 'Yellow';
			}
			return error;
		}
		//this validates code fields without spaces  
		function validate_code(fld) 
		{
			var error = "";
			var xters = /^[0-9a-zA-Z\_\.\-]+$/;
			if(!fld.value.match(xters))
			{
				error = "This input contains invalid characters.\n";
				fld.style.background = 'Yellow';
			}else{
				fld.style.background = 'White';
			}
			return error;
		}
		
		function validate_phone(fld) {
			var error = "";
			//fld.value.charAt(0); to extract the first xter
			var stripped = fld.value.replace(/[\(\)\.\-\ ]/g, '');     
		
			if (fld.value == "") {
				error = "You didn't enter a phone number.\n";
				fld.style.background = 'Yellow';
			} else if (isNaN(parseInt(stripped))) {
				error = "The phone number contains illegal characters.\n";
				fld.style.background = 'Yellow';
			} else if (stripped.length < 10) {
				error = "The phone number is the wrong length. Make sure you included an area code.\n";
				fld.style.background = 'Yellow';
			} else {
				fld.style.background = 'White';
			}
			return error;
		}
		function validate_email(fld) {
			var error="";
			var tfld = fld.value;                        // value of field with whitespace trimmed off
			var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
			var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
			
			if (fld.value == "") {
				fld.style.background = 'Yellow';
				error = "You didn't enter an email address.\n";
			} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
				fld.style.background = 'Yellow';
				error = "Please enter a valid email address.\n";
			} else if (fld.value.match(illegalChars)) {
				fld.style.background = 'Yellow';
				error = "The email address contains illegal characters.\n";
			} else {
				fld.style.background = 'White';
			}
			return error;
		}
		function validate_url(fld){
			var error = "";
			var filter = /^(http[s]?:\/\/)?([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2}(\/([-~%\.\(\)\w\d]*\/*)*(#[-\w\d]+)?)?$/;
			if (!filter.test(fld.value)){
				fld.style.background = 'Yellow'; 
				error = "The website address is not correct.\n"
			}else{
				fld.style.background = 'White';
			}
			return error; 
		}
		function validate_empty(fld) {
			var error = "";
		  
			if (fld.value.length == 0) {
				fld.style.background = 'Yellow'; 
				error = "The required colored field has not been filled in.\n"
			} else {
				fld.style.background = 'White';
			}
			return error;   
		}
	</script>
</html>
