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
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getTermination')}}">Termination</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#input" data-toggle="tab" class="nav-tab-class">Termination</a></li>
                <li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Termination</a></li>
            </ul>
        
            <div class="tab-content">
                <div class="tab-pane fade in active" id="input">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-terminate" enctype="multipart/form-data" return false>
                            <input type="hidden" name="student_id" id="student_id" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                             <div class="row">
                                <div class="col-md-8">
                                
                                    <div class="form-group col-md-12">
                                        <label for="concept" class="col-md-3 control-label">Date</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="terminate_date" name="terminate_date">
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
                                        <label for="remarks" class="col-md-3 control-label">Reason</label>
                                        <div class="col-md-9">
                                           	<textarea class="form-control" rows="2" id="remarks" name="remarks" 
                                            	placeholder="Enter full reason for termination here"> </textarea>
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
                                
                                <input type="text" class="form-control" name ="terminate-input" id= "terminate-input" 
                                	placeholder="31/12/2017 30/09/2018">
                                <div class="input-group-btn search-panel">
                                    <button type="button" id="terminate-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
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
		$('#terminate_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	
	$(function(){
		$("#frm-create-terminate").on('submit', function(e){
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
							
							var terminate_date = $('#terminate_date').val();
							$('#terminate_date').val(toDbaseDate(terminate_date));
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: "{{ route('updateTermination')}}",
							  data: new FormData($("#frm-create-terminate")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
								  	if(Object.keys(data).length > 0){
										$('#frm-create-terminate')[0].reset();
										$('#table_from tbody').empty();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									
								},
								error: OnError
							});
							$('#terminate_date').val(terminate_date);
						}
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_date(document.getElementById("terminate_date"));
			reason += validate_empty(document.getElementById("reg_no"));
			reason += validate_empty(document.getElementById("last_name"));
			if( isafterDate($('#terminate_date').val()) == true) reason += "Termination Date cannot be after current date \n";
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(document).on('click', '#terminate-search', function(e){
		e.preventDefault();
		//extract the dates from the string
		var dates = $('#terminate-input').val();
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
			$.get("{{ route('searchTermination') }}", {start_date: start_date, end_date: end_date}, function(data){
				
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
	
</script>
@endsection