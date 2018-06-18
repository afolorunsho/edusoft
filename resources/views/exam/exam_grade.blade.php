@extends('layouts.master')
@include('exam.popup_grade')
@section('content')
	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Exam</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getExamGrade')}}">Score Grade</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#input" data-toggle="tab" class="nav-tab-class">Score Grade</a></li>
            	<li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Score Grade</a></li>
            </ul>
           <div class="tab-content">
            	<div class="tab-pane fade in active" id="input">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-exam-grade" enctype="multipart/form-data" return false>
                            <input type="hidden" name="exam_grade_id" id="exam_grade_id"> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="row">
                                <div class="col-md-5">
                              		<h4>Add Grade Type</h4>
                                    <div class="form-group">
                                        <label for="class_id" class="col-md-4">Score Grade</label>
                                        <div class="input-group">
                                            <select class="form-control" name="score_grade_id" id="score_grade_id">
                                                <option value="">Please Select...</option>
                                                @foreach($score_grade as $y)
                                                    <option value="{{ $y->score_grade_id}}">
                                                    {{ $y->score_grade }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-addon">
                                                <span class="fa fa-plus" id="add-more-grade"></span>
                                            </div>
                                        </div>
                              		</div>
                                </div>
                                    
                                <div class="col-md-7 pull-right v-divider">
                                    <div>
                                            
                                        <div class="form-group col-md-12">
                                        	<div class="col-md-4 pull-left">
                                                <label for="class_id" class="col-md-4">Class</label>
                                                <select class="form-control col-md-8" name="class_id" id="class_id">
                                                    <option value="">Please Select...</option>
                                                    @foreach($class as $y)
                                                        <option value="{{ $y->class_id}}">
                                                        {{ $y->class_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-default" id="add_row">
                                                    <i class="fa fa-plus fa-fw fa-fw"></i>Add A Score Grade</button>
                                            </div>

                                            <div class="col-md-4 pull-right">
                                                <button type="submit" class="btn btn-primary" id="btn-save">
                                                    <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                            </div>
                                        </div>
                                      	<div class="form-group col-md-12">
                                            <table class="table table-hover table-striped table-condensed" id="table-input">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">Score Grade</th>
                                                        <th width="25%">Start Score</th>
                                                        <th width="25%">End Score</th>
                                                        <th width="30%">Remarks</th>
                                                        <th width="10%">Action</th>
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
	function showInfo(){
		$.get("{{ route('getScoreGradeList')}}", function(data){
			$('#table-view').empty().append(data);
			MergeCommonRows($('#table-report'));
		});
	}
	$(function(){
		$('#add-more-grade').on('click', function(e){
			e.preventDefault();
			$('#div-show').modal('show');
		})
	});
	//this is to create exam grade type
	$(document).on('click', '#exam-grade-submit', function(e){
		e.preventDefault();
		var score_grade = $('#score_grade').val();
		var operator =  $('#operator').val();
		
		if( score_grade == null || score_grade == ""){
			alert('Empty field not allowed');
		}else{
			$('#pleaseWaitDialog').modal();
			$.post("{{ route('createScoreGrade') }}", {score_grade: score_grade, operator: operator},function(data){
				//reload the select
				$('#score_grade_id').empty();
				$.get("{{ route('getScoreGrade')}}", function(data){
					$('#score_grade_id').append('<option value="">Please Select...</option>');
					if (data.length > 0) {
						$.each(data, function(i,l){
							var row_data = '<option value="'+ l.score_grade_id +'">' + l.score_grade + '</option>';
							$('#score_grade_id').append(row_data);
						});
					}else{
						alert('No record to display...');	
					}
				});
				if(Object.keys(data).length > 0){
					alert('Update successful');
				}else{
					alert('Update NOT successful');
				}
				$('#pleaseWaitDialog').modal('hide');
			});
		}
	});
	$('#add_row').on('click', function(e){
		e.preventDefault();
		add_row();
	});
	function add_row(){
		var remarks = '<select class="form-control remarks" name="remarks[]">';
		remarks = remarks + "<option value=''>Select</option>";
		remarks = remarks + "<option value='Exceptional'>Exceptional</option>";
		remarks = remarks + "<option value='Excellent'>Excellent</option>";
		remarks = remarks + "<option value='Distinction'>Distinction</option>";
		remarks = remarks + "<option value='Very good'>Very good</option>";
		remarks = remarks + "<option value='Good'>Good</option>";
		remarks = remarks + "<option value='Average'>Average</option>";
		remarks = remarks + "<option value='Satisfactory'>Satisfactory</option>";
		remarks = remarks + "<option value='Pass'>Pass</option>";
		remarks = remarks + "<option value='Pass'>Poor</option>";
		remarks = remarks + "<option value='Failure'>Failure</option>";
		remarks = remarks + '</select>';
		//credit and accepted left out
		$.get("{{ route('getScoreGrade')}}", function(data){
			var all_divs = '<select class="form-control grade_id" name="grade_id[]">';
			all_divs = all_divs + "<option value=''>Select</option>";
			if (data.length > 0) {
				$.each(data, function(i,l){
					all_divs = all_divs + "<option value='" + l.score_grade_id + "'>" + l.score_grade + "</option>";
				});
			}else{
				alert('No record to display...');	
			}
			all_divs = all_divs + '</select>';
			var row_data = '<tr>' +
				'<td>' + all_divs +  '</td>'+
				'<td align="right"><input type="text" class="form-control score_from text-right"'+
				' name="score_from[]" onkeyup="return allow_number(this)"></td>'+
				'<td align="right"><input type="text" class="form-control score_to text-right"'+
				' name="score_to[]" onkeyup="return allow_number(this)"></td>'+
				'<td>' + remarks +  '</td>'+
				'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
			'</tr>';
			$('#table-input tbody').append(row_data);
		});
	}
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_select(document.getElementById("class_id"));
			//the length of the table should be more than one
			var rows = document.getElementById('table-input').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
			if (rows > 0){}else{
				reason += "The score division table is not populated \n";
			}
			//iterate through the table and check that all inputs are validat
			var old_score_to = -1;
			var score_to = 0;
			$('#table-input tbody tr').each(function(){
				
				var grade_id = $(this).closest('tr').find('select.grade_id').val();
				var remarks = $(this).closest('tr').find('select.remarks').val();
				if( remarks == "" ) reason += "Remarks have to be selected for the score definition \n";
				
				remarks
				var score_from = $(this).closest('tr').find('input.score_from').val();
				score_to = $(this).closest('tr').find('input.score_to').val();
				
				score_from = parseFloat(convertToNumbers(score_from));
				score_to = parseFloat(convertToNumbers(score_to));
				if(isNaN(score_from) === true) score_from = 0.00;
				if(isNaN(score_to) === true) score_to = 0.00;
				if( score_to == 0 || score_to > 100 ) reason += "End score cannot be zero and cannot be more than 100 \n";
				if( score_from > score_to ) reason += "Wrong score sequence \n";
				if( old_score_to + 1 !== score_from ) reason += "Wrong start score \n";
				old_score_to = score_to;
			});
			//be sure that the last score is 100
			if( score_to !== 100 )  reason += "The last score should end in 100 \n";
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(function(){
		$("#frm-create-exam-grade").on('submit', function(e){
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
							  url: "{{ route('updateClassScoreGrade')}}",
							  data: new FormData($("#frm-create-exam-grade")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
								  	$('#pleaseWaitDialog').modal('hide');
									if(Object.keys(data).length > 0){
										//$('#frm-create-exam-grade')[0].reset();
										//$('#table-input tbody').empty();
										showInfo();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									
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
		$.get("{{ route('excelExamGradeScore') }}", function(data){
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
	$('#class_id').on('change', function(e){
		e.preventDefault();
		var class_id = $('#class_id').val();
		//usually grading are the same: hence, no need to wipe it off
		//$('#table-input tbody').empty();
		$.get("{{ route('getGradeRecord')}}", {class_id: class_id},function(data){
			if (data.length > 0) {
				$('#table-input tbody').empty();
				
				$.each(data, function(i,l){
					var remarks = '<select class="form-control remarks" name="remarks[]">';
					remarks = remarks + "<option value='" + l.remarks + "'>"+ l.remarks +"</option>";
					remarks = remarks + '</select>';
					
					var all_divs = '<select class="form-control grade_id" name="grade_id[]">';
					all_divs = all_divs + "<option value='" + l.score_grade_id + "'>" + l.score_grade + "</option>";
					all_divs = all_divs + '</select>';
					
					var row_data = '<tr>' +
						'<td>' + all_divs +  '</td>'+
						'<td align="right"><input type="text" class="form-control score_from text-right"'+
						' name="score_from[]" onkeyup="return allow_number(this)" value="' + l.score_from + '"></td>'+
						'<td align="right"><input type="text" class="form-control score_to text-right"'+
						' name="score_to[]" onkeyup="return allow_number(this)" value="' + l.score_to + '"></td>'+
						'<td>' + remarks +  '</td>'+
						'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
					'</tr>';
					$('#table-input tbody').append(row_data);
				});
			}
		});
	});
	
</script>
@endsection