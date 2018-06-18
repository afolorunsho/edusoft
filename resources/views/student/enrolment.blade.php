@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Student</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getEnrolment')}}">Enrolment</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#enrolment" data-toggle="tab" class="nav-tab-class">Enrolment</a></li>
                <li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Enrolment</a></li>
                <li class="nav"><a href="#class" data-toggle="tab" class="nav-tab-class">Class Enrolment</a></li>
            </ul>
        
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="enrolment">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-enrolment" enctype="multipart/form-data" return false>
                            <input type="hidden" name="enrol_id  " id="enrol_id" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                             <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="enrol_date" class="control-label col-md-8">Enrolment Date</label>
                                        <input type="text" class="form-control" name="enrol_date" id="enrol_date" 
                                        	placeholder="Enrolment Date">
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
                                <table class="table" id="enrol-table">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Reg. No</th>
                                            <th>Reg Date</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Other Names</th>
                                            <th>Gender</th>
                                            <th>DoB</th>
                                            <th>Photo</th>
                                         </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        @foreach($records as $c)
                                            <tr>
                                                <td>
                                                    <div><input type="checkbox" name="students[]" class="checkbox vert-align" 
                                                        value="{{ $c->student_id }}" ></div>
                                                </td>
                                                <td>{{ $c->reg_no }}</td>
                                                <td>{{ $c->reg_date }}</td>
                                                <td>{{ $c->last_name }}</td>
                                                <td>{{ $c->first_name }}</td>
                                                <td>{{ $c->other_name }}</td>
                                                <td>{{ $c->gender }}</td>
                                                <td>{{ $c->dob }}</td>
                                                <td><img src="{{url('photo/student'.'/'.$c->photo)}}" width="50" height="35" /></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
               		</div>
                </div>
                <div class="tab-pane" id="view">
                	<div class="panel panel-body">
                    	<form action="" method="POST" id="frm-create-subject" enctype="multipart/form-data" return false>
                        	<div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="class_id2" class="control-label col-md-8">Class</label>
                                         <select class="form-control" name="class_id2" id="class_id2" onchange="class2Change()">
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
                                @if(Auth::user()->role_id == '1' ||
                                    Auth::user()->role_id == '5' ||
                                    Auth::user()->role_id == '9' ||
                                    Auth::user()->role_id == '8')
                                    <div class="dropdown pull-right">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fa fa-download fa-fw fa-fw"></i>Export
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li id="export-to-excel"><a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw">
                                         	</i>Export to Excel</a></li>
                                       	</ul>
                                    </div>
                              	@endif
                                <div class="table-responsive col-md-12">
                                    <table class="table" id="view-table">
                                        <thead>
                                            <tr>
                                                <th>Reg. No</th>
                                                <th>Last Name</th>
                                                <th>First Name</th>
                                                <th>Other Name</th>
                                                <th>Enrol Date</th>
                                                <th>Photo</th>
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
                <!----->
                <div class="tab-pane" id="class">
                	<!-----GENERATE Class Form----->
                	<div class="panel panel-body">
                    	<div class="pull-right">
                            <button type="button" class="btn btn-primary" id="btn-forms">Generate Class Forms</button>
                        </div>
                    	<div class="panel-body" id="show-info">
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
		showInfo();
	});
	function showInfo(){
		$('#pleaseWaitDialog').modal();
		var table = $('#table-class-info').DataTable();
			table.clear();
			table.destroy();
		$.get("{{ route('classOccupancy')}}", function(data){
			$('#show-info').empty().append(data);
			$('#table-class-info').DataTable({
				dom: 'Bfrtip',
				buttons: [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5'
				]
			})
			$('#pleaseWaitDialog').modal('hide');
		});
	}
	$(function(){
		$('#enrol_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	$(document).ready(function(){
		$('#enrol-table').DataTable();
	});
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
	function class2Change(){
		//empty table section whenever there is a change in the class
		$('#class_div_id2').empty();
		var class_id = $('#class_id2').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id2').append('<option value="">Please Select...</option>');
			$.each(data, function(i,l){
				var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
				$('#class_div_id2').append(row_data);
			});
		});
	}
	$(function(){
		$("#frm-create-enrolment").on('submit', function(e){
			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to update this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						var is_valid = validateFormOnSubmit();
						//date in the right format and not after current date
						//student picked
						//class and section picked
						if (is_valid){
							var table_cnt = 0;
							//remove the unticked classes before proceeding to eloquent for update. At least one should be ticked
							$("#enrol-table tbody tr td:nth-child(1) input").each(function() {
								var checked = $(this).is(":checked");
								if( checked == true) {
									table_cnt = table_cnt + 1;
								}
							});
							if ( table_cnt > 0){
								//deletion not necessssary as the app will only take those ticked
								/*$("#enrol-table tbody tr td:nth-child(1) input").each(function() {
									var checked = $(this).is(":checked");
									if( checked == false) {
										$(this).closest('tr').remove();
									}
								});*/
								
								var enrol_date = $('#frm-create-enrolment #enrol_date').val();
								$('#frm-create-enrolment #enrol_date').val(toDbaseDate(enrol_date));
								$('#pleaseWaitDialog').modal();
								$.ajax({
								  url: "{{ route('updateEnrolment')}}",
							      data: new FormData($("#frm-create-enrolment")[0]),
								  async:false,
								  type:'post',
								  processData: false,
								  contentType: false,
								  success:function(data){
										if(Object.keys(data).length > 0){
											$('#frm-create-enrolment')[0].reset();
											$('#enrol-table tbody').empty();
											alert('Update successful');
										}else{
											alert('Update NOT successful');
										}
										$('#pleaseWaitDialog').modal('hide');
									},
									error: OnError
								});
								$('#frm-create-enrolment #enrol_date').val(enrol_date);
							}else{
								alert('No record was checked');	
							}
						}
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_date(document.getElementById("enrol_date"));
			reason += validate_select(document.getElementById("class_id"));
			reason += validate_select(document.getElementById("class_div_id"));
			if( isafterDate($('#enrol_date').val()) == true) reason += "Enrol Date cannot be after current date \n";
			
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$('#class_div_id2').on('change', function(){
		//load the respective student enrolled for this class div
		$('#view-table tbody').empty();
		var class_div_id = $('#class_div_id2').val();
		$.get("{{ route('getDivStudents') }}", {class_div_id: class_div_id}, function(data){
			$.each(data, function(i,l){
				//empty the table
				var other_name = l.other_name;
				if( other_name == null || other_name == 'null' || other_name == '-') other_name = "";
				var path = "{{url('photo/student')}}";
				var photo_image = path + '/' + l.photo;
				var row_data = '<tr>' +
					'<td>' + l.reg_no +'</td>'+
					'<td>' + l.last_name +'</td>'+
					'<td>' + l.first_name +'</td>'+
					'<td>' + other_name +'</td>'+
					'<td>' + l.enrol_date +'</td>'+
					'<td><img src= "' + photo_image + '" width="35" height="35" /></td>' +
					'</tr>';
				$('#view-table tbody:last-child').append(row_data);
			});
		});
	})
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
			
		$.get("{{ route('excelEnrolment') }}", function(data){
				$('#pleaseWaitDialog').modal('hide');
					
				var path = "{{url('/')}}";
				path = path + '/reports/excel/';
				
				var a = document.createElement("a");
				a.href = path + data.file;
				a.download = data.name;
				a.target = "_blank";
				a.title = data.file;
				document.body.appendChild(a);
				a.click();
				a.remove();
		});
	});
	$(document).on('click', '#btn-forms', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
			
		$.get("{{ route('classForms') }}", function(data){
				$('#pleaseWaitDialog').modal('hide');
				
				var path = "{{url('/')}}";
				path = path + '/reports/pdf/' + data;
				openInNewTab(path);
				
		});
	});
	//this should be for ecah student
	$(document).on('click', '.view-this', function(e){
		e.preventDefault();
		var student_id = $(this).val();
		$.get("{{ route('printRegistration') }}", {student_id: student_id}).done(function(data){ 
			//show student details, academic performance, fees, attendance for current year in a dialog box
			
		})
		.fail(function(xhr, status, error) {
			// error handling
			alert("data update error => " + error.responseJSON)
		});
		
	});
</script>
@endsection