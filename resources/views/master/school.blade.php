@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            {{---<h3 class="page-header"><i class="fa fa-file-text-o"></i>Master</h3>----}}
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Master</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getSchool')}}">School</a></li>
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
                	<form action="" method="POST" class="form-horizontal" id="frm-create-school" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="school_id" id="school_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="col-md-4 form-line">
                            <div class="form-group">
                                <label for="calendar">School</label>
                                <input type="text" class="form-control" id="school_name" name="school_name" placeholder="Enter School Name(e.g Nursery, Primary etc)">
                            </div>
                            <div class="form-group">
                                <label for="address">Location</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Location(e.g Block)">
                            </div>
                            <div class="form-group">
                                <label for="sequence">Order</label>
                                <input type="number" class="form-control" id="sequence" name="sequence" 
                                	min="1" max="10" step ="1" placeholder="Enter the School Order">
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
                            <div class="panel-heading">School Type Information</div>
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
		$("#frm-create-school").on('submit', function(e){
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
							  url: "{{ route('updateSchool')}}",
							  data: new FormData($("#frm-create-school")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-school')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#school_id').val('');
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
		
		$.get("{{ route('infoSchool')}}", function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("school_name"));
			reason += validate_empty(document.getElementById("address"));
			reason += validate_empty(document.getElementById("sequence"));
			
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
		var school_id = $(this).data('id');
		$('#school_id').val(school_id);
		$.get("{{ route('editSchool') }}", {school_id: school_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					
					$('#frm-create-school #school_name').val(l.school_name); //this uses the progam_id from level file
					$('#frm-create-school #address').val(l.address);
					$('#frm-create-school #sequence').val(l.sequence);
				});
			}else{
				alert('No record to display...');	
			}
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		try{
			$.get("{{ route('excelSchool') }}", function(data){
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
		}catch(err){ alert(err.message); }
	});
	$(document).on('click', '#export-to-pdf', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('pdfSchool') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	});
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var school_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delSchool') }}", {school_id: school_id, operator: operator})
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