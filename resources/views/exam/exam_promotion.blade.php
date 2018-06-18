@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Exam</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getExamPromotion')}}">Promotion</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#input" data-toggle="tab" class="nav-tab-class">Promotion</a></li>
                <li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Promotion</a></li>
            </ul>
        
            <div class="tab-content">
                <div class="tab-pane fade in active" id="input">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-promotion" enctype="multipart/form-data" return false>
                            <input type="hidden" name="promotion_id  " id="promotion_id" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                             <div class="row">
                                <div class="col-md-5">
                                
                                    <div class="form-group col-md-12">
                                        <label for="concept" class="col-md-6 control-label">Date</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="promotion_date" name="promotion_date">
                                        </div>
                                    </div>
                                     <div class="form-group col-md-12">
                                        <label for="semester_id" class="col-md-6 control-label">Term</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="semester_id" id="semester_id" style="width: 150px;">
                                                <option value="">Please Select</option>
                                                @foreach($semester as $key=>$y)
                                                    <option value="{{ $y->semester_id}}">
                                                    {{ $y->semester}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="passed_subjects" class="col-md-6 control-label">Passed Subjects</label>
                                        <div class="col-md-6">
                                        	<input type="text" class= "form-control passed_subjects text-right" 
                                            	name="passed_subjects" id="passed_subjects" onkeyup="return allow_number(this)">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="passed_score" class="col-md-6 control-label">Passed Score</label>
                                        <div class="col-md-6">
                                        	<input type="text" class= "form-control passed_scores text-right" 
                                            	name="passed_score" id="passed_score" onkeyup="return allow_number(this)">
                                        </div>
                                    </div>
                                   	<label for="concept" class="col-md-12" style="font-weight:900; font-size:larger">CLASS GROUP:</label>
                                    <div class="form-group col-md-12">
                                        <label for="class_id_from" class="col-md-3 control-label">Class</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="class_id_from" id="class_id_from" onchange="classChange()">
                                                <option value="">Please Select...</option>
                                                @foreach($class as $y)
                                                    <option value="{{ $y->class_id}}">
                                                    {{ $y->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="class_from" class="col-md-3 control-label">Section</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="class_from" id="class_from" style="width: 200px;">
                                                <option value="">Please Select...</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <label for="concept" class="col-md-12" style="font-weight:900; font-size:larger;">Promoted To:</label>
                                    <div class="form-group col-md-12">
                                        <label for="class_id_to" class="col-md-3 control-label">Class</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="class_id_to" id="class_id_to" onchange="classChange2()">
                                                <option value="">Please Select...</option>
                                                @foreach($class as $y)
                                                    <option value="{{ $y->class_id}}">
                                                    {{ $y->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="class_to" class="col-md-3 control-label">Section</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="class_to" id="class_to" style="width: 200px;">
                                                <option value="">Please Select...</option>
                                                
                                            </select>
                                        </div>
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
                                <!--------------another column---------------->
                                <div class="col-md-7 v-divider">
                                
                                    <div class="form-group">
                                        <div class="col-md-1">
                                            <input type="text" style="border:none !important; background-color:transparent !important;"
                                            class="form-control" id="" name="" disabled width="1">
                                        </div>
                                    </div>
                                    <!------list all those who have not enrolled-------->
                                
                                    <table class="table" id="table_from">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select" value="1" title="Select All"/></th>
                                                <th>Reg. No</th>
                                                <th>Last Name</th>
                                                <th>First Name</th>
                                                <th>Other Name</th>
                                                <th>Score</th>
                                                <th>Subjects</th>
                                             </tr>
                                        </thead>
                                        <tbody id="table-body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        		<div class="tab-pane fade" id="view">
                    <div class="panel panel-body" style="margin-top:5px;">
                    	<div class="col-md-5 pull-left">
                            <div class="input-group">
                                
                                <input type="text" class="form-control" name ="promotion-input" id= "promotion-input" 
                                	placeholder="31/12/2017 30/09/2018">
                                <div class="input-group-btn search-panel">
                                    <button type="button" id="promotion-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span id="search_concept">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body" id="show-info">
                            
                            </div>
                        </div>
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
	
	function classChange2(){
		//empty table section whenever there is a change in the class
		$('#class_to').empty();
		var class_id = $('#class_id_to').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_to').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#class_to').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	}
	
	$(function(){
		$("#frm-create-promotion").on('submit', function(e){
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
							
							var table_cnt = 0;
							$("#table_from tbody tr td:nth-child(1) input").each(function() {
								var checked = $(this).is(":checked");
								if( checked == true) {
									table_cnt = table_cnt + 1;
								}
							});
							if ( table_cnt > 0){
								//remove the unticked classes before proceeding to eloquent for update. At least one should be ticked
								/*$("#table_from tbody tr td:nth-child(1) input").each(function() {
									var checked = $(this).is(":checked");
									if( checked == false) {
										$(this).closest('tr').remove();
									}
								});*/
								var promotion_date = $('#promotion_date').val();
								$('#promotion_date').val(toDbaseDate(promotion_date));
								
								$('#pleaseWaitDialog').modal();
								//there is a dupilcation of route: likely replaced by another form
								$.ajax({
								  url: "{{ route('semesterPromotion')}}", 
								  data: new FormData($("#frm-create-promotion")[0]),
								  async:false,
								  type:'post',
								  processData: false,
								  contentType: false,
								  success:function(data){
										if(Object.keys(data).length > 0){
											$('#frm-create-promotion')[0].reset();
											$('#table_from tbody').empty();
											alert('Update successful');
										}else{
											alert('Update NOT successful');
										}
										$('#pleaseWaitDialog').modal('hide');
									},
									error: OnError
								});
								$('#promotion_date').val(promotion_date);
							}else{
								alert('No record was checked');	
							}
						}
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_date(document.getElementById("promotion_date"));
			reason += validate_select(document.getElementById("class_id_from"));
			reason += validate_select(document.getElementById("class_id_to"));
			reason += validate_select(document.getElementById("class_from"));
			reason += validate_select(document.getElementById("class_to"));
			reason += validate_select(document.getElementById("remarks"));
			if( isafterDate($('#promotion_date').val()) == true) reason += "Promotion Date cannot be after current date \n";
			
			if( $('#class_id_from').val() ==  $('#class_id_to').val()) reason += "No Promotion: Same class selected \n";
			
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$('#class_from').on('change', function(){
		//load the respective student enrolled for this class div
		$('#table_from tbody').empty();
		var class_div_id = $('#class_from').val();
		$.get("{{ route('getDivStudents') }}", {class_div_id: class_div_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					//empty the table
					var other_name = l.other_name;
					if( other_name == null || other_name == 'null' || other_name == '-') other_name = "";
					
					var row_data = '<tr>' +
						'<td><div><input type="checkbox" class="checkbox vert-align" name="students[]" value="' + l.student_id +'" ></div></td>' +
						'<td>' + l.reg_no +'</td>'+
						'<td>' + l.last_name +'</td>'+
						'<td>' + l.first_name +'</td>'+
						'<td>' + other_name +'</td>'+
						'</tr>';
					$('#table_from tbody:last-child').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	})
	$(document).on('click', '#promotion-search', function(e){
		e.preventDefault();
		//extract the dates from the string
		var dates = $('#promotion-input').val();
		//extract start date and end date
		var end_date = dates.slice(-10);
		var start_date = dates.slice(0,10);
		var reason = "";
		reason += validate_date_val(start_date);
		reason += validate_date_val(end_date);
		if(reason == ""){
			start_date = toDbaseDate(start_date);
			end_date = toDbaseDate(end_date);
			$('#pleaseWaitDialog').modal();
			var table = $('#table-class-info').DataTable();
			table.clear();
			table.destroy();
			$.get("{{ route('searchExamPromotion') }}", {start_date: start_date, end_date: end_date}, function(data){
				
				$('#show-info').empty().append(data);
				$('#table-class-info').DataTable();
				$('#pleaseWaitDialog').modal('hide');
			});
		}
	});
	
	
</script>
@endsection