@extends('layouts.master')
@section('content')
	<style type="text/css">
        .student-photo{
            height: 125px;
            padding-left: 1px;
            padding-right: 1px;
            border: 1px solid #ccc;
            background: #eee;
            width: 125px;
            margin: 0 auto;
			border:none;
        }
	</style>
	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Student</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getDiscipline')}}">Discipline</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#input" data-toggle="tab" class="nav-tab-class">Discipline</a></li>
                <li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Discipline</a></li>
            </ul>
        
            <div class="tab-content">
                <div class="tab-pane fade in active" id="input">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-discipline" enctype="multipart/form-data" return false>
                            <input type="hidden" name="student_id" id="student_id" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                             <div class="row">
                                <div class="col-md-8">
                                
                                    <div class="form-group col-md-12">
                                        <label for="concept" class="col-md-3 control-label">Date</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="discipline_date" name="discipline_date">
                                        </div>
                                    </div>
                                     <div class="form-group col-md-12">
                                        <label for="reg_no" class="col-md-3 control-label">Reg No.</label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" id="reg_no" name="reg_no">
                                        </div>
                                    </div>
                                    
                                   <div class="form-group col-md-12">
                                        <label for="last_name" class="col-md-3 control-label">Last Name</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" id="last_name" name="last_name" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="first_name" class="col-md-3 control-label">First Name</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" id="first_name" name="first_name" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="class_id" class="col-md-3 control-label">Class</label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" id="class_id" name="class_id" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="infraction" class="col-md-3 control-label">Infraction</label>
                                        <div class="col-md-9">
                                           	<select class="form-control" name="infraction" id="infraction" style="width: 200px;">
                                            	<option value="">Please Select...</option>
                                                <option value="Bullying">Bullying</option>
                                                <option value="Cheating">Cheating</option>
                                                <option value="Discourteous">Discourteous</option>
                                                <option value="Drugs">Drugs Possesion</option>
                                                <option value="Fighting">Fighting</option>
                                                <option value="Inattention">Inattention</option>
                                                <option value="Intoxication">Intoxication</option>
                                                <option value="Lying">Lying</option>
                                                <option value="Rudeness">Rudeness</option>
                                                <option value="Shouting">Shouting</option>
                                                <option value="Stealing">Stealing</option>
                                                <option value="Vandalism">Vandalism</option>
                                                <option value="Violence">Violence</option>
                                            </select>
                                        </div>
                                    </div>
                                   	<div class="form-group col-md-12">
                                        <label for="remarks" class="col-md-3 control-label">Remarks</label>
                                        <div class="col-md-9">
                                           	<textarea class="form-control" rows="2" id="remarks" name="remarks" 
                                            	placeholder="Enter full reason for discipline here"> </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="sanction" class="col-md-3 control-label">Sanction</label>
                                        <div class="col-md-9">
                                           	<select class="form-control" name="sanction" id="sanction" style="width: 200px;">
                                            	<option value="">Please Select...</option>
                                                <option value="Counseling">Counseling</option>
                                                <option value="Punishment">Corporal Punishment</option>
                                                <option value="Detention">Detention</option>
                                                <option value="Expulsion">Expulsion</option>
                                                <option value="Fine">Fine</option>
                                                <option value="Seclusion">Seclusion</option>
                                                <option value="Suspension">Suspension</option>
                                                <option value="Letter">Warning Letter</option>
                                                
                                            </select>
                                        </div>
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
                                <!--------------display the picture of the student here---------------->
                                <div class="col-md-4">
                                	<div class="pull-right">
                               			<img src="{{url('/img/avatar.png')}}" class="student-photo" id="showPhoto">
                                  	</div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        		<div class="tab-pane fade" id="view">
                    <div class="panel panel-body" style="margin-top:5px;">
                    	<div class="col-md-5 pull-left">
                            <div class="input-group">
                                
                                <input type="text" class="form-control" name ="discipline-input" id= "discipline-input" 
                                	placeholder="31/12/2017 30/09/2018">
                                <div class="input-group-btn search-panel">
                                    <button type="button" id="discipline-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
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
	
	$(function(){
		$('#discipline_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	
	$(function(){
		$("#frm-create-discipline").on('submit', function(e){
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
							
							var discipline_date = $('#discipline_date').val();
							$('#discipline_date').val(toDbaseDate(discipline_date));
							
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: "{{ route('updateDiscipline')}}",
							  data: new FormData($("#frm-create-discipline")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										$('#frm-create-discipline')[0].reset();
										$('#table_from tbody').empty();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									
								},
								error: OnError
							});
							$('#discipline_date').val(discipline_date);
						}else{
							
						}
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_date(document.getElementById("discipline_date"));
			reason += validate_select(document.getElementById("sanction"));
			reason += validate_select(document.getElementById("infraction"));
			reason += validate_empty(document.getElementById("reg_no"));
			reason += validate_empty(document.getElementById("last_name"));
			if( isafterDate($('#discipline_date').val()) == true) reason += "Discipline Date cannot be after current date \n";
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(document).on('click', '#discipline-search', function(e){
		e.preventDefault();
		//extract the dates from the string
		var dates = $('#discipline-input').val();
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
			var table = $('#table-class-info').DataTable();
			table.clear();
			table.destroy();
			$.get("{{ route('searchDiscipline') }}", {start_date: start_date, end_date: end_date}, function(data){
				$('#show-info').empty().append(data);
				$('#table-class-info').DataTable();
				$('#pleaseWaitDialog').modal('hide');
			});
		}
	});
	$('#reg_no').on('change', function(){
		//load the respective student enrolled for this class div
		var reg_no = $('#reg_no').val();
		$.get("{{ route('getActiveStudent') }}", {reg_no: reg_no}, function(data){
			//check if there is data
			
			//if(data.length>0){
				var other_name = data.other_name;
				if( other_name == null || other_name == 'null' || other_name == '-') other_name = "";
				$('#student_id').val(data.student_id);
				$('#last_name').val(data.last_name);
				$('#first_name').val(data.first_name);
				$('#other_name').val(other_name);
				$('#last_name').val(data.last_name);
				$('#class_id').val(data.class_name);
				if( data.photo !== null && data.photo !==""){
					var path = "{{url('photo/student')}}";
					var photo_image = path + '/' + data.photo;
					$('#showPhoto').removeAttr('src');
					document.getElementById("showPhoto").src = photo_image;
				}
			//}
		});
	})
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var discipline_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delDiscipline') }}", {discipline_id: discipline_id, operator: operator})
					.done(function(data){ 
						if(Object.keys(data).length > 0){
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
	
</script>
@endsection