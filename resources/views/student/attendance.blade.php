@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Student</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getAttendance')}}">Attendance</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#attendance" data-toggle="tab" class="nav-tab-class">Attendance</a></li>
                <li class="nav"><a href="#view-class" data-toggle="tab" class="nav-tab-class">View Class Attendance</a></li>
                <li class="nav"><a href="#view-student" data-toggle="tab" class="nav-tab-class">View Student Attendance</a></li>
            </ul>
        
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="attendance">
                	<div class="panel panel-body">
                	<form action="" method="POST" id="frm-create-attendance" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="attendance_id" id="attendance_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                         <div class="row">
                     		<div class="col-md-3">
                                <div class="form-group">
                                    <label for="attendance_date" class="control-label col-md-8">Attendance Date</label>
                                    <input type="text" class="form-control" name="attendance_date" id="attendance_date" 
                                    	placeholder="Enter date here">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_id" class="control-label col-md-8">Class</label>
                                     <select class="form-control" name="class_id" id="class_id" onchange="classChange()">
                                        <option value="">Please Select...</option>
                                        @foreach($class as $y)
                                            <option value="{{ $y->class_id}}">
                                            {{ $y->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_div_id" class="control-label col-md-8">Section</label>
                                   	<select class="form-control" name="class_div_id" id="class_div_id" style="width: 200px;">
                                        <option value="">Please Select...</option>
                                        
                                  	</select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                	<div class="pull-right">
                                        <button type="submit" class="btn btn-primary" id="btn-save">
                                        <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                    </div>
                                </div>
                            </div>
                   	 	</div>
                        <!------list all those who have not enrolled-------->
                        <div class="table-responsive">
                            <table class="table" id="attend-table">
                                <thead>
                                    <tr>
                                        <th>Present</th>
                                        <th>Reg. No</th>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Other Name</th>
                                        <th>Photo</th>
                                        <th>Arrival Time</th>
                                        <th>Remarks</th>
                                     </tr>
                                </thead>
                                <tbody id="table-body">
                                </tbody>
                            </table>
                        </div>
                  	</form>
                	</div>
        		</div>
                
                <div class="tab-pane fade in" id="view-student">
                    <div class="panel panel-body" id="show-student" style="margin-top:5px;">
                    	<div class="col-md-3 pull-left">
                            <div class="form-group">
                                <input type="text" class="form-control" id="lastname" placeholder="Student Reg No.">
                            </div>
                        </div>
                        <div class="col-md-7 pull-right">
                            <div class="input-group">
                                <div class="input-group-btn search-panel">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span id="search_concept">Search Criteria</span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                      <li><a href="#search_last">Day</a></li>
                                      <li><a href="#search_last">Week</a></li>
                                      <li><a href="#search_gender">Term</a></li>
                                      <li><a href="#search_gender">Academic Year</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="search_param" value="all" id="search_param">         
                                <input type="text" class="form-control" name="x" placeholder="Search value...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><span class="fa fa-search-plus"></span></button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><span class="fa fa-print"></span></button>
                                </span>
                            </div>
                        </div>
                        
                        <!---the action should allow for print a single record and delete, if not enrolled(enrol=0)---->
                  		<div class="form-group">
                      		<table class="table table-hover" id="view-table">
                         		<thead>
                          			<tr>
                                    	<th>Date</th>
                                        <th>Class</th>
                                        <th>Arrival Time</th>
                                        <th>Remarks</th>
                               		 </tr>
                              	</thead>
                              	<tbody id="table-body">
                                </tbody>
                        	</table>
                 		</div>
                    </div>
                </div>
                <div class="tab-pane fade in" id="view-class">
                    <div class="panel panel-body" id="show-class" style="margin-top:5px;">
                   		<div class="row">
                     		<div class="col-md-3">
                                <div class="form-group">
                                    <label for="attendance_date_class" class="control-label col-md-8">Attendance Date</label>
                                    <input type="text" class="form-control" name="attendance_date_class" id="attendance_date_class" 
                                    	placeholder="Enter date here">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_id_class" class="control-label col-md-8">Class</label>
                                     <select class="form-control" name="class_id_class" id="class_id_class" onchange="classChange2()">
                                        <option value="">Please Select...</option>
                                        @foreach($class as $y)
                                            <option value="{{ $y->class_id}}">
                                            {{ $y->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_div_id" class="control-label col-md-8">Section</label>
                                     <select class="form-control" name="class_div_id_class" id="class_div_id_class" style="width: 200px;">
                                        <option value="">Please Select...</option>
                                        
                                    </select>
                                </div>
                            </div>
                   	 	</div>
                        <!------list all those who have not enrolled-------->
                        <div class="table-responsive">
                            <table class="table" id="attend-table-class">
                                <thead>
                                    <tr>
                                        <th>Reg. No</th>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Other Name</th>
                                        <th>Arrival Time</th>
                                        <th>Remarks</th>
                                        <th>Photo</th>
                                     </tr>
                                </thead>
                                <tbody id="table-body">
                                </tbody>
                            </table>
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
	
	$(document).ready(function(){
		$('#view-table').DataTable();
	});
	
	$(function(){
		$('#attendance_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	$(function(){
		$('#attendance_date_class').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	//onChange Class Div then display the students in that class alone to enable you indicate attendance
	
	function classChange(){
		//empty table section whenever there is a change in the class
		$('#class_div_id').empty();
		var class_id = $('#class_id').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id').append('<option value="">Please Select...</option>');
			$.each(data, function(i,l){
				var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
				$('#class_div_id').append(row_data);
			});
		});
	}
	function classChange2(){
		//empty table section whenever there is a change in the class
		$('#class_div_id_class').empty();
		var class_id = $('#class_id_class').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id_class').append('<option value="">Please Select...</option>');
			$.each(data, function(i,l){
				var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
				$('#class_div_id_class').append(row_data);
			});
		});
	}
	$(function(){
		$("#frm-create-attendance").on('submit', function(e){
			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to update this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						var is_valid = validateFormOnSubmit();
						if (is_valid){
							//iterate through the table and update the Remarks columns with "Present for those checked"
							//AND Absent: + other remarks typed in the field for those not checked
							
							var attendance_date = $('#attendance_date').val();
							$('#attendance_date').val(toDbaseDate(attendance_date));
							
							$("#attend-table tbody tr td:nth-child(1) input").each(function() {
								var checked = $(this).is(":checked");
								if( checked == false){
									var remarks = $(this).closest('tr').find('input.remarks').val();
									 $(this).closest('tr').find('input.remarks').val("Absent: " + remarks);
								}else{
									$(this).closest('tr').find('input.remarks').val("Present");
								}
								var arrival_time = $(this).closest('tr').find('input.arrival_time').val();
								if(arrival_time == null || arrival_time =="" ) $(this).closest('tr').find('input.arrival_time').val("0:00");
								
							});
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: "{{ route('updateAttendance')}}",
							  data: new FormData($("#frm-create-attendance")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										$('#frm-create-attendance')[0].reset();
										$('#attend-table tbody').empty();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
								},
								error: OnError
							});
							$('#date_from').val( attendance_date );
						}
					}
				}
			});
		})
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_date(document.getElementById("attendance_date"));
			reason += validate_select(document.getElementById("class_id"));
			reason += validate_select(document.getElementById("class_div_id"));
			if( isafterDate($('#attendance_date').val()) == true) reason += "Attendance Date cannot be after current date \n";
			
			$("#attend-table tbody tr td:nth-child(1) input").each(function() {
				var checked = $(this).is(":checked");
				if( checked !== false){
					var arrival_time = $(this).closest('tr').find('input.arrival_time').val();
					if(validateHhMm_val(arrival_time) == false) reason += "Wrong Time Format \n";
				}
			});
			
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$('#class_div_id').on('change', function(){
		//load the respective student enrolled for this class div
		$('#attend-table tbody').empty();
		var class_div_id = $('#class_div_id').val();
		$.get("{{ route('getDivStudents') }}", {class_div_id: class_div_id}, function(data){
			$('#pleaseWaitDialog').modal();
			$.each(data, function(i,l){
				//the default access path is public NOT storage in the root
				var other_name = l.other_name;
				
				if( other_name == null || other_name == 'null' || other_name == '-') other_name = "";
				
				var path = "{{url('photo/student')}}";
				var photo_image = path + '/' + l.photo;
				var row_data = '<tr>' +
					'<td><div><input type="checkbox" class="checkbox vert-align" name="students[]" value="' + l.student_id +'" ></div></td>' +
					'<td>' + l.reg_no +'</td>'+
					'<td>' + l.last_name +'</td>'+
					'<td>' + l.first_name +'</td>'+
					'<td>' + other_name +'</td>'+
					'<td><img src= "' + photo_image + '" width="35" height="25" /></td>' +
					'<td><input type="text" name="arrival_time[]" class="form-control time" value="07:30"></td>'+
					'<td><input type="text" name="remarks[]" class="form-control remarks" value=""></td>'+
					'</tr>';
				$('#attend-table> tbody:last-child').append(row_data);
				
			});
			$('#pleaseWaitDialog').modal('hide');
		});
	})
	$('#class_div_id_class').on('change', function(){
		//load the respective student enrolled for this class div
		$('#attend-table-class tbody').empty();
		var attendance_date = toDbaseDate($('#attendance_date_class').val());
		var class_div_id = $('#class_div_id_class').val();
		
		//check date validity
		$.get("{{ route('getClassAttendance') }}", {class_div_id: class_div_id, attendance_date: attendance_date}, function(data){
			$('#pleaseWaitDialog').modal();
			$.each(data, function(i,l){
				//the default access path is public NOT storage in the root
				
				var other_name = l.other_name;
				if( other_name == null || other_name == 'null') other_name = "";
				
				var path = "{{url('photo/student')}}";
				var photo_image = path + '/' + l.photo;
				var row_data = '<tr>' +
					'<td>' + l.reg_no +'</td>'+
					'<td>' + l.last_name +'</td>'+
					'<td>' + l.first_name +'</td>'+
					'<td>' + other_name +'</td>'+
					'<td>' + l.arrival_time +'</td>'+
					'<td>' + l.remarks +'</td>'+
					'<td><img src= "' + photo_image + '" width="35" height="25" /></td>' +
					'</tr>';
				$('#attend-table-class> tbody:last-child').append(row_data);
				
			});
			$('#pleaseWaitDialog').modal('hide');
		});
	})
</script>
@endsection