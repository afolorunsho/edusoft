@extends('layouts.master')
@include('student.popup_assessment')
@section('content')
	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Student</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getAssessSetup')}}">Assessment</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#input" data-toggle="tab" class="nav-tab-class">Student Assessment</a></li>
            	<li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Assessment</a></li>
            </ul>
           <div class="tab-content">
            	<div class="tab-pane fade in active" id="input">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-assessment" enctype="multipart/form-data" return false>
                            <input type="hidden" name="assessment_id" id="assessment_id"> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="row">
                                <div class="col-md-5">
                              		<h4>Add Assessment Type</h4>
                                    <div class="form-group">
                                        <label for="class_id" class="col-md-4">Assessment Type</label>
                                        <div class="input-group">
                                            <select class="form-control" name="assessment_id" id="assessment_id">
                                                <option value="">Please Select...</option>
                                                @foreach($assessments as $y)
                                                    <option value="{{ $y->assessment_id}}">
                                                    {{ $y->assessment}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-addon">
                                                <span class="fa fa-plus" id="add-more-assessment"></span>
                                            </div>
                                        </div>
                              		</div>
                                </div>
                                    
                                <div class="col-md-7 pull-right v-divider">
                                    <div>
										<div class="pull-left col-md-4">
                                            <label for="class_id" class="col-md-8">Class</label>
											<select class="form-control" name="class_id" id="class_id" onchange="classChange()">
												<option value="">Please Select...</option>
                                                @foreach($class as $y)
                                                    <option value="{{ $y->class_id}}">
                                                    {{ $y->class_name }}</option>
                                                @endforeach
											</select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-default" id="add_para">
                                                <i class="fa fa-plus fa-fw fa-fw"></i>Add Parameter</button>
                                        </div>
                                        <div class="pull-right col-md-4">
                                                <button type="submit" class="btn btn-primary" id="btn-save">
                                                    <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                        </div>
                                        <table class="table table-hover table-striped table-condensed" id="table-input">
                                            <thead>
                                                <tr>
                                                    <th width="50%">Assessment</th>
                                                    <th width="50%">Parameter</th>
                                                    <th width="25px">Action</th>
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
                <div class="tab-pane fade in" id="view">
                    <div class="panel panel-body" id="table-view">
                	</div>
                </div>
        	</div>    
        </div>
        <div id="dialog" style="display: none"></div>
    </div>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		showInfo();
	});
	function classChange(){
		var class_id = $('#class_id').val();
		$.get("{{ route('editClassAssessment') }}", {class_id: class_id}
		,function(data){
			if (data.length > 0) {
				$('#table-input tbody').empty();
				$.each(data, function(i,l){
					////////////////////////////////
					var all_divs = '<select class="form-control assess_id" name="assess_id[]"> style="width: 100%"';
					all_divs = all_divs + '<option value="'+ l.assessment_id +'">' + l.assessment + '</option>';
					all_divs = all_divs + '</select>';
					
					var row_data = '<tr>' +
						'<td>' + all_divs +  '</td>'+
						'<td align="right"><input type="text" class="form-control parameter" name="parameter[]" size="20" value="' + l.parameter + '"></td>'+
						'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
					'</tr>';
					$('#table-input tbody').append(row_data);
					
				});
			}else{
				alert('No record to display...');	
			}
		});
	}
	function showInfo(){
		$.get("{{ route('getAssessmentList')}}", function(data){
			$('#table-view').empty().append(data);
			$('#table-report').DataTable();
		});
	}
	$(function(){
		$('#add-more-assessment').on('click', function(e){
			e.preventDefault();
			$('#div-show').modal('show');
		})
	});
	$(document).on('click', '#assessment-submit', function(e){
		e.preventDefault();
		var assessment = $('#assessment-type').val();
		var operator =  $('#operator').val();
		
		if( assessment == null || assessment == ""){
			alert('Empty field not allowed');
		}else{
			
			$.post("{{ route('updateAssessHead') }}", {assessment: assessment, operator: operator},function(data){
				
				if(Object.keys(data).length > 0){
					alert('Update Successful');
					try{
						$('#assessment_id').empty();
						$('#assessment_id').append('<option value="">Please Select...</option>');
						
						$.each(data, function(i,l){
							var row_data = '<option value="'+ l.assessment_id +'">' + l.assessment + '</option>';
							$('#assessment_id').append(row_data);
						});
					}catch(err){ alert(err.message); }
				}else{
					alert('Update not successful');
				}
			});
		}
	});
	$('#add_para').on('click', function(e){
		e.preventDefault();
		add_row();
	});
	function add_row(){
		var all_divs = '<select class="form-control assess_id" name="assess_id[]">';
		var class_id = $('#class_id').val();
		if( class_id == null || class_id == ""){
			alert('You need to select a class');
		}else{
			$.get("{{ route('getAssessmentHead')}}", function(data){
				
				//all_divs = all_divs + '<option value="">Select</option>';
				
				if (data.length > 0) {
					$.each(data, function(i,l){
						all_divs = all_divs + '<option value="'+ l.assessment_id +'">' + l.assessment + '</option>';
					});
				}else{
					alert('No record to display...');	
				}
				all_divs = all_divs + '</select>';
				
				var row_data = '<tr>' +
					'<td>' + all_divs +  '</td>'+
					'<td align="right"><input type="text" class="form-control parameter" name="parameter[]" size="20"></td>'+
					'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
				'</tr>';
				$('#table-input tbody').append(row_data);
	
			});
		}
	}
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_select(document.getElementById("class_id"));
			//the length of the table should be more than one
			var rows = document.getElementById('table-input').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
			if (rows > 0){}else{
				reason += "The assessment table is not populated \n";
			}
			//iterate through the table and check that all inputs are validat
			var old_score_to = -1;
			var score_to = 0;
			$('#table-input tbody tr').each(function(){
				
				var assessment_id = $(this).closest('tr').find('select.assess_id').val();
				var parameter = $(this).closest('tr').find('input.parameter').val();
				
				if( assessment_id == "" || assessment_id == null ) reason += "No assessment type was selected \n";
				if( parameter == "" || parameter == null ) reason += "The assessment parameter was not entered \n";
			});
			
			return true;
		}catch(err){ alert(err.message); }
	}
	$(function(){
		$("#frm-create-assessment").on('submit', function(e){
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
							document.getElementById("btn-save").innerHTML = 
								'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
							$.ajax({
							  url: "{{ route('updateAssessPara')}}",
							  data: new FormData($("#frm-create-assessment")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
								  
									/*if(Object.keys(data).length > 0){
										$('#frm-create-assessment')[0].reset();
										$('#table-input tbody').empty();
										showInfo();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}*/
									alert('Update successful');
									document.getElementById("btn-save").innerHTML = 
										'<i class="fa fa-save fa-fw fa-fw"></i>Save';
								},
								error: OnError
							});
						}
					}
				}
			});
		})
		
	});
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('excelAssessment') }}", function(data){
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
	
	$("#table-input tbody").on("click", ".del-this", function(e){
		e.preventDefault();
		var this_ = $(this);
		del_row(this_);
	});
	function del_row(this_){
		this_.fadeOut('slow',function(){
			this_.closest('tr').remove();
		});
	}
	
</script>
@endsection