@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            {{---<h3 class="page-header"><i class="fa fa-file-text-o"></i>Settings</h3>----}}
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Master</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getSubject')}}">Subjects</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage Subjects
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                	<form action="" method="POST" class="form-horizontal" id="frm-create-subject" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="subject_id" id="subject_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="col-md-4 form-line">
                            <div class="form-group">
                                <label for="subject">Subject Name</label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter Subject Name">
                            </div>
                            <div class="form-group">
                                <label for="short_name">Short Name</label>
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
                            <div class="panel-heading">Subject Information</div>
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
		$("#frm-create-subject").on('submit', function(e){
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
							  url: "{{ route('updateSubject')}}",
							  data: new FormData($("#frm-create-subject")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-subject')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#subject_id').val('');
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
		
		$.get("{{ route('infoSubject')}}", function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("subject"));
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
		var subject_id = $(this).data('id');
		$('#subject_id').val(subject_id);
		$.get("{{ route('editSubject') }}", {subject_id: subject_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					
					$('#frm-create-subject #subject').val(l.subject); //this uses the progam_id from level file
					$('#frm-create-subject #short_name').val(l.short_name);
					$('#frm-create-subject #subject_id').val(l.subject_id);
				});
			}else{
				alert('No record to display...');	
			}
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('excelSubject') }}", function(data){
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
		$.get("{{ route('pdfSubject') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	});
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var subject_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delSubject') }}", {subject_id: subject_id, operator: operator})
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