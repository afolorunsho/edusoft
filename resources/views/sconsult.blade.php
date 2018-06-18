<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="We have a school sotware to solve your school academic and administrative challenges">
    <meta name="keywords" content="business cafe, business center, college application, school application, college software, school software, software, passport photos, graphic design, edu_soft">
    <meta name="author" content="System Consult">
    <title>Top School Software</title>
    <!-- Bootstrap core CSS -->
    <link href="{!! asset('css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/font-awesome.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/bootstrap-theme.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/full-slider.css') !!}" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <style>
		html {
			background-image: url('images/edu_soft/menu_dashboard.png');
			background-position: center center;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			height: 100%; 
			overflow: hidden;
		}
		body {           
			height:100%;
			overflow: scroll;
		}
		#map {
			width: 550px;
        	height: 400px;
			-ms-border-radius: 10px 10px 10px 10px; /*explorer */
			-moz-border-radius: 10px 10px 10px 10px; /*mozilla support */
			-webkit-border-radius: 10px 10px 10px 10px; /*chrome and safari */
			-o-border-radius: 10px 10px 10px 10px; /*opera*/
			border-radius: 10px 10px 10px 10px;
      	}
		#map-area{
			border:thick #00F;
			margin:10 0;
		}
		hr{
			/*background-color: #fff;
			border-top: 1px dashed #8c8b8b;*/
			margin: 0;
			padding: 0;
			border: 0; 
			  height: 1px; 
			  background-image: -webkit-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
			  background-image: -moz-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
			  background-image: -ms-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
			  background-image: -o-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0); 
		}
		a:hover, a:visited, a:link, a:active {
		  text-decoration: none;
		}
		
		.controls {
		  margin-bottom: 10px;
		}
		
		.collapse-group {
		  padding: 10px;
		  border: 1px solid darkgrey;
		  margin-bottom: 10px;
		}
		
		.panel-title .trigger:before {
		  content: '\e082';
		  font-family: 'Glyphicons Halflings';
		  vertical-align: text-bottom;
		}
		
		.panel-title .trigger.collapsed:before {
		  content: '\e081';
		}
		/*use the full width to display the image and proportionate height*/
		
		.image {
			width: auto;
		  	max-width: 100%;
		  	height: auto;
		  	overflow:hidden;
			position: relative;
			transition: 0.3s ease;
			cursor: pointer;
		}
		
		.image:hover {
			transform: scale(1.5, 1.5); /** default is 1, scale it to 1.5 */
			opacity: 1;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
			background-color:#8DA5C6;
    		z-index: 999;
		}
		
	</style>
  </head>

  <body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<!-- Navbar Container -->
		<div class="container">
        	<div class="navbar-header">
				<a href="#">
                    <img src="{{url('/img/sch_logo.png')}}" alt="Logo" width="75px" height="75px"/>
                </a>
                System Consult
			</div>
			 <ul class="nav navbar-nav navbar-right" style="font-size:80%;">
             	 <h3>EduSoft</h3> <i>...the software of choice for schools</i>
			 </ul>
		</div>
	</nav>
    <header>
        <div id="carousel-generic" class="carousel slide" data-ride="carousel">
			
			<ol class="carousel-indicators">
				<li data-target="#carousel-generic" data-slide-to="0" class="active"></li>
				<li data-target="#carousel-generic" data-slide-to="1"></li>
				<li data-target="#carousel-generic" data-slide-to="2"></li>
                <li data-target="#carousel-generic" data-slide-to="3"></li>
				<li data-target="#carousel-generic" data-slide-to="4"></li>
                <li data-target="#carousel-generic" data-slide-to="5"></li>
			</ol>
			
			<div class="carousel-inner" role="listbox">
				<div class="item active" align="center"><img src="images/center/edu-soft.png" alt=""/></div>
                <div class="item" align="center"><img src="images/center/edu-benefits.png" alt="" /></div>
				<div class="item" align="center"><img src="images/center/edu-features.png" alt="" /></div>
                <div class="item" align="center"><img src="images/edu_soft/overview.png" alt="" /></div>
				<div class="item" align="center"><img src="images/edu_soft/overview2.png" alt="" /></div>
                <div class="item" align="center"><img src="images/edu_soft/overview3.png" alt="" /></div>
			</div>

			<a class="left carousel-control" href="#carousel-generic" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#carousel-generic" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
    </header>
    <!-- Page Content -->
    <section class="py-5" style="font-size:95%">
		<div class="container">
			<div class="row">
				<div class="jumbotron">
                	<div class="col-md-6">
                        <ul>
                        	<li>Do you spend most of your time on operational issues rather than on strategic matters?</li>
                            <li>Do you have to search through a mountain of records just to get a piece of information?</li>
                            <li>Do you spend inordinate amount of time on record keeping and ensuring its accuracy?</li>
                            <li>Do you give results at the end of the term or have to wait until the resumption of the new term for parents/guardians to know the performance of their wards?</li>
                        </ul>
              		</div>
                    <div class="col-md-6">
                        <ul>
                            <li>Are you limited to a fixed number of tests, exams, continuous assessments etc for students</li>
                        	<li>Do you know the financial performance of the school at the click of a button?</li>
                            <li>Do you have to physically receive evidence of bank lodgment or have to physically hand over receipts for fees paid to parents?</li>
                            <li>Do you easily aggregate scores and derive their grades without errors?</li>
                            <li>Is it possible for you to re-generate all the school activities at the press of a button?</li>
                        </ul>
              		</div>
                    <div style="clear:both;">
                        <br/>
                        <p>Then you need <strong>EduSoft</strong></p>
                   	</div>
              	</div>
                <div class="col-md-12">
                	<div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading1">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse1" 
                                	aria-expanded="true" aria-controls="collapse1" class="trigger collapsed">
                                  	Introduction
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse1" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading1">
                              	<div class="panel-body">
                                	<div align="center"><img src="images/edu_soft/signon_page.png" alt="" width="500" height="200" class="image"/></div>
                              		<p> 
				It is our pleasure to introduce the <b>Lite Edition</b> of our international software, EduSoft, for educational institutions to you. The software
				is a product of our desire to assist schools, colleges and other educational institutions to easily capture and maintain proper records of school
				activities and to access these records in the most convenient way. It is also a product of collaborations with our co-developers
				in other parts of the world which enable us to use modern technology to solve business problems.
			</p>
			
			<p> 
				The deep desire to help schools led us to make findings regarding how schools keep their records and the challenges they face in the 
				process of doing so. The first attempt to assist schools was far back in 2001 when we developed a software for Grays 
				International College, Kaduna, Nigeria using Visual Basic in view of the challenges they were facing in keeping massive records of transactions. Since, then we have
				developed different software solutions to assist businesses in maintaining proper records of transactions and activities. What you have in this software, therefore, 
				is a REAL SOLUTION, not another burden.
			</p>
			<h4>Developers</h4>
			<p>EduSoft was designed and developed by <i><Strong>System Consult</Strong></i> for schools in Nigeria. However, since the standards in Nigeria are 
			universal, the software can be used anywhere in the world to solve the challenges schools are facing in today's 
			fast-paced world.</p>
			<p>
				This school software is a web-based application that runs on even laptops and phones. Hence, You do not need a gigantic infastructure to set it up. You could have it on your 
				 website and access it on any device. You could also have it on any system in your school and access it over your Local Area Network(LAN) or Wide Area Network(WAN)
			</p>
           <p>Please click <a href="edu_soft_pp.pdf" target="_blank">here</a> to view the powerpoint presentation</p>
           
            
                              	</div>
                        	</div>
                   		</div>
             		</div>
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading2">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse2" 
                                	aria-expanded="true" aria-controls="collapse2" class="trigger collapsed">
                                  	Benefits
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse2" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading2">
                              	<div class="panel-body">
                                	<div align="center"><img src="images/edu_soft/menu_dashboard.png" alt="" class="image"/></div>
                              		<p><strong>Among the challenges that the EduSoft School System helps you overcome include:</strong>
							<ul>
								<li>Inability to make well informed decisions due to lack of timely and accurate information</li>
								<li>Inability to gather all data/information in a single place for easy access</li>
								<li>Difficulties and pains in obtaining vital information on fees payments, oustanding fees, bank balances, student performances, attendance, cost of running the institution etc</li>
								<li>Pains in the collation, sorting and analysing large volume of transactions</li>
								<li>High level of management involvement in tactical and operational activities</li>
								<li>Inability to securely communicate student performances to parents/guardians</li>
								<li>High cost of printing receipts, certificates and other stationeries</li>
								<li>High cost of purchasing registers, forms and other books for the manual recording of transactions</li>
								<li>Pain, difficulties and errors in the computation, aggregation, grading and collation of scores in various tests and exams</li>
								<li>Pain, difficulties and errors in the manual recording of transactions</li>
								<li>Large volume of paper-work arising from non-automation/partial auto-mation of processes</li>
								<li>Errors in the manual recording of transactions</li>
							</ul>
                            </p>
                            <br>
							<p><strong>Even where you have a school software these challenges are not uncommon:</strong></p>
					        <ul>
					            <li>Difficulties in understanding the flow of the software</li>
					            <li>Large manual work inspite of automation</li>
					            <li>The software creating more problems than it is solving</li> 
					            <li>Difficulties in incorporating different tests, homework, exams in the terminal results</li>
								<li>Inability to capture all the tests, assignments, exams etc undertaken by students</li>
								<li>Inability to export data in csv, excel or text format for further uses(e.g analysis, reconciliation etc)</li>
								<li>Complex and difficult to use software</li>
								<li>Lack of adequate support from the developers of the software etc</li>
							</ul>
						</p>
                              	</div>
                        	</div>
                   		</div>
             		</div>
               	</div>
                <div class="col-md-12">
                	<h3>Features</h3>
                	<div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading3">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse3" 
                                	aria-expanded="true" aria-controls="collapse3" class="trigger collapsed">
                                  	Registration
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse3" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading3">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/student_registration.png" alt="" class="image"/></div>
                                    <p>This software allows you to capture student details easily through three sources: Form Input, where images are captured; Table Input to quickly capture lot of students at once and Data Import.</p>
                                    <p>Please note that the details captured here will be used in other parts of the application. Hence, it is important to ensure that they are accurately captured at this point</p>
							
                              	</div>
                        	</div>
                   		</div>
             		</div>
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading4">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse4" 
                                	aria-expanded="true" aria-controls="collapse4" class="trigger collapsed">
                                  	Attendance
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse4" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading4">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/student_attendance.png" alt="" class="image"/></div>
                                    <p>EduSoft captures various activities by students including attendance, transfer/movement from classes, exit from the school, termination, discipline, achievements etc. This way, a complete record of the student is maintained in the application.</p>
                                    
                              	</div>
                        	</div>
                   		</div>
             		</div>
             	</div>
                <div class="col-md-12">
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading5">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse5" 
                                	aria-expanded="true" aria-controls="collapse5" class="trigger collapsed">
                                  	Examination
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse5" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading5">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/sch_result.png" alt="" class="image"/></div>
                                    <p>The power of this software is its flexibility. The application gives you the flexibility of conducting series of exams and collating this seamlessly: You even have the option of excluding any from the terminal result, while still maintaining the record in the system.</p>
                                    <p>In addition to capturing the scores from the various tests, continous assessments, home work, exams etc, the software allows you to rate the students on subjective criteria and allows the teachers to include comments for each students in the result slips.</p>
                                    
                              	</div>
                        	</div>
                   		</div>
             		</div>
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading6">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse6" 
                                	aria-expanded="true" aria-controls="collapse6" class="trigger collapsed">
                                  	Score Grading
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse6" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading6">
                              	<div class="panel-body">
                                	<div align="center"><img src="images/edu_soft/score_grade.png" alt="" class="image"/></div>
                                    <p>It is normal for scores to be graded and this software gives you the opportunity to do this in two ways. 1. Through 
										the Grade Form and through the Division Form. However, it is what is defined in the Grade Form that is used in the terminal
										results. 
										</p>
                              	</div>
                        	</div>
                   		</div>
             		</div>
               	</div>
                <div class="col-md-12">
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading7">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse7" 
                                	aria-expanded="true" aria-controls="collapse7" class="trigger collapsed">
                                  	Fees
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse7" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading7">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/fees_student.png" alt="" class="image"/></div>
                                    <p>Various fees will be charged by the school and these have to be posted to the various fees head for proper accounting and tracking. This software allows you to do this seamlessly as it allows fees to be defined per class and for students. It also gives you the opportunity to indicate fees payment instructions to parents. 
										</p>
                              	</div>
                        	</div>
                   		</div>
             		</div>
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading8">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse8" 
                                	aria-expanded="true" aria-controls="collapse8" class="trigger collapsed">
                                  	Expenses
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse8" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading8">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/expense_view.png" alt="" class="image"/></div>
                                    <p>Expenses will be incurred in the course of running the school. These include salaries, stationeries, fuel etc and all these have to be recorded in the books of account. This software enables you to record all these expenses. To record these, the software allows you to create expense categories, sub-categories and expense items. 
										</p>
                              	</div>
                        	</div>
                   		</div>
             		</div>
               	</div>
             	<div class="col-md-12">
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading9">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse9" 
                                	aria-expanded="true" aria-controls="collapse9" class="trigger collapsed">
                                  	Data Import/Export
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse9" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading9">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/score_import.png" alt="" class="image"/></div>
                                    <p>Another important feature of this system is Data Import and Export.</p>
                                    <p>Data Import allows you to easily capture data input from other sources other than through the keyboard.
                                     This was provided to allow you to import mass data generated by other means into the application 
                                     without requiring you to go through the laborious form field inputs.</p>
							 		
                                    <p>On the other hand, Data Export helps you to move data our of the system 
                                    for further analysis and other purposes. This feature also allows you to send reports electronically 
                                    to parents and print various reports</p>
                                    <p>This facility is available in most of the forms and tables and useful for:</p>
                                    <ul>
                                        <li>Integrating data from the system to other systems or to prepare other reports</li>
                                        <li>Data analytics, though some of these are available in the system</li>
                                        <li>Generating receipts, result slips and other vital pdf reports for printing and emailing</li>
                                    </ul>
                              	</div>
                        	</div>
                   		</div>
             		</div>
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading10">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse10" 
                                	aria-expanded="true" aria-controls="collapse10" class="trigger collapsed">
                                  	Email
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse10" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading6">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/result_email.png" alt="" class="image"/></div>
                                    <p>One of the most important engagements with parents is through email. Hence, this software has email support for payment receips, school results and other student activities.</p>
								
                              	</div>
                        	</div>
                   		</div>
             		</div>
               	</div>
                <div class="col-md-12">
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading11">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse11" 
                                	aria-expanded="true" aria-controls="collapse7" class="trigger collapsed">
                                  	Enquiries/Reports
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse11" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading11">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/enquiries.png" alt="" class="image"/></div>
                                    <p>This features allows you to interrogate the system and spool out reports without going through complex procedures. Hence, you have the opportuniy of getting just any information from the system in the most convenient way.
										</p>
                              	</div>
                        	</div>
                   		</div>
             		</div>
                    <div class="col-md-6">
                    	<div class="panel panel-default">
                      		<div class="panel-heading" role="tab" id="heading12">
                              	<h4 class="panel-title">
                                	<a role="button" data-toggle="collapse" href="#collapse12" 
                                	aria-expanded="true" aria-controls="collapse12" class="trigger collapsed">
                                  	Financial Performance
                                	</a>
                              	</h4>
                       		</div>
                      		<div id="collapse12" class="panel-collapse collapse" 
                          		role="tabpanel" aria-labelledby="heading12">
                              	<div class="panel-body">
                              		<div align="center"><img src="images/edu_soft/performance_monthly.png" alt="" class="image"/></div>
                                    <p>This software is not just for assessing students academic and non-academic performances, it is a full fledge accounting application. Hence, it generates your income and expenses and report your financial performance monthly or yearly as desired. It also gives you your  bank/cash position at any time. All these are aimed at supporting effective decision making.
										</p>
                              	</div>
                        	</div>
                   		</div>
             		</div>
               	</div>
			</div>
		</div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark">
    	<div class="container text-white" style="font-size:90%">
			
            <div class="row">
              <div class="col-md-5">
				<h3>Our EduSoft Application</h3>
				<p>
                    Our web-based school softare, EduSoft, was developed by System Consult with the following benefits:
                    <ul class="default-bullet">
                        <li>Timely and accurate information for decision making</li>
                        <li>More time to focus on strategic issues rather than operational matters</li>
                        <li>Central data repository for easy data access</li>
                        <li>Removal of people-dependent procedures</li>
                        <li>Improved student engagement and satisfaction</li>
                        <li>Easy tracking of debts, absentees, exam scores, cash position etc</li>
                        <li>Single point of contact for all tasks</li>
                        <li>Reduced cost of operations</li>
                        <li>Easy tracking of expenses</li>
                        <li>Efficient billing and tracking of fees etc</li>
                    </ul></p>
                    <div><br/></div>
                    <a href="help-overview.html" target="_blank"><i class="fa fa-database"></i>Click here to know more about this application for educational institutions</a>
                
			  </div>
              <div class="col-md-4">
				<h3>Why Us</h3>
                <p>Please find below why our customers prefer us:</p>
                <ul class="default-bullet">
                    <li>High quality output</li>
                    <li>Customer focused business solutions</li>
                    <li>conducive work environment</li>
                    <li>State of the art technology</li>
                    <li>Easy access to our office, no parking fees</li>
                    <li>Honesty in all our dealings: we do not cut corners</li>
                    <li>Expertise in Desktop Publishing</li>
                    <li>Customer friendliness</li>
                </ul>
                <div><br/></div>
				<p><strong>What else do you need? Please visit us today!</p></strong>
			  </div>
			  <div class="col-md-3">
			  	<h3>Contact Us</h3>
				<p>
                	<address>
                        <strong>PubTech Business Cafe.</strong><br>
                        <Strong>Shop 102, Block A(Back Entrance)</Strong><br/>
                        FairTrade Business Complex<br/>
                        [Opposite New Golden Hotel]<br/>
                        22, Kigoma Street<br/>
                        [Road Opposite NAFDAC H/Q]<br/>
                        Wuse Zone 7, Abuja, FCT<br/>
                        Nigeria<br/>
                        <div style="font-size:80%">
                            P. O. Box 8308, Wuse, Abuja<br/>
                            <abbr title="Phone"/>P: +234-705-868-1749
                            <abbr title="Mobile"/>M: +234-805-328-1398<br/>
                            <abbr title="Website"/>W: www.pubtechltd.com<br>
                        </div>
                    </address>
				</p>
			  </div>
			</div>
		</div>
		<hr/>
		<div class="container text-white" style="font-size:100%">
			<div class="row">
				<div class="col-sm-6 pull-left">
					<h4>Send us a Message</h4>
					<div id="contact-div">
						<form role="form" method="POST" name="contact-form" id="contact-form">
                        {!! csrf_field() !!}

							<div class="messages"></div>
							<div class="controls">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="form_name">Firstname *</label>
											<input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your firstname *" required data-error="Firstname is required.">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="form_lastname">Lastname *</label>
											<input id="form_lastname" type="text" name="surname" class="form-control" placeholder="Please enter your lastname *" required data-error="Lastname is required.">
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="form_email">Email *</label>
											<input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required data-error="Valid email is required.">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="form_phone">Phone</label>
											<input id="form_phone" type="tel" name="phone" class="form-control" placeholder="Please enter your phone">
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="form_message">Message *</label>
											<textarea id="form_message" name="message" class="form-control" 
                                            placeholder="Message for us *" rows="4" required data-error="Please,leave us a message."></textarea>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<div class="g-recaptcha" data-sitekey="6LcJzDoUAAAAAO2UCDM94FQEo0AkKXkRvhrj_2-T"></div>
											<!--Replace data-sitekey with your own one, generated at
                                             https://www.google.com/recaptcha/admin
											<strong>Captcha:</strong>
                                            {!! app('captcha')->display() !!}
                                            {!! $errors->first('g-recaptcha-response', '<p class="alert alert-danger">:message</p>') !!}-->
                                            
										</div>
									</div>
									
									<div class="col-md-12">
										<input type="submit" class="btn btn-success btn-send" value="Send message" id="submit-btn">
									</div> 
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="text-muted"><strong>*</strong> These fields are required.</p>
									</div>
								</div>
							</div>
						</form>
                       
					</div>
				</div>
				<div class="col-sm-6" id="map-area" align="center">
					<h4>Visit us</h4>
					<div id="map" align="right"></div>
				</div>
			</div>
		</div>
     	<hr/>
     	<div class="container text-white" style="font-size:90%">
     		<div class="col-sm-12">
				<div class="col-sm-12 pull-right">
					<p class="navbar-text">
						<a href="index.html"><img src="images/logo.png" height="50px" width="75px" alt="pubtech logo" /></a>
						<!--text based navigation-->
						&nbsp; PubTech Business Cafe All rights reserved. &nbsp;&nbsp; &copy;
                        <a href='signon' target="_blank"><span class="glyphicon glyphicon-user"></span> Login</a>
                         &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
                        <?php 
							ini_set('date.timezone', 'Europe/London');
							$startYear = 2013; 
							$thisYear = date('Y'); 
							if ($startYear == $thisYear) {
								echo $startYear;
							}else {
								echo "{$startYear}-{$thisYear}";
							}
						?> 
					</p>
                    
				</div>
			</div>
		</div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('js/contact.js') !!}"></script>
    <script src="{!! asset('js/validator.js') !!}"></script>
    
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>
	
    <script type="text/javascript">
	
		/*$(document).ready(function(){      
			var ht= $("img").height(),      
			wd=$("img").width(),      
			mult=3.0; //change to the no. of times you want to increase your image 
			  //size.
			$("img").on('mouseenter', function(){
				$(this).animate({height: ht*mult, width: wd*mult}, 500);
			});
			$("img").on('mouseleave', function(){
				$(this).animate({height: ht, width: wd}, 500);
			})
		});*/
		
		/*$('img').load(function() {
			$(this).data('height', this.height);
		}).
		bind('mouseenter mouseleave', function(e) {
			$(this).stop().animate({
				height: $(this).data('height') * (e.type === 'mouseenter' ? 2.0 : 1)
			});
		});*/
		/*$('.image').hover(function(){
			//max-width: 100%;
  			//height: auto;
  			//width: auto;
  
			$(this).css({width:"100%",height:"auto"});
		},function(){
			$(this).css({width:"500",height:"200"});   
		});*/
		
		$(document).ready(function () {
			var online;
			var map;
			// check whether this function works (online only)
			try {
			  var x = google.maps.MapTypeId.TERRAIN;
			  online = true;
			} catch (e) {
			  online = false;
			}
			if (online == true){
				// Define the latitude and longitude positions
				var latitude = parseFloat("9.050887336710495");
				var longitude = parseFloat("7.457260067597986");
				var latlngPos = new google.maps.LatLng(latitude, longitude);
				// Set up options for the Google map
				var myOptions = {
					zoom: 16,
					center: latlngPos,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				// Define the map
				map = new google.maps.Map(document.getElementById("map"), myOptions);
				// Add the marker
				var marker = new google.maps.Marker({
					position: latlngPos,
					map: map,
					title: "PubTech-Business Center"
				});
			}
		});
		
		$(function () {
		
			$('#contact-div').validator();
			$('#contact-div').on('submit', function (e) {
				if (!e.isDefaultPrevented()) {
					/*var v = grecaptcha.getResponse();
					if(v.length == 0)
					{
						alert("You can't leave Captcha Code empty");
						return false;
					}
					*/
					try{
						$.ajax({
							url: "{{ route('contactus')}}",
							data: new FormData($("#contact-form")[0]),
							async:false,
							type:'post',
							processData: false,
							contentType: false,
							success: function (data)
							{
								if( data == "Success"){
									alert('Thank you for contacting us.\n We shall respond to your inquiry as quickly as possible.');
									$('#contact-form')[0].reset();
									grecaptcha.reset();
								}else{
									
									alert("Your message was not delivered, please check your settings or telephone us");
								}
								document.getElementById("submit-btn").disabled = false;
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) { 
								alert("Status: " + textStatus); alert("Error: " + errorThrown); 
								document.getElementById("submit-btn").disabled = false;
							} 
						});
					}catch(err){ alert(err.message); }
					return false;
				}
			})
		});
		$('#carousel-generic').carousel();
		var winWidth = $(window).innerWidth();
		$(window).resize(function () {
	
			if ($(window).innerWidth() < winWidth) {
				$('.carousel-inner>.item>img').css({
					'min-width': winWidth, 'width': winWidth
				});
			}
			else {
				winWidth = $(window).innerWidth();
				$('.carousel-inner>.item>img').css({
					'min-width': '', 'width': ''
				});
			}
		});
		$(".open-button").on("click", function() {
		  $(this).closest('.collapse-group').find('.collapse').collapse('show');
		});
		
		$(".close-button").on("click", function() {
		  $(this).closest('.collapse-group').find('.collapse').collapse('hide');
		});
			
    </script>
  </body>
</html>
