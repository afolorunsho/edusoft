@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Master</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getSyllabus')}}">Syllabus</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#syllabus_input" data-toggle="tab" class="nav-tab-class">Syllabus</a></li>
                <li class="nav"><a href="#view-syllabus" data-toggle="tab" class="nav-tab-class">View Syllabus</a></li>
                <li class="nav"><a href="#import" data-toggle="tab" class="nav-tab-class">Import Syllabus</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="syllabus_input">
                	<div class="panel panel-body">
                        <form method="POST" id="frm-create-syllabus" class="form-horizontal" return false>
                            <input type="hidden" name="syllabus_id" id="syllabus_id"> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="row">
                                <div class="col-md-4">
                                     <div class="form-group">
                                        <label for="class_id"  class="control-label col-md-4">Class</label>
                                        <div class="controls col-md-8">
                                            <!----when a class is picked then the drop down in the section table
                                                should be populated with the sections appropriate to that class---->
                                            <select class="form-control" name="class_id" id="class_id" onchange="classChange()">
                                                <option value="">Please Select...</option>
                                                @foreach($class as $y)
                                                    <option value="{{ $y->class_id}}">
                                                    {{ $y->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
										<!--here display ONLY the subjects defined for that class--->
                                        <label for="subject_id" class="control-label col-md-4">Subject</label>
                                        <div class="controls col-md-8">
                                            <select class="form-control" name="subject_id" id="subject_id">
                                                <option value="">Please Select...</option>
                                                @foreach($class_subjects as $c)
                                                    <option value="{{ $c->subject_id }}">
                                                    {{ $c->subject }}</option>
                                                @endforeach
                                            </select>
                                     	</div>
                                    </div> 
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="syllabus">Update Syllabus</label>
                                        <textarea  class="form-control" id="syllabus" name="syllabus" 
                                        	placeholder="Enter Subject Outline" rows="10"></textarea>
                                    </div>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-danger" id="btn-delete">
                                            <i class="fa fa-trash-o fa-fw fa-fw"></i>Delete</button>
                                           
                                        <button type="submit" class="btn btn-primary" id="btn-save">
                                            <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <!----as it is, this form attachs a subject to a class and also to sections---->
                                        <table class="table table-hover table-striped table-condensed" id="table-section">
                                            <thead>
                                                <tr>
                                                    <th width="30%">Check</th>
                                                    <!--<th width="25%">ID</th>-->
                                                    <th width="70%">Class Section</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>
                        </form>
               		</div>
              	</div>
               	<div class="tab-pane fade in" id="view-syllabus">
                    <div class="panel panel-body" id="show-info" style="margin-top:5px;">
               		</div>
             	</div>
                <div class="tab-pane fade in" id="import">
                    <div class="panel panel-body" style="margin-top:5px;">
                    	<form method="post" class="form-horizontal" id="frm-import-syllabus" name="frm-import-syllabus"
                           role="form" enctype="multipart/form-data" return false>
                           
                           <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                           <input type="hidden" id="token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <input type="file" id="table_file" name="table_file">
                                </div>
                                <div class="col-md-2">
                                    <button type="button"  class="btn btn-default" id="btn_import_syllabus">
                                        <i class="fa fa-upload fa-fw"></i>Upload</button>
                                </div>
                                <div class="col-md-2 pull-right">
                                    <button id="btn_update_syllabus"
                                        type="button"  class="btn btn-primary" >
                                            <i class="fa fa-save fa-fw"></i>Update Database</button>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <!--<hr />-->
                                        <div id="table_div_syllabus">
                                            <!--populate dynamically-->
                                            <table class="table table-hover table-striped table-condensed" id="import_syllabus"
                                                style="font-size:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="15%">Class</th>
                                                        <th width="15%">Subject</th>
                                                        <th width="50%">Syllabus</th>
                                                        <th width="20%">Section</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    <!--<hr />-->
                                </div>
                            </div>
                        </form>
               		</div>
             	</div>
         	</div>
        </div>
        <div id="dialog" style="display: none"></div>
    </div>
    <form action="" method="POST" name= "frm-data-syllabus" id="frm-data-syllabus" enctype="multipart/form-data" return false>
        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
        <input type="hidden" name="reviewer" id="reviewer" value="">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="sch_class" id="sch_class" value="">
        <input type="hidden" name="subject" id="subject" value="">
        <input type="hidden" name="syllabus" id="syllabus" value="">
        <input type="hidden" name="section" id="section" value="">
     </form>
@endsection
@section('scripts')
<script type="text/javascript">
	
	$(document).ready( function() {
		showInfo();
	});
	$('#subject_id').on('change', function(){
		//this should populate the subject area with the subjects defined for that sections ONLY
		
		var subject_id = $('#subject_id').val();
		var class_id = $('#class_id').val();
		//generate subjects upon class change: ONLY those subjects defined for that class in the syllabus table
		$.get("{{ route('getClassSyllabus')}}", {subject_id: subject_id, class_id: class_id}, function(data){
			$('#frm-create-syllabus #syllabus').val(data);
		});
	})
	function classChange(){
		//empty table section whenever there is a change in the class
		$('#table-section tbody').empty();
		var class_id = $('#class_id').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			//generate a table for the selected class for all the sections there in and enable 
			//check button to indicate the relevant classes
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<tr>' +
						'<td><div><input type="checkbox" class="checkbox vert-align" name="sections[]" value="' + l.class_div_id +'" ></div></td>' +
						'<td><input style="border:none;background-color:transparent;" type="text" class="form-control sections" readonly="readonly" value="'+ l.class_div + '"></td>'+
						'</tr>';
					$('#frm-create-syllabus #table-section> tbody:last-child').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	}
	$(function(){
		$("#frm-create-syllabus").on('submit', function(e){
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
							  url: "{{ route('updateSyllabus')}}",
						  	  data: new FormData($("#frm-create-syllabus")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-syllabus')[0].reset();
										$('#table-section tbody').empty();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#syllabus_id').val('');
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
		
		$('#frm-create-syllabus')[0].reset();
		$.get("{{ route('infoSyllabus')}}", function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			MergeCommonRows($('#table-class-info'));
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("syllabus"));
			reason += validate_select(document.getElementById("subject_id"));
			reason += validate_select(document.getElementById("class_id"));
			//var rows = document.getElementById("table-section").getElementsByTagName("tbody")[0].rows.length;
			var rows = document.getElementById("table-section").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
			if( rows > 0) {
				//check if at least one row was ticked
				var count = 0;
				$('#table-section').find('input[type="checkbox"]:checked').each(function () {
				   	count = count + 1;
				});
				if(count < 1) reason += "Invalid Input: no class section was ticked \n";
			}else{
				reason += "Invalid Input: class section is empty \n";
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
		var syllabus_id = $(this).data('id');
		$('#syllabus_id').val(syllabus_id);
		$('#table-section tbody').empty();
		//show the data tab
		
		$.get("{{ route('editSyllabus') }}", {syllabus_id: syllabus_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					$('.nav-tabs a[href="#syllabus"]').tab('show');
					$('#frm-create-syllabus #syllabus').val(l.syllabus);
					$('#frm-create-syllabus #class_id').val(l.class);
					$('#frm-create-syllabus #subject_id').val(l.subject);
					//empty the table
					var row_data = '<tr>' +
						'<td><div><input type="checkbox" class="checkbox vert-align" name="sections[]" value="' + 
						l.section_id +'" ></div></td>' +
						'<td><input style="border:none;background-color:transparent;" type="text" class="form-control sections" readonly="readonly" value="'+ l.class_div + '"></td>'+
						'</tr>';
					$('#frm-create-syllabus #table-section> tbody:last-child').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('excelSyllabus') }}", function(data){
				
				$('#pleaseWaitDialog').modal('hide');
				var path = "{{url('/')}}";
				path = path + '/reports/excel/';
				path = path + 'syllabus-csvfile.xlsx';
				openInNewTab(path);
				
				//syllabus-csvfile.xlsx
				/*var a = document.createElement("a");
				a.href = path + data.file;
				a.href = path;
				a.download = data.name;
				a.target = "_blank";
				a.title = data.file;
				document.body.appendChild(a);
				a.click();
				a.remove();*/
		});
	});
	$('#btn-delete').click(function(){
		var syllabus_id = $('#syllabus_id').val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delSyllabus') }}", {syllabus_id: syllabus_id, operator: operator})
					.done(function(data){ 
						//update the log at the server end
						if(Object.keys(data).length > 0){
							alert("Successful");
							$('#frm-create-syllabus')[0].reset();
							$('#table-section tbody').empty();
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
	$('#btn_import_syllabus').on('click', function(e){
		e.preventDefault();
		
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to upload this file?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				if(result) {
					$.ajax({
						url: "{{ route('syllabusImport')}}",
						data: new FormData($("#frm-import-syllabus")[0]),
						async:false,
						type:'post',
						processData: false,
						contentType: false,
						success:function(data){
							$('#table_div_syllabus').children().remove();
							$('#table_div_syllabus').append(data);
						},
						error: function(jqXHR, exception) { 
							return_error(jqXHR, exception);
						}
					});
				}
			}
		});
	});
	
	$('#btn_update_syllabus').on('click', function(e){
		e.preventDefault();
		try{
			var table = $('#frm-import-syllabus #import_syllabus tbody');
			//alert(semester);
			$("#btn_update_syllabus").attr("disabled", true);
			$('#pleaseWaitDialog').modal();
			
			table.find('tr').each(function(i, el){
				var is_valid = true;
				var tds = $(this).find('td');
				var sch_class = tds.eq(0).text();
				var subject = tds.eq(1).text();
				var syllabus = tds.eq(2).text();
				var section = tds.eq(3).text();
				if( sch_class == "" || subject == "" || syllabus == "" || section == ""){
					is_valid = false;
				}
				
				if (is_valid){
					$('#frm-data-syllabus #sch_class').val(sch_class);
					$('#frm-data-syllabus #subject').val(subject);
					$('#frm-data-syllabus #syllabus').val(syllabus);
					$('#frm-data-syllabus #section').val(section);
					var $ele = $(this).closest('tr');
					$.ajax({
						url: "{{ route('updateSyllabusImport')}}",
						data: new FormData($("#frm-data-syllabus")[0]),
						async:false,
						type:'post',
						processData: false,
						contentType: false,
						success:function(data){
							
							if(Object.keys(data).length > 0){
								//remove the row
								//$(this).remove();
								$ele.fadeOut().remove();
								//$(this).parent().parent().remove();
								//$ele.css('background-color', '#ccc')
							}else{
								$ele.css('background-color', '#ccc');	
							}
						},
						error: function(jqXHR, exception) { 
							return false;
							return_error(jqXHR, exception);
						}
					});
				}
			});
			$("#btn_update_syllabus").attr("disabled", false);
			$('#pleaseWaitDialog').modal('hide');
			
		}catch(err){
			return false; 
			alert(err.message); 
		}
	});
	
</script>
@endsection