@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            {{---<h3 class="page-header"><i class="fa fa-file-text-o"></i>Settings</h3>----}}
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Exam</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getExamName')}}">Name</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage Exams
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                	<form action="" method="POST" class="form-horizontal" id="frm-create-exam" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="exam_id" id="exam_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="col-md-4 form-line">
                            <div class="form-group">
                                <label for="subject">Short Name</label>
                                <input type="text" class="form-control" id="exam_name" name="exam_name" placeholder="Enter Exam Name">
                            </div>
                            <div class="form-group">
                                <label for="short_name">Exam Name</label>
                                <input type="text" class="form-control" id="short_name" name="short_name" placeholder="Enter Short Name">
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
                            <div class="panel-heading">Exam Information</div>
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
		$("#frm-create-exam").on('submit', function(e){
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
							  url: "{{ route('updateExam')}}", 
							  data: new FormData($("#frm-create-exam")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									
									if(Object.keys(data).length > 0){
										alert('Update successful');
										showInfo();
										$('#frm-create-exam')[0].reset();
										
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#exam_id').val('');
								},
								error: OnError
							});
						}else{
							
						}
					}
				}
			});
		})
		
	});
	
	function showInfo(){
		
		$.get("{{ route('infoExam')}}", function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("exam_name"));
			reason += validate_empty(document.getElementById("short_name"));
			
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
		var exam_id = $(this).data('id');
		$('#exam_id').val(exam_id);
		$.get("{{ route('editExam') }}", {exam_id: exam_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					
					$('#frm-create-exam #exam_name').val(l.exam_name); //this uses the progam_id from level file
					$('#frm-create-exam #short_name').val(l.short_name);
					$('#frm-create-exam #exam_id').val(l.exam_id);
				});
			}else{
				alert('No record to display...');	
			}
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('excelExam') }}", function(data){
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
		$.get("{{ route('pdfExam') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	});
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var exam_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delExam') }}", {exam_id: exam_id, operator: operator}).done(function(data){ 
						//update the log at the server end
						if(Object.keys(data).length > 0){
							alert('Update successful');
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