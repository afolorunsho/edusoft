@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Student</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getExit')}}">Exit</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#input-exit" data-toggle="tab" class="nav-tab-class">Exit</a></li>
                <li class="nav"><a href="#view-exit" data-toggle="tab" class="nav-tab-class">View Exit</a></li>
            </ul>
        
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="input-exit">
                	<div class="panel panel-body">
                	<form action="" method="POST" id="frm-create-exit" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="exit_id" id="exit_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                         <div class="row">
                     		<div class="col-md-3">
                                <div class="form-group">
                                    <label for="exit_date" class="control-label col-md-8">Exit Date</label>
                                    <input type="text" class="form-control" name="exit_date" id="exit_date" 
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
                            <table class="table" id="exit-table">
                                <thead>
                                    <tr>
                                        <th width="5%">X</th>
                                        <th width="10%">Reg. No</th>
                                        <th width="15%">Last Name</th>
                                        <th width="15%">First Name</th>
                                        <th width="10%">Other Name</th>
                                        <th width="10%">Photo</th>
                                        <th width="15%">Reason</th>
                                        <th width="20%">Remarks</th>
                                     </tr>
                                </thead>
                                <tbody id="table-body">
                                </tbody>
                            </table>
                        </div>
                  	</form>
                	</div>
        		</div>
                <div class="tab-pane fade in" id="view-exit">
                    <div class="panel panel-body" style="margin-top:5px;">
                    	<div class="col-md-5 pull-left">
                            <div class="input-group">
                                
                                <input type="text" class="form-control" name ="view-date" id= "view-date" 
                                	placeholder="31/12/2017 30/09/2018">
                                <div class="input-group-btn search-panel">
                                    <button type="button" id="exit-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span id="search_concept">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body" id="show-info">
                            
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
	
	$(document).ready(function(){
		$('#view-table').DataTable();
	});
	
	$(function(){
		$('#exit_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
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
	$(function(){
		$("#frm-create-exit").on('submit', function(e){
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
							
							var table_cnt = 0;
							$("#exit-table tbody tr td:nth-child(1) input").each(function() {
								var checked = $(this).is(":checked");
								if( checked == true) {
									table_cnt = table_cnt + 1;
								}
							});
							if ( table_cnt > 0){
								
								var exit_date = $('#exit_date').val();
								$('#exit_date').val(toDbaseDate(exit_date));
								
								$('#pleaseWaitDialog').modal();
								$.ajax({
								  url: "{{ route('updateExit')}}",
								  data: new FormData($("#frm-create-exit")[0]),
								  async:false,
								  type:'post',
								  processData: false,
								  contentType: false,
								  success:function(data){
									  
										if(Object.keys(data).length > 0){
											$('#frm-create-exit')[0].reset();
											$('#exit-table tbody').empty();
											alert('Update successful');
										}else{
											alert('Update NOT successful');
										}
										$('#pleaseWaitDialog').modal('hide');
									},
									error: OnError
								});
								$('#exit_date').val( exit_date );
							}else{
								alert('No student was selected');
							}
						}
						$('#pleaseWaitDialog').modal('hide');
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_date(document.getElementById("exit_date"));
			reason += validate_select(document.getElementById("class_id"));
			reason += validate_select(document.getElementById("class_div_id"));
			if( isafterDate($('#exit_date').val()) == true) reason += "Exit Date cannot be after current date \n";
			
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	var reasons = '<select class="form-control reasons" name="reasons[]">'+
			'<option value="">Select</option>'+
			'<option value= "Graduation" >Graduation</option>'+
			'<option value= "Transfer" >Transfer</option>'+
			'<option value= "Death" >Death</option>'+
			'<option value= "Termination" >Termination</option>'+
			'<option value= "Absconded" >Absconded</option>'+
			'<option value= "Others" >Others</option>'+
			'</select>';
			
	$('#class_div_id').on('change', function(){
		//load the respective student enrolled for this class div
		$('#exit-table tbody').empty();
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
					'<td>' + reasons + '</td>'+
					'<td><input type="text" name="remarks[]" class="form-control remarks" value=""></td>'+
					'</tr>';
				$('#exit-table> tbody:last-child').append(row_data);
				
			});
			$('#pleaseWaitDialog').modal('hide');
		});
	})
	$(function(){
		$('#view-input').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	$(document).on('click', '#exit-search', function(e){
		e.preventDefault();
		//extract the dates from the string
		var dates = $('#view-date').val();
		//extract start date and end date
		var end_date = dates.slice(-10);
		var start_date = dates.slice(0,10);
		var reason = "";
		reason += validate_date_val(start_date);
		reason += validate_date_val(end_date);
		if(reason == ""){
			start_date = toDbaseDate(start_date);
			end_date = toDbaseDate(end_date);
			$('#pleaseWaitDialog').modal();
			var table = $('#table-exit-info').DataTable();
			table.clear();
			table.destroy();
			$.get("{{ route('searchExit') }}", {start_date: start_date, end_date: end_date}, function(data){
				$('#show-info').empty().append(data);
				$('#table-exit-info').DataTable({
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
	});
	
</script>
@endsection