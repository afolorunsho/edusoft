@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Master</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getClass')}}">Class</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage Class
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                
                	<form method="POST" id="frm-create-class" return false>
                    	<input type="hidden" name="class_id" id="class_id"> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="row">
                     		<div class="col-md-6">
                            	<div class="row">
                                    <div class="col-md-6">
                                        <label for="class_name" class="control-label">Class</label>
                                        <div class="form-group">
                                            <input type="" name="class_name" class="form-control" id="class_name" placeholder="Enter Class Code">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="description" class="control-label">Description</label>
                                        <div class="form-group">
                                            <input type="" name="description" class="form-control" id="description" placeholder="Enter Description">
                                        </div>
                                    </div>
                               	</div>
                                <div class="row">
                                    <div class="col-md-6">
                                    	<label for="capacity" class="control-label">Capacity</label>
                                        <div class="form-group">
                                            <input class="form-control input-md" id="capacity" name="capacity" 
                                            	type="number" min="1" max="60" step="1" value ="10"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5 pull-right">
                                    	<label for="sequence" class="control-label">Order</label>
                                        <div class="form-group">
                                            <input class="form-control input-md" id="sequence" name="sequence" 
                                            	type="number" min="1"  max="20" step="1" value ="1"/>
                                        </div>
                                    </div>
                             	</div>
                                <div class="row">
                                    <div class="col-md-7">
                                    	<label for="school_id" class="control-label">School</label>
                                        <div class="form-group">
                                             <select class="form-control" name="school_id" id="school_id" 
                                             	class="col-md-8" style="width: 250px;">
                                                <option value="">Please Select...</option>
                                                @foreach($records as $y)
                                                    <option value="{{ $y->school_id}}">
                                                    {{ $y->school_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                              	</div>
                                <div class="row">
                                    <div class="col-md-6">
                                    	<label for="div_type"class="control-label">Section Type</label>
                                        <div class="form-group">
                                            <select class="form-control" name="div_type" id="div_type" style="width: 200px;">
                                                <option value="">Please Select...</option>
                                                <option value="Alphabetic">Alphabetic</option>
                                                <option value="Numeric">Numeric</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 pull-right">
                                    	<label for="div_no"class="control-label">Section No</label>
                                        <div class="form-group">
                                            <input class="form-control input-md" id="div_no" name="div_no" 
                                            	type="number" min="1" max="10" step="1" value ="1"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                      <div class="pull-right">
                                        <button type="button" class="btn btn-primary" id="btn-generate">
                                            <i class="fa-fw"></i>Generate Sections</button>
                                      </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8" id="division_section">
                                    	<table class="table table-hover table-striped table-condensed" id="class-div">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Divisions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    		</tbody>
                                     	</table>
                                    </div>
                                </div>
                                <div class="form-group">
                             		<div class="col-md-12">
                                 		<div class="pull-right">
                                        	<button type="submit" class="btn btn-primary" id="btn-save">
                                            	<i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                      	</div>
                                    </div>
                                </div>
                   			</div>
                            <div class="col-md-6 pull-right v-divider">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Class Information</div>
                                    <div class="panel-body" id="show-info">
                                    	<div class="pull-right">
                                            <button type="button" class="btn btn-primary" id="btn-sequence">
                                                <i class="fa-fw"></i>Manage Sequence</button>
                                     	</div>
                                    	<table class="table table-hover table-striped table-condensed" id="table-class-seq">
                                            <thead>
                                                <tr>
                                                    <th>Class</th>
                                                    <th>Sequence</th>
                                                    <th>Capacity</th>
                                                    <th colspan="2" style="text-align:center" width="50px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    		</tbody>
                                     	</table>
                                    </div>
                                </div>
                            </div>
                    	</div>
               		</form>
               	</div>
            </section>
        </div>
        <div id="dialog" style="display: none"></div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
	
	var class_generated = false; //this is to indicate whether class section has been generated
	
	$(document).ready( function() {
		showInfo();
	});
	$(function(){
		$("#frm-create-class").on('submit', function(e){
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
							  url: "{{ route('updateClass')}}",
							  data: new FormData($("#frm-create-class")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-class')[0].reset();
										$('#class-div tbody').empty();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#class_id').val('');
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
		
		$.get("{{ route('infoClass')}}", function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("class_name"));
			reason += validate_empty(document.getElementById("description"));
			reason += validate_empty(document.getElementById("sequence"));
			reason += validate_empty(document.getElementById("div_type"));
			reason += validate_empty(document.getElementById("div_no"));
			reason += validate_select(document.getElementById("school_id"));
			if(class_generated == false) reason += "Class Section has not been generated \n";
			
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
		var class_id = $(this).data('id');
		$('#class_id').val(class_id);
		$.get("{{ route('editClass') }}", {class_id: class_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					
					$('#frm-create-class #class_name').val(l.class_name); //this uses the progam_id from level file
					$('#frm-create-class #description').val(l.description);
					$('#frm-create-class #capacity').val(l.capacity);
					$('#frm-create-class #sequence').val(l.order);
					$('#frm-create-class #div_no').val(l.div_no);
					$('#frm-create-class #div_type').val(l.div_type);
					$('#frm-create-class #school_id').val(l.school);
					
					//extract the sections for this class id
					$.get("{{ route('editClassDiv') }}", {class_id: class_id}, function(data){
						var no = 1;
						$('#class-div tbody').empty();
						$.each(data, function(i,l){
							
							add_row(no, l.class_div);
							no = no + 1;
							
						});
					});
				});
			}else{
				alert('No record to display...');	
			}
		});
	});
	
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('excelClass') }}", function(data){
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
		$.get("{{ route('pdfClass') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	});
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var class_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delClass') }}", {class_id: class_id, operator: operator})
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
	//////////////////////////////////////////////////////ADD SECTIONS
	$(function(){
		$('#btn-generate').on('click', function(e){
			e.preventDefault();
			
			$('#class-div tbody').empty();
			//this should generate the divisions/sections for the class
			var class_name = $('#class_name').val();
			var div_type = $('#div_type').val();
			var div_no = $('#div_no').val();
			//iterate this number of times
			var div_name = "";
			var increment = "";
			if( div_type == "Alphabetic") increment = "A";
			if( div_type == "Numeric") increment = 1;
			for(count = 0; count < div_no; count++){
				if( class_name !== ""){
					if( div_type == "Alphabetic"){
						if(count > 0) {
							increment = nextChar(increment);
						}
						div_name = class_name + "-" + increment;
						add_row(count+1, div_name);
					}else{
						if(count > 0) {
							increment = increment + 1;
						}
						div_name = class_name + "-" + increment;
						add_row(count+1, div_name);
					}
				}else{
					alert('Input class name');	
				}
			}
		})
	});
	function add_row(no, div_name){
		
		var row_data = '<tr>' +
			'<td>'+ no +'</td>'+
			'<td><input style="border:none;background-color:transparent;" type="text" class="sections" id="sections[]" name="sections[]" value="'+ 
				div_name +'"></td>'+
			'</tr>';
		$('#frm-create-class #class-div> tbody:last-child').append(row_data);
		
		class_generated = true;
	}
	function nextChar(c) {
		return String.fromCharCode(c.charCodeAt(0) + 1);
	}
	
</script>
@endsection