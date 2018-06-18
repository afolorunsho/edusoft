@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Fees</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getFeePay')}}">Payment</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav active"><a href="#payment-tab" data-toggle="tab" class="nav-tab-class">Payment</a></li>
            <li class="nav"><a href="#view-fees" data-toggle="tab" class="nav-tab-class">View Fees Payment</a></li>
            <li class="nav"><a href="#view-bank" data-toggle="tab" class="nav-tab-class">View Bank Payment</a></li>
            <li class="nav"><a href="#academic-year" data-toggle="tab" class="nav-tab-class">View Yearly Fees</a></li>
            <li class="nav"><a href="#import-payment" data-toggle="tab" class="nav-tab-class">Import Payments</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="payment-tab">
            	<div class="panel panel-body">
                <form action="" method="POST" id="frm-create-fees-payment" enctype="multipart/form-data" >
                    <input type="hidden" name="payment_id" id="payment_id"> 
                    <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                    <input type="hidden" name="reviewer" id="reviewer" value="">
                    <input type="hidden" name="_token" value="{{ csrf_token()}}">
                    <div class="row">
                        <div class="col-md-5">
                         	
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label for="txn_date">Date</label>
                                    <div class="form-group">
                                        <input type="text" name="txn_date" class="form-control text-right" id="txn_date" 
                                            placeholder="Enter lodgement date">
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
                                    <label for="total_amount">Total Amount</label>
                                    <div class="form-group">
                                        <input type="text" name="total_amount" class="form-control text-right" id="total_amount" 
                                            placeholder="Enter lodgement amount" onkeyup="return allow_number(this)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="channel">Pay Channel</label>
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
                                    <label for="reference">Payment Reference</label>
                                    <div class="form-group">
                                        <input type="text" name="reference" class="form-control" id="reference" 
                                            placeholder="Payment Ref/Slip No">
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
                            <div class="col-md-12">
								<div class="form-row pull-right">
									<button type="submit" class="btn btn-primary" id="btn-save">
										<i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                </div>
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
                <div class="panel panel-body" style="margin-top:1px;">
                    <div class="panel panel-body" style="margin-top:1px;">
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
                    <div class="form-group" id="div-fees-table">
                        <table class="table table-hover" id="fees-table">
                            <thead>
                                <tr>
                                    <th width="10%">Date</th>
                                    <th width="10%">Reg No</th>
                                    <th width="13%">Last Name</th>
                                    <th width="12%">First Name</th>
                                   	<th width="10%">Class</th>
                                    <th width="10%">Fees</th>
                                    <th width="10%">Amount</th>
                                    <th width="20%">Narration</th>
                                    <th width="5%">Action</th>
                                 </tr>
                            </thead>
                            <tbody id="table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade in" id="view-bank">
                <div class="panel panel-body" style="margin-top:1px;">
                    <div class="panel panel-body" style="margin-top:1px;">
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
                        @if(Auth::user()->role_id == '1' ||
                            Auth::user()->role_id == '5' ||
                            Auth::user()->role_id == '9' ||
                            Auth::user()->role_id == '8')
                        <div class="dropdown pull-right">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fa fa-download fa-fw fa-fw"></i>Export
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li id="bank-to-excel"><a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw">
                                    </i>Export to Excel</a></li>
                                </ul>
                        </div>
                        @endif
                    </div>
                    
                    <!---the action should allow for print a single record and delete, if not enrolled(enrol=0)---->
                    <div class="form-group" id="div-bank-table">
                        <table class="table table-hover" id="bank-table">
                            <thead>
                                <tr>
                                	<th>Reference</th>
                                    <th>Date</th>
                                    <th>Bank</th>
                                    <th>Amount</th>
                                    <th>Channel</th>
                                    <th>Narration</th>
                                    <th width="25px">Dlt</th>
                                    <th width="25px">Prt</th>
                                 </tr>
                            </thead>
                            <tbody id="table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade in" id="import-payment">
                <div class="panel panel-body" style="margin-top:5px;">
                    <div class="panel panel-body" style="margin-top:5px;">
                    	<form method="post" class="form-horizontal" id="frm-import-payment" name="frm-import-payment"
                           role="form" enctype="multipart/form-data" return false>
                           
                           <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                           <input type="hidden" id="token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <input type="file" id="table_file" name="table_file">
                                </div>
                                <div class="col-md-2">
                                    <button type="button"  class="btn btn-default" id="btn_import_payment">
                                        <i class="fa fa-upload fa-fw"></i>Upload</button>
                                </div>
                                <div class="col-md-4">
                                	<label for="semester_p" class="col-md-2 control-label">Term:</label>
                                	<select class="form-control" name="semester_p" id="semester_p" style="width: 150px;">
                                        <option value="">Please Select</option>
                                        @foreach($semester as $key=>$y)
                                            <option value="{{ $y->semester_id}}">
                                            {{ $y->semester}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 pull-right">
                                    <button id="btn_update_payment"
                                        type="button"  class="btn btn-primary" >
                                            <i class="fa fa-save fa-fw"></i>Update Database</button>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <!--<hr />-->
                                        <div id="table_div_payment">
                                            <!--populate dynamically-->
                                            <table class="table table-hover table-striped table-condensed" id="import_payment"
                                                style="font-size:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">Date</th>
                                                        <th width="10%">Reg. No</th>
                                                        <th width="15%">Last Name</th>
                                                        <th width="10%">First Name</th>
                                                        <th width="15%">Fees</th>
                                                        <th width="10%">Amount</th>
                                                        <th width="10%">Bank</th>
                                                        <th width="20%">Narration</th>
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
            <div class="tab-pane fade in" id="academic-year">
                <div class="panel panel-body" style="margin-top:5px;">
                    <form action="" method="POST" id="frm-yearly-fees" enctype="multipart/form-data" return false>
                    	<input type="hidden" name="_token" value="{{ csrf_token()}}">
                      	<div class="row">
                     		<div class="col-md-2">
                                <div class="form-group">
                                    <label for="academic_y" class="control-label col-md-12">Academic Year</label>
                                    <select class="form-control" name="academic_y" id="academic_y">
                                        <option value="">Please Select...</option>
                                        @foreach($academic as $y)
                                            <option value="{{ $y->academic_id}}">
                                            {{ $y->academic }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="class_y" class="control-label col-md-8">Class</label>
                                     <select class="form-control" name="class_y" id="class_y" onchange="classChange()">
                                        <option value="">Please Select...</option>
                                        @foreach($class as $y)
                                            <option value="{{ $y->class_id}}">
                                            {{ $y->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="class_div_y" class="control-label col-md-8">Section</label>
                                   	<select class="form-control" name="class_div_y" id="class_div_y" style="width: 150px;">
                                        <option value="">Please Select...</option>
                                        
                                  	</select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fees_y" class="control-label col-md-8">Fees</label>
                                   	<select class="form-control" name="fees_y" id="fees_y" style="width: 150px;">
                                        <option value="">Please Select...</option>
                                        @foreach($fees as $y)
                                            <option value="{{ $y->fee_id}}">
                                            {{ $y->fee_name }}</option>
                                        @endforeach
                                        
                                  	</select>
                                </div>
                            </div>
                            <div class="col-md-1">
                            	<button class="btn btn-default btn-sm" type="button" name="search-button" id="search-button">Get</button>
                            </div>
                      	</div>
                        <div class="col-md-12">
                            <!------list all those who have not enrolled-------->
                            <div class="table-students">
                                <table class="table" id="all-fees-table">
                                	<thead>
                                        <tr>
                                            <th>Reg. No</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Class</th>
                                            <th>Fees</th>
                                            <th>Payment</th>
                                  		</tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                       	</div>
                  	</form>
             	</div>
        	</div>
        </div>
        <div id="dialog" style="display: none"></div>
    </div>
    <form action="" method="POST" class="form-horizontal" name= "frm-data-payment" id="frm-data-payment" enctype="multipart/form-data" return false>
        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
        <input type="hidden" name="reviewer" id="reviewer" value="">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="payment_date" id="payment_date" value="">
        <input type="hidden" name="last_name" id="last_name" value="">
        <input type="hidden" name="first_name" id="first_name" value="">
        <input type="hidden" name="reg_no" id="reg_no" value="">
        <input type="hidden" name="fees" id="fees" value="">
        <input type="hidden" name="bank" id="bank" value="">
        <input type="hidden" name="narration" id="narration" value="">
        <input type="hidden" name="amount" id="amount" value="">
        <input type="hidden" name="semester" id="semester" value="">
     </form>
     
	@include('popup.ajax_wait')
    @include('fees.fees_batch')
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
						
						$('#pleaseWaitDialog').modal();
						$.ajax({
						  	url: "{{ route('updateFeesPayment')}}",
						  	data: new FormData($("#frm-create-fees-payment")[0]),
						  	async:false,
						  	type:'post',
						  	processData: false,
						  	contentType: false,
						  	success:function(data){
								
								if(Object.keys(data).length > 0){
									printReceipt(data.payment_id);
									$('#frm-create-fees-payment')[0].reset();
									alert('Update successful');
								}else{
									alert('Update NOT successful');
								}
								$('#pleaseWaitDialog').modal('hide');
								$('#payment_id').val('');
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
			var table = $('#fees-table').DataTable();
			table.clear();
			table.destroy();
			
			$('#fees-table tbody').empty();
			$.get("{{ route('searchFeesPmt') }}", {start_date: start_date, end_date: end_date}, function(data){
				if (data.length > 0) {
					var all_data = '';
					$.each(data, function(i,l){
						
						var row_data = '<tr>' +
							'<td>' + convertDate(l.payment_date) +'</td>'+
							'<td>' + l.reg_no +'</td>'+
							'<td>' + l.last_name +'</td>'+
							'<td>' + l.first_name +'</td>'+
							'<td>' + l.class_div +'</td>'+
							'<td>' + l.fee_name +'</td>'+
							'<td align="right">' + numberWithCommas(l.amount) +'</td>'+
							'<td>' + l.narration +'</td>'+
							'<td style="vertical-align: middle; width: 25px;"><button class="btn btn-sm print-rcpt" value="'+ l.student_id + '" ><i class="fa fa-print fa-lg"></i></button></td>'+
							'</tr>';
						all_data = all_data + row_data;
					});
					$('#fees-table tbody').append(all_data);
					$('#fees-table').DataTable();
				}else{
					alert('No record to display...');	
				}
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
			
			$('#fees-table tbody').empty();
			$('#pleaseWaitDialog').modal();
			$.get("{{ route('searchBankPmt') }}", {start_date: start_date, end_date: end_date}, function(data){
				if (data.length > 0) {
					var all_data = '';
					$.each(data, function(i,l){
						var row_data = '<tr>' +
							'<td><a href="#" data-id="'+ l.payment_id + '" class="edit-this">' + l.reference +'</a></td>'+
							'<td>'+ convertDate(l.txn_date) + '</td>'+
							'<td>' + l.bank_name +'</td>'+
							'<td align="right">' + numberWithCommas(l.amount) +'</td>'+
							'<td>' + l.channel +'</td>'+
							'<td>' + l.narration +'</td>'+
							'<td style="vertical-align: middle; width: 25px;"><button class="btn btn-danger btn-sm del-this" value="'+ l.payment_id + '" ><i class="fa fa-trash-o fa-lg"></i></button></td>'+
							'<td style="vertical-align: middle; width: 25px;"><button class="btn btn-sm print-this" value="'+ l.payment_id + '" ><i class="fa fa-print fa-lg"></i></button></td>'+
							'</tr>';
							
						all_data = all_data + row_data;
					});
					$('#bank-table tbody').append(all_data);
					$('#bank-table').DataTable();
				}else{
					alert('No record to display...');	
				}
				$('#pleaseWaitDialog').modal('hide');
			});
		}
	});
	
	$(document).on('click', '.print-rcpt', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var student_id = $(this).val();
		printFeeReceipt(student_id);
	});
	function printFeeReceipt(student_id) {
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
			$.get("{{ route('printFeeReceipt') }}", {student_id: student_id, start_date: start_date, end_date: end_date}, function(data){
				var path = "{{url('/')}}";
				path = path + '/reports/pdf/' + data;
				openInNewTab(path);
			});
		}else{
			alert('Invalid selection');	
		}
	}
	$(document).on('click', '.print-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var payment_id = $(this).val();
		printReceipt(payment_id);
	});
	function printReceipt(payment_id) {
		$.get("{{ route('printPayDetails') }}", {payment_id: payment_id}, function(data){
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		});
	}
	$('#btn_import_payment').on('click', function(e){
		e.preventDefault();
		
		document.getElementById("btn_import_payment").innerHTML = 
			'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
				
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to upload this file?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				if(result) {
					$('#pleaseWaitDialog').modal();
					$.ajax({
						url: "{{ route('paymentImport')}}",
						data: new FormData($("#frm-import-payment")[0]),
						async:false,
						type:'post',
						processData: false,
						contentType: false,
						success:function(data){
							$('#table_div_payment').children().remove();
							$('#table_div_payment').append(data);
							document.getElementById("btn_import_payment").innerHTML = 
								'<i class="fa fa-upload fa-fw"></i>Upload';
						},
						error: function(jqXHR, exception) { 
							return_error(jqXHR, exception);
						}
					});
					$('#pleaseWaitDialog').modal('hide');
				}
			}
		});
	});
	
	$('#btn_update_payment').on('click', function(e){
		e.preventDefault();
		try{
			var table = $('#frm-import-payment #import_payment tbody');
			var semester = $('#frm-import-payment #semester_p').val();
			//alert(semester);
			document.getElementById("btn_update_payment").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
			
			table.find('tr').each(function(i, el){
				var is_valid = true;
				var tds = $(this).find('td');
				var payment_date = toDbaseDate(tds.eq(0).text());
				//alert(payment_date);
				var reg_no = tds.eq(1).text();
				var last_name = tds.eq(2).text();
				var first_name = tds.eq(3).text();
				var fees = tds.eq(4).text();
				var amount = tds.eq(5).text();
				var bank = tds.eq(6).text();
				var narration = tds.eq(7).text();
				if( reg_no == "" || last_name == "" || payment_date == "" || fees == "" || amount == "" || 
					bank == "" || narration == "" || semester == "" | semester == null){
					is_valid = false;
				}
				var amount = parseFloat(convertToNumbers(amount));
				if(isNaN(amount) === true) amount = 0.00;
				if( amount == 0 ) is_valid = false;
				
				if (is_valid){
					$('#frm-data-payment #payment_date').val(payment_date);
					$('#frm-data-payment #reg_no').val(reg_no);
					$('#frm-data-payment #last_name').val(last_name);
					$('#frm-data-payment #first_name').val(first_name);
					$('#frm-data-payment #fees').val(fees);
					$('#frm-data-payment #amount').val(amount);
					$('#frm-data-payment #bank').val(bank);
					$('#frm-data-payment #narration').val(narration);
					$('#frm-data-payment #semester').val(semester);
					var $ele = $(this).closest('tr');
					$.ajax({
						url: "{{ route('updatePaymentImport')}}",
						data: new FormData($("#frm-data-payment")[0]),
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
			document.getElementById("btn_update_payment").innerHTML = 
				'<i class="fa fa-save fa-fw"></i>Update Database';
		}catch(err){
			return false; 
			alert(err.message); 
		}
	});
	$(document).on('click', '.edit-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var payment_id = $(this).data('id');
		$('#frm-create-fees-payment #payment_id').val(payment_id);
		try{
			$.get("{{ route('editPayment') }}", {payment_id: payment_id}, function(data){
				
				/*change tab focus*/
				$('.nav-tabs a[href="#payment-tab"]').tab('show');
				$('#frm-create-fees-payment #table-input tbody').empty();
				var i = 0;
				if (data.length > 0) {
					$.each(data, function(i,l){
						if( i == 0){
							$('#frm-create-fees-payment #txn_date').val(convertDate(l.txn_date));
							$('#frm-create-fees-payment #narration').val(l.narration);
							$('#frm-create-fees-payment #reference').val(l.reference);
							$('#frm-create-fees-payment #channel').val(l.channel);
							$('#frm-create-fees-payment #bank_id').val(l.bank_id);
							$('#frm-create-fees-payment #semester_id').val(l.semester_id);
							$('#frm-create-fees-payment #total_amount').val(numberWithCommas(l.total_amount));
						}
						var row_data = '<tr>' +
							'<td>' + l.reg_no +  '</td>'+
							'<td>' + l.last_name +  '</td>'+
							'<td>' + l.first_name +  '</td>'+
							'<td>' + l.fee_name +  '</td>'+
							'<td>' + numberWithCommas(l.fee_amount) +  '</td>'+
							'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
						'</tr>';
						$('#frm-create-fees-payment #table-input tbody').append(row_data);
						i = i + 1;
					});
				}else{
					alert('No record to display...');	
				}
				$('#frm-create-fees-payment #txn_date').focus();
			});
			
		}catch(err){ alert(err.message); }
	});
	$("#bank-table tbody").on("click", ".del-this", function(e){
		e.preventDefault();
		var payment_id = $(this).val();
		/////////////////////////////////////////
		var operator = $('#frm-create-fees-payment #operator').val();
		//NB: bring up all the transactions in the batch in a dialog form, to ensure proper information before deletion
		$.get("{{ route('delFeesBatch') }}", {payment_id: payment_id}, function(data){
			
			var count = 0;
			var semester = '';
			$('#deleteForm #payment_id').val(payment_id);
			$.each(data, function(i,l){
				
				if(count == 0){
					$('#deleteForm #txn_date').val(l.txn_date);
					$('#deleteForm #channel').val(l.channel);
					$('#deleteForm #bank_id').val(l.bank);
					$('#deleteForm #reference').val(l.reference);
					$('#deleteForm #narration').val(l.description);
					$('#deleteForm #total_amount').val(l.total_amount);
				}
				else{
					//fill in the table with data
					semester  = l.semester;
					var row_data = '<tr>' +
							'<td>' + l.reg_no +  '</td>'+
							'<td>' + l.last_name +  '</td>'+
							'<td>' + l.first_name +  '</td>'+
							'<td>' + l.fees +  '</td>'+
							'<td>' + l.amount +  '</td>'+
						'</tr>';
					$('#deleteForm #table-input tbody').append(row_data);
				}
				count = count + 1;
			});
			$('#deleteForm #semester_id').val(semester);
			
			$('#fee_batch_show').modal();
		});
	});
	$("#deleteForm").on("click", "#btn-delete", function(e){
		e.preventDefault();
		
		var operator = $('#frm-create-exp-txn #operator').val();
		var payment_id = $('#deleteForm #payment_id').val();
		
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delPayment') }}", {payment_id: payment_id, operator: operator}).done(function(data){ 
						//update the log at the server end
						if(Object.keys(data).length > 0){
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
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		var dates = $('#lodge-input').val();
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
			$.get("{{ route('excelLodgement') }}", {start_date: start_date, end_date: end_date}, function(data){
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
				
				//path = path + '/reports/pdf/' + data;
				//openInNewTab(path);
			});
		}else{
			alert('Please select valid date range');	
		}
	});
	$(document).on('click', '#bank-to-excel', function(e){
		e.preventDefault();
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
			document.getElementById("bank-to-excel").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
			
			$.get("{{ route('excelBank') }}", {start_date: start_date, end_date: end_date}, function(data){
				
				document.getElementById("bank-to-excel").innerHTML = 
					'<a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw"></i>Export to Excel</a>';
				
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
		}else{
			alert('Please select valid date range');	
		}
	});
	//////////////////////////////////////////////
	function classChange(){
		//empty table section whenever there is a change in the class
		$('#class_div_y').empty();
		var class_id = $('#class_y').val();
		//generate sections upon class change
		$.get("{{ route('getClassSection')}}", {class_id: class_id}, function(data){
			$('#class_div_y').append('<option value="">Please Select...</option>');
			if (data.length > 0) {
				$.each(data, function(i,l){
					var row_data = '<option value="'+ l.class_div_id +'">' + l.class_div + '</option>';
					$('#class_div_y').append(row_data);
				});
			}else{
				alert('No record to display...');	
			}
		});
	}
	$(document).on('click', '#search-button', function(e){
		e.preventDefault();
		//load the respective student enrolled for this class div and the various services
		try{
			var table = $('#all-fees-table').DataTable();
			table.clear();
			table.destroy();
			
			$('#all-fees-table tbody').empty();
			
			//check for semester and class: section and fees are optional
			var class_div_id = $('#class_div_y').val();
			var academic_id = $('#academic_y').val();
			var fee_id = $('#fees_y').val();
			var class_id = $('#class_y').val();
			if( academic_id !== "" && class_id !== ""){
				$.get("{{ route('getYearlyFees')}}", 
				{class_id: class_id, class_div_id: class_div_id, academic_id: academic_id, fee_id: fee_id},
				 function(data){
					
					$('#pleaseWaitDialog').modal('hide');
					
					$('#all-fees-table tbody').append(data);
					$('#all-fees-table').DataTable({
						dom: 'Bfrtip',
						buttons: [
							'copyHtml5',
							'excelHtml5',
							'csvHtml5'
						]
					});
				});
			}else{
				alert('You have to select Academic Year and Class');
			}
		}catch(err){ alert(err.message); }
	});
	
</script>
@endsection