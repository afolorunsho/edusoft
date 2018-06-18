@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Student</li>
                <li><i class="fa fa-file-text-o"></i>Transfer</li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#input" data-toggle="tab" class="nav-tab-class">Transfer</a></li>
                <li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Transfer</a></li>
            </ul>
        
            <div class="tab-content">
                <div class="tab-pane fade in active" id="input">
                	<div class="panel panel-body">
                	<form action="" method="POST" id="frm-create-subject" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="transfer_id  " id="transfer_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                         <div class="row">
                     		<div class="col-md-6">
                            
                                <div class="form-group">
                                    <label for="concept" class="col-md-3 control-label">Date</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="transfer_date" name="transfer_date">
                                    </div>
                                </div>
                                <label for="concept" class="col-md-12" style="font-weight:900; font-size:larger">From:</label>
                                <div class="form-group">
                                    <label for="class_id_from" class="col-md-3 control-label">Class</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="class_id_from" id="class_id_from" onchange="classChange()">
                                            <option value="">Please Select...</option>
                                            @foreach($class as $y)
                                                <option value="{{ $y->class_id}}">
                                                {{ $y->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="class_from" class="col-md-3 control-label">Section</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="class_from" id="class_from" style="width: 200px;">
                                            <option value="">Please Select...</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group pull-right">
                                    <button class="btn" type="button"><span class="fa fa-forward fa-2x"></span></button>
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
                                             </tr>
                                        </thead>
                                        <tbody id="table-body">
                                        </tbody>
                                    </table>
                        	</div>
                            <!--------------another column---------------->
                            <div class="col-md-6 v-divider">
                            
                                <div class="form-group">
                                	<div class="col-md-1">
                                    	<input type="text" style="border:none !important; background-color:transparent !important;"
                                    	class="form-control" id="" name="" disabled width="1">
                                  	</div>
                                </div>
                                <label for="concept" class="col-md-12" style="font-weight:900; font-size:larger;">To:</label>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Class</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="class_id_to" name="class_id_to" disabled>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="class_to" class="col-md-3 control-label">Section</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="class_to" id="class_to" style="width: 200px;">
                                            <option value="">Please Select...</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group pull-left">
                                    <button class="btn" type="button"><span class="fa fa-backward fa-2x"></span></button>
                                </div>
                                <!------list all those who have not enrolled-------->
                                
                                    <table class="table" id="table_to">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select" value="1" title="Select All"/></th>
                                                <th>Reg. No</th>
                                                <th>Last Name</th>
                                                <th>First Name</th>
                                                <th>Other Name</th>
                                             </tr>
                                        </thead>
                                        <tbody id="table-body">
                                        </tbody>
                                    </table>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                          <div class="pull-right">
                                            <button type="submit" class="btn btn-primary" id="btn-save">
                                                <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                          </div>
                                        </div>
                                    </div>
                                    
                        	</div>
                    	</div>
                  	</form>
                    </div>
                </div>
        		<div class="tab-pane fade" id="view">
                    <div class="panel panel-body" style="margin-top:5px;">
                    	<div class="col-md-5 pull-left">
                            <div class="form-group">
                                <input type="text" class="form-control" id="lastname" placeholder="Date Period(01/12/2007 31/12/2007)">
                            </div>
                        </div>
                        <div class="col-md-7 pull-right">
                            <div class="input-group">
                                <div class="input-group-btn search-panel">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span id="search_concept">Search Criteria</span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                      <li><a href="#search_last">From Class</a></li>
                                      <li><a href="#search_last">To Class</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="search_param" value="all" id="search_param">         
                                <input type="text" class="form-control" name="x" placeholder="Search value...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><span class="fa fa-search-plus"></span></button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><span class="fa fa-print"></span></button>
                                </span>
                            </div>
                        </div>
                        
                        <!---the action should allow for print a single record and delete, if not enrolled(enrol=0)---->
                  		<div class="form-group">
                      		<table class="table table-hover" id="view-table">
                         		<thead>
                          			<tr>
                                    	<th>Date</th>
                                        <th>Class</th>
                                        <th>Arrival Time</th>
                                        <th>Remarks</th>
                               		 </tr>
                              	</thead>
                              	<tbody id="table-body">
                                </tbody>
                        	</table>
                 		</div>
                    </div>
                </div>
            </div>
    	</div>
 	</div>
@endsection
@section('scripts')
<script type="text/javascript">
	
	$(function(){
		$('#transfer_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	function classChange(){
		//empty table section whenever there is a change in the class
		$('#class_from').empty();
		$('#class_to').empty();
		var class_id = $('#class_id_from').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_from').append('<option value="">Please Select...</option>');
			$('#class_to').append('<option value="">Please Select...</option>');
			
			$.each(data, function(i,l){
				var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
				$('#class_from').append(row_data);
				var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
				$('#class_to').append(row_data);
			});
		});
	}
	
	$(function(){
		$("#frm-create-transfer").on('submit', function(e){
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
							
							var transfer_date = $('#transfer_date').val();
							$('#transfer_date').val(toDbaseDate(transfer_date));
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: '/student/transfer/update',
							  data: new FormData($("#frm-create-transfer")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
								  	$('#pleaseWaitDialog').modal('hide');
								  	if(Object.keys(data).length > 0){
										
										$('#frm-create-transfer')[0].reset();
										$('#table_to tbody').empty();
										$('#table_from tbody').empty();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									
								},
								error: OnError
							});
							$('#transfer_date').val(transfer_date);
						}else{
							
						}
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_date(document.getElementById("transfer_date"));
			reason += validate_select(document.getElementById("class_id_from"));
			reason += validate_select(document.getElementById("class_from"));
			if( isafterDate($('#transfer_date').val()) == true) reason += "Transfer Date cannot be after current date \n";
			
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
			$.each(data, function(i,l){
				var other_name = l.other_name;
				if( other_name == null || other_name == 'null') other_name = "";
				//empty the table
				var row_data = '<tr>' +
					'<td>' + l.reg_no +'</td>'+
					'<td>' + l.last_name +'</td>'+
					'<td>' + l.first_name +'</td>'+
					'<td>' + other_name +'</td>'+
					'<td>' + l.enrol_date +'</td>'+
					'<td><img src= "' + photo_image + '" width="35" height="35" /></td>' +
					'</tr>';
				$('#table_from tbody:last-child').append(row_data);
			});
			//"{{url('photo/student'.'/'.$c->photo)}}"
		});
	})
	
</script>
@endsection