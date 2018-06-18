<!DOCTYPE html><head>
  	<meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Educational Software">
    <meta name="author" content="Abayomi Folorunsho">
    <meta name="keyword" content="School, College, Education, Accounting, System Consult, PubTech, Cafe, Business Center">
    <link rel="shortcut icon" href="{!! asset('img/favicon.png') !!}">

    <title>EduSoft</title>

    <!-- Bootstrap CSS -->    
    <link href="{!! asset('css/bootstrap.min.css') !!}" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="{!! asset('css/bootstrap-theme.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/bootstrap-dialog.min.css') !!}" rel="stylesheet">
   
    <!--external css-->
    <!-- font icon -->
    <link href="{!! asset('css/elegant-icons-style.css') !!}" rel="stylesheet" />
    <link href="{!! asset('css/font-awesome.min.css') !!}" rel="stylesheet" />    
    <!-- full calendar css-->
    <link href="{!! asset('assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css') !!}" rel="stylesheet" />
	<link href="{!! asset('assets/fullcalendar/fullcalendar/fullcalendar.css') !!}" rel="stylesheet" />
    <!-- easy pie chart-->
    <link href="{!! asset('assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css') !!}" rel="stylesheet" type="text/css" media="screen"/>
    <!-- owl carousel -->
    <link rel="stylesheet" href="{!! asset('css/owl.carousel.css') !!}" type="text/css">
	<link href="{!! asset('css/jquery-jvectormap-1.2.2.css') !!}" rel="stylesheet">
    <!-- Custom styles -->
	<link rel="stylesheet" href="{!! asset('css/fullcalendar.css') !!}">
	<link href="{!! asset('css/widgets.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/style.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/style-responsive.css') !!}" rel="stylesheet" />
	<link href="{!! asset('css/xcharts.min.css') !!}" rel=" stylesheet">	
	<link href="{!! asset('css/jquery-ui-1.10.4.min.css') !!}" rel="stylesheet">
    <!--
    <link href="{!! asset('css/normalize.css') !!}" rel="stylesheet" type="text/css"/>-->
	<link href="{!! asset('css/datepicker.css') !!}" rel="stylesheet" type="text/css"/>	
    
    <!-----one of these css should be enabled-------------for sortable icon
    <link href="{!! asset('css/dataTables.bootstrap.min.css') !!}" rel="stylesheet">---->
    <link href="{!! asset('css/jquery.dataTables.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/buttons.dataTables.min.css') !!}" rel="stylesheet">
   	<link href="{!! asset('css/jquery.resizableColumns.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/bootstrap-timepicker.min.css') !!}" rel="stylesheet">
    {!! Charts::assets() !!}
    
  <style>
    	* {
			margin: 0;
		}
  		html, body {   
		 	height: 100%;
		 	background: transparent url("/img/sky.jpg") top left no-repeat;
			background-size:cover;
		 	position: relative;
		}
		.wrap {
			min-height: 100%;
			height: auto !important;
			height: 100%;
			margin: 0 auto -50px; /* the bottom margin is the negative value of the footer's height */
		}
		.footer, .push {
			height: 50px; /* .push must be the same height as .footer */
		}
		.v-divider{
			 border-left:2px solid #ccc;
			 height:100%;
		}
		hr {
			height: 1px;
			background-color: #ccc;
			margin-top: 5px;
			padding-top: 5px;
			width: 100%;
		}
		.h-divider{
			 border-top: 2px solid #ccc;
			 margin-top: 5px;
			 padding-top: 5px;
			 width:100%;
		}
		#sidebar ul li .selected{ /*selected screen*/
			color:#000; /*black*/
			font-weight:bolder;
			background-color: #FFF;/*white*/
		}
		#sidebar .active{
			background-color:#999;
			height: auto;
		}
		#sidebar .active a{
			border-bottom: 1px solid #eee;
			color:#FFF;
		}
		#sidebar li ul.selected-body{
			background-color:#FFC;
		}
		.required-field-block {
			position: relative;   
		}
		.required-field-block .required-icon {
			display: inline-block;
			vertical-align: middle;
			margin: -0.25em 0.25em 0em;
			background-color: #E8E8E8;
			border-color: #E8E8E8;
			padding: 0.5em 0.8em;
			color: rgba(0, 0, 0, 0.65);
			text-transform: uppercase;
			font-weight: normal;
			border-radius: 0.325em;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			-ms-box-sizing: border-box;
			box-sizing: border-box;
			-webkit-transition: background 0.1s linear;
			-moz-transition: background 0.1s linear;
			transition: background 0.1s linear;
			font-size: 75%;
		}
			
		.required-field-block .required-icon {
			background-color: transparent;
			position: absolute;
			top: 0em;
			right: 0em;
			z-index: 10;
			margin: 0em;
			width: 30px;
			height: 30px;
			padding: 0em;
			text-align: center;
			-webkit-transition: color 0.2s ease;
			-moz-transition: color 0.2s ease;
			transition: color 0.2s ease;
		}
		
		.required-field-block .required-icon:after {
			position: absolute;
			content: "";
			right: 1px;
			top: 1px;
			z-index: -1;
			width: 0em;
			height: 0em;
			border-top: 0em solid transparent;
			border-right: 30px solid transparent;
			border-bottom: 30px solid transparent;
			border-left: 0em solid transparent;
			border-right-color: inherit;
			-webkit-transition: border-color 0.2s ease;
			-moz-transition: border-color 0.2s ease;
			transition: border-color 0.2s ease;
		}
		
		.required-field-block .required-icon .text {
			color: #B80000;
			font-size: 26px;
			margin: -3px 0 0 12px;
		}
		.required:after {
			content: "*";
			padding-left:1%;
			color:red;
		}
		#btn-save{
			margin-top:2px !important;
		}
		table input, table option, table select, table td, table th{
			padding:0 !important;
			margin:0 !important;
			font-size:85% !important;
		}
		label, table th{
			color:#000 !important;
		}
		/*to reduce the bottom margins between form groups*/
		.form-group {
			margin-bottom: 5px !important;
		}
		/*to reduce the left and right padding between elemnets having this class e.g <div class="col-lg-3 input">*/
		.form-control {
		   	padding-left: 2px !important;
		   	margin: 0 !important;
		   	font-size:90% !important;
		   	width:97%;
			float:left;
		}
		input[type="text"], input[type="date"], input[type="number"], input[type="password"], 
			input[type="email"], input[type="url"], select {
		   height: 30px;
		   font-size: 10px;
		   line-height: 30px;
		}
		.panel-body{
			border:none !important;
		}
		
		 #ui-datepicker-div {
			font-size:100% !important;
		}
		.ui-datepicker {
			background-color: #fff;
			border: 1px solid #66AFE9;
			border-radius: 4px;
			box-shadow: 0 0 8px rgba(102,175,233,.6);
			display: none;
			margin-top: 4px;
			padding: 10px;
			width: 240px;font-size: 9pt !important;
		}
		.nav-tab-class{
			color:#FC9;	
		}
		form label {font-weight:bold}
		
		.table-fixed table {
            table-layout:fixed;
        }
        .table-fixed th, .table-fixed td {
            padding:5px 10px;
            max-width: 100px;
			min-width: 100px;
        }
        .table-fixed thead {
            background:#f9f9f9;
            display:table;
            width:100%;
            width:calc(100% - 18px);
        }
        .table-fixed tbody {
            height:350px;
            overflow:auto;
            overflow-x:hidden;
            display:block;
        }
        .table-fixed tbody tr {
            display:table;
            width:100%;
            table-layout:fixed;
        }
		/*another scrollable
		.zui-table {
			border: none;
			border-right: solid 1px #DDEFEF;
			border-collapse: separate;
			border-spacing: 0;
			font: normal 13px Arial, sans-serif;
		}
		.zui-table thead th {
			background-color: #DDEFEF;
			border: none;
			color: #336B6B;
			padding: 10px;
			text-align: left;
			text-shadow: 1px 1px 1px #fff;
			white-space: nowrap;
		}
		.zui-table tbody td {
			border-bottom: solid 1px #DDEFEF;
			color: #333;
			padding: 10px;
			text-shadow: 1px 1px 1px #fff;
			white-space: nowrap;
		}
		.zui-wrapper {
			position: relative;
		}
		.zui-scroller {
			margin-left: 141px;
			overflow-x: scroll;
			overflow-y: visible;
			padding-bottom: 5px;
			width: 300px;
		}
		.zui-table .zui-sticky-col {
			border-left: solid 1px #DDEFEF;
			border-right: solid 1px #DDEFEF;
			left: 0;
			position: absolute;
			top: auto;
			width: 120px;
		}
		<div class="zui-wrapper">
			<div class="zui-scroller">
				<table class="zui-table">
					<thead>
						<tr>
							<th class="zui-sticky-col">Name</th>
							<th>Number</th>
							<th>Position</th>
							<th>Height</th>
							<th>Born</th>
							<th>Salary</th>
							<th>Prior to NBA/Country</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="zui-sticky-col">DeMarcus Cousins</td>
							<td>15</td>
							<td>C</td>
							<td>6'11"</td>
							<td>08-13-1990</td>
							<td>$4,917,000</td>
							<td>Kentucky/USA</td>
						</tr>
						<tr>
							<td class="zui-sticky-col">Isaiah Thomas</td>
							<td>22</td>
							<td>PG</td>
							<td>5'9"</td>
							<td>02-07-1989</td>
							<td>$473,604</td>
							<td>Washington/USA</td>
						</tr>
						<tr>
							<td class="zui-sticky-col">Ben McLemore</td>
							<td>16</td>
							<td>SG</td>
							<td>6'5"</td>
							<td>02-11-1993</td>
							<td>$2,895,960</td>
							<td>Kansas/USA</td>
						</tr>
						<tr>
							<td class="zui-sticky-col">Marcus Thornton</td>
							<td>23</td>
							<td>SG</td>
							<td>6'4"</td>
							<td>05-05-1987</td>
							<td>$7,000,000</td>
							<td>Louisiana State/USA</td>
						</tr>
						<tr>
							<td class="zui-sticky-col">Jason Thompson</td>
							<td>34</td>
							<td>PF</td>
							<td>6'11"</td>
							<td>06-21-1986</td>
							<td>$3,001,000</td>
							<td>Rider/USA</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>*/
	</style>
  </head>
  <body>
  	<section id="container" class="background">
		<div class="wrap">
			@include('layouts.header.header')
			@include('layouts.sidebars.sidebar')
            
			<section id="main-content">
				<div class="wrapper">
					@if(session('status'))
						<div class="alert alert-info">
							<a class="close" data-dismiss="alert">×</a>
							<strong>{{ session('status') }}</strong> 
						</div>
					@endif 
					@yield('content')
				</div>
				
			</section>
			<div class="push"></div>
		</div>
		<div class="footer">
			@include('layouts.footer.footer')
		</div>
  	</section>
    
    <!-- have a default picture of children in a school -->
    
    <input type="hidden" name="username" id="username" value="{{ Auth::user()->username }}">
   
    <!-- javascripts 
    <script src="{!! asset('js/jquery.js') !!}"></script>-->
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
	<script src="{!! asset('assets/chart-master/Chart.js') !!}"></script>
   
    <!--custome script for all page-->
    <script src="{!! asset('js/scripts.js') !!}"></script>
    <!-- custom script for this page-->
    <script src="{!! asset('js/sparkline-chart.js') !!}"></script>
    <script src="{!! asset('js/easy-pie-chart.js') !!}"></script>
	<script src="{!! asset('js/jquery-jvectormap-1.2.2.min.js') !!}"></script>
	<script src="{!! asset('js/jquery-jvectormap-world-mill-en.js') !!}"></script>
	<script src="{!! asset('js/xcharts.min.js') !!}"></script>
	<script src="{!! asset('js/jquery.autosize.min.js') !!}"></script>
	<script src="{!! asset('js/jquery.placeholder.min.js') !!}"></script>
	<script src="{!! asset('js/gdp-data.js') !!}"></script>	
	<script src="{!! asset('js/morris.min.js') !!}"></script>
	<script src="{!! asset('js/sparklines.js') !!}"></script>	
	<script src="{!! asset('js/charts.js') !!}"></script>
	<script src="{!! asset('js/jquery.slimscroll.min.js') !!}"></script>
    <!----------Data tables----------->
    <script src="{!! asset('js/dataTables.bootstrap.min.js') !!}"></script>
    <script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>
    <script src="{!! asset('js/dataTables.buttons.min.js') !!}"></script>
    <script src="{!! asset('js/jszip.min.js') !!}"></script>
    <script src="{!! asset('js/pdfmake.min.js') !!}"></script>
    <script src="{!! asset('js/vfs_fonts.js') !!}"></script>
    <script src="{!! asset('js/buttons.html5.min.js') !!}"></script>
    <script src="{!! asset('js/jquery.resizableColumns.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap-timepicker.min.js') !!}"></script>
    
    @yield('scripts')
  <script>
  	
	//put the logo of the institute in the header, if any
	$(document).ready( function() {
		$.get("{{ route('getInstituteLogo') }}", function(data){
			if(data !== null && data !==""){
				var path = "{{url('photo/institute')}}";
				var photo_image = path + '/' + data;
				$('#schl_logo').removeAttr('src');
				document.getElementById("schl_logo").src = photo_image;
			}
		});
	});
	//put the name of the institute in the header
	$(document).ready( function() {
		$.get("{{ route('getInstituteName') }}", function(data){
			$("#schl_name").text(data);
		});
	});
  	$(document).ready(function(e){
		$('.search-panel .dropdown-menu').find('a').click(function(e) {
			e.preventDefault();
			var param = $(this).attr("href").replace("#","");
			var concept = $(this).text();
			$('.search-panel span#search_concept').text(concept);
			$('.input-group #search_param').val(param);
		});
	});
  	/*$('a[rel=popover]').popover();
	$('#popover').popover({ 
		html : true,
		placement: 'bottom',
		title: function() {
		  return $("#popover-head").html();
		},
		content: function() {
		  return $("#popover-content").html();
		}
	});*/
	$(function() {
		$('.required-icon').tooltip({
			placement: 'left',
			title: 'Required field'
			});
	});
	/*
	<div class="required-field-block">
		<input type="text" placeholder="Name" class="form-control">
		<div class="required-icon">
			<div class="text">*</div>
		</div>
	</div>
	*/
	$("#search").popover({
		title: '<h4>Enter your search criteria</h4>',
		container: 'body',
		placement: 'bottom',
		html: true, 
		content: function(){
			  return $('#popover-form').html();
		}
	});

	
  	var username = $("#frm-create-class #username").val();
	//==============================================
  	$(document).ready(function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		});
	});
  	//knob
 	$(function() {
        $(".knob").knob({
          'draw' : function () { 
            $(this.i).val(this.cv + '%')
          }
        })
   	});

      //carousel
      $(document).ready(function() {
          $("#owl-slider").owlCarousel({
              navigation : true,
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem : true

          });
      });

      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });
	  
	  /* ---------- Map ---------- */
	$(function(){
		$('#map').vectorMap({
			map: 'world_mill_en',
			series: {
			  regions: [{
				values: gdpData,
				scale: ['#000', '#000'],
				normalizeFunction: 'polynomial'
			  }]
			},
			backgroundColor: '#eef3f7',
			onLabelShow: function(e, el, code){
			  el.html(el.html()+' (GDP - '+gdpData[code]+')');
			}
		});
	});
	
	function Noofmonths(date1, date2) {
		var Nomonths;
		
		Nomonths= (date2.getFullYear() - date1.getFullYear()) * 12;
		Nomonths-= date1.getMonth() + 1;
		Nomonths+= date2.getMonth() +1; // we should add + 1 to get correct month number
		return Nomonths <= 0 ? 0 : Nomonths;
	}
	function trim (str)
	{
		return str.replace (/^\s+|\s+$/g, '');
	}
	function isbeforeDate(dateStr){
		var date_input = toJSDate(dateStr); // Create date for 2016-03-01T00:00:00
		var now   = new Date();         // Create a date for the current instant
		now.setHours(0,0,0,0);          // Set time to 00:00:00.000
		
		if (date_input >= now ) { //false: it is not before date.
		  return false;
		} else {
		  return true;
		}	
	}
	function isafterDate(dateStr){
		var date_input = toJSDate(dateStr); // Create date for 2016-03-01T00:00:00
		var now   = new Date();         // Create a date for the current instant
		now.setHours(0,0,0,0);          // Set time to 00:00:00.000
		
		if (date_input <= now ) {
		  return false;
		} else {
		  return true;
		}	
	}
	function isafterAnotherDate(date_start, date_end){
		var date_start = toJSDate(date_start); // Create date for 2016-03-01T00:00:00
		var date_end = toJSDate(date_end); // Create date for 2016-03-01T00:00:00
		
		if (date_start >= date_end) {
		  return true;
		} else {
		  return false;
		}	
	}
	function toJSDate(dateStr) {
		var dateParts = dateStr.split("/");
		var dateObject = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
		return dateObject;
	}
	function incrementDate(dateObj, days) {
		var nextDate = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate()+days);
		return nextDate;
	}
	function incrementMonth(dateObj, month) {
		var nextDate = new Date(dateObj.getFullYear(), dateObj.getMonth()+month, dateObj.getDate());
		return nextDate;
	}
	function incrementYear(dateObj, year) {
		var nextDate = new Date(dateObj.getFullYear()+year, dateObj.getMonth(), dateObj.getDate());
		return nextDate;
	}
	function fromJSDate(dateObj) {
		//year-month-date
		MyDateString = ('0' + dateObj.getDate()).slice(-2) + '/'
             + ('0' + (dateObj.getMonth()+1)).slice(-2) + '/'
             + dateObj.getFullYear();
		
		return MyDateString;
	}
	function toDate(dateStr) {
		var parts = dateStr.split("/");
		return new Date(parts[2], parts[1] - 1, parts[0]);
	}
	function toDbaseDate(dateStr) {
		return dateStr.split("/").reverse().join("-");
	}
	function fromDbaseDate(dateStr) {
		if( dateStr !== null && dateStr !== "") return dateStr.split("-").reverse().join("/");
	}
	// Checks a string to see if it in a valid date format
	function validate_date(fld) {
		// format D(D)/M(M)/(YY)YY
		var tfld = trim(fld.value);
		var error="";
		var dateFormat = /^\d{1,4}[\.|\/|-]\d{1,2}[\.|\/|-]\d{1,4}$/;
	
		if (dateFormat.test(tfld)) {
			// remove any leading zeros from date values
			s = tfld.replace(/0*(\d*)/gi,"$1");
			var dateArray = s.split(/[\.|\/|-]/);
		  
			// correct month value
			dateArray[1] = dateArray[1]-1;
	
			// correct year value
			if (dateArray[2].length<4) {
				// correct year value
				dateArray[2] = (parseInt(dateArray[2]) < 50) ? 2000 + parseInt(dateArray[2]) : 1900 + parseInt(dateArray[2]);
			}
			var testDate = new Date(dateArray[2], dateArray[1], dateArray[0]);
			if (testDate.getDate()!=dateArray[0] || testDate.getMonth()!=dateArray[1] || testDate.getFullYear()!=dateArray[2]) {
				fld.style.background = 'Yellow';
				error = "You didn't enter a valid date.\n";
				//return false;
			} else {
				fld.style.background = 'White';
				//return true;
			}
		} else {
			error = "You didn't enter a valid date.\n";
			return false;
		}
		return error;
	}
	///////////////using the date value NOT the date ID element
	function validate_date_val(tfld) {
		// format D(D)/M(M)/(YY)YY
		//var tfld = trim(fld.value);
		var error="";
		var dateFormat = /^\d{1,4}[\.|\/|-]\d{1,2}[\.|\/|-]\d{1,4}$/;
	
		if (dateFormat.test(tfld)) {
			// remove any leading zeros from date values
			s = tfld.replace(/0*(\d*)/gi,"$1");
			var dateArray = s.split(/[\.|\/|-]/);
		  
			// correct month value
			dateArray[1] = dateArray[1]-1;
	
			// correct year value
			if (dateArray[2].length<4) {
				// correct year value
				dateArray[2] = (parseInt(dateArray[2]) < 50) ? 2000 + parseInt(dateArray[2]) : 1900 + parseInt(dateArray[2]);
			}
			var testDate = new Date(dateArray[2], dateArray[1], dateArray[0]);
			if (testDate.getDate()!=dateArray[0] || testDate.getMonth()!=dateArray[1] || testDate.getFullYear()!=dateArray[2]) {
				//fld.style.background = 'Yellow';
				error = "You didn't enter a valid date.\n";
				//return false;
			} else {
				//fld.style.background = 'White';
				//return true;
			}
		} else {
			error = "You didn't enter a valid date.\n";
		}
		return error;
	}
	function reset_background(fld){
		fld.style.background = 'White';
	}
	function validate_email(fld) {
		var error="";
		var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
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
			error = "The required field has not been filled in.\n"
		} else {
			fld.style.background = 'White';
		}
		return error;   
	}
	function validate_password(fld) {
		var error = "";
		var illegalChars = /[\W_]/; // allow only letters and numbers 
	 
		if (fld.value == "") {
			fld.style.background = 'Yellow';
			error = "You didn't enter a password.\n";
		} else if ((fld.value.length < 7) || (fld.value.length > 15)) {
			error = "The password is the wrong length. \n";
			fld.style.background = 'Yellow';
		} else if (illegalChars.test(fld.value)) {
			error = "The password contains illegal characters.\n";
			fld.style.background = 'Yellow';
		} else if (!((fld.value.search(/(a-z)+/)) && (fld.value.search(/(0-9)+/)))) {
			error = "The password must contain at least one numeral.\n";
			fld.style.background = 'Yellow';
		} else {
			fld.style.background = 'White';
		}
	   return error;
	}
	function validate_pass_complex(fld) {
		
		/*if (password.length < 8)
		  alert("bad password");
		var hasUpperCase = /[A-Z]/.test(password);
		var hasLowerCase = /[a-z]/.test(password);
		var hasNumbers = /\d/.test(password);
		var hasNonalphas = /\W/.test(password);
		if (hasUpperCase + hasLowerCase + hasNumbers + hasNonalphas < 3)
		  alert("bad password");*/
	 
		if (fld.value == "") {
			fld.style.background = 'Yellow';
			error = "You didn't enter a password.\n";
		} else if ((fld.value.length < 8) || (fld.value.length > 15)) {
			error = "The password is the wrong length. \n";
			fld.style.background = 'Yellow';
		} else if (illegalChars.test(fld.value)) {
			error = "The password contains illegal characters.\n";
			fld.style.background = 'Yellow';
		} else if (!(fld.value.search(/\d/))) {
			error = "Your password must contain at least one digit.\n";
			fld.style.background = 'Yellow';
		} else if (!(fld.value.search(/(a-zA-Z)+/)) ) {
			error = "Your password must contain at least one letter.\n";
			fld.style.background = 'Yellow';
		} else if (!(fld.value.search(/\W/)) ) {
			error = "Your password must contain at least one special xter.\n";
			fld.style.background = 'Yellow';
		}else {
			fld.style.background = 'White';
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
	function validate_no(fld){
		
		var error = "";
		//var stripped = fld.value.replace(/[\(\)\.\-\ ]/g, '');     
		var stripped = fld.value.replace(/[^0-9.,]/g, '');
		
	   if (fld.value == "") {
			error = "You didn't enter a figure.\n";
			fld.style.background = 'Yellow';
		} else if (isNaN(parseInt(stripped))) {
			error = "The number contains illegal characters.\n";
			fld.style.background = 'Yellow';
		} else {
			fld.style.background = 'White';
		}
		return error;
	}
	function validate_bolean(fld){
		var error = "";
		
	   if (fld.value != false && fld.value != true) {
			error = "You didn't enter a valid input.\n";
			fld.style.background = 'Yellow';
		} else {
			fld.style.background = 'White';
		}
		return error;
	}
	function validate_select(fld){
	
		var error = "";
		if (fld.value == "Default" || fld.value == "")
		{
			error = "You didn't pick from the drop-down list.\n";
			fld.style.background = 'Yellow';
		} else {
			fld.style.background = 'White';
		}
		return error;
	}
	function validateHhMm(inputField) {
		//inputField = inputField.value;
		//12:50, 23:40 etc
		var start_time = inputField.value;
		
		var time = start_time.split(":");
		var hour = time[0];
		if(hour == '00') {hour = 24;}
		if(hour > 23) return false;
		
		if(!(/\d\d\:\d\d/.test(start_time))) {
			return false
		}
		return true;
	}
	function validateHhMm_val(inputField) {
		//inputField = inputField.value;
		//12:50, 23:40 etc
		var start_time = inputField;
		
		var time = start_time.split(":");
		var hour = time[0];
		if(hour == '00') {hour = 24;}
		if(hour > 23) return false;
		
		if(!(/\d\d\:\d\d/.test(start_time))) {
			return false
		}
		return true;
	}
	function validTimeSeq(start_time, end_time) {
		var error = "";
		try{
			start_time = start_time.value;
			end_time = end_time.value
			
			var end = end_time.split(":");
			var end_hour = end[0];
			if(end_hour == '00') {end_hour = 24}
			var end_min = end[1];
			//var end_input = end_hour+"."+end_min;
			  
			var start = start_time.split(":");
			var start_hour = start[0];
			if(start_hour == '00') {start_hour = 24}
			var start_min = start[1];
			//var start_input = start_hour+"."+start_min;
			  
			var d1 = new Date();
			d1.setHours(start_hour);
			d1.setMinutes(start_min);
			
			var d2 = new Date();
			d2.setHours(end_hour);
			d2.setMinutes(end_min);
			
			if (d1 > d2) {
				error = "End Time cannot be less than Start Time.\n";
			}
		}catch(err){ error = "Issue with time format.\n"; }	
	  	return error;
	}
	$(".time").keypress(function(event) 
	{
	   var regexs = [/[0-2]/,/[0-9]/,/:/,/[0-5]/,/[0-9]/];
	   
	   var key = event.which;
	   var string = $(this).val() + String.fromCharCode(key);
	   var characters  = string.split("");
	   var passed = true;
	   var isBackspace = key === 8;
	   var shouldTest = characters.length < 5 && ! isBackspace;
	   passed = ! ( characters.length > 5 && ! isBackspace );
	   if(shouldTest)
	   {
		   for (var i = 0; i < characters.length; i++) 
		   {
			   var character = characters[i];
			   var regex = regexs[i];
	
			   var testFailed = ! regex.test(character) ;
	
			   if( testFailed ) 
			   { 
				   passed = false; 
				   break;
			   }
		   }
	   }
	   return passed; 
	});
	
	/*$('.time').blur(function() { 
	  	var value = $(this).val();
		var response = isValidTime(value);
		//alert(response);
	  	return response;
	});
	
	function isValidTime(text) {
	   var regexp = new RegExp(/^([0-2][0-3]):([0-5][0-9])$/)
	   return regexp.test(text);
	}*/
	/*Note: Other than 48 to 57, also add the keycodes for numbers entered from the numeric keypad. 
	Additionally, you'll need some validation to ensure that the format is indeed dd:dd.
	
	For example:*/
	
	$('.time').blur(function (e) {
		if(!(/\d\d\:\d\d/.test($('.time').val()))) {}
	});
	function OnError(xhr, errorType, exception) {
		
		var responseText;
		$("#dialog").html("");
		
		try {
			responseText = jQuery.parseJSON(xhr.responseText);
			$("#dialog").append("<div><b>" + errorType + " " + exception + "</b></div>");
			$("#dialog").append("<div><u>Exception</u>:<br /><br />" + responseText.ExceptionType + "</div>");
			$("#dialog").append("<div><u>StackTrace</u>:<br /><br />" + responseText.StackTrace + "</div>");
			$("#dialog").append("<div><u>Message</u>:<br /><br />" + responseText.Message + "</div>");
		} catch (e) {
			responseText = xhr.responseText;
			$("#dialog").html(responseText);
		}
		
		$("#dialog").dialog({
			title: "jQuery Exception Details",
			width: 700,
			buttons: {
				Close: function () {
					$(this).dialog('close');
				}
			}
		});
	}
	function keypress(fld){}
    function allow_number (fld) {
		//replace all non-numeric xters
		var currentVal = fld.value;
        //currentVal = currentVal.replace(/\D/g,'');//replace all non-digit
		var testDecimal = testDecimals(currentVal);
		if (testDecimal.length > 1) {
			//console.log("You cannot enter more than one decimal point");
			currentVal = currentVal.slice(0, -1);
		}
		fld.value = replaceCommas(currentVal);
		return fld.value;
	}
	function replaceCommas(yourNumber) {
		var components = yourNumber.toString().split(".");
		if (components.length === 1)
			components[0] = yourNumber;
		components[0] = components[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		if (components.length === 2)
			components[1] = components[1].replace(/\D/g, "");
		return components.join(".");
	}
	function convertToNumbers(number){
		return (number.replace(/,/g, ''));
	}
	function testDecimals(currentVal) {
		var count;
		currentVal.match(/\./g) === null ? count = 0 : count = currentVal.match(/\./g);
		return count;
	}
	function addCommas(n){
		var rx=  /(\d+)(\d{3})/;
		return String(n).replace(/^\d+/, function(w){
			while(rx.test(w)){
				w= w.replace(rx, '$1,$2');
			}
			return w;
		});
	}
	function validateDate(dateValue)
    {
        var selectedDate = dateValue;
        if(selectedDate == '')
           return false;

        var regExp = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
        var dateArray = selectedDate.match(regExp); // is format OK?

        if (dateArray == null){
            return false;
        }

        //month = dateArray[1];
        //day= dateArray[3];
        //year = dateArray[5];
		day = dateArray[1];
        month = dateArray[3];
        year = dateArray[5];        
        

        if (month < 1 || month > 12){
            return false;
        }else if (day < 1 || day> 31){ 
            return false;
        }else if ((month==4 || month==6 || month==9 || month==11) && day ==31){
            return false;
        }else if (month == 2){
            var isLeapYear = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
            if (day> 29 || (day ==29 && !isLeapYear)){
                return false
            }
        }
        return true;
    }
	//<input placeholder="mm/dd/yyyy" onkeyup="this.value = fixDatePattern(this.value)">
	function fixDatePattern(currDate) {
		var currentDate = currDate;
		var currentLength = currentDate.length; //lent of current date
		var lastNumberEntered = currentDate[currentLength - 1];
	
		//this is to trim the input
		if (!isNumber(lastNumberEntered)) {
		  return currentDate.substring(0, currentLength - 1);
		}
		//total lent has to be 10
		if (currentLength > 10) {
		  return currentDate.substring(0, 10);
		}
		if (currentLength == 1 && currentDate > 1) {
		  var transformedDate = "0" + currentDate + '/';
		  dateCountTracker = 2;
		  currentLength = transformedDate.length;
		  return transformedDate;
		} else if (currentLength == 4 && currentDate[3] > 3) {
		  var transformedDate = currentDate.substring(0, 3) + "0" + currentDate[3] + '/';
		  dateCountTracker = 5;
		  currentLength = transformedDate.length;
		  return transformedDate;
		} else if (currentLength == 2 && (dateCountTracker != 2 && dateCountTracker != 3)) {
		  dateCountTracker = currentLength;
		  return currentDate + '/';
		} else if (currentLength == 5 && (dateCountTracker != 5 && dateCountTracker != 6)) {
		  dateCountTracker = currentLength;
		  return currentDate + '/';
		}
    	dateCountTracker = currentLength;
    	return currentDate;
  	}

	function isNumber(n) {
    	return !isNaN(parseFloat(n)) && isFinite(n);
  	}
	function convertDate(inputFormat) {
	  function pad(s) { return (s < 10) ? '0' + s : s; }
	  var d = new Date(inputFormat);
	  return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
	}
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	function MergeCommonRows(table) {
		var firstColumnBrakes = [];
		// iterate through the columns instead of passing each column as function parameter:
		for(var i=1; i<=table.find('th').length; i++){
			var previous = null, cellToExtend = null, rowspan = 1;
			table.find("td:nth-child(" + i + ")").each(function(index, e){
				var jthis = $(this), content = jthis.text();
				// check if current row "break" exist in the array. If not, then extend rowspan:
				if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
					// hide the row instead of remove(), so the DOM index won't "move" inside loop.
					jthis.addClass('hidden');
					cellToExtend.attr("rowspan", (rowspan = rowspan+1));
				}else{
					// store row breaks only for the first column:
					if(i === 1) firstColumnBrakes.push(index);
					rowspan = 1;
					previous = content;
					cellToExtend = jthis;
				}
			});
		}
		// now remove hidden td's (or leave them hidden if you wish):
		$('td.hidden').remove();
	}
	function openInNewTab(url) {
		window.open(url, '_blank');
    }
	/*var students = document.getElementById('student-sidebar'),
		fees = document.getElementById('fees-sidebar'),
		exams = document.getElementById('exam-sidebar'),
		system = document.getElementById('system-sidebar'),
		master = document.getElementById('master-sidebar'),
		institution = document.getElementById('institution-sidebar'),
		accounts = document.getElementById('accounts-sidebar'),
		transactions = document.getElementById('transactions-sidebar');
		
		students.style.display = 'none';
		fees.style.display = 'none';
		exams.style.display = 'none';
		system.style.display = 'none';
		master.style.display = 'none';
		institution.style.display = 'none';
		accounts.style.display = 'none';
		transactions.style.display = 'none';
		
	$("#header-settings").on('click', function(event) {
		event.preventDefault();
		//hide others except settings and dashboard
		students.style.display = 'none';
		fees.style.display = 'none';
		exams.style.display = 'none';
		system.style.display = 'none';
		master.style.display = 'block';
		institution.style.display = 'block';
		accounts.style.display = 'none';
		transactions.style.display = 'none';
		
	});
	$("#header-students").on('click', function(event) {
		event.preventDefault();
		//hide others except settings and dashboard
		students.style.display = 'block';
		fees.style.display = 'none';
		exams.style.display = 'none';
		system.style.display = 'none';
		master.style.display = 'none';
		institution.style.display = 'none';
		accounts.style.display = 'none';
		transactions.style.display = 'none';
		
	});
	$("#header-fees").on('click', function(event) {
		event.preventDefault();
		//hide others except settings and dashboard
		students.style.display = 'none';
		fees.style.display = 'block';
		exams.style.display = 'none';
		system.style.display = 'none';
		master.style.display = 'none';
		institution.style.display = 'none';
		accounts.style.display = 'none';
		transactions.style.display = 'none';
		
	});
	$("#header-exams").on('click', function(event) {
		event.preventDefault();
		//hide others except settings and dashboard
		students.style.display = 'none';
		fees.style.display = 'none';
		exams.style.display = 'block';
		system.style.display = 'none';
		master.style.display = 'none';
		institution.style.display = 'none';
		accounts.style.display = 'none';
		transactions.style.display = 'none';
		
	});
	$("#header-admin").on('click', function(event) {
		event.preventDefault();
		//hide others except settings and dashboard
		students.style.display = 'none';
		fees.style.display = 'none';
		exams.style.display = 'none';
		system.style.display = 'none';
		master.style.display = 'none';
		institution.style.display = 'none';
		accounts.style.display = 'block';
		transactions.style.display = 'block';
		
	});*/
	function return_error(jqXHR, exception) { 
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
	}
	
  </script>
 </body>
</html>