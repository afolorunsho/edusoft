@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Fees</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('showFeesDue')}}">Oustanding</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#all-fees-due" data-toggle="tab" class="nav-tab-class">Oustanding Fees</a></li>
            </ul>
        
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="all-fees-due">
                	<div class="panel panel-body">
                	<form action="" method="POST" id="frm-fees-due" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="service_id" id="service_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                         <div class="row">
                     		<div class="col-md-2">
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
                            <div class="col-md-2">
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="class_div_id" class="control-label col-md-8">Section</label>
                                   	<select class="form-control" name="class_div_id" id="class_div_id" style="width: 150px;">
                                        <option value="">Please Select...</option>
                                        
                                  	</select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fees_id" class="control-label col-md-8">Fees</label>
                                   	<select class="form-control" name="fees_id" id="fees_id" style="width: 150px;">
                                        <option value="">Please Select...</option>
                                        @foreach($fees as $y)
                                            <option value="{{ $y->fee_id}}">
                                            {{ $y->fee_name }}</option>
                                        @endforeach
                                        
                                  	</select>
                                </div>
                            </div>
                            <div class="col-md-1">
                            	<button class="btn btn-default btn-sm" type="button" name="search-button" id="search-button">Get</button>
                            </div>
                            <div class="col-md-1">
                           		<div class="dropdown">
                                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-download fa-fw fa-fw"></i>Process
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li id="btn-print-dues"><a role="menuitem"><i class="fa fa-print fa-sm fa-fw"></i>Class Owing</a></li>
                                        <li class="divider"></li>
                                        <li id="btn-print-bills"><a role="menuitem"><i class="fa fa-print fa-sm fa-fw"></i>Class Bills</a></li>
                                    </ul>
                           		</div>
                           	</div>
                          	<div class="col-md-1"> 
                            	<div class="dropdown">
                                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-envelope fa-fw fa-fw"></i>Email
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li id="btn-email-dues"><a role="menuitem"><i class="fa fa-envelope fa-sm fa-fw"></i>Class Owing</a></li>
                                        <li class="divider"></li>
                                        <li id="btn-email-bills"><a role="menuitem"><i class="fa fa-envelope fa-sm fa-fw"></i>Class Bills</a></li>
                                    </ul>
                                </div>
                            </div>
                   	 	</div>
                        <div class="col-md-12">
                            <!------list all those who have not enrolled-------->
                            <div class="table-students">
                                <table class="table" id="all-fees-table">
                                	
                                	<thead>
                                        <tr>
                                            <th>Reg. No</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Class</th>
                                            <th>Charge</th>
                                            <th>Discount</th>
                                            <th>Schorlarship</th>
                                            <th>Refund</th>
                                            <th>Paid</th>
                                            <th style="background-color:#CCC">Balance</th>
                                            <th width="25px">Prt</th>
                                            <th width="25px">Eml</th>
                                  		</tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                       	</div>
                  	</form>
                	</div>
        		</div>
            </div>
    	</div>
 	</div>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">

	var path = "{{url('/')}}";
	path = path + '/reports/pdf/bills/';
	
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
	$(document).on('click', '#search-button', function(e){
		e.preventDefault();
		//load the respective student enrolled for this class div and the various services
		try{
			var table = $('#all-fees-table').DataTable();
				table.clear();
				table.destroy();
			$('#all-fees-table tbody').empty();
			//check for semester and class: section and fees are optional
			var class_div_id = $('#class_div_id').val();
			var semester_id = $('#semester_id').val();
			var fee_id = $('#fees_id').val();
			var class_id = $('#class_id').val();
			
			if( semester_id !== "" && class_id !== ""){
				$('#pleaseWaitDialog').modal();
				
				$.get("{{ route('getAllFeesDue') }}", {
						class_id: class_id,
						class_div_id: class_div_id, 
						semester_id: semester_id, 
						fee_id: fee_id}, 
				function(data){
					
					if (data.length > 0) {
						$.each(data, function(i,l){
							//the default access path is public NOT storage in the root
							var row_ = '';
							var balance = parseFloat(convertToNumbers(l.due));
							if(balance < 0.00){
								row_ = '<tr class="colored">';
							}else{
								row_ = '<tr>';
							}
							var row_data = row_ +
								'<td>' + l.reg_no +'</td>'+
								'<td>' + l.last_name +'</td>'+
								'<td>' + l.first_name +'</td>'+
								'<td>' + l.class_name +'</td>'+
								'<td align="right">' + l.charge +'</td>'+
								'<td align="right">' + l.discount +'</td>'+
								'<td align="right">' + l.scholarship +'</td>'+
								'<td align="right">' + l.refund +'</td>'+
								'<td align="right">' + l.payment +'</td>'+
								'<td align="right" style="background-color:#CCC">' + l.due +'</td>'+
								'<td style="vertical-align: middle; width: 25px;"><button class="btn btn-sm print-this" value="'+ l.student_id + '" ><i class="fa fa-print fa-lg"></i></button></td>'+
								'<td style="vertical-align: middle; width: 25px;"><button class="btn btn-sm email-this" value="'+ l.student_id + '" ><i class="fa fa-envelope fa-lg"></i></button></td>'+
							'</tr>';
							
							$('#all-fees-table> tbody:last-child').append(row_data);
							
						});
						$('#pleaseWaitDialog').modal('hide');
						$('#all-fees-table').DataTable();
					}else{
						alert('No record to display...');	
					}
					
				});
			}else{
				alert('You have to select Term and Class');
			}
		}catch(err){ alert(err.message); }
	})
	$(document).on('click', '.email-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var student_id = $(this).val();
		var semester_id = $('#semester_id').val();
		
		if( semester_id !== "" ){
			sendDueEmailReport(student_id, semester_id);
		}
	});
	function sendDueEmailReport(student_id, semester_id){
		if( student_id !== "" && semester_id !== ""){
			
			$.get("{{ route('emailStudentDues') }}", {
				student_id: student_id,
				semester_id: semester_id
			}, 
			function(data){});
		}
	}
	function sendBillEmailReport(student_id, semester_id){
		if( student_id !== "" && semester_id !== ""){
			
			$.get("{{ route('emailStudentBills') }}", {
				student_id: student_id,
				semester_id: semester_id
			}, 
			function(data){});
		}
	}
	
	$(document).on('click', '.print-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var student_id = $(this).val();
		printDues(student_id);
	});
	function printDues(student_id){
		var class_div_id = $('#class_div_id').val();
		var semester_id = $('#semester_id').val();
		if( class_div_id !== "" && semester_id !== "" ){
			$.get("{{ route('printStudentFees') }}", {
				student_id: student_id,
				class_div_id: class_div_id,
				semester_id: semester_id
			}, 
			function(data){
				if(data !== "" && data !== null){
					
					var path = "{{url('/')}}";
					path = path + '/reports/pdf/bills/';
					path = path + data;
					//var w = window.open(path);
					var w = window.open(path);
					w.print();
				}
			});
		}else{
			alert("Please select Class Section and Academic Term");	
		}
	}
	$(document).on('click', '#btn-print-dues', function(e){
		e.preventDefault();
		//this prints at the class level since that is where fees are defined
		var class_id = $('#class_id').val();
		//then the semester is crucial also
		var semester_id = $('#semester_id').val();
		if( class_id !== "" && semester_id !== "" ){
			
			document.getElementById("btn-print-dues").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			//first get all the student records for the indicated class	
			$.get("{{ route('listClassStudents') }}", {class_id: class_id}, function(data){
				//for each student, print the fees
				$.each(data, function(i,l){
					var student_id = l.student_id;
					$.get("{{ route('printAllFees') }}", {
							class_id: class_id,
							semester_id: semester_id,
							student_id: student_id
							},function(data){}
						);
				});
				document.getElementById("btn-print-dues").innerHTML = 
					'<a role="menuitem"><i class="fa fa-print fa-sm fa-fw"></i>Class Owing</a>';
				
				alert('bills generated...');
				
				var path = "{{url('/')}}";
				path = path + '/reports/pdf/bills/';
				var w = window.open(path);
			});
		}else{
			alert("You need to select the Class and Academic Term");	
		}
	});
	$(document).on('click', '#btn-print-bills', function(e){
		e.preventDefault();
		//this prints at the class level since that is where fees are defined
		var class_id = $('#class_id').val();
		//then the semester is crucial also
		var semester_id = $('#semester_id').val();
		if( class_id !== "" && semester_id !== "" ){
			
			document.getElementById("btn-print-bills").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
				
			$.get("{{ route('listClassStudents') }}", {class_id: class_id}, function(data){
				//return student records
				$.each(data, function(i,l){
					var student_id = l.student_id;
					
					$.get("{{ route('printStudentBill') }}", {
							class_id: class_id,
							semester_id: semester_id,
							student_id: student_id
						},function(data){}
					);
				});
				document.getElementById("btn-print-bills").innerHTML = 
					'<a role="menuitem"><i class="fa fa-print fa-sm fa-fw"></i>Class Bills</a>';
				alert('bills generated...');
				
				var path = "{{url('/')}}";
				path = path + '/reports/pdf/bills/';
				var w = window.open(path);
			});
		}else{
			alert("You need to select the Class and Academic Term");
		}
	});
	$(document).on('click', '#btn-email-dues', function(e){
		e.preventDefault();
		//this prints at the class level since that is where fees are defined
		var class_id = $('#class_id').val();
		//then the semester is crucial also
		var semester_id = $('#semester_id').val();
		if( class_id !== "" && semester_id !== "" ){
			
			document.getElementById("btn-print-dues").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
				
			$.get("{{ route('listClassStudents') }}", {class_id: class_id}, function(data){
				//return student records
				$.each(data, function(i,l){
					var student_id = l.student_id;
					sendDueEmailReport(student_id, semester_id);
				});
				document.getElementById("btn-print-dues").innerHTML = 
					'<a role="menuitem"><i class="fa fa-print fa-sm fa-fw"></i>Class Owing</a>';
				
				alert('Outstanding fees sent electronically...');
			});
		}else{
			alert("You need to select the Class and Academic Term");	
		}
	});
	$(document).on('click', '#btn-email-bills', function(e){
		e.preventDefault();
		//this prints at the class level since that is where fees are defined
		var class_id = $('#class_id').val();
		var semester_id = $('#semester_id').val();
		
		if( class_id !== "" && semester_id !== "" ){
			document.getElementById("btn-print-bills").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			
			$.get("{{ route('listClassStudents') }}", {class_id: class_id}, function(data){
				//return student records
				$.each(data, function(i,l){
					var student_id = l.student_id;
					sendBillEmailReport(student_id, semester_id);
					
				});
				document.getElementById("btn-print-bills").innerHTML = 
					'<a role="menuitem"><i class="fa fa-print fa-sm fa-fw"></i>Class Bills</a>';
				alert('Bills sent electronically...');
			});
		}else{
			alert("You need to select the Class and Academic Term");
		}
	});
	
</script>
@endsection