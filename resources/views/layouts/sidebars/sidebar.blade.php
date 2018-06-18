	  
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <ul class="sidebar-menu">                
                  <li class="active">
                      <a href="{{route('dashboard-stat')}}">
                          <i class="fa fa-bar-chart"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                  @if(Auth::user()->role_id == '1' ||
                  		Auth::user()->role_id == '2' ||
                  		Auth::user()->role_id == '4' ||
                   		Auth::user()->role_id == '5' ||
                        Auth::user()->role_id == '8')
                      	<li class="sub-menu" id="institution-sidebar">
                          <a href="javascript:;" class="">
                              <i class="fa fa-institution"></i>
                              <span>Settings</span>
                              <span class="menu-arrow arrow_carrot-right"></span>
                          </a>
                          <ul class="sub">
                              <li><a href="{{route('getInstitute')}}">Institute</a></li>
                              <li><a href="{{route('getAcademic')}}">Academic Year</a></li>
                              <li><a href="{{route('getEvents')}}">Events</a></li>
                          </ul>
                      	</li>
                  
                      	<li class="sub-menu" id="master-sidebar">
                          <a href="javascript:;" class="">
                              <i class="fa fa-desktop"></i>
                              <span>Master</span>
                              <span class="menu-arrow arrow_carrot-right"></span>
                          </a>
                          <ul class="sub">
                              <li><a href="{{route('getSchool')}}">School</a></li>
                              <li><a href="{{route('getSchedule')}}">School Timetable</a></li>
                              <li><a href="{{route('getClass')}}">Class</a></li>
                              <li><a href="{{route('getSubject')}}">Subjects</a></li>
                              <li><a href="{{route('getSyllabus')}}">Syllabus</a></li>
                          </ul>
                      	</li>
                      	<li class="sub-menu" id="student-sidebar">
							  <a href="javascript:;" class="">
								  <i class="fa fa-user"></i>
								  <span>Student</span>
								  <span class="menu-arrow arrow_carrot-right"></span>
							  </a>
							  <ul class="sub">
									<li><a href="{{route('getRegistration')}}">Registration</a></li> 
									<li><a href="{{route('getEnrolment')}}">Enrolment</a></li>
									<li><a href="{{route('getAttendance')}}">Attendance</a></li>
									<li><a href="{{route('getTransfer')}}">Transfer</a></li>
									<li><a href="{{route('getPromotion')}}">Movement</a></li>
									<li><a href="{{route('getTermination')}}">Termination</a></li>
									<li><a href="{{route('getDiscipline')}}">Discipline</a></li>
									<li><a href="{{route('getAchievement')}}">Achievement</a></li>
									<li><a href="{{route('getAssessSetup')}}">Assessment</a></li>
									<li><a href="{{route('getExit')}}">Exit</a></li>
							  </ul>
                		</li>
                   		<li class="sub-menu" id="fees-sidebar">
							<a href="javascript:;" class="">
								<i class="fa fa-money"></i>
								<span>Fees</span>
								<span class="menu-arrow arrow_carrot-right"></span>
						  </a>
							<ul class="sub">
								<li><a href="{{route('getFeeHead')}}">Fees Head</a></li>
								<li><a href="{{route('getFeeStruct')}}">Class Fees</a></li>
								<li><a href="{{route('getStudentService')}}">Student Services</a></li>
				   
								<li><a href="{{route('getFeePay')}}">Fees Payment</a></li>
								<li><a href="{{route('getDiscount')}}">Discount/Scholarship</a></li>
								<li><a href="{{route('getFeeRefund')}}">Fees Refund</a></li>
								<li><a href="{{route('showFeesDue')}}">Fees Due</a></li>
								<li><a href="{{route('getFeeInstruct')}}">Payment Instruction</a></li>
						
							</ul>
						</li>
						<li class="sub-menu" id="exam-sidebar">
						  <a href="javascript:;" class="">
							  <i class="fa fa-pencil"></i>
							  <span>Exam</span>
							  <span class="menu-arrow arrow_carrot-right"></span>
						  </a>
						  <ul class="sub">
							  <li><a href="{{route('getExamName')}}">Exam Name</a></li>
							  <li><a href="{{route('getExamClass')}}">Class Exam</a></li>
							  <li><a href="{{route('getExamGrade')}}">Score Grade</a></li>
							  <li><a href="{{route('getExamDiv')}}">Score Division</a></li>
							
							  <li><a href="{{route('getExamScore')}}">Score Entry</a></li>
							  <li><a href="{{route('getTermScore')}}">Term Score</a></li>
						  </ul>
					  	</li>
                      	<li class="sub-menu" id="accounts-sidebar">
                          <a href="javascript:;" class="">
                              <i class="fa fa-credit-card"></i>
                              <span>Accounts</span>
                              <span class="menu-arrow arrow_carrot-right"></span>
                          </a>
                          <ul class="sub">
                              <li><a href="{{route('getGroup')}}">Account Groups</a></li>
                              <li><a href="{{route('getBank')}}">Banks</a></li> <!---to create the various bank account -->
                              <li><a href="{{route('getExp')}}">Expenses</a></li> <!---to create the various expense accounts-->
                             
                          </ul>
                      	</li>
						<li class="sub-menu" id="transactions-sidebar">
						  <a href="javascript:;" class="">
							  <i class="fa fa-ticket"></i>
							  <span>Transactions</span>
							  <span class="menu-arrow arrow_carrot-right"></span>
						  </a>
						  <ul class="sub">
							  <!---This should include essay, practical, class work etc-->                          
							  <li><a href="{{route('getTxnExp')}}">Expenses</a></li>
							  <li><a href="{{route('getTxnFT')}}">Funds Transfer</a></li> <!--- this is for bank transfer postings-->
                       	
						  </ul>
						</li>
					@endif
                   	@if(Auth::user()->role_id == '1' ||
                   		Auth::user()->role_id == '9' ||
                        Auth::user()->role_id == '10' ||
                        Auth::user()->role_id == '6')
                        
                      <li class="sub-menu" id="system-sidebar">
                          <a href="javascript:;" class="">
                              <i class="fa fa-database fa-fw"></i>
                              <span>Enquiries</span>
                              <span class="menu-arrow arrow_carrot-right"></span>
                          </a>
                          <ul class="sub">
                          		<li><a href="{{route('get_enquiryStudent')}}"><i class="fa fa-user"></i>Student</a></li> 
                                <li><a href="{{route('get_enquiryRegistration')}}"><i class="fa fa-user"></i>Registration</a></li>
                                <li><a href="{{route('get_enquiryEnrolment')}}"><i class="fa fa-user"></i>Enrolment</a></li>
                                <li><a href="{{route('get_enquiryAttend')}}"><i class="fa fa-calendar"></i>Attendance</a></li>
                                <li><a href="{{route('get_enquiryFees')}}"><i class="fa fa-credit-card"></i>Fees Payment</a></li>
                                <li><a href="{{route('get_enquiryScholarship')}}"><i class="fa fa-credit-card"></i>Scholarship</a></li>
                                <li><a href="{{route('get_enquiryExams')}}"><i class="fa fa-pencil"></i>Exams</a></li>
                                <li><a href="{{route('get_enquiryExpenses')}}"><i class="fa fa-credit-card"></i>Expenses</a></li>
                                <li><a href="{{route('get_enquiryTransfer')}}"><i class="fa fa-bank"></i>Funds Transfer</a></li>
                                <li><a href="{{route('get_enquiryBank')}}"><i class="fa fa-bank"></i>Bank Transactions</a></li> 
                                <li><a href="{{route('get_enquiryPerformance')}}"><i class="fa fa-bank"></i>Fin.Performance</a></li>
                                <li><a href="{{route('get_enquiryStatement')}}"><i class="fa fa-bank"></i>Fin.Statement</a></li>
                                <li><a href="{{route('get_enquiryJounal')}}"><i class="fa fa-bank"></i>Journal</a></li> 
                                
                          </ul>
                      </li>
             	  	@endif
                  	@if(Auth::user()->role_id == '1' )
                      <li class="sub-menu" id="system-sidebar">
                          <a href="javascript:;" class="">
                              <i class="fa fa-database fa-fw"></i>
                              <span>System</span>
                              <span class="menu-arrow arrow_carrot-right"></span>
                          </a>
                          <ul class="sub">
                              <!---create, delete, edit, block--> 
                              <li><a href="{{route('getUsers')}}">Manage User</a></li>
                              <!---default backup folder, email backup folder, backup delete days, restore backup--> 
                              <li><a href="{{ route('viewBackup') }}">Manage Backup</a></li>
                          </ul>
                      </li>
              		@endif
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>

      <!--
      	<i class="fa fa-cloud"></i>
        <i class="fa fa-heart"></i>
        <i class="fa fa-car"></i>
        <i class="fa fa-file"></i>
        <i class="fa fa-bars"></i>
		
        <i class="glyphicon glyphicon-cloud"></i>
        <i class="glyphicon glyphicon-remove"></i>
        <i class="glyphicon glyphicon-user"></i>
        <i class="glyphicon glyphicon-envelope"></i>
        <i class="glyphicon glyphicon-thumbs-up"></i>
        
        <i class="fa fa-home fa-fw" aria-hidden="true">
        <i class="fa fa-book fa-fw" aria-hidden="true">
        <i class="fa fa-pencil fa-fw" aria-hidden="true">
        <i class="fa fa-cog fa-fw" aria-hidden="true">

      sidebar end-->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>