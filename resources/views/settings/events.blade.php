@extends('layouts.master')
@section('content')
@include('settings.popup_event')
@include('popup.file_destination')
	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Settings</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getEvents')}}">Events Calendar</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#core-events" data-toggle="tab" class="nav-tab-class">Core Events</a></li>
                <li class="nav"><a href="#view-events" data-toggle="tab" class="nav-tab-class">View Events</a></li>
            	{{---<li class="nav"><a href="#other-events" data-toggle="tab" class="nav-tab-class">Other Events</a></li>----}}
            </ul>
           <div class="tab-content">
            	<div class="tab-pane fade in active" id="core-events">
                	<div class="panel panel-body">
                        
                    	<form class="form-horizontal" id="frm-create-core-events" enctype="multipart/form-data" return false>
                            <input type="hidden" name="academic_start" id="academic_start" value="">
                            <input type="hidden" name="academic_end" id="academic_end" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="col-md-3 form-line">
                               	<div class="form-group">
                                    <label for="academic_id">Academic Year</label>
                                    <div class="input-group">
                                        <select class="form-control" name="academic_id" id="academic_id" class="col-md-8" style="width: 200px;">
                                            <option value="">Please Select...</option>
                                            @foreach($records as $y)
                                                <option value="{{ $y->academic_id}}">
                                                {{ $y->academic }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                         	</div>
                             <div class="col-md-9">
                             	<div class="col-md-12">
                             		<div class="event-score">
                                        <button type="button" class="btn btn-default pull-left" id="add_row">
                                            <i class="fa fa-plus fa-fw fa-fw"></i>Add Event</button>
                                        <button type="submit" class="btn btn-primary pull-right" id="btn-save-core">
                                            <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                    
                                    </div>
                             	</div>
                          		<div class="col-md-12">
                                    <table class="table table-hover table-striped table-condensed" id="table-input">
                                        <caption>Core Events in the Academic Calendar</caption>
                                        <thead>
                                            <tr>
                                                <th width="27%">Term</th>
                                                <th width="12%">Start Date</th>
                                                <th width="12%">End Date</th>
                                                <th width="20%">Holiday</th>
                                                <th width="12%">Start Date</th>
                                                <th width="12%">End Date</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                			</div>
                 		</form>
                    </div>
              	</div>
                <div class="tab-pane fade in" id="view-events">
                	<div class="panel panel-body">
                        <div class="col-md-6 pull-left v-divider">
                            <div class="panel panel-default">
                                <div class="panel-heading">Academic Semester Information</div>
                                <div class="panel-body" id="show-term">
                                
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-md-6 pull-right v-divider">
                            <div class="panel panel-default">
                                <div class="panel-heading">Event Calendar Information</div>
                                <div class="panel-body" id="show-event">
                                
                                </div>
                            </div>
                        </div>
                	</div>
              	</div>
                <div class="tab-pane fade in" id="other-events">
                	<div class="panel panel-body">
                        <form method="POST" class="form-horizontal" id="frm-create-events" enctype="multipart/form-data" return false>
                            <input type="hidden" name="event_id" id="event_id" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="col-md-4 form-line">
                                <div class="form-group">
                                    <label for="events">Event Name</label>
                                    <input type="text" class="form-control" id="event_name" name="event_name" placeholder="Enter Event Name">
                                </div>
                                <div class="form-group">
                                    <label for="event_type_id">Event Type</label>
                                    <div class="input-group">
                                        <select class="form-control" name="event_type_id" id="event_type_id">
                                            <option value="">Please Select...</option>
                                            @foreach($event_types as $key=>$g)
                                                <option value="{{ $g->event_type_id}}">
                                                {{ $g->event_type}}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-addon">
                                            <span class="fa fa-plus" id="add-more-event"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date_from">Event Start Date</label>
                                    <input type="text" class="form-control" id="date_from" name="date_from" placeholder="Enter Event Start Date">
                                </div>	
                                <div class="form-group">
                                    <label for="date_to ">Event End Date</label>
                                    <input type="text" class="form-control" id="date_to" name="date_to" placeholder="Enter Event End Date">
                                </div>
                                 <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                      <div class="pull-right">
                                        <button type="submit" class="btn btn-primary" id="btn-save">
                                            <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-8 pull-right v-divider">
                            <div class="panel panel-default">
                                <div class="panel-heading">Event Calendar Information</div>
                                <div class="panel-body" id="show-info">
                                
                                </div>
                            </div>
                        </div>
                    </div>
          		</div>
        	</div>
      	</div>
 	</div>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		showEvent();
	});
	$(document).ready( function() {
		showTerm();
	});
	var academic_beg = '';
	var academic_sto = '';
	$(function(){
		$('#date_from').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	$(function(){
		$('#date_to').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	$('#add_row').on('click', function(e){
		e.preventDefault();
		add_row();
	});
	$("#table-input tbody").on("change", ".semester_end", function(e){
		e.preventDefault();
		var row = $(this).closest("tr");
		var last_date = row.find("input.semester_end").val();
		last_date = toJSDate(last_date);
		last_date = incrementDate(last_date, 1);
		last_date = fromJSDate(last_date);
		
		row.find('input.holiday_start').val(last_date);
		
	});
	function add_row(){
		var academic_id = $('#academic_id').val();
		if(academic_id !== null && academic_id !== ""){
			var academic_end = $('#academic_end').val();
			var academic_start = $('#academic_start').val();
		
			//determine if it is the first row in the table, then start with academic_start
			var tbody = $("#table-input tbody");
			if (tbody.children().length == 0) {
				var row_data = '<tr>' +
					'<td><input type="text" class="form-control semester" name="semester[]"></td>'+
					'<td><input type="text" placeholder="dd/mm/yyyy" '+
						'class="form-control semester_start text-right" name="semester_start[]" value="'+ academic_start +'" readonly></td>'+
					'<td><input type="text" placeholder="dd/mm/yyyy" '+
						'class="form-control semester_end text-right" name="semester_end[]" value=""></td>'+
					'<td>Holiday Dates =></td>'+
					'<td><input type="text" placeholder="dd/mm/yyyy" '+
						'class="form-control holiday_start text-right" name="holiday_start[]" value="" readonly></td>'+
					'<td><input type="text" placeholder="dd/mm/yyyy" '+
						'class="form-control holiday_end text-right" name="holiday_end[]" value="'+ academic_end +'"></td>'+
					'<td><button class="btn btn-md remove-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
				'</tr>';
				$('#table-input tbody').append(row_data);
			}else{
				//get the end date of the last row
				var tr = $('#table-input tr:last'); 
				var last_date = $(tr).find("td").find('input.holiday_end').val();
				//increment it by one day and gray it out
				last_date = toJSDate(last_date);
				last_date = incrementDate(last_date, 1);
				last_date = fromJSDate(last_date);
				var row_data = '<tr>' +
					'<td><input type="text" class="form-control semester" name="semester[]"></td>'+
					'<td><input type="text" placeholder="dd/mm/yyyy" '+
						'class="form-control semester_start text-right" name="semester_start[]" value="'+ last_date +'" readonly></td>'+
					'<td><input type="text" placeholder="dd/mm/yyyy" '+
						'class="form-control semester_end text-right" name="semester_end[]" value=""></td>'+
					'<td>Holiday Dates =></td>'+
					'<td><input type="text" placeholder="dd/mm/yyyy" '+
						'class="form-control holiday_start text-right" name="holiday_start[]" value="" readonly></td>'+
					'<td><input type="text" placeholder="dd/mm/yyyy" '+
						'class="form-control holiday_end text-right" name="holiday_end[]" value="'+ academic_end +'"></td>'+
					'<td><button class="btn btn-md remove-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
				'</tr>';
				$('#table-input tbody').append(row_data);
			}
			$('.semester_end').datepicker({dateFormat: 'dd/mm/yy'});
			$(".semester_end").on("blur", function (e){
				var isValidDate = validateDate(e.target.value);
				if(!isValidDate){
					 alert("Please enter a valid date in DD/MM/YYYY format");
				}
			});
			
			$('.holiday_end').datepicker({dateFormat: 'dd/mm/yy'});
			$(".holiday_end").on("blur", function (e){
				var isValidDate = validateDate(e.target.value);
				if(!isValidDate){
					 alert("Please enter a valid date in DD/MM/YYYY format");
				}
			});
		}else{
			alert('No academic year was selected...');	
		}
	}
	$('#academic_id').on('change', function(e){
		e.preventDefault();
		//populate academic start and end date
		var academic_id = $('#academic_id').val();
		
		$.get("{{ route('getAcademicDates')}}",{academic_id: academic_id}, function(data){
			$('#academic_start').val(data.date_from);
			$('#academic_end').val(data.date_to);
			academic_beg = data.date_from;
			academic_sto = data.date_to;
			//then fill the table with information, if it exists
			$.get("{{ route('getAcademicCalendar')}}",{academic_id: academic_id}, function(data){
				if(data !== null && data !== "") $('#table-input tbody').empty().append(data);   
			});
		});
		
	});
	function validateCoreEvents() {
		try{
			var reason = "";
			var holiday_end = "";  //the last holiday should correspond with calendar end date
			var academic_end = $('#academic_end').val();
			var academic_start = $('#academic_start').val();
			var count = 0;
			
			//////////now check the table data for validity
			$('#table-input tbody tr').each(function(){
				
				var semester = trim($(this).closest('tr').find('input.semester').val());
				//replace / in the string as it connects folder
				semester = semester.replace('/', '-');
				semester = semester.replace('\\', '-');
				$(this).closest('tr').find('input.semester').val(semester);
				
				var semester_start = trim($(this).closest('tr').find('input.semester_start').val());
				var semester_end = trim($(this).closest('tr').find('input.semester_end').val());
				holiday_end = trim($(this).closest('tr').find('input.holiday_end').val());
				var holiday_start = trim($(this).closest('tr').find('input.holiday_start').val());
				var semester = $(this).closest('tr').find('input.semester').val();
				
				reason += validate_date_val(semester_start);
				reason += validate_date_val(semester_end);
				reason += validate_date_val(holiday_start);
				reason += validate_date_val(holiday_end);
				//semester cannot be empty
				var last_date = toJSDate(semester_end);
				last_date = incrementDate(last_date, 1);
				var curr_date = toJSDate(holiday_start);
				//check whether the two are the same
				if(curr_date.getTime() !== last_date.getTime()) reason += "The dates do not seem to synchronise \n";
			
				if( semester == "" || semester == null) reason += "Academic Term should not be empty \n";
				//semester start cannot be greater than semester end
				if( isafterAnotherDate(semester_start, semester_end) == true)  reason += "Session start cannot be more than end date \n";
				//holiday start cannot be greater than holiday end
				if( isafterAnotherDate(holiday_start, holiday_end) == true)  reason += "Holiday start cannot be more than end date \n";
				//first semester start should correspond with the academic year start date
				if (count == 0){
					if( semester_start !== academic_start)  reason += "Academic start date not same as first semester date \n";
				}
				count++;
			});
			
			//last input holiday end date should correspond with the academic year end date
			if( holiday_end !== academic_end)  reason += "Academic end date not same as last holiday date \n";
			reason += validate_select(document.getElementById("academic_id"));
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(function(){
		$("#frm-create-core-events").on('submit', function(e){
  			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to update this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						var is_valid = validateCoreEvents();
						
						if (is_valid){
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: "{{ route('updateCoreEvent')}}",
							  data: new FormData($("#frm-create-core-events")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										showEvent();
										showTerm();
										alert('Update successful');
										$('#frm-create-core-events')[0].reset();
									}else{
										alert('Update NOT successful: Ensure date harmonisation with previous dates');
									}
									$('#pleaseWaitDialog').modal('hide');
								},
								error: OnError
							});
						}
					}
				}
			});
		})
	});
	$(function(){
		$("#frm-create-events").on('submit', function(e){
  			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to update this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						var is_valid = validateFormOnSubmit();
						//alert(is_valid);
						if (is_valid){
							$('#date_from').val(toDbaseDate($('#date_from').val()));
							$('#date_to').val(toDbaseDate($('#date_to').val()));
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: "{{ route('updateEvent')}}",
							  data: new FormData($("#frm-create-events")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										showEvent();
										showTerm();
										alert('Update successful');
										$('#frm-create-events')[0].reset();
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#event_id').val('');
								},
								error: OnError
							});
							$('#date_from').val( fromDbaseDate($('#date_from').val()) );
							$('#date_to').val( fromDbaseDate($('#date_to').val()) );
						}
					}
				}
			});
		})
		
	});
	
	function showEvent(){
		
		$.get("{{ route('infoEvent')}}", function(data){
			$('#show-event').empty().append(data);
		});
	}
	function showTerm(){
		
		$.get("{{ route('infoSemester')}}", function(data){
			$('#show-term').empty().append(data);
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("event_name"));
			reason += validate_select(document.getElementById("event_type_id"));
			reason += validate_date(document.getElementById("date_from"));
			reason += validate_date(document.getElementById("date_to"));
			
			var start_date = $('#date_from').val();
			var end_date = $('#date_to').val();
			//start cannot be less than end
			var start_ =  toDate(start_date);
			var end_ =  toDate(end_date);
			
			if (start_ >= end_){
				reason += 'invalid date sequence';
			}
			
			//events year can only be for a period of not less than nine months
			start_date = toJSDate(start_date);
			end_date = toJSDate(end_date);
			
			if (reason != "") {
				alert("Some fields need correction:\n\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(document).on('click', '.edit-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var event_id = $(this).data('id');
		$('#event_id').val(event_id);
		$.get("{{ route('editEvent') }}", {event_id: event_id}, function(data){
			$.each(data, function(i,l){
				
				$('#frm-create-events #event_name').val(l.event_name); //this uses the progam_id from level file
				$('#frm-create-events #event_type_id').val(l.event_type_id); 
				$('#frm-create-events #date_from').val(l.date_from);
				$('#frm-create-events #date_to').val(l.date_to);
			});
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		//alert('event');
		try{
			$.get("{{ route('excelEvent') }}", function(data){
				$('#pleaseWaitDialog').modal('hide');
				
				var path = "{{url('/')}}";
				path = path + '/reports/excel/';
				
				path = path + 'sch_events-csvfile.xlsx';
				openInNewTab(path);
			});
		}catch(err){ alert(err.message); }
		
	});
	$(document).on('click', '#export-to-pdf', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('pdfEvent') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	});
	$("#table-input tbody").on("click", ".remove-this", function(e){
		e.preventDefault();
		var this_ = $(this);
		del_row(this_);
	});
	function del_row(this_){
		this_.fadeOut('slow',function(){
			this_.closest('tr').remove();
		});
	}
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var event_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delEvent') }}", {event_id: event_id, operator: operator}).done(function(data){ 
						if(Object.keys(data).length > 0){
							showEvent();
							showTerm();
							alert('Update successful');
						}else{
							alert('Update NOT successful');
						}
					})
					.fail(function(xhr, status, error) {
						// error handling
						alert("data update error => " + error.responseJSON)
					});
				}
			}
		});
	});
	$(document).on('click', '.del-term', function(e){
		e.preventDefault();
		var semester_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delSemester') }}", {semester_id: semester_id, operator: operator}).done(function(data){ 
						if(Object.keys(data).length > 0){
							showEvent();
							showTerm();
							alert('Update successful');
						}else{
							alert('Update NOT successful');
						}
					})
					.fail(function(xhr, status, error) {
						// error handling
						alert("data update error => " + error.responseJSON)
					});
				}
			}
		});
	});
	$(function(){
		$('#add-more-event').on('click', function(e){
			e.preventDefault();
			$('#event-show').modal('show');
		})
	});
	$(document).on('click', '#event-type-submit', function(e){
		e.preventDefault();
		var event_type = $('#event').val();
		var operator =  $('#operator').val();
		
		if( event_type == null || event_type == ""){
			alert('Empty field not allowed');
		}else{
			$.post("{{ route('createEventType') }}", {event_type: event_type, operator: operator},function(data){
				if(Object.keys(data).length > 0){
					showEvent();
					showTerm();
					alert('Update successful');
				}else{
					alert('Update NOT successful');
				}
			});
		}
	});
</script>
@endsection