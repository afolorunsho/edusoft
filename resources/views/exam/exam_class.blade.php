@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Exam</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getExamClass')}}">Class Exam</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#exam_class" data-toggle="tab" class="nav-tab-class">Exam Class</a></li>
            	<li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Exam Class</a></li>
            </ul>
           <div class="tab-content">
            	<div class="tab-pane fade in active" id="exam_class">
                	<div class="panel panel-body">
                   		<form action="" method="POST" id="frm-create-exam-class" enctype="multipart/form-data" >
                            <input type="hidden" name="exam_class_id" id="exam_class_id"> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="row">
                                <div class="col-md-4">
                                    
                                    <div class="form-group">
                                        <label for="class_id" class="control-label col-md-8">Class</label>
                                         <select class="form-control" name="class_id" id="class_id">
                                            <option value="">Please Select...</option>
                                            @foreach($class as $y)
                                                <option value="{{ $y->class_id}}">
                                                {{ $y->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-primary" id="btn-save">
                                                <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="col-md-8 pull-right v-divider">
                                    <div>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-default" id="add_row">
                                                <i class="fa fa-plus fa-fw fa-fw"></i>Add Exam</button>
                                        </div>
                                        <table class="table table-hover table-striped table-condensed" id="table-input">
                                            <thead>
                                                <tr>
                                                    <th>Exam Name</th>
                                                    <th>Max Score</th>
                                                    <th>Exam Weight</th>
                                                    <th>Action</th>
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
	function showInfo(){
		$.get("{{ route('infoExamClass')}}", function(data){
			$('#table-view').empty().append(data);
			MergeCommonRows($('#table-view'));
		});
	}
	//this need to be outside so that it can be picked by the 2 functions using it
	var all_exams = '<select class="form-control exam_id" name="exam_id[]">';
	//this class populates the dynamic exam drop down, all_exams, used in the exam table 
	$.get("{{ route('listExams') }}", function(data){
		all_exams = all_exams + "<option value=''>Select</option>";
		if (data.length > 0) {
			$.each(data, function(i,l){
				var exam_id = l.exam_id;
				var exam_name = l.exam_name;
				all_exams = all_exams + "<option value='" + exam_id + "'>" + exam_name + "</option>";
			});
			all_exams = all_exams + '</select>';
		}else{ alert('no record to diplay')}
	});
	$('#add_row').on('click', function(e){
		e.preventDefault();
		add_row();
	});
	function add_row(){
		var row_data = '<tr>' +
			'<td>' + all_exams +  '</td>'+
			'<td align="right"><input type="text" class="form-control max_score text-right"'+
			' name="max_score[]" onkeyup="return allow_number(this)"></td>'+
			'<td align="right"><input type="text" class="form-control exam_weight text-right"'+
			' name="exam_weight[]" onkeyup="return allow_number(this)"></td>'+
			'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
		'</tr>';
		$('#table-input tbody').append(row_data);
	}
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_select(document.getElementById("class_id"));
			//reason += validate_select(document.getElementById("subject_id"));
			//the length of the table should be more than one
			var rows = document.getElementById('table-input').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
			if (rows > 0){}else{
				reason += "The exam table is not populated \n";
			}
			//iterate through the table and check that all inputs are validat
			var weight_total = 0;
			$('#table-input tbody tr').each(function(){
								
				var exam_id = $(this).closest('tr').find('select.exam_id').val();
				var max_score = $(this).closest('tr').find('input.max_score').val();
				var exam_weight = $(this).closest('tr').find('input.exam_weight').val();
				
				if(exam_id == "") reason += "Wrong exam type \n";
				
				max_score = parseFloat(convertToNumbers(max_score));
				exam_weight = parseFloat(convertToNumbers(exam_weight));
				if(isNaN(max_score) === true) max_score = 0.00;
				if(isNaN(exam_weight) === true) exam_weight = 0.00;
				if( max_score == 0 || max_score > 100 ) reason += "Wrong maximum score for the exam type \n";
				if( exam_weight > 100 ) reason += "Wrong score weight for the exam type \n";
				weight_total = weight_total + exam_weight;
			});
			if(weight_total !== 100) reason += "Total exam weight must be 100 \n";
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(function(){
		$("#frm-create-exam-class").on('submit', function(e){
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
							  url: "{{ route('updateExamClass')}}",
							  data: new FormData($("#frm-create-exam-class")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									$('#pleaseWaitDialog').modal('hide');
									if(Object.keys(data).length > 0){
										alert('Update successful');
										showInfo();
										//$('#frm-create-exam-class')[0].reset();
										//$('#table-input tbody').empty();
										
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
		$.get("{{ route('excelExamClass') }}", function(data){
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
		$.get("{{ route('pdfExamClass') }}", function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
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