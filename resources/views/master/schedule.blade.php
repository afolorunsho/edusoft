@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            {{---<h3 class="page-header"><i class="fa fa-file-text-o"></i>Master</h3>----}}
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Master</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getSchedule')}}">Timetable</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage School Timetable
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                	<form action="" method="POST" class="form-horizontal" id="frm-create-schedule" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="timetable_id" id="timetable_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="col-md-4 form-line">
                            <div class="form-group">
                                <label for="period_name">Period name</label>
                                <input type="text" class="form-control" id="period_name" name="period_name" placeholder="Enter Period Name">
                            </div>
                             <div class="form-group">
                                <label for="school_id">School</label>
                                <div class="input-group">
                                	<select class="form-control" name="school_id" id="school_id" class="col-md-8" style="width: 250px;">
                                    	<option value="">Please Select...</option>
                                        @foreach($records as $y)
                                            <option value="{{ $y->school_id}}">
                                            {{ $y->school_name }}</option>
                                        @endforeach
                                	</select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="time_from">Time From</label>
                                <input type="time" class="form-control time" id="time_from" name="time_from" 
                                	placeholder="Enter Start Time(HH:MM)">
                            </div>	
                            <div class="form-group">
                                <label for="time_to ">Time To</label>
                                <input type="time" class="form-control time" id="time_to" name="time_to" 
                                	placeholder="Enter End Time(HH:MM)">
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
                            <div class="panel-heading">School Timetable Information</div>
                            <div class="panel-body" id="show-info">
                            
                            </div>
                        </div>
                    </div>
                </div>
         	</section>
      	</div>
 	</div>
@endsection
@section('scripts')
<script type="text/javascript">
	
	$(document).ready( function() {
		showInfo();
	});
	$(function(){
		$("#frm-create-schedule").on('submit', function(e){
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
							  url: "{{ route('updateSchedule')}}",
							  data: new FormData($("#frm-create-schedule")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-schedule')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#timetable_id').val('');
								},
								error: OnError
							});
						}
					}
				}
			});
		})
		
	});
	
	function showInfo(){
		
		$.get("{{ route('infoSchedule')}}", function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("period_name"));
			reason += validate_select(document.getElementById("school_id"));
			reason += validTimeSeq(document.getElementById("time_from"), document.getElementById("time_to"));
			
			if(validateHhMm(document.getElementById("time_from")) == false) reason += "Wrong Time From Format \n";
			if(validateHhMm(document.getElementById("time_to")) == false) reason += "Wrong Time To Format \n";
			
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
		var timetable_id = $(this).data('id');
		$('#timetable_id').val(timetable_id);
		$.get("{{ route('editSchedule') }}", {timetable_id: timetable_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					
					$('#frm-create-schedule #period_name').val(l.period_name); //this uses the progam_id from level file
					$('#frm-create-schedule #school_id').val(l.school_id);
					$('#frm-create-schedule #time_from').val(l.time_from);
					$('#frm-create-schedule #time_to').val(l.time_to);
					$('#frm-create-schedule #timetable_id').val(l.timetable_id);
				});
			}else{
				alert('No record to display...');	
			}
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('excelSchedule') }}", function(data){
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
	$(document).on('click', '#export-to-pdf', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('pdfSchedule') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	});
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var timetable_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delSchedule') }}", {timetable_id: timetable_id, operator: operator})
						.done(function(data){ 
						if(Object.keys(data).length > 0){
							alert("Successful");
							showInfo();
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