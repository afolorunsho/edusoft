@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Fees</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getFeeRefund')}}">Refund</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav active"><a href="#refund" data-toggle="tab" class="nav-tab-class">Refund</a></li>
            <li class="nav"><a href="#view-fees" data-toggle="tab" class="nav-tab-class">View Fees Refund</a></li>
            <!--if the bank is clicked, then the details of refund will come up with options for pdf printing-->
            <li class="nav"><a href="#view-bank" data-toggle="tab" class="nav-tab-class">View Bank Refund</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="refund">
            	<div class="panel panel-body">
                <form action="" method="POST" id="frm-create-fees-refund" enctype="multipart/form-data" >
                    <input type="hidden" name="refund_id" id="refund_id"> 
                    <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                    <input type="hidden" name="reviewer" id="reviewer" value="">
                    <input type="hidden" name="_token" value="{{ csrf_token()}}">
                    <div class="row">
                        <div class="col-md-5">
                            
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label for="class_name">Date</label>
                                    <div class="form-group">
                                        <input type="text" name="txn_date" class="form-control text-right" id="txn_date" 
                                            placeholder="Enter refund date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="semester_id">Term</label>
                                    <div class="form-group">
                                        <select class="form-control" name="semester_id" id="semester_id" style="width: 150px;">
                                            <option value="">Please Select</option>
                                            @foreach($semester as $key=>$y)
                                                <option value="{{ $y->semester_id}}">
                                                {{ $y->semester}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label for="class_name">Total Amount</label>
                                    <div class="form-group">
                                        <input type="text" name="total_amount" class="form-control text-right" id="total_amount" 
                                            placeholder="Enter refund amount" onkeyup="return allow_number(this)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="class_name">Pay Channel</label>
                                    <div class="form-group">
                                        <select class="form-control" name="channel" id="channel" style="width: 150px;">
                                            <option value="">Please Select...</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="POS">POS</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label for="reference">Refund Reference</label>
                                    <div class="form-group">
                                        <input type="text" name="reference" class="form-control" id="reference" 
                                            placeholder="Refund Chq/Voucher No">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="bank_id">Bank</label>
                                    <div class="form-group">
                                        <select class="form-control" name="bank_id" id="bank_id" style="width: 150px;">
                                            <option value="">Please Select</option>
                                            @foreach($banks as $key=>$y)
                                                <option value="{{ $y->bank_id}}">
                                                {{ $y->bank_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="narration">Narration</label>
                                <div class="form-group">
                                    <input type="text" name="narration" class="form-control" id="narration" 
                                        placeholder="Enter Narration here">
                                </div>
                            </div>
                            <div class="col-md-12 pull-right">
                                <button type="submit" class="btn btn-primary" id="btn-save">
                                    <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                            </div>
                        </div>
                        <div class="col-md-7 pull-right" style="border-left:2px solid #ccc;height:300px">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default" id="add_row">
                                    <i class="fa fa-plus fa-fw fa-fw"></i>Add a Record</button>
                            </div>
                            <table class="table table-hover table-striped table-condensed" id="table-input">
                                <thead>
                                    <tr>
                                        <th width="15%">Reg. No</th>
                                        <th width="20%">Last Name</th>
                                        <th width="20%">First Name</th>
                                        <th width="20%">Fees Type</th>
                                        <th width="20%">Amount Paid</th>
                                        <th width="5%">Action</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="tab-pane fade in" id="view-fees">
                <div class="panel panel-body" style="margin-top:5px;">
                    <div class="panel panel-body" style="margin-top:5px;">
                    	<div class="col-md-5 pull-left">
                            <div class="input-group">
                                <input type="text" class="form-control" name ="fees-input" id= "fees-input" 
                                	placeholder="31/12/2017 30/09/2018">
                                <div class="input-group-btn search-panel">
                                    <button type="button" id="fees-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span id="search_concept">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!---the action should allow for print a single record and delete, if not enrolled(enrol=0)---->
                    <div class="form-group">
                        <table class="table table-hover" id="fees-table">
                            <thead>
                                <tr>
                                    <th width="10%">Date</th>
                                    <th width="10%">Reg No</th>
                                    <th width="13%">Last Name</th>
                                    <th width="12%">First Name</th>
                                   	<th width="10%">Class</th>
                                    <th width="15%">Fees</th>
                                    <th width="10%">Amount</th>
                                    <th width="20%">Narration</th>
                                 </tr>
                            </thead>
                            <tbody id="table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade in" id="view-bank">
                <div class="panel panel-body" style="margin-top:5px;">
                    <div class="panel panel-body" style="margin-top:5px;">
                    	<div class="col-md-5 pull-left">
                            <div class="input-group">
                                
                                <input type="text" class="form-control" name ="bank-input" id= "bank-input" 
                                	placeholder="31/12/2017 30/09/2018">
                                <div class="input-group-btn search-panel">
                                    <button type="button" id="bank-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span id="search_concept">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!---the action should allow for print a single record and delete, if not enrolled(enrol=0)---->
                    <div class="form-group">
                        <table class="table table-hover" id="bank-table">
                            <thead>
                                <tr>
                                	<th width="10%">Date</th>
                                    <th width="20%">Bank</th>
                                    <th width="15%">Amount</th>
                                    <th width="10%">Channel</th>
                                    <th width="10%">Reference</th>
                                    <th width="25%">Narration</th>
                                    <th width="10%">Action</th>
                                 </tr>
                            </thead>
                            <tbody id="table-body">
                            </tbody>
                        </table>
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

	$(function(){
		$('#txn_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	var all_students = '<select class="form-control regno" name="regno[]">';
	$.get("{{ route('listActiveStudents') }}", function(data){
		all_students = all_students + "<option value=''>Select</option>";
		if (data.length > 0) {
			$.each(data, function(i,l){
				var reg_id = l.student_id;
				var reg_no = l.reg_no;
				all_students = all_students + "<option value='" + reg_id + "'>" + reg_no + "</option>";
			});
		}else{
			alert('No record to display...');	
		}
		all_students = all_students + '</select>';
	});
	
	var fees = '<select class="form-control fees" name="fees[]">';
	$.get("{{ route('listFees') }}", function(data){
		fees = fees + "<option value=''>Select</option>";
		if (data.length > 0) {
			$.each(data, function(i,l){
				var fee_id = l.fee_id;
				var fee_name = l.fee_name;
				fees = fees + "<option value='" + fee_id + "'>" + fee_name + "</option>";
			});
		}else{
			alert('No record to display...');	
		}
		fees = fees + '</select>';
	});
	$('#add_row').on('click', function(e){
		e.preventDefault();
		add_row();
	});
	function add_row(){
		var row_data = '<tr>' +
			'<td>' + all_students +  '</td>'+
			'<td><input type="text" class="form-control last_name" name="last_name[]" readonly="readonly" ></td>'+
			'<td><input type="text" class="form-control first_name" name="first_name[]" readonly="readonly" ></td>'+
			'<td>' + fees +  '</td>'+
			'<td align="right"><input type="text" class="form-control amount text-right"'+
			' name="amount[]" onkeyup="return allow_number(this)"></td>'+
			'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
		'</tr>';
		$('#table-input tbody').append(row_data);
	}
	$("#table-input tbody").on("change", ".regno", function(){
		var row = $(this).closest("tr");
		var student_id = row.find("select.regno").val();
		
		//populate last name and first name
		$.get("{{ route('getStudent') }}", {student_id: student_id}, function(data){
			row.find("input.last_name").val(data.last_name);
			row.find("input.first_name").val(data.first_name);
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
	function validateFormOnSubmit() {
		var reason = "";
		try{
			//ensure that the amount in the table area is the same as the total amount
			//ensure valid date
			//ensure channel is picked
			//ensure bank is picked
			//ensure narration
			//ensure that a valid fees is picked
			//ensure that a valid student is picked
			
			reason += validate_date(document.getElementById("txn_date"));
			reason += validate_empty(document.getElementById("narration"));
			reason += validate_empty(document.getElementById("reference"));
			reason += validate_select(document.getElementById("channel"));
			reason += validate_select(document.getElementById("bank_id"));
			reason += validate_select(document.getElementById("semester_id"));
			//now iterate through the table to ensure correctness of input
			var table_amount = 0;
			$('#table-input tbody tr').each(function(){
								
				var td_last_name = $(this).closest('tr').find('input.last_name').val();
				var td_amount = $(this).closest('tr').find('input.amount').val();
				var td_fees = $(this).closest('tr').find('select.fees').val();
				if(td_fees == "") reason += "Wrong fees type \n";
				if(td_last_name == "") reason += "Student record does not exist \n";
				
				td_amount = parseFloat(convertToNumbers(td_amount));
				if(isNaN(td_amount) === true) td_amount = 0.00;
				if( td_amount == 0 ) reason += "Amount in the table should not be blank \n";
				
				table_amount += td_amount;
			});
			var total_amount = $('#total_amount').val();
			total_amount = parseFloat(convertToNumbers(total_amount));
			if ( total_amount != table_amount) {
				reason += table_amount + " The posting figures do not agree\n";
			}
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$('#btn-save').on('click', function(e){
		e.preventDefault();
		submit_input();
	});
	function submit_input(){
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
							url: "{{ route('updateFeesRefund')}}",
						  	data: new FormData($("#frm-create-fees-refund")[0]),
						  	async:false,
						  	type:'post',
						  	processData: false,
						  	contentType: false,
						  	success:function(data){
								
								if(Object.keys(data).length > 0){
									printRefund(data.payment_id);
									$('#frm-create-fees-refund')[0].reset();
									alert('Update successful');
								}else{
									alert('Update NOT successful');
								}
								document.getElementById("btn-save").innerHTML = 
									'<i class="fa fa-save fa-fw fa-fw"></i>Save';
								
								$('#refund_id').val('');
								
							},
							error: OnError
						});
					}
				}
			}
		});	
	}
	$(document).on('click', '#fees-search', function(e){
		e.preventDefault();
		//extract the dates from the string
		var dates = $('#fees-input').val();
		//extract start date and end date
		var end_date = dates.slice(-10);
		var start_date = dates.slice(0,10);
		var reason = "";
		reason += validate_date_val(start_date);
		reason += validate_date_val(end_date);
		if(reason == ""){
			start_date = toDbaseDate(start_date);
			end_date = toDbaseDate(end_date);
			$('#fees-table> tbody').empty();
			document.getElementById("fees-search").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
				
			$.get("{{ route('searchFeesRefund') }}", {start_date: start_date, end_date: end_date}, function(data){
				//bank-table
				if (data.length > 0) {
					$.each(data, function(i,l){
						//get the student's present class
						var student_id = l.student_id;
						$.get("{{ route('getStudentClass') }}", {student_id: student_id}, function(info){
							var row_data = '<tr>' +
								'<td>' + convertDate(l.refund_date) +'</td>'+
								'<td>' + l.reg_no +'</td>'+
								'<td>' + l.last_name +'</td>'+
								'<td>' + l.first_name +'</td>'+
								'<td>' + info.class_div +'</td>'+
								'<td>' + l.fee_name +'</td>'+
								'<td align="right">' + numberWithCommas(l.amount) +'</td>'+
								'<td>' + l.narration +'</td>'
								'</tr>';
							$('#fees-table> tbody:last-child').append(row_data);
						});
					});
				}else{
					alert('No record to display...');	
				}
				document.getElementById("fees-search").innerHTML = 
					'<span id="search_concept">Search</span>';
			});
		}
	});
	$(document).on('click', '#bank-search', function(e){
		e.preventDefault();
		//extract the dates from the string
		var dates = $('#bank-input').val();
		//extract start date and end date
		var end_date = dates.slice(-10);
		var start_date = dates.slice(0,10);
		var reason = "";
		reason += validate_date_val(start_date);
		reason += validate_date_val(end_date);
		if(reason == ""){
			start_date = toDbaseDate(start_date);
			end_date = toDbaseDate(end_date);
			
			var table = $('#bank-table').DataTable();
				table.clear();
				table.destroy();
				
			$('#bank-table> tbody').empty();
			document.getElementById("bank-search").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
				
			$.get("{{ route('searchBankRefund') }}", {start_date: start_date, end_date: end_date}, function(data){
				//bank-table
				if (data.length > 0) {
					$.each(data, function(i,l){
						//the txn date should be a hyperlink that should bring the whole refund for the fees paid for
						var row_data = '<tr>' +
							'<td>'+ convertDate(l.txn_date) + '</td>'+
							'<td>' + l.bank_name +'</td>'+
							'<td align="right">' + numberWithCommas(l.amount) +'</td>'+
							'<td>' + l.channel +'</td>'+
							'<td>' + l.reference +'</td>'+
							'<td>' + l.narration +'</td>'+
							'<td style="vertical-align: middle; width: 25px;"><button class="btn btn-sm print-this" value="'+ l.payment_id + '" ><i class="fa fa-print fa-lg"></i></button></td>'+
							'</tr>';
						$('#bank-table> tbody:last-child').append(row_data);
					});
					$('#bank-table').DataTable();
				}else{
					alert('No record to display...');	
				}
				document.getElementById("bank-search").innerHTML = 
					'<span id="search_concept">Search</span>';
			});
		}
	});
	$(document).on('click', '.print-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var refund_id = $(this).val();
		printRefund(refund_id);
	});
	function printRefund(refund_id) {
		$('#pleaseWaitDialog').modal();
	  	$.get("{{ route('printRefundDetails') }}", {refund_id: refund_id}, function(data){
			$('#pleaseWaitDialog').modal('hide');
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	}
	
</script>
@endsection