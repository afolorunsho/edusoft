		<style>
			.popover {
				width: 400px;
				max-width: 100%;
			}
			.photo{
				width: 30px !important;
				height: 30px !important;
				border-radius: 100%;
			}
			.pict-logo{
				width: 30px !important;
				height: 30px !important;
				border-radius: 15%;
			}
		</style>
		<header class="header dark-bg row">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" 
                	data-placement="bottom"><i class="icon_menu"></i>
                </div>
            </div>
            <div class="nav pull-left top-menu">
                <a href="{{ route('dashboard') }}" class="logo">Edu <span class="lite in-line">Soft</span>
                    <img id="schl_logo" class="pict-logo">
					<span id="schl_name"></span>
                </a>  
            </div>  
            <div class="top-nav notification-row"> 
            	<!--logo start-->
                {{-----          
                <div class="nav pull-left top-menu">
                	
                    <div class="input-group" style="margin-top:10px">
                        <!--<button class="btn btn-default" type="submit" id="search"><i class="fa fa-search fa-2x"></i></button>-->
                        <i class="fa fa-search fa-2x" id="search"></i>
                    </div>
                </div>
                <div class="nav pull-left top-menu" class="hide">
                	<div class="form-group" class="hide">
                        <div id="popover-form" class="hide">
                            <form id="myform" class="form-inline" role="form">
                                <div class="form-group">
                                    <select class="form-control">
                                      <!--details, attendance, scores, discipline-->
                                      <option value="student-search">Student</option>
                                      <!--attendance, occupancy, student names-->
                                      <option value="class-search">Class</option>
                                      <!--defaults and age analysis-->
                                      <option value="fees-search">Fees</option>
                                      <!--exam statistics per class-->
                                      <option value="exam-search">Exam</option>
                                      <option value="bank-search">Bank</option>
									  
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input id="text_search" type="text" placeholder="Search"  class="form-control" />
                                </div>
                                <div class="form-group"><button type="submit" class="btn btn-warning">Search</button></div>
                            </form>
                        </div>
                	</div>
                </div>
               	-------------}}
                <ul class="nav pull-right top-menu">
                	
                	<li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="profile-ava">
                                <span>Enquiries</span>
                            </span>
                            <b class="caret"></b>
                        </a>
                         @if(Auth::user()->role_id == '1' ||
							Auth::user()->role_id == '9' ||
							Auth::user()->role_id == '10' ||
							Auth::user()->role_id == '6')
                            <ul class="dropdown-menu">
                                <div class="log-arrow-up"></div>
                                <li><a href="{{route('get_enquiryStudent')}}"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Student</a></li> 
                                <li><a href="{{route('get_enquiryRegistration')}}"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Registration</a></li>
                                <li><a href="{{route('get_enquiryEnrolment')}}"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Enrolment</a></li>
                                <li><a href="{{route('get_enquiryAttend')}}"><i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;Attendance</a></li>
                                <li><a href="{{route('get_enquiryFees')}}"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Fees Payment</a></li>
                                <li><a href="{{route('get_enquiryScholarship')}}"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Scholarship</a></li>
                                <li><a href="{{route('get_enquiryExams')}}"><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;Exams</a></li>
                                <li><a href="{{route('get_enquiryExpenses')}}"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Expenses</a></li>
                                <li><a href="{{route('get_enquiryTransfer')}}"><i class="fa fa-bank"></i>&nbsp;&nbsp;&nbsp;Funds Transfer</a></li>
                                <li><a href="{{route('get_enquiryBank')}}"><i class="fa fa-bank"></i>&nbsp;&nbsp;&nbsp;Bank Transactions</a></li> 
                                <li><a href="{{route('get_enquiryPerformance')}}"><i class="fa fa-bank"></i>&nbsp;&nbsp;&nbsp;Financial Performance</a></li>
                                <li><a href="{{route('get_enquiryStatement')}}"><i class="fa fa-bank"></i>&nbsp;&nbsp;&nbsp;Financial Statement</a></li>
                                <li><a href="{{route('get_enquiryJounal')}}"><i class="fa fa-bank"></i>&nbsp;&nbsp;&nbsp;Posting Journal</a></li> 
                            </ul>
                   		@endif
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="profile-ava">
                                <span>Quick Links</span>
                            </span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                             @if(Auth::user()->role_id == '1' ||
								Auth::user()->role_id == '2' ||
								Auth::user()->role_id == '4' ||
								Auth::user()->role_id == '5' ||
								Auth::user()->role_id == '8')
                                <li><a href="{{route('getRegistration')}}"><i class="fa fa-user"></i>Registration</a></li> 
                                <li><a href="{{route('getEnrolment')}}"><i class="fa fa-user"></i>Enrolment</a></li>
                                <li><a href="{{route('getAttendance')}}"><i class="fa fa-calendar"></i>Attendance</a></li>
                                <li><a href="{{route('getFeePay')}}"><i class="fa fa-credit-card"></i>Fees Payment</a></li>
                                <li><a href="{{route('getExamScore')}}"><i class="fa fa-pencil"></i>Score Entry</a></li>
                                <li><a href="{{route('getTxnExp')}}"><i class="fa fa-credit-card"></i>Expenses</a></li>
                                <li><a href="{{route('getTxnFT')}}"><i class="fa fa-bank"></i>Funds Transfer</a></li> 
                          	@endif
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="profile-ava">
                                <img src="{{url('photo/user').'/'.Auth::user()->photo }}" class="photo">
                                <span class="username">{{ Auth::user()->name }}</span>
                            </span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li>
                                <a onClick="showPassword(); return false;" href="#"><i class="icon_key_alt"></i>Change Password</a>
                            </li>
                            <li>
                                <a href="#" target="_blank"><i class="fa fa-question"></i>Documentation</a>
                                <ul>
                                	<li><a href="EduSoft Tutorial - Overview.pdf" target="_blank">Overview</a></li>
                                    <li><a href="EduSoft Tutorial - Setup.pdf" target="_blank">Setup</a></li>
                                    <li><a href="EduSoft Tutorial - Activities.pdf" target="_blank">Activities</a></li>
                                    <li><a href="EduSoft Tutorial - Output.pdf" target="_blank">Output</a></li>
                                    <li><a href="EduSoft Tutorial - Activities.pdf" target="_blank">FAQ</a></li>
                                </ul>
                            </li><li>
                                <a href="{{route('logout')}}"><i class="fa fa-sign-out"></i>Log Out</a>
                            </li>
                            
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
                <!-- notificatoin dropdown end-->
            </div>
      </header> 
		@include('popup.password_change')  
      <script>
	  		$('a[rel=popover]').popover();
			
			function showPassword(){
				try{
					$('#pass_change_show').modal();
					
				}catch(err){ alert(err.message); }
			}
			
	  </script>   
      