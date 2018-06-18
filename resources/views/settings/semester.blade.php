@extends('layouts.master')
@section('content')
@include('popup.file_destination')
	<div class="row">
        <div class="col-lg-12">
            {{---<h3 class="page-header"><i class="fa fa-file-text-o"></i>Settings</h3>----}}
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Settings</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getSemester')}}">Semester/Term</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage Academic Semester
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                	<form action="" method="POST" class="form-horizontal" id="frm-create-semester" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="semester_id" id="semester_id" value=""> 
                        <input type="hidden" name="active" id="active" value="1"> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="col-md-4 form-line">
                            <div class="form-group">
                                <label for="semester">Semester(Term)</label>
                                <input type="text" class="form-control" id="semester" name="semester" placeholder="Enter Academic Semester">
                            </div>
                            <div class="form-group">
                                <label for="academic_id">Academic Year</label>
                                <div class="input-group">
                                	<select class="form-control" name="academic_id" id="academic_id" class="col-md-8" style="width: 250px;">
                                    	<option value="">Please Select...</option>
                                        @foreach($records as $y)
                                            <option value="{{ $y->academic_id}}">
                                            {{ $y->academic }}</option>
                                        @endforeach
                                	</select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date_from">Semester Start Date</label>
                                <input type="text" class="form-control" id="date_from" name="date_from" placeholder="Enter Academic Start Date">
                            </div>	
                            <div class="form-group">
                                <label for="date_to ">Semester End Date</label>
                                <input type="text" class="form-control" id="date_to" name="date_to" placeholder="Enter Academic End Date">
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
                    <div class="col-md-7 pull-right v-divider">
                        <div class="panel panel-default">
                            <div class="panel-heading">Academic Semester Information</div>
                            <div class="panel-body" id="show-info">
                            
                            </div>
                        </div>
                    </div>
                </div>
         	</section>
      	</div>
 	</div>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		showInfo();
	});
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
	$(function(){
		$("#frm-create-semester").on('submit', function(e){
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
							$('#date_from').val(toDbaseDate($('#date_from').val()));
							$('#date_to').val(toDbaseDate($('#date_to').val()));
							
							var str = $('#semester').val();
							str = str.replace('\\', '-');
							str = str.replace('/', '-');
							
							$('#semester').val(str);
							
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  	url: "{{ route('updateSemester')}}",
							  	data: new FormData($("#frm-create-semester")[0]),
							  	async:false,
							  	type:'post',
							  	processData: false,
							  	contentType: false,
							  	success:function(data){
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-semester')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#semester_id').val('');
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
	
	function showInfo(){
		
		$.get("{{ route('infoSemester')}}", function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var semester_id = $('#semester_id').val();
			if( semester_id == null || semester_id == ""){
				alert('Use the Core Events in the Event Form to add a new Term...This Form is for edit only');	
				return false;
			}else{
				
				var reason = "";
				reason += validate_empty(document.getElementById("semester"));
				reason += validate_select(document.getElementById("academic_id"));
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
				
				//semester year can only be for a period of not less than nine months
				start_date = toJSDate(start_date);
				end_date = toJSDate(end_date);
				
				var months = Noofmonths(start_date, end_date);
				if (months < 1 || months > 6 ){
					reason += 'Semester period can not be less than 1month and can not be more than 6months';
				}
				if (reason != "") {
					alert("Some fields need correction:\n\n" + reason);
					return false;
				}
				return true;
			}
		}catch(err){ alert(err.message); }
	}
	$(document).on('click', '.edit-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var semester_id = $(this).data('id');
		$('#semester_id').val(semester_id);
		
		$.get("{{ route('editSemester') }}", {semester_id: semester_id}, function(data){
			$.each(data, function(i,l){
				
				$('#frm-create-semester #semester').val(l.semester); //this uses the progam_id from level file
				$('#frm-create-semester #academic_id').val(l.academic_id); //this uses the progam_id from level file
				$('#frm-create-semester #date_from').val(l.date_from);
				$('#frm-create-semester #date_to').val(l.date_to);
			});
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		//alert('semester');
		$('#pleaseWaitDialog').modal();
		try{
			$.get("{{ route('excelSemester') }}", function(data){
				$('#pleaseWaitDialog').modal('hide');
				var path = "{{url('/')}}";
				path = path + '/reports/excel/';
				
				path = path + 'semester-csvfile.xlsx';
				openInNewTab(path);
			});
		}catch(err){ alert(err.message); }
		
	});
	$(document).on('click', '#export-to-pdf', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('pdfSemester') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	});
	$(document).on('click', '.del-this', function(e){
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
					$.post("{{ route('delSemester') }}", {semester_id: semester_id, operator: operator})
						.done(function(data){ 
						if(Object.keys(data).length > 0){
							showInfo();
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