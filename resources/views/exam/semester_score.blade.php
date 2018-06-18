@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Exam</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getTermScore')}}">Total Score</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#process" data-toggle="tab" class="nav-tab-class">Score Process</a></li>
                <li class="nav"><a href="#student-comment" data-toggle="tab" class="nav-tab-class">Comments</a></li>
                <li class="nav"><a href="#assessments" data-toggle="tab" class="nav-tab-class">Assessments</a></li>
                <li class="nav"><a href="#promotion" data-toggle="tab" class="nav-tab-class">Overall Result</a></li>
                <li class="nav"><a href="#class_result" data-toggle="tab" class="nav-tab-class">Class Results</a></li>
                <li class="nav"><a href="#student_result" data-toggle="tab" class="nav-tab-class">Student Result</a></li>
                <li class="nav"><a href="#print_result" data-toggle="tab" class="nav-tab-class">Print Result</a></li>
                <li class="nav"><a href="#distribution" data-toggle="tab" class="nav-tab-class">Class Distribution</a></li>
            </ul>
        
            <div class="tab-content">
                <div class="tab-pane fade in active" id="process">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-semester-score" enctype="multipart/form-data" return false>
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                             <div class="row">
                                <div class="col-md-5">
                                
                                    <div class="form-group col-md-12">
                                        <label for="semester_id" class="col-md-2 control-label">Term</label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="semester_id" id="semester_id" style="width: 150px;">
                                                <option value="">Please Select</option>
                                                @foreach($semester as $key=>$y)
                                                    <option value="{{ $y->semester_id}}">{{ $y->semester}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 pull-right">
                                            <button type="button" id="generate_score" class="btn btn-default">
                                                Generate Exam Scores</button>
                                       	</div>
                                    </div>
                           		</div>
                            </div>
                        </form>
                    </div>
                </div>
        		
                <div class="tab-pane fade" id="student-comment">
                    <div class="row">
                        <div class="panel panel-default">
							<form action="" method="POST" id="frm-semester-teacher" enctype="multipart/form-data" return false>
								<input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
								<input type="hidden" name="reviewer" id="reviewer" value="">
								<input type="hidden" name="_token" value="{{ csrf_token()}}">
							
								<div class="form-group col-md-12">
									<label for="semester_id3" class="col-md-1 control-label">Term</label>
									<div class="col-md-2">
										<select class="form-control" name="semester_id3" id="semester_id3" style="width: 150px;">
											<option value="">Please Select</option>
											@foreach($semester as $key=>$y)
												<option value="{{ $y->semester_id}}">
												{{ $y->semester}}</option>
											@endforeach
										</select>
									</div>    
									<label for="class_id" class="col-md-1 control-label">Class</label>
									<div class="col-md-2">
										<select class="form-control" name="class_id" id="class_id" onchange="getSection()">
											<option value="">Please Select...</option>
											@foreach($class as $y)
												<option value="{{ $y->class_id}}">{{ $y->class_name }}</option>
											@endforeach
										</select>
									</div>
									<label for="class_div_id" class="col-md-1 control-label">Section</label>
									<div class="col-md-2">
										<select class="form-control" name="class_div_id" id="class_div_id" 
                                        	onchange="studentScore()">
										   
										</select>
									</div>
									<div class="col-md-1">
											<button type="button" class="btn btn-primary" id="teacher_update">
												<i class="fa fa-save fa-fw fa-fw"></i>Save</button>
									</div>
								</div>
								<div class="panel-body table-responsive" id="show-student" style="overflow: auto">
                               
									<table class="table" id="table-student">
										<thead>
											<tr>
												<th width="5%">RegNo</th>
												<th width="7%">Last Name</th>
												<th width="8%">First Name</th>
												<th width="7%">Total Score</th>
												<th width="10%">Score Obtained</th>
                                                <th width="8%">Average Score</th>
												<th width="10%">Subjects Taken</th>
												<th width="8%">Subjects Passed</th>
												<th width="7%">Subjects Failed</th>
												<th width="30%">Comments</th>
											 </tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="assessments">
                    <div class="row">
                        <div class="panel panel-default">
							<form action="" method="POST" id="frm-semester-comment" enctype="multipart/form-data" return false>
								<input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
								<input type="hidden" name="reviewer" id="reviewer" value="">
								<input type="hidden" name="_token" value="{{ csrf_token()}}">
							
								<div class="form-group col-md-8">
									<label for="semester_id_comment" class="col-md-1 control-label">Term</label>
									<div class="col-md-3">
										<select class="form-control" name="semester_id_comment" id="semester_id_comment">
											<option value="">Please Select</option>
											@foreach($semester as $key=>$y)
												<option value="{{ $y->semester_id}}">
												{{ $y->semester}}</option>
											@endforeach
										</select>
									</div>    
									<label for="class_id" class="col-md-1 control-label">Class</label>
									<div class="col-md-3">
										<select class="form-control" name="class_id_comment" id="class_id_comment" 
                                        	onchange="getSection2()">
											<option value="">Please Select...</option>
											@foreach($class as $y)
												<option value="{{ $y->class_id}}">{{ $y->class_name }}</option>
											@endforeach
										</select>
									</div>
									<label for="class_div_id_comment" class="col-md-1 control-label">Section</label>
									<div class="col-md-3">
										<select class="form-control" name="class_div_id_comment" id="class_div_id_comment" >
										   
										</select>
									</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="assessment_id" class="col-md-3 control-label">Assessment</label>
									<div class="col-md-6">
										<select class="form-control" name="assessment_id" id="assessment_id" 
                                        	onchange="studentPara()">
										   
										</select>
									</div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary" id="comment_update">
                                            <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                    </div>
								</div>
                                
                                <div id="show-assessment">
                                </div>
                               
							</form>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade in" id="promotion">
                	<div class="panel panel-body">
                		<form action="" method="POST" id="frm-create-promotion" enctype="multipart/form-data" return false>
                            <input type="hidden" name="promotion_id  " id="promotion_id" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                             <div class="row">
                                <div class="col-md-4">
                                
                                    <div class="form-group col-md-12">
                                        <label for="promotion_date" class="col-md-4 control-label">Date</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-right" id="promotion_date" name="promotion_date">
                                        </div>
                                    </div>
                                     <div class="form-group col-md-12">
                                        <label for="semester_id1" class="col-md-4 control-label">Term</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="semester_id1" id="semester_id1" style="width: 150px;">
                                                <option value="">Please Select</option>
                                                @foreach($semester as $key=>$y)
                                                    <option value="{{ $y->semester_id}}">{{ $y->semester}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                          <div class="pull-right">
                                            <button type="button" class="btn btn-default" id="btn-generate">
                                                <i class="fa fa-save fa-fw fa-fw"></i>Generate</button>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <!--------------another column---------------->
                                <div class="col-md-8 v-divider">
                                
                                    <table class="table" id="table-promotion">
                                        <thead>
                                            <tr>
                                                <th width="5%">X</th>
                                                <th width="20%">Class</th>
                                                <th width="25%">Minimum Pass Subjects</th>
                                                <th width="25%">Minimum Total Score</th>
                                                <th width="25%">Minimum Average Score</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                        	@foreach($class as $y)
                                            <tr>
                                           		<td><div><input type="checkbox" class="checkbox vert-align" 
                                                	name="class_id[]" value="{{ $y->class_id}}"></div></td>
                                             	<td>{{ $y->class_name}}</td>
                                                <td align="right"><input type="text" class="form-control subjects text-right" 
                                                	name="subjects[]" onkeyup="return allow_number(this)"></td>
                                              	<td align="right"><input type="text" class="form-control score text-right" 
                                                	name="score[]" onkeyup="return allow_number(this)"></td>
                                              	<td align="right"><input type="text" class="form-control average text-right" 
                                                	name="average[]" onkeyup="return allow_number(this)"></td>
                                         	</tr>
                                         	@endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                  	</div>
            	</div>
                <div class="tab-pane fade" id="student_result">
                    <div class="row">
                        <div class="panel panel-default">
                        	<div class="form-group col-md-12">
                                <label for="semester_id4" class="col-md-1 control-label">Term</label>
                                <div class="col-md-3">
                                    <select class="form-control" name="semester_id4" id="semester_id4" style="width: 150px;">
                                        <option value="">Please Select</option>
                                        @foreach($semester as $key=>$y)
                                            <option value="{{ $y->semester_id}}">
                                            {{ $y->semester}}</option>
                                        @endforeach
                                    </select>
                                </div>    
                                <label for="class_id" class="col-md-1 control-label">Student</label>
                                <div class="col-md-1">
                                   <input type="text" class="form-control" name="reg_no" id="reg_no" onchange="getName()">
                                </div>
                                <label class="col-md-2 control-label" id="full_name">Full Name</label>
                                
                                <button class="btn btn-default" type="button" id="get_student_result">Get</button>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-download fa-fw fa-fw"></i>Export
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li id="export-student-to-pdf"><a role="menuitem">
                                        	<i class="fa fa-file-pdf-o fa-fw fa-fw"></i>Display Results</a></li>
                                     	<li class="divider"></li>
                                        <li id="export-to-email"><a role="menuitem">
                                        	<i class="fa fa-envelope fa-fw fa-fw"></i>Email Results</a></li>
                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body table-responsive" id="result-student" style="overflow: auto">
                            	<table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th width="10%">Subject</th>
                                            <th width="7%">Score</th>
                                            <th width="10%">Class Highest</th>
                                            <th width="10%">Class Lowest</th>
                                            <th width="15%">Subject Highest</th>
                                            <th width="10%">Subject Lowest</th>
                                            <th width="10%">Class Position</th>
                                            <th width="13%">Subject Position</th>
                                            <th width="7%">Grade</th>
                                            <th width="8%">Remarks</th>
                                         </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="print_result">
                    <div class="row">
                        <div class="panel panel-default">
                        	<div class="form-group col-md-12">
                                <label for="prt_semester" class="col-md-1 control-label">Term</label>
                                <div class="col-md-2">
                                    <select class="form-control" name="prt_semester" id="prt_semester" style="width: 150px;">
                                        <option value="">Please Select</option>
                                        @foreach($semester as $key=>$y)
                                            <option value="{{ $y->semester_id}}">
                                            {{ $y->semester}}</option>
                                        @endforeach
                                    </select>
                                </div>    
                                <label for="prt_class_id" class="col-md-1 control-label">Class</label>
								<div class="col-md-2">
										<select class="form-control" name="prt_class_id" id="prt_class_id" 
                                        	onchange="getSection_prt()">
											<option value="">Please Select...</option>
											@foreach($class as $y)
												<option value="{{ $y->class_id}}">{{ $y->class_name }}</option>
											@endforeach
										</select>
								</div>
								<label for="prt_class_div_id" class="col-md-1 control-label">Section</label>
								<div class="col-md-2">
                                    <select class="form-control" name="prt_class_div_id" id="prt_class_div_id" >
                                       
                                    </select>
								</div>
                                    
                                <button class="btn btn-default" type="button" id="get_result_print">Get</button>
                                
                            </div>
                            <div class="panel-body table-responsive" style="overflow: auto">
                            	<table class="table" id="table-prt-result">
                                    <thead>
                                        <tr>
                                            <th>Reg. No</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Subjects</th>
                                            <th>Total Score</th>
                                            <th>Obtained</th>
                                            <th>Average</th>
                                            <th>Failed</th>
                                            <th>Passed</th>
                                            <th>Position</th>
                                            <th width="50px" colspan = "2">Action</th>
                                         </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="class_result">
                    <div class="row">
                        <div class="panel panel-default">
                        	<div class="form-group col-md-12">
                                <label for="semester_id5" class="col-md-1 control-label">Term</label>
                                <div class="col-md-2">
                                    <select class="form-control" name="semester_id5" id="semester_id5" style="width: 150px;">
                                        <option value="">Please Select</option>
                                        @foreach($semester as $key=>$y)
                                            <option value="{{ $y->semester_id}}">
                                            {{ $y->semester}}</option>
                                        @endforeach
                                    </select>
                                </div>    
                                <label for="result_class_id" class="col-md-1 control-label">Class</label>
                                <div class="col-md-2">
                               		<select class="form-control" name="result_class_id" id="result_class_id">
                                        <option value="">Please Select...</option>
                                        @foreach($class as $y)
                                            <option value="{{ $y->class_id}}">{{ $y->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="col-md-1 btn btn-default" type="button" id="get_class_result">Get</button>
                                
                                <div class="col-md-2">
                                	<button class="btn btn-default" type="button" id="export-result-to-pdf">
                                    	<i class="fa fa-download fa-fw fa-fw"></i>Generate Term Results Reports</button>
                                </div>
                                <div class="dropdown pull-right">
                                	<button class="btn btn-default" type="button" id="export-result-to-email">
                                    	<i class="fa fa-envelope fa-fw fa-fw"></i>Email All Results</button>
                                </div>
                            </div>
                            <div class="panel-body" id="class_result">
                            	<table class="table" id="table-display-class">
                                    <thead>
                                        <tr>
                                            <th>Reg. No</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Other Name</th>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th>Score Obtained</th>
                                            <th>Grade</th>
                                            <th>Remarks</th>
                                         </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--------------------->
                <div class="tab-pane fade in" id="distribution">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-distribution" enctype="multipart/form-data" return false>
                            <input type="hidden" name="promotion_id  " id="promotion_id" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="from-sequence" id="from-sequence" value="">
                            <input type="hidden" name="to-sequence" id="to-sequence" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="semester_id5" class="col-md-12 control-label">Term:</label>
                                    <div class="col-md-12">
                                        <select class="form-control" name="semester_id6" id="semester_id6" style="width: 130px;">
                                            <option value="">Please Select</option>
                                            @foreach($semester as $key=>$y)
                                                <option value="{{ $y->semester_id}}">{{ $y->semester}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="distribute_date" class="col-md-12 control-label">Date:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control text-right" id="distribute_date" name="distribute_date">
                                    </div>
                                
                                    <label for="class_from" class="col-md-12 control-label">From:</label>
                                    <div class="col-md-12">
                                        <select class="form-control" name="class_from" id="class_from" style="width: 130px;">
                                            <option value="">Please Select...</option>
                                            @foreach($class as $y)
                                                <option value="{{ $y->class_id}}">{{ $y->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                
                                    <label for="class_to" class="col-md-12 control-label">To:</label>
                                    <div class="col-md-12">
                                        <select class="form-control" name="class_to" id="class_to" style="width: 130px;">
                                            <option value="">Please Select...</option>
                                            @foreach($class as $y)
                                                <option value="{{ $y->class_id}}">{{ $y->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="class_to" class="col-md-12 control-label"></label>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary pull-right" id="btn-process">
                                            <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                    </div>
                                </div>
                                <div class="col-md-9 v-divider">
                                    <table class="table" id="table-distribution">
                                        <thead>
                                            <tr>
                                                <th width="5%">X</th>
                                                <th width="10%">Reg. No</th>
                                                <th width="15%">Last Name</th>
                                                <th width="15%">First Name</th>
                                                <th width="10%">Subject Passed</th>
                                                <th width="10%">Score</th>
                                                <th width="15%">Old Class</th>
                                                <th width="15%">New Class</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    	</div>
 	</div>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">
	$(function(){
		$('#promotion_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	$(function(){
		$('#distribute_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	$('#generate_score').on("click",function(e){
		e.preventDefault();
		var semester = $('#semester_id').val();
		if( semester !== ""){
			document.getElementById("generate_score").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
			
			//take a backup first before processing scores
			$.get("{{ route('createBackup') }}", function(data){
				$.ajax({
				  url: "{{ route('semesterScore')}}",
				  data: new FormData($("#frm-semester-score")[0]),
				  async:false,
				  type:'post',
				  processData: false,
				  contentType: false,
				  success:function(data){
						if(Object.keys(data).length > 0){
							alert('Successful operation');
						}
						document.getElementById("generate_score").innerHTML = 'Generate Exam Scores';
					},
					error: OnError
				});
			});
		}
	});
	
	function getSection(){
		//this event displays the sections for the selected class
		
		var class_id = $('#class_id').val();
		//generate sections upon class change
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id').empty();
			$('#class_div_id').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#class_div_id').append(row_data);
				});
			}else{
				alert('No record to display...');
			}
			$('#pleaseWaitDialog').modal('hide');
		});
	}
	function studentScore(){
		//add the teachers comments to student score
		
		var semester_id = $('#semester_id3').val();
		var class_div_id = $('#class_div_id').val();
		if(  class_div_id !== "" && semester_id !== ""){
			$('#pleaseWaitDialog').modal();
			try{
				$.get("{{ route('getStudentScore') }}", {semester_id: semester_id, class_div_id: class_div_id}, function(data){
					$('#table-student tbody').empty();
					if (data.length > 0) {
						$.each(data, function(i,l){
							//empty the table
							var comment = l.comment;
							if(comment == null || comment == 'null') comment = "";
							//this is to make laravel read the reg_no
							var row_data = '<tr>' +
								'<td><input type="text" class="form-control reg_no" readonly ="readonly" name="reg_no[]" value="' + l.reg_no + '" style="border:none;background-color:transparent;" ></td>'+
								'<td>' + l.last_name +'</td>'+
								'<td>' + l.first_name +'</td>'+
								'<td>' + l.score_total +'</td>'+
								'<td>' + l.score_obtained +'</td>'+
								'<td>' + l.average_score +'</td>'+
								'<td>' + l.no_subject +'</td>'+
								'<td>' + l.subject_passed +'</td>' +
								'<td>' + l.subject_failed  +'</td>' +
								'<td><textarea rows="2" class="form-control" name="remarks[]">' + comment  + '</textarea></td>' +
								'</tr>';
													
							$('#table-student tbody:last-child').append(row_data);
						});
					}else{
						alert('No record to display...');	
					}
					$('#pleaseWaitDialog').modal('hide');
				});
			}catch(err){ alert(err.message); }
		}
	}
	function getName(){
		var reg_no = $('#reg_no').val();
		//generate sections upon class change
		$.get("{{ route('getFullName')}}", {reg_no: reg_no}, function(data){
			$('#full_name').text(data);
		});
		
	}
	$('#get_class_result').on('click', function(e){
		e.preventDefault();
		classResult();
		
	});
	function classResult(){
		//result_class_id
		//semester_id5
		
		var semester_id = $('#semester_id5').val();
		if(semester_id == null || semester_id == ""){
			alert('You have to pick a school academic term');
			return;	
		}
		var class_id = $('#result_class_id').val();
		try{
			document.getElementById("get_class_result").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
			
			$.get("{{ route('getClassScore') }}", {semester_id: semester_id, class_id: class_id}, function(data){
				var table = $('#table-display-class').DataTable();
				table.clear();
				table.destroy();
				$('#table-display-class tbody').empty();
				
				if (data.length > 0) {
					$.each(data, function(i,l){
						//empty the table
						var other_name = l.other_name;
						if( other_name == null || other_name == 'null' || other_name == '-') other_name = "";
						var row_data = '<tr>' +
							'<td>' + l.reg_no +'</td>'+
							'<td>' + l.last_name +'</td>'+
							'<td>' + l.first_name +'</td>'+
							'<td>' + other_name +'</td>'+
							'<td>' + l.class_div +'</td>'+
							'<td>' + l.subject +'</td>'+
							'<td>' + l.exam_score +'</td>' +
							'<td>' + l.score_grade  +'</td>' +
							'<td>' + l.remarks +'</td>' +
							'</tr>';
						$('#table-display-class tbody:last-child').append(row_data);
					});
					$('#table-display-class').DataTable();
				}else{
					alert('No record to display...');	
				}
				document.getElementById("get_class_result").innerHTML = 'Get';
			});
		}catch(err){ alert(err.message); }
	}
	$('#get_student_result').on('click', function(e){
		e.preventDefault();
		studentResult();
		
	});
	function studentResult(){
		var reg_no = $('#reg_no').val();
		var semester_id = $('#semester_id4').val();
		
		if( reg_no !== "" && semester_id !== ""){
			document.getElementById("get_student_result").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
			try{
				$.get("{{ route('getStudentResult') }}", {reg_no: reg_no, semester_id: semester_id}, function(data){
					$('#result-student tbody').empty();
					if (data.length > 0) {
						$.each(data, function(i,l){
							//empty the table
							var row_data = '<tr>' +
								'<td>' + l.subject +'</td>'+
								'<td>' + l.exam_score +'</td>' +
								'<td>' + l.class_highest  +'</td>' +
								'<td>' + l.class_lowest +'</td>' +
								'<td>' + l.subject_highest  +'</td>' +
								'<td>' + l.subject_lowest +'</td>' +
								'<td>' + l.class_position  +'</td>' +
								'<td>' + l.subject_position +'</td>' +
								'<td>' + l.score_grade  +'</td>' +
								'<td>' + l.remarks +'</td>' +
								'</tr>';
							$('#result-student tbody:last-child').append(row_data);
						});
					}else{
						alert('No record to display...');	
					}
					document.getElementById("get_student_result").innerHTML = 'Get';
				});
			}catch(err){ alert(err.message); }
		}
	}
	$(document).on('click', '#export-to-email', function(e){
		e.preventDefault();
		var reg_no = $('#reg_no').val();
		var semester_id = $('#semester_id4').val();
		sendEmailReport(reg_no, semester_id);
		
	});
	//this is just for one student. but get the report name from the controller
	$(document).on('click', '#export-student-to-pdf', function(e){
		e.preventDefault();
		var reg_no = $('#reg_no').val();
		var semester_id = $('#semester_id4').val();
		getPDFReport(reg_no, semester_id);
	});
	function getPDFReport(reg_no, semester_id){
		if( reg_no !== "" && semester_id !== ""){
			//$('#pleaseWaitDialog').modal();
			//$.get("{{ route('pdfResult') }}", {reg_no: reg_no, semester_id: semester_id}, function(data){
				//$('#pleaseWaitDialog').modal('hide');
				
				var path = "{{url('/')}}";
				path = path + '/reports/pdf/results/';
				//path = path + data;
				openInNewTab(path);
			//});
		}else{
			alert('Nothing was selected');	
		}
	}
	function sendEmailReport(reg_no, semester_id){
		if( reg_no !== "" && semester_id !== ""){
			
			$.get("{{ route('emailResult') }}", {reg_no: reg_no, semester_id: semester_id}, function(data){
				
				//alert(data);
				
				//var path = "{{url('/')}}";
				//path = path + '/reports/pdf/results/';
				//path = path + data;
				//openInNewTab(path);
			});
		}else{
			alert('Nothing was selected');	
		}
	}
	//this generates for the whole class and NOT just one student
	$(document).on('click', '#export-result-to-pdf', function(e){
		e.preventDefault();
		
		var semester_id = $('#semester_id5').val();
		if( semester_id == "" || semester_id == null){
			alert('The academic term cannot be empty');
			return;
		}else{
			$('#pleaseWaitDialog').modal();
			$.get("{{ route('listEnrolStudents') }}", {}, function(data){
				//return student records
				$.each(data, function(i,l){
					var reg_no = l.reg_no;
					
					$.get("{{ route('pdfResult') }}", {reg_no: reg_no, semester_id: semester_id}, function(data){
						
					});
				});
				$('#pleaseWaitDialog').modal('hide');
				alert('results generated...');
			});
		}
	});
	$(document).on('click', '#export-result-to-email', function(e){
		e.preventDefault();
		
		var semester_id = $('#semester_id5').val();
		if( semester_id == "" || semester_id == null){
			alert('The academic term cannot be empty');
			return;
		}else{
			$('#pleaseWaitDialog').modal();
			$.get("{{ route('listEnrolStudents') }}", {}, function(data){
				//return student records
				$.each(data, function(i,l){
					var reg_no = l.reg_no;
					sendEmailReport(reg_no, semester_id);
				});
				$('#pleaseWaitDialog').modal('hide');
				alert('results generated...');
			});
		}
	});
	
	$(document).on('click', '#teacher_update', function(e){
		e.preventDefault();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to update this record?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				//update the students performance with their teachers remarks
				if(result) {
					document.getElementById("teacher_update").innerHTML = 
						'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
					$('#pleaseWaitDialog').modal();
					$.ajax({
					  url: "{{ route('semesterTeacher')}}",
					  data: new FormData($("#frm-semester-teacher")[0]),
					  async:false,
					  type:'post',
					  processData: false,
					  contentType: false,
					  success:function(data){
							if(Object.keys(data).length > 0){
								alert('Update successful');
								$('#table-student tbody').empty();
							}else{
								alert('Update NOT successful');
							}
							document.getElementById("teacher_update").innerHTML = 
								'<i class="fa fa-save fa-fw fa-fw"></i>Save';
				
							$('#pleaseWaitDialog').modal('hide');
						},
						error: OnError
					});
				}
			}
		});
	});
	$(document).on('click', '#btn-generate', function(e){
		e.preventDefault();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to update this record?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				
				if(result) {
					var isvalid = validatePromotion();
					if (isvalid){
						document.getElementById("btn-generate").innerHTML = 
							'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
						
						$('#pleaseWaitDialog').modal();
						//check maximum score here
						var promotion_date = $('#promotion_date').val();
						$('#promotion_date').val(toDbaseDate(promotion_date));
						//all need to be checked
						//this promotion has been changed to determine overall assessment: passed or failed based on the criteria
						$('.class_id').prop('checked', true);
						$.ajax({
						  url: "{{ route('semesterPromotion')}}",
						  data: new FormData($("#frm-create-promotion")[0]),
						  async:false,
						  type:'post',
						  processData: false,
						  contentType: false,
						  success:function(data){
								if(Object.keys(data).length > 0){
									alert('Update successful');
								}else{
									alert('Update NOT successful');
								}
								document.getElementById("btn-generate").innerHTML = 
									'<i class="fa fa-save fa-fw fa-fw"></i>Generate';
				
								$('#pleaseWaitDialog').modal('hide');
							},
							error: OnError
						});
						$('#promotion_date').val(promotion_date);
					}
				}
			}
		});
	});
	//this has been changed to grand assessment: Passed or Failed
	function validatePromotion() {
		var reason = "";
		try{
			reason += validate_select(document.getElementById("semester_id1"));
			reason += validate_date(document.getElementById("promotion_date"));
			
			$('#table-promotion tbody tr').each(function(){
								
				var subject = $(this).closest('tr').find('input.subjects').val();
				var score = $(this).closest('tr').find('input.score').val();
				var average = $(this).closest('tr').find('input.average').val();
				
				subject = parseFloat(convertToNumbers(subject));
				score = parseFloat(convertToNumbers(score));
				average = parseFloat(convertToNumbers(average));
				
				if(isNaN(score) === true) score = 0.00;
				if(isNaN(subject) === true) subject = 0.00;
				if(isNaN(average) === true) average = 0.00;
				if( score > 0 && subject > 0 && average > 0) reason += "You only need to define just one NOT all \n";
				
			});
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$('#class_to').on('change', function(e){
		e.preventDefault();
		
		//get all the promoted students for this class and populate the table
		//get the class sequence: from-sequence and to-sequence
		var class_id = $('#class_from').val();
		$.get("{{ route('getClassSequence') }}", {class_id: class_id}, function(data){
			$('#from-sequence').val(data);
		});
		class_id = $('#class_to').val();
		$.get("{{ route('getClassSequence') }}", {class_id: class_id}, function(data){
			$('#to-sequence').val(data);
		});
		var semester_id = $('#semester_id6').val();
		var my_class_id = $('#class_from').val();
		
		try{
			$('#pleaseWaitDialog').modal();
			$.get("{{ route('getPromotedStudents') }}", { class_id: my_class_id, semester_id: semester_id}, function(data){
				$('#table-distribution tbody').empty();
				
				var class_id = $('#class_to').val();
				$.get("{{ route('getClassSection') }}", {class_id: class_id}, function(section){
					
					var html = '<select class="form-control new_class" name="new_class[]">';
					$.each(section, function(i,l){
						var div_id = l.class_div_id;
						var div_name = l.class_div;
						html = html + "<option value='" + div_id + "'>" + div_name + "</option>";
					});
					html = html + '</select>';
					
					if (data.length > 0) {
						$.each(data, function(i,l){
							
							var row_data = '<tr>' +
								'<td><div><input type="checkbox" class="checkbox vert-align" name="student_id[]" value=' + l.student_id + '></div></td>'+
								'<td>' + l.reg_no +'</td>' +
								'<td>' + l.last_name  +'</td>' +
								'<td>' + l.first_name +'</td>' +
								'<td>' + l.subject_passed  +'</td>' +
								'<td>' + l.total_score +'</td>' +
								'<td><input type="text" class="form-control old_class" readonly ="readonly" name="old_class[]" value="' + l.class_div + '"style="border:none;background-color:transparent;" ></td>'+
								'<td>' + html +'</td>' +
								'</tr>';
							$('#table-distribution tbody:last-child').append(row_data);
						});
					}else{
						alert('No record to display...');	
					}
					$('#pleaseWaitDialog').modal('hide');
				});
			});
		}catch(err){ alert(err.message); }
	});
	$(function(){
		$("#frm-create-distribution").on('submit', function(e){
			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to distribute students?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						var is_valid = validateDistribution();
						if (is_valid){
							
							var distribute_date = $('#distribute_date').val();
							$('#distribute_date').val(toDbaseDate(distribute_date));
							
							$('#pleaseWaitDialog').modal();
							document.getElementById("btn-process").innerHTML = 
								'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
							
							$.ajax({
							  url: "{{ route('semesterDistribution')}}",
							  data: new FormData($("#frm-create-distribution")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										$('#frm-create-distribution')[0].reset();
										$('#table-distribution tbody').empty();
										alert('Update successful');
									}else{
										alert('Update NOT successful\n Note that the term should be the last in the academic year');
									}
									$("#btn-process").attr("disabled", false);
									$('#pleaseWaitDialog').modal('hide');
									document.getElementById("btn-process").innerHTML = 
										'<i class="fa fa-save fa-fw fa-fw"></i>Save';
								},
								error: OnError
							});
							$('#distribute_date').val( distribute_date );
						}
					}
				}
			});
		});
	});
	function validateDistribution() {
		var reason = "";
		try{
			reason += validate_date(document.getElementById("distribute_date"));
			reason += validate_select(document.getElementById("class_from"));
			reason += validate_select(document.getElementById("class_to"));
			reason += validate_select(document.getElementById("semester_id6"));
			
			var sequence_from = $('#from-sequence').val();
			var sequence_to = $('#to-sequence').val();
			
			if( sequence_to <= sequence_from) reason += "The new class should not be less than or same as the old class \n";
			
			if( isafterDate($('#distribute_date').val()) == true) reason += "Promotion Date cannot be after current date \n";
			if( $('#class_from').val() ==  $('#class_to').val()) reason += "No Promotion: Same class selected \n";
			
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	////////////////////////////////////////////this takes car of assessment
	function getSection2(){
		//get the section based on the class and also the assessment criteria defined for that class
		
		var class_id = $('#class_id_comment').val();
		//generate sections upon class change
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_id_comment').empty();
			
			$('#class_div_id_comment').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#class_div_id_comment').append(row_data);
				});
			}else{
				alert('No record to display...');
			}
			$('#pleaseWaitDialog').modal('hide');
		});
		//now fill the assessment criteria
		
		$.get("{{ route('getClassAssessment')}}", {class_id: class_id}, function(data){
			$('#assessment_id').empty();
			$('#assessment_id').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.assessment_id +'">' + l.assessment + '</option>';
					$('#assessment_id').append(row_data);
				});
			}else{
				alert('No record to display...');
			}
		});
	}
	function studentPara(){
		var class_id = $('#class_id_comment').val();
		var class_div_id = $('#class_div_id_comment').val();
		var assessment_id = $('#assessment_id').val();
		var semester_id = $('#semester_id_comment').val();
		
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('getStudentAssessment')}}", {class_id: class_id, 
			class_div_id: class_div_id, assessment_id: assessment_id, semester_id: semester_id}, function(data){
			
			$('#show-assessment').empty().append(data);
			$('#pleaseWaitDialog').modal('hide');
		});
	}
	$('#comment_update').on("click",function(e){
		e.preventDefault();
		
		var class_id = $('#class_id_comment').val();
		var class_div_id = $('#class_div_id_comment').val();
		var assessment_id = $('#assessment_id').val();
		var semester_id = $('#semester_id_comment').val();
		
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to update this record?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				//update the students performance with their teachers remarks
				if(result) {
					document.getElementById("comment_update").innerHTML = 
						'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
					$.ajax({
					  url: "{{ route('updateStudentAssessment')}}",
					  data: new FormData($("#frm-semester-comment")[0]),
					  async:false,
					  type:'post',
					  processData: false,
					  contentType: false,
					  success:function(data){
							/*if(Object.keys(data).length > 0){
								alert('Update successful');
								$('#table-student tbody').empty();
							}else{
								alert('Update NOT successful');
							}*/
							alert('Update successful');
							document.getElementById("comment_update").innerHTML = 
								'<i class="fa fa-save fa-fw fa-fw"></i>Save';
						},
						error: OnError
					});
				}
			}
		});
	});
	function getSection_prt(){
		
		var class_id = $('#prt_class_id').val();
		//generate sections upon class change
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#prt_class_div_id').empty();
			$('#prt_class_div_id').append('<option value="">Please Select...</option>');
			
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#prt_class_div_id').append(row_data);
				});
			}else{
				alert('No record to display...');
			}
			$('#pleaseWaitDialog').modal('hide');
		});	
	}
	$(document).on('click', '.prt-to-email', function(e){
		e.preventDefault();
		
		var reg_no = $(this).val();
		var semester_id = $('#prt_semester').val();
		sendEmailReport(reg_no, semester_id);
		
	});
	//this is just for one student. but get the report name from the controller
	$(document).on('click', '.prt-to-pdf', function(e){
		e.preventDefault();
		
		var reg_no = $(this).val();
		var semester_id = $('#prt_semester').val();
		getPDFReport(reg_no, semester_id);
	});
	$('#get_result_print').on("click",function(e){
		e.preventDefault();
		
		var class_div_id = $('#prt_class_div_id').val();
		var semester_id = $('#prt_semester').val();
		
		if( class_div_id !== "" && class_div_id !== null && semester_id !== "" && semester_id !== null){
			try{
				document.getElementById("get_result_print").innerHTML = 
					'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
				$.get("{{ route('prtStudentScore')}}", {
						class_div_id: class_div_id, semester_id: semester_id}, function(data){
					
					$('#table-prt-result tbody').empty();
					if (data.length > 0) {
						$.each(data, function(i,l){
							//empty the table
							var row_data = '<tr>' +
								'<td>' + l.reg_no +'</td>'+
								'<td>' + l.last_name +'</td>' +
								'<td>' + l.first_name  +'</td>' +
								'<td>' + l.no_subject +'</td>' +
								'<td>' + l.score_total  +'</td>' +
								'<td>' + l.score_obtained +'</td>' +
								'<td>' + l.average_score  +'</td>' +
								'<td>' + l.subject_failed +'</td>' +
								'<td>' + l.subject_passed  +'</td>' +
								'<td>' + l.class_position +'</td>' +
								'<td style="vertical-align: middle; width: 25px;"><button class="btn btn-sm prt-to-pdf" value="'+ l.reg_no + '" ><i class="fa fa-print fa-lg"></i></button></td>'+
								'<td style="vertical-align: middle; width: 25px;"><button class="btn btn-sm prt-to-email" value="'+ l.reg_no + '" ><i class="fa fa-envelope fa-lg"></i></button></td>'+
								'</tr>';
							$('#table-prt-result tbody:last-child').append(row_data);
						});
					}else{
						alert('No record to display...');	
					}
					document.getElementById("get_result_print").innerHTML = 'Get';
				});
			}catch(err){ alert(err.message); }
		}else{
			alert('Make valid selections...');
		}
	});
</script>
@endsection