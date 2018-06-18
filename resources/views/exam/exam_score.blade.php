@extends('layouts.master')
@section('content')
    
	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Exam</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getExamScore')}}">Score Entry</a></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#subject" data-toggle="tab" class="nav-tab-class">Score by Subject</a></li>
                <li class="nav"><a href="#class-score" data-toggle="tab" class="nav-tab-class">Score by Class</a></li>
            	<li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Score</a></li>
                <li class="nav"><a href="#import-score" data-toggle="tab" class="nav-tab-class">Import Score</a></li>
            </ul>
            
           <div class="tab-content">
            	<div class="tab-pane fade in active" id="subject">
                	<div class="panel panel-body">
                        
                        <form action="" method="POST" id="frm-create-exam-score" enctype="multipart/form-data" >
                            <input type="hidden" name="exam_score_id" id="exam_score_id"> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="semester_id" class="col-md-8">Term</label>
                                         <div class="col-md-8">
                                            <select class="form-control" name="semester_id" id="semester_id" style="width: 100%;">
                                                <option value="">Please Select</option>
                                                @foreach($semester as $key=>$y)
                                                    <option value="{{ $y->semester_id}}">
                                                    {{ $y->semester}}</option>
                                                @endforeach
                                            </select>
                                    	</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exam_date" class="col-md-8">Date</label>
                                        <div class="col-md-8">
                                            <input type="text" name="exam_date" class="form-control text-right" id="exam_date" 
                                                placeholder="Enter Exam date" width="10">
                                       	</div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="exam_id" class="col-md-8">Exam</label>
                                         <div class="col-md-8">
                                            <select class="form-control" name="exam_id" id="exam_id" style="width: 100%;">
                                                <option value="">Please Select</option>
                                                @foreach($exams as $key=>$y)
                                                    <option value="{{ $y->exam_id}}">
                                                    {{ $y->exam_name}}</option>
                                                @endforeach
                                            </select>
                                     	</div>
                                    </div>
									<div class="form-group">
                                        <label for="class_id" class="col-md-8">Class</label>
                                         <div class="col-md-8">
                                            <select class="form-control" name="class_id" id="class_id" onchange="classChange()" style="width: 100%;">
                                                <option value="">Please Select...</option>
                                                @foreach($class as $y)
                                                    <option value="{{ $y->class_id}}">
                                                    {{ $y->class_name }}</option>
                                                @endforeach
                                            </select>
                                     	</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_div_id" class="control-label col-md-8">Section</label>
                                         <div class="col-md-8">
                                             <select class="form-control" name="class_div_id" id="class_div_id" style="width: 100%;">
                                                <option value="">Please Select...</option>
                                                
                                            </select>
                                      	</div>
                                    </div>
                                     <div class="form-group">
                                        <label for="subject_id" class="col-md-8">Subject</label>
                                         <div class="col-md-8">
                                            <select class="form-control" name="subject_id" id="subject_id" style="width: 100%;">
                                                <option value="">Please Select...</option>
                                                
                                            </select>
                                     	</div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                    	<div class="pull-left col-md-4">
                                            <button type="button" class="btn btn-default" id="btn-add">
                                                <i class="fa fa-plus fa-fw fa-fw"></i>Add</button>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-info" id="btn-edit">
                                                <i class="fa fa-edit fa-fw fa-fw"></i>Edit</button>
                                        </div>
                                        <div class="pull-right col-md-4">
                                            <button type="submit" class="btn btn-primary" id="btn-save">
                                                <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                        </div>
                                    </div>
                                </div>
                                <!-----------all the students in the class should be displayed for score entry
                                opportunity to delete any student who didnt participate in the exam
                                remarks is for those who didnt write: sick, travel etc--------->
                                <div class="col-md-8 pull-right v-divider">
                                    <div>
                                        <table class="table table-hover table-striped table-condensed" id="score-table">
                                            <thead>
                                                <tr>
                                                    <th width="5%">X</th>
                                                    <th width="10%">Reg No</th>
                                                    <th width="20%">Last Name</th>
                                                    <th width="15%">First Name</th>
                                                    <th width="10%">Max Score</th>
													<th width="15%">Score</th>
													<th width="20%">Remarks</th>
                                                    <th width="5%">Action</th>
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
                <div class="tab-pane fade in" id="class-score">
                    
                    <div class="panel panel-body">
                    	<form method="post" class="form-horizontal" id="frm-class-score" name="frm-class-score"
                      		role="form" enctype="multipart/form-data" return false>
                               
                           <input type="hidden" name="operator_cl" id="operator_cl" value="{{ Auth::user()->username }}">
                           <input type="hidden" id="token" value="{{ csrf_token() }}">
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="semester_id_cl" class="col-md-12"><strong>Term</strong></label>
                                         <div class="col-md-12">
                                            <select class="form-control" name="semester_id_cl" id="semester_id_cl" style="width: 100%;">
                                                <option value="">Please Select</option>
                                                @foreach($semester as $key=>$y)
                                                    <option value="{{ $y->semester_id}}">
                                                    {{ $y->semester}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exam_date_cl" class="col-md-12"><strong>Date</strong></label>
                                        <div class="col-md-12">
                                            <input type="text" name="exam_date_cl" class="form-control text-right" id="exam_date_cl" placeholder="Enter Exam date" width="10">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exam_id_cl" class="col-md-12"><strong>Exam</strong></label>
                                         <div class="col-md-12">
                                            <select class="form-control" name="exam_id_cl" id="exam_id_cl" style="width: 100%;">
                                                <option value="">Please Select</option>
                                                @foreach($exams as $key=>$y)
                                                    <option value="{{ $y->exam_id}}">
                                                    {{ $y->exam_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="class_id_cl" class="col-md-12">Class</label>
                                         <div class="col-md-12">
                                            <select class="form-control" name="class_id_cl" id="class_id_cl" onchange="classChange2()" 
                                            	style="width: 100%;">
                                                <option value="">Please Select...</option>
                                                @foreach($class as $y)
                                                    <option value="{{ $y->class_id}}">
                                                    {{ $y->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="class_div_id_cl" class="col-md-12">Section</label>
                                         <div class="col-md-12">
                                             <select class="form-control" name="class_div_id_cl" id="class_div_id_cl"       onchange="sectionChange()" style="width: 100%;">
                                                <option value="">Please Select...</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right col-md-1">
                                    <button type="button" class="btn btn-primary" id="btn-class-score">
                                        <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div id="class-score-div" class="table-responsive" style="border: 1px solid #D3D3D3;            border-radius: 5px 5px 0 0; min-height: 350px; overflow: auto;">
                                </div>
                            </div>
                  		</form>
                    </div>
             	</div>
            	<div class="tab-pane fade in" id="view">
                    <div class="panel panel-body">
                        <!---------filter by term, student, class, subject----------->
                        <div class="form-group">
                            <label for="semester_id2" class="col-md-8">Term</label>
                             <div class="col-md-8">
                                <select class="form-control" name="semester_id_cl" id="semester_id2" style="width: 100%;">
                                    <option value="">Please Select</option>
                                    @foreach($semester as $key=>$y)
                                        <option value="{{ $y->semester_id}}">
                                        {{ $y->semester}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="panel panel-body" id="table-view">
                		</div>
                    </div>
            	</div>
                <div class="tab-pane fade in" id="import-score">
                    <div class="panel panel-body" style="margin-top:5px;">
                        <div class="panel panel-body" style="margin-top:5px;">
                            <form method="post" class="form-horizontal" id="frm-import-score" name="frm-import-score"
                               role="form" enctype="multipart/form-data" return false>
                               
                               <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                               <input type="hidden" id="token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <input type="file" id="table_file" name="table_file">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-default" id="btn_import_score">
                                            <i class="fa fa-upload fa-fw"></i>Upload</button>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="semester_p" class="col-md-2 control-label">Term:</label>
                                        <select class="form-control" name="semester_p" id="semester_p" style="width: 100%;">
                                            <option value="">Please Select</option>
                                            @foreach($semester as $key=>$y)
                                                <option value="{{ $y->semester_id}}">
                                                {{ $y->semester}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 pull-right">
                                        <button id="btn_update_score"
                                            type="button"  class="btn btn-primary" >
                                                <i class="fa fa-save fa-fw"></i>Update Database</button>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <!--<hr />-->
                                            <div id="table_div_score">
                                                <!--populate dynamically-->
                                                <table class="table table-hover table-striped table-condensed" id="import_score"
                                                    style="font-size:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%">Exam</th>
                                                            <th width="10%">Date</th>
                                                            <th width="15%">Subject</th>
                                                            <th width="10%">Reg No</th>
                                                            <th width="15%">Last Name</th>
                                                            <th width="15%">First Name</th>
                                                            <th width="15%">Class</th>
                                                            <th width="10%">Score</th>
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
        </div>
        <div id="dialog" style="display: none"></div>
    </div>
    <form action="" method="POST" name= "frm-data-score" id="frm-data-score" enctype="multipart/form-data" return false>
        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
        <input type="hidden" name="reviewer" id="reviewer" value="">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="exam" id="exam" value="">
        <input type="hidden" name="semester_id" id="semester_id" value="">
        <input type="hidden" name="exam_date" id="exam_date" value="">
        <input type="hidden" name="subject" id="subject" value="">
        <input type="hidden" name="reg_no" id="reg_no" value="">
        <input type="hidden" name="class_section" id="class_section" value="">
        <input type="hidden" name="exam_score" id="exam_score" value="">
     </form>
     <div style="display:none">
         <form action="" method="POST" name= "frm-data-class-score" id="frm-data-class-score" enctype="multipart/form-data" return false>
            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
            <input type="hidden" name="reviewer" id="reviewer" value="">
            <input type="hidden" name="_token" value="{{ csrf_token()}}">
            
            <table>
                <tbody>
                </tbody>
            </table>
         </form>
    </div>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">
	
	$(function(){
		$('#exam_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	$(function(){
		$('#exam_date_cl').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	function classChange2(){
		//this event displays the sections for the selected class
		$('#class_div_id_cl').empty();
		var class_id = $('#class_id_cl').val();
		//generate sections upon class change
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id_cl').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#class_div_id_cl').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
		$('#pleaseWaitDialog').modal('hide');
	}
	function classChange(){
		//this event displays the sections for the selected class
		$('#class_div_id').empty();
		var class_id = $('#class_id').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#class_div_id').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	}
	$('#class_div_id').on('change', function(){
		//this should populate the subject area with the subjects defined for that sections ONLY
		$('#subject_id').empty();
		var class_div_id = $('#class_div_id').val();
		//generate subjects upon class change: ONLY those subjects defined for that class in the syllabus table
		$.get("{{ route('getSectionSubject')}}", {class_div_id: class_div_id}, function(data){
			$('#subject_id').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.subject_id +'">' + l.subject + '</option>';
					$('#subject_id').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	})
	function sectionChange(){
		//NB: you cannot post-date
		//populate the table area after this
		var class_div_id = $('#class_div_id_cl').val();
		var semester_id = $('#semester_id_cl').val();
		var class_id = $('#class_id_cl').val();
		var exam_id = $('#exam_id_cl').val();
		var exam_date = toDbaseDate($('#exam_date_cl').val());
		//generate subjects upon class change: ONLY those subjects defined for that class in the syllabus table
		
			$.get("{{ route('getStudentClassExam')}}", {
					exam_id: exam_id,
					exam_date: exam_date,
					class_div_id: class_div_id,
					semester_id: semester_id, 
					class_id: class_id }, 
			function(data)
			{
				$('#class-score-div').empty().append(data);
                
			});
	}
	$('#btn-edit').on('click', function(e){
		e.preventDefault();
		//this should call the score for edit, as specified
		try{
			var class_div_id = $('#class_div_id').val();
			var subject_id = $('#subject_id').val();
			var exam_id = $('#exam_id').val();
			var semester_id = $('#semester_id').val();
			var exam_date = trim($('#exam_date').val());
			if( class_div_id !== "" &&  subject_id !== "" &&  exam_id !== "" &&  semester_id !== "" &&  exam_date !== ""){
				
				$('#score-table tbody').empty();
				$.get("{{ route('editStudentScore') }}", {
						class_div_id: class_div_id, 
						subject_id: subject_id, 
						exam_id: exam_id,
						semester_id: semester_id,
						exam_date: exam_date}
				, function(data){
					if (data.length > 0) {
						$.each(data, function(i,l){
							//get all the students for this class and the maximum score for the selected subject
							var row_data = '<tr>' +
								'<td><div><input type="checkbox" class="checkbox vert-align student" name="students[]" value="' + l.student_id +'" checked></div></td>' +
								'<td>' + l.reg_no +'</td>'+
								'<td>' + l.last_name +'</td>'+
								'<td>' + l.first_name +'</td>'+
								'<td>' + $('#exam_score_id').val() +'</td>'+
								'<td align="right"><input type="text" class="form-control exam_score text-right"'+
									' name="exam_score[]" onkeyup="return allow_number(this)" value="'+ l.exam_score +'"></td>'+
								'<td><input type="text" name="remarks[]" class="form-control remarks" value="'+ l.remarks +'"></td>'+
								'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
								'</tr>';
							$('#score-table> tbody:last-child').append(row_data);
						});
					}else{
						alert('No record to display...');	
					}
				});
			}else{
				alert('Invalid selection');	
			}
		}catch(err){ alert(err.message); }
	});
	$('#subject_id').on('change', function(e){
		e.preventDefault();
		getMaxScore();
	});
	
	$('#btn-add').on('click', function(e){
		e.preventDefault();
		//this populates the max score field appropriately 
		getMaxScore();
		//get the maximum score for the selected subject
		$('#score-table tbody').empty();
		var class_div_id = $('#class_div_id').val();
		
		$.get("{{ route('getDivStudents') }}", {class_div_id: class_div_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					//get all the students for this class and the maximum score for the selected subject
					var row_data = '<tr>' +
						'<td><div><input type="checkbox" class="checkbox vert-align student" name="students[]" value="' + l.student_id +'" checked></div></td>' +
						'<td>' + l.reg_no +'</td>'+
						'<td>' + l.last_name +'</td>'+
						'<td>' + l.first_name +'</td>'+
						'<td>' + $('#exam_score_id').val() +'</td>'+
						'<td align="right"><input type="text" class="form-control exam_score text-right"'+
							' name="exam_score[]" onkeyup="return allow_number(this)"></td>'+
						'<td><input type="text" name="remarks[]" class="form-control remarks" value=""></td>'+
						'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
						'</tr>';
					$('#score-table> tbody:last-child').append(row_data);
				});
			}else{
				alert('No record to display...');
			}
		});
	})
	
	function validateClassScoreOnSubmit() {
		var reason = "";
		try{
			
			reason += validate_select(document.getElementById("class_div_id_cl"));
			reason += validate_select(document.getElementById("exam_id_cl"));
			reason += validate_select(document.getElementById("semester_id_cl"));
			reason += validate_date(document.getElementById("exam_date_cl"));
			
			//the length of the table should be more than one
			var rows = document.getElementById('tbl_class_score').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
			if (rows > 0){}else{
				reason += "The exam score table is not populated \n";
			}
			//date cannot be in future
			if( isafterDate($('#exam_date_cl').val()) == true) reason += "Exam Date cannot be after current date \n";
			//get the maximum score for this exam and ensure that the scores do not exceed the score
			//NB this does not validate exam scores
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
			
		}catch(err){ alert(err.message); }
	}
	function validateFormOnSubmit() {
		var reason = "";
		try{
			
			reason += validate_select(document.getElementById("class_div_id"));
			reason += validate_select(document.getElementById("subject_id"));
			reason += validate_select(document.getElementById("exam_id"));
			reason += validate_select(document.getElementById("semester_id"));
			reason += validate_date(document.getElementById("exam_date"));
			
			//the length of the table should be more than one
			var rows = document.getElementById('score-table').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
			if (rows > 0){}else{
				reason += "The exam score table is not populated \n";
			}
			//date cannot be in future
			if( isafterDate($('#exam_date').val()) == true) reason += "Exam Date cannot be after current date \n";
			//get the maximum score for this exam and ensure that the scores do not exceed the score
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
			
		}catch(err){ alert(err.message); }
	}
	function getMaxScore(){
		var exam_id = $('#exam_id').val();
		var class_id = $('#class_id').val();
		if( exam_id !== "" && class_id !== "" && subject_id !== "" ){
			$.get("{{ route('getExamMaxScore')}}", {exam_id: exam_id, class_id: class_id}, 
				function(data){
					$('#exam_score_id').val(data);
			});
		}else{
			alert('Ensure that you select Exam, Class and Subject');	
		}
	}
	$(function(){
		$("#frm-create-exam-score").on('submit', function(e){
			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to update this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						var isvalid = validateFormOnSubmit();
						if (isvalid){
							//check maximum score here
							var max_score = $('#exam_score_id').val();
							var proceed = true;
							if( max_score !== ""){
								$('#score-table tbody tr').each(function(){
									var exam_score = $(this).closest('tr').find('input.exam_score').val();
									exam_score= parseFloat(convertToNumbers(exam_score));
									if(isNaN(exam_score) === true || exam_score < 0) exam_score = 0.00;
									if( exam_score > max_score ){
										proceed = false;
									}
									
								});
							}else{
								alert('Exam not defined for this class');
								proceed = false;
							}
							//all need to be checked
							$('.student').prop('checked', true);
							if(proceed == true){
								var exam_date = $('#exam_date').val();
								$('#exam_date').val(toDbaseDate(exam_date));
								
								document.getElementById("btn-save").innerHTML = 
									'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
			
								$('#pleaseWaitDialog').modal();
								
								$.ajax({
								  url: "{{ route('updateExamScore')}}", 
								  data: new FormData($("#frm-create-exam-score")[0]),
								  async:false,
								  type:'post',
								  processData: false,
								  contentType: false,
								  success:function(data){
									  	if(Object.keys(data).length > 0){
											alert('Update successful');
											$('#frm-create-exam-score')[0].reset();
											$('#score-table tbody').empty();
										}else{
											alert('Update NOT successful');
										}
										document.getElementById("btn-save").innerHTML = 
											'<i class="fa fa-save fa-fw fa-fw"></i>Save';
									
										$('#pleaseWaitDialog').modal('hide');
									},
									error: OnError
								});
								$('#exam_date').val(exam_date);
							}
						}
					}
				}
			});
		})
	});
	//this is to populate the table with the exams in the semester
	$('#semester_id2').on('change', function(){
		showInfo();
	});
	function showInfo(){
		var semester_id = $('#semester_id2').val();
		$('#pleaseWaitDialog').modal();
		var table = $('#exam-table-view').DataTable();
		table.clear();
		table.destroy();
		$.get("{{ route('listExamScore')}}",{semester_id: semester_id}, function(data){
			$('#pleaseWaitDialog').modal('hide');
			$('#table-view').empty().append(data);
			$('#exam-table-view').DataTable();
		});
	}
	$(document).on('click', '#export-to-pdf', function(e){
		e.preventDefault();
		var semester_id = $('#semester_id2').val();
		if( semester_id != '' && semester_id != null){
			
			$('#pleaseWaitDialog').modal();
			//prepare the report for ecah student in the term
			$.get("{{ route('listEnrolStudents') }}", {}, function(data){
				//return student records
				$.each(data, function(i,l){
					var reg_no = l.reg_no;
					
					$.get("{{ route('pdfExamScores') }}", {reg_no: reg_no, semester_id: semester_id}, function(data){
						
					});
				});
				
				$('#pleaseWaitDialog').modal('hide');
				alert('Exam scores generated...');
				var path = "{{url('/')}}";
				path = path + '/reports/pdf/scores/';
				openInNewTab(path);
			});
		}
	});
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		var semester_id = $('#semester_id2').val();
		if( semester_id != '' && semester_id != null){
			$('#pleaseWaitDialog').modal();
			$.get("{{ route('excelExamScores') }}",{semester_id: semester_id}, function(data){
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
		}
	});
	$('#btn_import_score').on('click', function(e){
		e.preventDefault();
		
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to upload this file?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				if(result) {
					document.getElementById("btn_import_score").innerHTML = 
						'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
				
					$.ajax({
						url: "{{ route('scoreImport')}}",
						data: new FormData($("#frm-import-score")[0]),
						async:false,
						type:'post',
						processData: false,
						contentType: false,
						success:function(data){
							$('#table_div_score').children().remove();
							$('#table_div_score').append(data);
							document.getElementById("btn_import_score").innerHTML = 
								'<i class="fa fa-upload fa-fw"></i>Upload';
						},
						error: function(jqXHR, exception) { 
							return_error(jqXHR, exception);
						}
					});
				}
			}
		});
	});
	
	$('#btn-class-score').on('click', function(e){
		e.preventDefault();
		//this is to update class score
		try{
			var table = $('#tbl_class_score tbody');
			var semester_id = $('#semester_id_cl').val();
			var class_div_id = $('#class_div_id_cl').val();
			var class_id = $('#class_id_cl').val();
			var exam_id = $('#exam_id_cl').val();
			var exam_date = toDbaseDate($('#exam_date_cl').val());
			
			var isvalid = validateClassScoreOnSubmit();
			if (isvalid){
			
				BootstrapDialog.confirm({
					title: 'RECORD UPDATE',
					message: 'Are you sure you want to update these records?',
					type: BootstrapDialog.TYPE_WARNING,
					closable: true,
					callback: function(result) {
						if(result) {
							document.getElementById("btn-class-score").innerHTML = 
								'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
							
							table.find('tr').each(function(i, el){
								
								var tds = $(this).find('td');
								var reg_no = tds.eq(0).find('input').val();
								
								var row_data = '<tr id="only_row">' +
									'<td><input type="text" class="exam_id" name="exam_id" value="'+ exam_id + '" ></td>'+
									'<td><input type="text" class="exam_date" name="exam_date" value="'+ exam_date + '" ></td>'+
									'<td><input type="text" class="reg_no" name="reg_no" value="'+ reg_no + '" ></td>'+
									'<td><input type="text" class="class_div_id" name="class_div_id" value="'+ class_div_id + '" ></td>'+
									'<td><input type="text" class="semester_id" name="semester_id" value="'+ semester_id + '" ></td>'+
									'<td><input type="text" class="class_id" name="class_id" value="'+ class_id + '" ></td>'+
								'</tr>';
								$('#frm-data-class-score table tbody').empty().append(row_data);
								
								var i = 0
								var scoreArray = [];
								$.each(tds, function(index, item) {
									//you need to start from the score area
									if(i > 1){
										//use val since it is a text box
										var exam_score = tds.eq(i).find('input').val();
										if(isNaN(exam_score) === true || exam_score < 0) exam_score = 0.00;
										//the same as before: just for repetition: since index increments
										if(index > 1) {
											scoreArray.push(exam_score);
											//store the scores in the class_score array
											var cell_data = '<td><input type="text" class="class_score" name="class_score[]" value="'+ exam_score + '" ></td>';
											//append the cell to the row
											$('#frm-data-class-score table tbody #only_row').append(cell_data);
										}
										//OR row_data.append(cell_data); 
										//OR row_data += cell_data;
										//$('#frm-data-class-score table tbody').append(row_data);
									}
									i = i + 1;
								});
								
								var $ele = $(this).closest('tr');
								$.ajax({
									url: "{{ route('updateClassScore')}}",
									data: new FormData($("#frm-data-class-score")[0]),
									async:false,
									type:'post',
									processData: false,
									contentType: false,
									success:function(data){
										
										if(Object.keys(data).length > 0){
											$ele.fadeOut().remove();
										}else{
											$ele.css('background-color', '#ccc');	
										}
										//you need to reset the form here so that the array will not continue to count
										$('#frm-data-class-score')[0].reset();
									},
									error: function(jqXHR, exception) { 
										return false;
										return_error(jqXHR, exception);
									}
								});
							});
							document.getElementById("btn-class-score").innerHTML = 
								'<i class="fa fa-save fa-fw fa-fw"></i>Save';
						}
					}
				});
			}
		}catch(err){
			return false; 
			alert(err.message); 
		}
	});
	$('#btn_update_score').on('click', function(e){
		e.preventDefault();
		try{
			var table = $('#frm-import-score #import_score tbody');
			var semester = $('#frm-import-score #semester_p').val();
			//alert(semester);
			
			//var semester_id = $("#$semester_p").val();
			$('#pleaseWaitDialog').modal();
			table.find('tr').each(function(i, el){
				var is_valid = true;
				var tds = $(this).find('td');
				var exam = tds.eq(0).text();
				var exam_date = toDbaseDate(tds.eq(1).text());
				var subject = tds.eq(2).text();
				var reg_no = tds.eq(3).text();
				var last_name = tds.eq(4).text();
				var first_name = tds.eq(5).text();
				var class_section = tds.eq(6).text();
				var exam_score = tds.eq(7).text();
				if( exam == "" || exam_date == "" || subject == "" || reg_no == "" || last_name == "" || 
					first_name == "" || class_section == "" || exam_score == "" || semester == ""){
					is_valid = false;
				}
				
				var exam_score = parseFloat(convertToNumbers(exam_score));
				if(isNaN(exam_score) === true) exam_score = 0.00;
				if( exam_score == 0 ) is_valid = false;
				
				if (is_valid){
					$('#frm-data-score #exam').val(exam);
					$('#frm-data-score #exam_date').val(exam_date);
					$('#frm-data-score #subject').val(subject);
					$('#frm-data-score #reg_no').val(reg_no);
					$('#frm-data-score #class_section').val(class_section);
					$('#frm-data-score #exam_score').val(exam_score);
					$('#frm-data-score #semester_id').val(semester);
					var $ele = $(this).closest('tr');
					$.ajax({
						url: "{{ route('updateScoreImport')}}",
						data: new FormData($("#frm-data-score")[0]),
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
			$('#pleaseWaitDialog').modal('hide');
			
		}catch(err){
			return false; 
			alert(err.message); 
		}
	});
    //
    /*
    $('.serial').keypress(function(e) {
        console.log(this);
        if (e.which == 13) {
            $(this).closest('tr').next().find('input.serial').focus();
            e.preventDefault();
        }
    });
    //Assuming you want to move to the cell below when hitting enter in any input, use this:
    $('#class-score-div .table-fixed input').keypress(function(e) {
        if (e.keyCode == 13) {
            var $this = $(this), index = $this.closest('td').index();

            $this.closest('tr').next().find('td').eq(index).find('input').focus();
            e.preventDefault();
        }
    });
    $('body').on("keydown", '#class-score-div #tbl_class_score tr td', function(e) {
        var keyC = e.keyCode;
        if (keyC == 32) {
             alert('Enter pressed');
        }
    });
    //if your page was dynamically creating elements with the class name dosomething you would bind the event to a parent which already exists
    $(document).on("keydown", '#class-score-div #tbl_class_score tr td', function(e) {
        var keyC = e.keyCode;
        if (keyC == 32) {
             alert('Enter pressed');
        }
    });
    document.querySelector("#class-score-div #tbl_class_score tr td").addEventListener("keydown", function(event) {
       alert('inside'); 
    });*/
    

</script>
@endsection