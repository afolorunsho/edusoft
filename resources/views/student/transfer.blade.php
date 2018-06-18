@extends('layouts.master')
@section('content')

	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Student</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getTransfer')}}">Transfer</a></li>
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
                        <form action="" method="POST" id="frm-create-transfer" enctype="multipart/form-data" return false>
                            <input type="hidden" name="transfer_id  " id="transfer_id" value=""> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                             <div class="row">
                                <div class="col-md-5">
                                
                                    <div class="form-group col-md-12">
                                        <label for="concept" class="col-md-3 control-label">Date</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="transfer_date" name="transfer_date">
                                        </div>
                                    </div>
                                    <label for="concept" class="col-md-12" style="font-weight:200; font-size:larger;">&nbsp;</label>
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
                                    <label for="concept" class="col-md-12" style="font-weight:200; font-size:larger;">&nbsp;</label>
                                    <label for="concept" class="col-md-12" style="font-weight:900; font-size:larger">From:</label>
                                    <div class="form-group col-md-12">
                                        <label for="class_from" class="col-md-3 control-label">Section</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="class_from" id="class_from" style="width: 200px;">
                                                <option value="">Please Select...</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <label for="concept" class="col-md-12" style="font-weight:900; font-size:larger;">To:</label>
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
                                
                                <input type="text" class="form-control" name ="transfer-input" id= "transfer-input" 
                                	placeholder="31/12/2017 30/09/2018">
                                <div class="input-group-btn search-panel">
                                    <button type="button" id="transfer-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
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
								var transfer_date = $('#transfer_date').val();
								$('#transfer_date').val(toDbaseDate(transfer_date));
								
								$('#pleaseWaitDialog').modal();
								$.ajax({
								  url: "{{ route('updateTransfer')}}",
								  data: new FormData($("#frm-create-transfer")[0]),
								  async:false,
								  type:'post',
								  processData: false,
								  contentType: false,
								  success:function(data){
										if(Object.keys(data).length > 0){
											$('#frm-create-transfer')[0].reset();
											$('#table_from tbody').empty();
											alert('Update successful');
										}else{
											alert('Update NOT successful');
										}
										$('#pleaseWaitDialog').modal('hide');
									},
									error: OnError
								});
								$('#transfer_date').val(transfer_date);
							}else{
								alert('No record was checked');	
							}
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
			reason += validate_select(document.getElementById("class_to"));
			if( isafterDate($('#transfer_date').val()) == true) reason += "Transfer Date cannot be after current date \n";
			
			if( $('#class_from').val() ==  $('#class_to').val()) reason += "No Transfer: Same class selected \n";
			
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
				if( other_name == null || other_name == 'null' || other_name == '-') other_name = "";
				//empty the table
				var row_data = '<tr>' +
					'<td><div><input type="checkbox" class="checkbox vert-align" name="students[]" value="' + l.student_id +'" ></div></td>' +
					'<td>' + l.reg_no +'</td>'+
					'<td>' + l.last_name +'</td>'+
					'<td>' + l.first_name +'</td>'+
					'<td>' + other_name +'</td>'+
					'</tr>';
				$('#table_from tbody:last-child').append(row_data);
			});
		});
	})
	$(document).on('click', '#transfer-search', function(e){
		e.preventDefault();
		//extract the dates from the string
		var dates = $('#transfer-input').val();
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
			$.get("{{ route('searchTransfer') }}", {start_date: start_date, end_date: end_date}, function(data){
				$('#show-info').empty().append(data);
				$('#table-class-info').DataTable();
				$('#pleaseWaitDialog').modal('hide');
				
			});
		}
	});
	
</script>
@endsection