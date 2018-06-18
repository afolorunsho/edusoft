@extends('layouts.master')
@section('content')
	<div class="row">
        <div class="col-lg-12">
            {{---<h3 class="page-header"><i class="fa fa-file-text-o"></i>Settings</h3>----}}
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Settings</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getAcademic')}}">Academic Year</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage Academic Year
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                	<form action="" method="POST" class="form-horizontal" id="frm-create-academics" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="academic_id" id="academic_id" value=""> 
                        <input type="hidden" name="active" id="active" value="1"> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="col-md-4 form-line">
                            <div class="form-group">
                                <label for="academic">Academic Year</label>
                                <input type="text" class="form-control" id="academic" name="academic" placeholder="Enter Academic Year">
                            </div>
                            <div class="form-group">
                                <label for="date_from">Academic Year Start Date</label>
                                <input type="text" class="form-control" id="date_from" name="date_from" placeholder="Enter Academic Start Date">
                            </div>	
                            <div class="form-group">
                                <label for="date_to ">Academic Year End Date</label>
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
                            <div class="panel-heading">Academic Information</div>
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
		$("#frm-create-academics").on('submit', function(e){
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
							
							var str = $('#academic').val();
							str = str.replace('\\', '-');
							str = str.replace('/', '-');
							
							$('#academic').val(str);
							
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: "{{ route('updateAcademic')}}",
							  data: new FormData($("#frm-create-academics")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-academics')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful\n Ensure that the next academic starts from where the last ended\n');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#academic_id').val('');
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
		
		$.get("{{ route('infoAcademic')}}", function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("academic"));
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
			
			//academic year can only be for a period of not less than nine months
			start_date = toJSDate(start_date);
			end_date = toJSDate(end_date);
			
			var months = Noofmonths(start_date, end_date);
			if (months < 9 || months > 12 ){
				reason += 'Academic period can not be less than 9months and can not be more than 12months';
			}
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
		var academic_id = $(this).data('id');
		$('#academic_id').val(academic_id);
		$.get("{{ route('editAcademic') }}", {academic_id: academic_id}, function(data){
			$.each(data, function(i,l){
				
				$('#frm-create-academics #academic').val(l.academic); //this uses the progam_id from level file
				$('#frm-create-academics #date_from').val(l.date_from);
				$('#frm-create-academics #date_to').val(l.date_to);
			});
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		try{
			$.get("{{ route('excelAcademic') }}", function(data){
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
		$.get("{{ route('pdfAcademic') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	});
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var academic_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delAcademic') }}", {academic_id: academic_id, operator: operator})
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