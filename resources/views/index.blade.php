<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="We have a school sotware to solve your school academic and administrative challenges">
    <meta name="keywords" content="business cafe, business center, college application, school application, college software, school software, software, passport photos, graphic design">
    <meta name="author" content="System Consult">
    <title>Abuja Top Business Center</title>
    <!-- Bootstrap core CSS -->
    <link href="{!! asset('css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/font-awesome.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/bootstrap-theme.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/full-slider.css') !!}" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <style>
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
			<div class="navbar-header" style="margin-top:-20px; padding:0;">
				<a class="navbar-brand" href="#"><h3>PubTech Business Cafe</h3></a>
			</div>
			 <ul class="nav navbar-nav navbar-right" style="font-size:80%;">
             	 P: +234-705-868-1749&nbsp;&nbsp; M: +234-805-328-1398&nbsp;&nbsp; &nbsp;22, Kigoma Street, Wuse Zone 7, Abuja, FCT
				<!--<li class="active"><a href="#">Home</a></li>
				<li><a href="#">Support</a></li>
                <li><a href='signon' target="_blank"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>-->
                
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
                <div class="item" align="center"><img src="images/center/biz-center.png" alt="" /></div>
				<div class="item" align="center"><img src="images/center/graphics.png" alt=""/></div>
				<div class="item" align="center"><img src="images/center/products.png" alt="" /></div>
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
    <section class="py-5" style="font-size:90%">
		<!--<div class="container">-->
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Solution<i class="fa fa-file-text pull-right"></i></h3>
						</div>
						<div class="panel-body">
                        	<div class="col-md-12">
                            	 <p>
                                    It is our pleasure to introduce the <i><Strong>Lite Edition</Strong></i> of our international software, EduSoft, for educational institutions to you. 
                                    </p>
                                    <p>
                                    The college software is a product of our desire to assist schools, colleges and other institutions to easily capture and maintain proper records of school activities, both academic and administrative, and to access these records in the most convenient way.
                                    </p>
                        	</div>
                            <div class="col-md-12">
                            	<h4 class="panel-title">Setup</h4>
                                <div class="col-md-6">
                                	<p>
                                    This application allows you to setup the school with vital details and customise basic photos and images. It also allows you to capture student details. The interface is intuitive and user-friendly.
                                    </p>
                            	</div>
                                <div class="col-md-6">
                                	 <img src="images/center/institute_setup.png" width="200" height="150" class="image"/>
                            	</div>
                                
                         	</div>
                            <div class="col-md-12">
                            	<h4 class="panel-title">Exams</h4>
                            	<div class="col-md-6">
                                	<p>
                                    On school exams, the software allows you to customise various class subject exams and define their weight in the final result. 
                                    </p>
                                    <p>
                                    The application, therefore does not limit you to pre-defined exams. It allows you to setup exams per class and per subject. 
                                    </p>
                            	</div>
                                <div class="col-md-6">
                                	 <img src="images/center/result_term.jpg" width="200" height="150" class="image"/>
                            	</div>
                                
                          	</div>
                            <div class="col-md-12">
                            	<h4 class="panel-title">Student Fees</h4>
                            	<div class="col-md-6">
                                	<p>
                                    An important part of school activities is the definition of optional and mandatory fees for classes and students in a seamless manner. This application not only allow you to do this but it also allows you to bill students and track oustanding fees.
                                    </p>
                            	</div>
                                <div class="col-md-6">
                                	 <img src="images/center/fee_outstanding.png" width="200" height="150" class="image"/>
                                     <img src="images/center/fees_receipt.jpg" width="200" height="150" class="image"/>
                                     
                            	</div>
                                
                                
                          	</div>
                            <div class="col-md-12">
                            	<h4 class="panel-title">Financial Performance</h4>
                            	<div class="col-md-6">
                                	<p>
                                    This software is not just about academic activities, it is also an accounting application. Hence, it allows you to post expenses incurred in running the school. Above all, it allows you to ascertain your periodic financial performance.
                                    </p>
                            	</div>
                                <div class="col-md-6">
                                	 <img src="images/center/performance_yearly.png" width="200" height="150" class="image"/>
                            	</div>
                                
                          	</div>
                            <div class="col-md-12">
                                <p>
                                    Please feel free to signup and <a href="#contact-form">contact us</a> today for this solution.
                                </p>
                                <p>For more information about this software, please click <a href="{{ route('getConsult') }}" target="_blank">here</a></p>
                                
               				</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-primary">
						<div class="panel-heading">
							Services
							<i class="fa fa-ticket pull-right"></i>
						</div>
						<div class="panel-body">
							
							<p>We render the following following business center services:</p>
							<div class="col-md-6">
							    <p><Strong>Web Applications</Strong></p>
								<ul class="custom-bullet">
									<li>System Design</li>
									<li>Data Architecture</li>
									<li>System Development</li>
									<li>Database Management</li>
								</ul>
								<br>
								<p><Strong>Graphic Designs</Strong></p>
								<ul class="custom-bullet">
									<li>Jotters</li>
                                    <li>Event Programmes</li>
                                    <li>Posters</li>
									<li>Company Profile</li>
									<li>Letter Heads</li>
									<li>Logo</li>
									<li>Envelopes</li>
								</ul>
								<br>
								<p><Strong>Printing</Strong></p>
								<ul class="custom-bullet">
									<li>Direct Image(DI) Print</li>
									<li>Duplex Printing</li>
									<li>Photocopy</li>
								</ul>
								<br>
								<p><Strong>Photo Services</Strong></p>
								<ul class="custom-bullet">
								    <li>Photo prints</li>
									<li>Instant passport photographs</li>
									<li>Image Management</li>
                                    <li>Digital Album</li>
								</ul>
								<br>
								<p><Strong>Cyber Cafe</Strong></p>
								<ul class="custom-bullet">
									<li>Internet Services</li>
									<li>Online Form Filling</li>
									<li>Biometric Registration</li>
									<li>Online Signature Capture</li>
								</ul>
							    <br>
								<p><Strong>Card Services</Strong></p>
								<ul class="custom-bullet">
									<li>Identity Cards</li>
									<li>Business cards</li>
									<li>Labels</li>
								</ul>
								<br>
								<p><Strong>Others</Strong></p>
								<ul class="custom-bullet">
									<li>Document Binding</li>
									<li>Document Lamination</li>
									<li>Document Scanning</li>
									<li>USB Flash, Memory Cards and other Office Stationery</li>
								</ul>
							</div>
							<div class="col-md-6">
								<img src="images/center/web-desktop.png" width="200" height="150"  />
								<img src="images/center/web-wedding.png" width="200" height="150"  />
							    <img src="images/center/web-internet.png" width="200" height="150"  />
                                <img src="images/center/bklet.JPG" width="200" height="150"  />
                                <img src="images/center/jotter.jpg" width="200" height="150"  />
							</div>
						</div>
					</div>
				</div>
			</div>
		<!--</div>-->
    </section>

    <!-- Footer -->
    <footer class="bg-dark">
    	<div class="container text-white" style="font-size:90%">
			
            <div class="row">
              <div class="col-md-5">
				<h3>Our EduSoft Application</h3>
				<p>
                    Our web-based school softare, EduSoft, was developed by our sister firm, <a href="{{ route('getConsult') }}" target="_blank">System Consult</a>, with the following benefits:
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
                    <p>Please click <a href="edu_soft_pp.pdf" target="_blank">here</a> to view the powerpoint presentation</p>
                
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
								<div class="input-group">
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
								<div class="input-group">
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
								<div class="input-group">
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
								<div class="input-group">
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
			
    </script>
  </body>
</html>
