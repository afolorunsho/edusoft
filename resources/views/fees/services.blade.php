@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Fees</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getStudentService')}}">Services</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#service" data-toggle="tab" class="nav-tab-class">Services</a></li>
                <li class="nav"><a href="#view-student" data-toggle="tab" class="nav-tab-class">View Student Services</a></li>
            </ul>
        
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="service">
                	<div class="panel panel-body">
                	<form action="" method="POST" id="frm-create-service" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="service_id" id="service_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                         <div class="row">
                     		<div class="col-md-3">
                                <div class="form-group">
                                    <label for="semester" class="control-label col-md-8">Term/Semester</label>
                                    <select class="form-control" name="semester_id" id="semester_id">
                                        <option value="">Please Select...</option>
                                        @foreach($semester as $y)
                                            <option value="{{ $y->semester_id}}">
                                            {{ $y->semester }}</option>
                                        @endforeach
                                    </select>
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
                        <div class="col-md-6">
                            <!------list all those who have not enrolled-------->
                            <div class="table-students">
                                <table class="table" id="student-table">
                                	<caption>Student Table</caption>
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Reg. No</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Other Name</th>
                                         </tr>
                                    </thead>
                                    <tbody id="table-body">
                                    </tbody>
                                </table>
                            </div>
                       	</div>
                        <div class="col-md-6">
                            <!------list all those who have not enrolled-------->
                            <div class="table-services">
                                <table class="table" id="services-table">
                                	<caption>Optional Fees</caption>
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Service</th>
                                            <th>Amount</th>
                                         </tr>
                                    </thead>
                                    <tbody id="table-body">
                                    </tbody>
                                </table>
                            </div>
                       	</div>
                  	</form>
                	</div>
        		</div>
                <div class="tab-pane fade in" id="view-student">
                    <div class="panel panel-body" id="show-student" style="margin-top:5px;">
                    		<div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label col-md-8">Term/Semester</label>
                                    <select class="form-control" name="semester_id2" id="semester_id2">
                                        <option value="">Please Select...</option>
                                        @foreach($semester as $y)
                                            <option value="{{ $y->semester_id}}">
                                            {{ $y->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="class_id2" class="control-label col-md-8">Class</label>
                                     <select class="form-control" name="class_id2" id="class_id2" onchange="classChange2()">
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
                                    <label for="class_div_id2" class="control-label col-md-8">Section</label>
                                   	<select class="form-control" name="class_div_id2" id="class_div_id2" style="width: 200px;">
                                        <option value="">Please Select...</option>
                                        
                                  	</select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fees_id2" class="control-label col-md-8">Fees</label>
                                   	<select class="form-control" name="fees_id2" id="fees_id2" style="width: 200px;">
                                        <option value="">Please Select...</option>
                                        @foreach($fees as $y)
                                            <option value="{{ $y->fee_id}}">
                                            {{ $y->fee_name }}</option>
                                        @endforeach
                                        
                                  	</select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" id="services-search" class="btn btn-default"><span id="search_concept">Search</span>
                                </button>
                        	</div>
                        <!---the action should allow for print a single record and delete, if not enrolled(enrol=0)---->
                  		<div class="form-group">
                      		<table class="table table-hover" id="student-fees-table">
                         		<thead>
                          			<tr>
                                    	<th>Reg No</th>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Other Name</th>
                                        <th>Class</th>
                                        <th>Fees</th>
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
	
	$(function(){
		$('#service_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	//onChange Class Div then display the students in that class alone to enable you indicate service
	
	function classChange(){
		//empty table section whenever there is a change in the class
		$('#class_div_id').empty();
		var class_id = $('#class_id').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#class_div_id').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	}
	function classChange2(){
		//empty table section whenever there is a change in the class
		$('#class_div_id2').empty();
		var class_id = $('#class_id2').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id2').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#class_div_id2').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	}
	$(function(){
		$("#frm-create-service").on('submit', function(e){
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
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: "{{ route('updateStudentService')}}",
							  data: new FormData($("#frm-create-service")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
								  	if(Object.keys(data).length > 0){
										$('#frm-create-service')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									
									$('#service_id').val('');
								},
								error: OnError
							});
						}
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_select(document.getElementById("semester_id"));
			reason += validate_select(document.getElementById("class_id"));
			reason += validate_select(document.getElementById("class_div_id"));
			
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$('#class_div_id').on('change', function(){
		//load the respective student enrolled for this class div and the various services
		try{
			$('#student-table tbody').empty();
			var class_div_id = $('#class_div_id').val();
			
			$.get("{{ route('getDivStudents') }}", {class_div_id: class_div_id}, function(data){
				if(data.length > 0){
					$.each(data, function(i,l){
						var other_name = l.other_name;
						if( other_name == null || other_name == 'null' || other_name == '-') other_name = "";
						//the default access path is public NOT storage in the root
						var row_data = '<tr>' +
							'<td><div><input type="checkbox" class="checkbox vert-align" name="students[]" value="' + 
								l.student_id +'" ></div></td>' +
							'<td>' + l.reg_no +'</td>'+
							'<td>' + l.last_name +'</td>'+
							'<td>' + l.first_name +'</td>'+
							'<td>' + other_name +'</td>'+
							'</tr>';
						$('#student-table> tbody:last-child').append(row_data);
					});
				}
				///////////////////get the fees for this sections
				$('#services-table tbody').empty();
				if(data.length > 0){
					$.get("{{ route('getDivServices') }}", {class_div_id: class_div_id}, function(data){
						$.each(data, function(i,l){
							var row_data = '<tr>' +
								'<td><div><input type="checkbox" class="checkbox vert-align" name="fees[]" value="' + 
									l.fee_id +'" ></div></td>' +
								'<td>' + l.fee_name +'</td>'+
								'<td  align="right">' + l.fee_amount +'</td>'+
								'</tr>';
							$('#services-table> tbody:last-child').append(row_data);
						});
					});
				}else{
					alert('No record to display...');	
				}
				////////////////////
			});
			
		}catch(err){ alert(err.message); }
	})
	$(document).on('click', '#services-search', function(e){
		e.preventDefault();
		try{
			$('#student-fees-table tbody').empty();
			var class_id = $('#class_id2').val();
			var class_div_id = $('#class_div_id2').val();
			var fee_id = $('#fees_id2').val();
			var semester_id = $('#semester_id2').val();
			if( semester_id == "" || class_id == "" ){
				alert("The only selection that can be empty are Class Section and Fees");
				return	
			}
			var table = $('#student-fees-table').DataTable();
				table.clear();
				table.destroy();
			//$('#student-fees-table').dataTable().fnDestroy();
				
			$('#pleaseWaitDialog').modal();
			
			$.get("{{ route('getStudentFees') }}", {
				class_id: class_id,
				class_div_id: class_div_id,
				fee_id: fee_id,
				semester_id: semester_id
			}, 
			function(data){
				if (data.length > 0) {
					$.each(data, function(i,l){
						var other_name = l.other_name;
						if( other_name == null || other_name == 'null') other_name = "";
						var row_data = '<tr>' +
							'<td>' + l.reg_no +'</td>'+
							'<td>' + l.last_name +'</td>'+
							'<td>' + l.first_name +'</td>'+
							'<td>' + other_name +'</td>'+
							'<td>' + l.class +'</td>'+
							'<td>' + l.fees +'</td>'+
							
							'</tr>';
						$('#student-fees-table> tbody:last-child').append(row_data);
					});
					$('#student-fees-table').DataTable({
						dom: 'Bfrtip',
						buttons: [
							'copyHtml5',
							'excelHtml5',
							'csvHtml5'
						]
					})
				}else{
					alert('No record to display...');	
				}
				$('#pleaseWaitDialog').modal('hide');
			});
			
		}catch(err){ alert(err.message); }
	})
</script>
@endsection