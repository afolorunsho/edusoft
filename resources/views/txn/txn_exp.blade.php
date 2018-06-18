@extends('layouts.master')
@section('content')
	<style>
		
	</style>
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Transaction</li>
                <li><a href="{{route('getTxnExp')}}"><i class="fa fa-credit-card"></i>Expenses</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#expenses" data-toggle="tab" class="nav-tab-class">Expense Input</a></li>
                <li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Expenses</a></li>
                <li class="nav"><a href="#import-expense" data-toggle="tab" class="nav-tab-class">Import Expenses</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="expenses">
                	<div class="panel panel-body">
                        <div class="row">
                            <form id="frm-create-exp-txn" method="post">
                                <div class="col-md-3 pull-left" style="border-right:2px solid #ccc;height:450px">
                                    <input type="hidden" name="txn_id" id="txn_id"> 
                                    <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                                    <input type="hidden" name="reviewer" id="reviewer" value="">
                                    <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                    
                                    
                                    <div class="col-md-12">
                                        <label for="txn_date">Transaction Date</label>
                                        <div class="form-group">
                                            <input type="text" name="txn_date" class="form-control text-right" width="10px" id="txn_date" 
                                                placeholder="Enter Txn Date" onkeydown="return false;">
                                        </div>
                                    </div>
                                
                                    <div class="col-md-12">
                                        <label for="voucher_no">Voucher No.</label>
                                        <div class="form-group">
                                            <input type="text" name="voucher_no" class="form-control" id="voucher_no" 
                                                placeholder="Enter Voucher No">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="beneficiary">Beneficiary</label>
                                        <div class="form-group">
                                            <input type="text" name="beneficiary" class="form-control" id="beneficiary" 
                                                placeholder="Enter Beneficiary">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="total_amount">Total Amount</label>
                                        <div class="form-group">
                                            <input type="text" name="total_amount" class="form-control text-right" id="total_amount" 
                                            placeholder="Enter Total Amount" onKeyUp="return allow_number(this)">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="pay_channel">Payment Channel</label>
                                        <div class="form-group">
                                            <select class="form-control" name="pay_channel" id="pay_channel" style="width: 175px;">
                                                <option value="Cash">Cash</option>
                                                <option value="Cheque">Cheque</option>
                                                <option valu="POS">POS</option>
                                                <option value="Transfer">Transfer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="bank_id">Bank Name</label>
                                        <div class="form-group">
                                            <select class="form-control" name="bank_id" id="bank_id" style="width: 175px;">
                                                @foreach($banks as $key=>$y)
                                                    <option value="{{ $y->bank_id}}">
                                                    {{ $y->bank_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-12">
                                        <label for="bank_ref">Bank Payment Ref</label>
                                        <div class="form-group">
                                            <input type="text" name="bank_ref" class="form-control" id="bank_ref" placeholder="Enter Payment Ref">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="description">Narration</label>
                                        <div class="form-group">
                                            <input type="text" name="description" class="form-control" id="description" placeholder="Enter Narration">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 pull-right">       
                                    <div class="form-group col-md-12">
                                        
                                        <div class="pull-left">
                                            <button type="button" class="btn btn-default" id="add_row">
                                                <i class="fa fa-plus fa-fw fa-fw"></i>Add a Record</button>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control number-input text-right" id="cumulative" 
                                                placeholder="cumulative amount" disabled>
                                        </div>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-primary" id="btn-save">
                                                <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table" id="table-input">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Expense</th>
                                                    <th width="10%">Qty</th>
                                                    <th width="15%">Price</th>
                                                    <th width="20%">Amount</th>
                                                    <th width="25%">Narration</th>
                                                    <th width="5%"></th>
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
                <div class="tab-pane fade in" id="view">
                	<div class="panel panel-body">
                        <div class="col-md-4 pull-left">
                            
							<label class="col-md-3" for="date_range">Date Period</label>
							<div class="col-md-9 pull-left">
								<input type="" name="date_range" class="form-control" id="date_range" 
									placeholder="01/01/2018 31/12/2018">
							</div>
					   
                        </div>
                        <div class="col-md-8 pull-right">
                            <div class="input-group">
                                <div class="input-group-btn search-panel">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span id="search_concept">Search Criteria</span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu"  id="rmenu">
                                      <li><a href="#" data-value="All">All</a></li>
									  <li role="separator" class="divider"></li>
                                      <li><a href="#" data-value="Code">Code</a></li>
                                      <li><a href="#" data-value="Bank">Bank</a></li>
                                      <li><a href="#" data-value="Voucher">Voucher</a></li>
                                      <li><a href="#" data-value="Beneficiary">Beneficiary</a></li>
									  <li><a href="#" data-value="Channel">Channel</a></li>
									  <li><a href="#" data-value="Amount">Amount</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="search_param" value="all" id="search_param">         
                                <input type="text" class="form-control" name="x" id="search-val" 
									placeholder="Search value...">
                                
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="searc-butt" type="button">
                                    <span class="fa fa-search-plus"></span></button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="export-to-excel">
										<i class="fa fa-file-excel-o fa-fw fa-fw">
											</i>Export to Excel
									</button>
                                </span>
                            </div>
                        </div>
                        <!---on saving, the system creates unique batch id for a group of transaction via invoice_id---->
                        <div class="form-group" id="exp-search-div">
                            <table class="table table-hover" id="exp-search-table">
                                <thead>
                                    <tr>
                                        <th style="width:3%;">No</th>
                                        <th style="width:8%;">Date</th>
                                        <th style="width:10%;">Beneficiary</th>
                                        <th style="width:5%;">Voucher</th>
                                        <th style="width:10%;">Expense</th>
                                        <th style="width:15%;">Narration</th>
                                        <th style="width:5%;">Qty</th>
                                        <th style="width:7%;">Price</th>
                                        <th style="width:10%;">Amount</th>
                                        <th style="width:15%;">Bank</th>
                                        <th style="width:5%;">Ref</th>
                                        <th style="width:5%;">Channel</th>
                                        <th style="width:2%;">X</th>
                                        <th style="width:2%;">X</th>
                                     </tr>
                                </thead>
                                <tbody id="table-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade in" id="import-expense">
                    <div class="panel panel-body" style="margin-top:5px;">
                        <div class="panel panel-body" style="margin-top:5px;">
                            <form method="post" class="form-horizontal" id="frm-import-expense" name="frm-import-expense"
                               role="form" enctype="multipart/form-data" return false>
                               
                               <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                               <input type="hidden" id="token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <input type="file" id="table_file" name="table_file">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-default" id="btn_import_expense">
                                            <i class="fa fa-upload fa-fw"></i>Upload</button>
                                    </div>
                                    <div class="col-md-2 pull-right">
                                        <button id="btn_update_expense"
                                            type="button"  class="btn btn-primary" >
                                                <i class="fa fa-save fa-fw"></i>Update Database</button>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <!--<hr />-->
                                            <div id="table_div_expense">
                                                <!--populate dynamically-->
                                                <table class="table table-hover table-striped table-condensed" id="import_expense"
                                                    style="font-size:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">Date</th>
                                                            <th width="5%">Voucher</th>
                                                            <th width="10%">Beneficiary</th>
                                                            <th width="10%">Channel</th>
                                                            <th width="10%">Bank</th>
                                                            <th width="10%">Reference</th>
                                                            <th width="10%">Expense</th>
                                                            <th width="5%">Qty</th>
                                                            <th width="10%">Price</th>
                                                            <th width="10%">Amount</th>
                                                            <th width="15%">Narration</th>
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
    <form action="" method="POST" class="form-horizontal" name= "frm-data-expense" id="frm-data-expense" enctype="multipart/form-data" return false>
        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
        <input type="hidden" name="reviewer" id="reviewer" value="">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="expense_date" id="expense_date" value="">
        <input type="hidden" name="voucher" id="voucher" value="">
        <input type="hidden" name="beneficiary" id="beneficiary" value="">
        <input type="hidden" name="channel" id="channel" value="">
        <input type="hidden" name="bank" id="bank" value="">
        <input type="hidden" name="reference" id="reference" value="">
        <input type="hidden" name="expense" id="expense" value="">
        <input type="hidden" name="qty" id="qty" value="">
        <input type="hidden" name="price" id="price" value="">
        <input type="hidden" name="amount" id="amount" value="">
        <input type="hidden" name="narration" id="narration" value="">
        
     </form>
    @include('txn.exp_batch')
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">
	$('#search-table').DataTable();
	
	$(function(){
		$('#txn_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	
	$('#btn-save').on('click', function(e){
		e.preventDefault();
		submit_input();
	});
	//pull the list of expenses from the server
	var html = '<select class="form-control expense_id" name="expense_id[]">';
	$.get("{{ route('listExp') }}", function(data){
		
		$.each(data, function(i,l){
			var exp_name = l.expense_name;
			var exp_id = l.expense_id;
			html = html + "<option value='" + exp_id + "'>" + exp_name + "</option>";
		});
		html = html + '</select>';
	});
	$('#add_row').on('click', function(e){
		e.preventDefault();
		add_row();
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
						  	url: "{{ route('updateTxnExp')}}",
						    data: new FormData($("#frm-create-exp-txn")[0]),
						  	async:false,
						  	type:'post',
						  	processData: false,
						  	contentType: false,
						  	success:function(data){
								if(Object.keys(data).length > 0){
									$('#frm-create-exp-txn')[0].reset();
									alert('Update successful');
								}else{
									alert('Update NOT successful');
								}
								document.getElementById("btn-save").innerHTML = 
									'<i class="fa fa-save fa-fw fa-fw"></i>Save';
								
							},
							error: OnError
						});
					}
				}
			}
		});	
	}
	function add_row(){
		var row_data = '<tr>' +
			'<td>' + html +  '</td>'+
			'<td align="right"><input type="text" class="form-control qty text-right" name="qty[]" onkeyup="return allow_number(this)"></td>'+
			'<td align="right"><input type="text" class="form-control price text-right" name="price[]" onkeyup="return allow_number(this)"></td>'+
			'<td align="right"><input type="text" class="form-control amount text-right" name="amount[]" onkeyup="return allow_number(this)"></td>'+
			'<td><input type="text" class="form-control narration" name="narration[]" value=""></td>'+
			'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
		'</tr>';
		
		$('#table-input tbody').append(row_data);
	}
	$("#table-input tbody").on("change", ".qty", function(){
		var row = $(this).closest("tr");
		calc_fields(row);
	});
	$("#table-input tbody").on("change", ".price", function(){
		var row = $(this).closest("tr");
		calc_fields(row);
	});
	$("#table-input tbody").on("change", ".amount", function(){
		var row = $(this).closest("tr");
		var td_qty = row.find("input.qty").val();
		var td_price = row.find("input.price").val();
		var td_amount = row.find("input.amount").val();
		
		td_price = parseFloat(convertToNumbers(td_price));
		td_qty = parseFloat(convertToNumbers(td_qty));
		td_amount = parseFloat(convertToNumbers(td_amount));
		if(isNaN(td_price) === true) td_price = 0.00;
		if(isNaN(td_qty) === true) td_qty = 0.00;
		if(isNaN(td_amount) === true) td_amount = 0.00;
		if (td_amount > 0.00 && td_qty > 0.00){
			td_price = td_amount / td_qty;
			row.find('input.price').val(addCommas(td_price.toFixed(2)));
		}
		row.find('input.amount').val(addCommas(td_amount.toFixed(2)));
		refresh_cum();
	});
	function calc_fields(row){
		var td_qty = row.find("input.qty").val();
		var td_price = row.find("input.price").val();
		var td_amount = row.find("input.amount").val();
		
		td_price = parseFloat(convertToNumbers(td_price));
		td_qty = parseFloat(convertToNumbers(td_qty));
		td_amount = parseFloat(convertToNumbers(td_amount));
		if(isNaN(td_price) === true) td_price = 0.00;
		if(isNaN(td_qty) === true) td_qty = 0.00;
		if(isNaN(td_amount) === true) td_amount = 0.00;
		
		if (td_qty > 0.00){
			if (td_price > 0.00){
				td_amount = td_qty * td_price;
				row.find('input.amount').val(addCommas(td_amount.toFixed(2)));
			}
			else if (td_price == 0.00){
				if (td_amount > 0.00){
					td_price = td_amount / td_qty;
					row.find('input.price').val(addCommas(td_price.toFixed(2)));
				}
			}
		}
		else if (td_qty == 0.00){
			if (td_price > 0.00 && td_amount > 0.00){
				 td_qty = td_amount / td_price;
				 row.find('input.qty').val(addCommas(td_qty.toFixed(2)));
			}
		}
		row.find('input.price').val(addCommas(td_price.toFixed(2)));
		row.find('input.qty').val(addCommas(td_qty.toFixed(2)));
		refresh_cum();
	}
	function refresh_cum(){
		//iterate through the amount fields
		var sum = 0;
		$('#table-input tbody input.amount').each(function(){
			//$(this).css('text-align','right');
			var value = $(this).val();
			value = parseFloat(convertToNumbers(value));
			// add only if the value is number
			if(!isNaN(value) && value.length != 0) {
				sum += parseFloat(value);
			}
		});
		sum = addCommas(sum.toFixed(2))
		$('#cumulative').val(sum);
		//$("#cumulative").css('text-align','right');
	}
	$("#table-input tbody").on("click", ".del-this", function(e){
		e.preventDefault();
		try{
			var this_ = $(this);
			//check if there is amount
			var td_amount = this_.closest('tr').find('input.amount').val();
			
			if(td_amount !="" && td_amount !=null){
				BootstrapDialog.confirm({
					title: 'Delete a Row',
					message: 'Are you sure you want to delete this row?',
					type: BootstrapDialog.TYPE_DANGER,
					closable: true,
					callback: function(result) {
						if(result) {
							del_row(this_);
						}
					}
				});
			}else{
				del_row(this_);
			}
		}catch (e){}
	});
	function del_row(this_){
		this_.fadeOut('slow',function(){
			this_.closest('tr').remove();
			refresh_cum();
		});
	}
	$('input.number-input').on('input', function() {
	  	this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
		//$(this).css('text-align','right');
	});
	
	$(document).ready(function() {
		$("input:text").focus(function() { $(this).select(); } );
	});
	$("#table-input tbody").keydown(function (event) {
		//event.preventDefault();
		if (event.ctrlKey || event.metaKey) {
			switch (String.fromCharCode(event.which).toLowerCase()) {
			case 's':
				submit_input();//save
				break;
			case 'i':
				add_row(); //add a row
				break;
			case 'd':
				event.preventDefault();
				//it can not delete very clean
				break;
			}
		}
	});
	function validateFormOnSubmit() {
		var reason = "";
		
		reason += validate_empty(document.getElementById("voucher_no"));
		reason += validate_empty(document.getElementById("beneficiary"));
		reason += validate_empty(document.getElementById("description"));
		reason += validate_empty(document.getElementById("bank_ref"));
		reason += validate_date(document.getElementById("txn_date"));
		reason += validate_select(document.getElementById("bank_id"));
		reason += validate_select(document.getElementById("pay_channel"));
		//////////now check the table data for validity
		$('#table-input tbody tr').each(function(){
								
			var td_expense = $(this).closest('tr').find('select.expense_id').val();
			var td_price = $(this).closest('tr').find('input.price').val();
			var td_qty = $(this).closest('tr').find('input.qty').val();
			var td_amount = $(this).closest('tr').find('input.amount').val();
			var td_narration = $(this).closest('tr').find('input.narration').val();
			
			td_price = parseFloat(convertToNumbers(td_price));
			td_qty = parseFloat(convertToNumbers(td_qty));
			td_amount = parseFloat(convertToNumbers(td_amount));
			if(isNaN(td_price) === true) td_price = 0.00;
			if(isNaN(td_qty) === true) td_qty = 0.00;
			if(isNaN(td_amount) === true) td_amount = 0.00;
			if( td_amount == 0 || td_qty == 0 || td_price == 0) {
				reason += "Amount in the table should not be blank \n";
			}
			if( td_narration == null || td_narration == "") reason += "Narration should not be blank \n";
			if( td_expense == null || td_expense == "") reason += "Expense code can not be blank \n";
		});
		var cum_amount = $('#total_amount').val();
		var ctrl_amount = $('#cumulative').val();
		cum_amount = parseFloat(convertToNumbers(cum_amount));
		ctrl_amount = parseFloat(convertToNumbers(ctrl_amount));
		if ( cum_amount != ctrl_amount) {
			reason += cum_amount + " The posting figures do not agree \n";
		}
		
		if (reason != "") {
			alert("Some fields need correction:\n" + reason);
			return false;
		}
		//convert all inputs to database formats
		var txn_date = $('#txn_date').val();
		$('#txn_date').val(toDbaseDate(txn_date));
						
		return true;
	}
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		var dates = $('#date_range').val();
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
			
			$.get("{{ route('excelExpense') }}", {start_date: start_date, end_date: end_date}, function(data){
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
		}else{
			alert('Please select valid date range');	
		}
	});
	////////////////////////////////////////This is the search area
	$("#rmenu").on("click", "a", function(e){
		e.preventDefault();
		var $this = $(this);
		document.getElementById("searc-butt").innerHTML = 
							'<span class="fa fa-search-plus"></span>';
		$("#search_param").val($this.data("value"));
		$("#search_param").attr('name',$this.data("value"));
	});
	$(document).on('click', '#searc-butt', function(e){
		e.preventDefault();
		var search_param = $('#search_param').val();  //search parameter
		var search_val = $('#search-val').val();  //search value
		var dates = $('#date_range').val();
		//extract start date and end date
		var end_date = dates.slice(-10);
		var start_date = dates.slice(0,10);
		var reason = "";
		reason += validate_date_val(start_date);
		reason += validate_date_val(end_date);
		if(reason == ""){
			if( search_param !== "All" && (search_val == null || search_val == "")){
				alert("The search value cannot be blank");
				return;
			}
			//if the first sign in amount is < or > then extract this separately. default is =
			if( search_param == "Amount" ){
				var search_amount = search_val;
				var sign_ = search_val.substring(0, 1);
				if( sign_ == "<" || sign_ == ">" || sign_ == "="){
					search_amount = search_amount.substring(2); //Begin the extraction at position 2, and extract the rest of the string:
				}
				//check if it is well formatted
				var td_amount = parseFloat(convertToNumbers(search_amount));
				if(isNaN(td_amount) === true){
					alert("Invalid value in the amount field");
					return;
				}
			}
			//proceed to the server after this validation
			start_date = toDbaseDate(start_date);
			end_date = toDbaseDate(end_date);
			document.getElementById("searc-butt").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			
			var table = $('#exp-search-table').DataTable();
				table.clear();
				table.destroy();
				
			$.get("{{ route('queryExpense') }}", {
					start_date: start_date, 
					end_date: end_date,
					search_param: search_param,
					search_val: search_val
				}, function(data){
					if (data.length > 0) {
						$.each(data, function(i,l){
							var row_data = '<tr>' +
								'<td><a href="#" data-id="' + l.txn_id +'" class="edit-this">'+ l.txn_id +'</a></td>'+
								'<td class="text-right">'+ convertDate(l.txn_date) +'</td>'+
								'<td>'+ l.beneficiary +'</td>'+
								'<td>'+  l.voucher_no  +'</td>'+
								'<td>'+  l.expense_name  +'</td>'+
								'<td>'+  l.narration +'</td>'+
								'<td class="text-right">'+ numberWithCommas(l.qty) +'</td>'+
								'<td class="text-right">'+ numberWithCommas(l.price) +'</td>'+
								'<td class="text-right">'+ numberWithCommas(l.amount) +'</td>'+
								'<td>'+  l.bank_name +'</td>'+
								'<td>'+  l.bank_ref +'</td>'+
								'<td>'+  l.pay_channel +'</td>'+
								'<td style="vertical-align: middle; width: 25px;"><button value="'+  l.txn_id +'" class="btn btn-info btn-sm review-this"><i class="fa fa-check-square fa-lg"></i></button>'+
								'</td>'+
								'<td style="vertical-align: middle; width: 25px;"><button value="'+  l.txn_id +'" class="btn btn-danger btn-sm remove-this"><i class="fa fa-trash-o fa-lg"></i></button>'+
								'</td>'+
							'</tr>';
							
							$('#exp-search-table> tbody:last-child').append(row_data);
						});
						
					}else{alert('No record to display...');}
					//$('#exp-search-table').DataTable();
					
					/*$('#exp-search-div').empty().append(data);*/
					
					$('#exp-search-table').DataTable({
						dom: 'Bfrtip',
						buttons: [
							'copyHtml5',
							'excelHtml5',
							'csvHtml5'
						]
					})
					document.getElementById("searc-butt").innerHTML = 
						'<span class="fa fa-search-plus"></span>';
			});
			
		}else{
			alert('Please select valid date range');	
		}
	});
	
	$('#btn_update_expense').on('click', function(e){
		e.preventDefault();
		try{
			var table = $('#frm-import-expense #import_expense tbody');
			
			document.getElementById("btn_update_expense").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
			
			table.find('tr').each(function(i, el){
				var is_valid = true;
				var tds = $(this).find('td');
				
				var expense_date = toDbaseDate(tds.eq(0).text());
				var voucher = tds.eq(1).text();
				var beneficiary = tds.eq(2).text();
				var channel = tds.eq(3).text();
				var bank = tds.eq(4).text();
				var reference = tds.eq(5).text();
				var expense = tds.eq(6).text();
				var qty = tds.eq(7).text();
				var price = tds.eq(8).text();
				var amount = tds.eq(9).text();
				var narration = tds.eq(10).text();
				
				if( expense_date == "" || voucher == "" || beneficiary == "" || channel == "" || bank == "" || narration == "" || 
					reference == "" || narration == "" || expense == "" || qty == "" || price == "" || amount == ""){
					is_valid = false;
				}
				qty = parseFloat(convertToNumbers(qty));
				if(isNaN(qty) === true) qty = 1;
				
				price = parseFloat(convertToNumbers(price));
				if(isNaN(price) === true) price = 0.00;
				if( price == 0 ) is_valid = false;
				
				amount = parseFloat(convertToNumbers(amount));
				if(isNaN(amount) === true) amount = 0.00;
				if( amount == 0 ) is_valid = false;
				
				if (is_valid){
					$('#frm-data-expense #expense_date').val(expense_date);
					$('#frm-data-expense #voucher').val(voucher);
					$('#frm-data-expense #beneficiary').val(beneficiary);
					$('#frm-data-expense #channel').val(channel);
					$('#frm-data-expense #bank').val(bank);
					$('#frm-data-expense #reference').val(reference);
					$('#frm-data-expense #expense').val(expense);
					$('#frm-data-expense #qty').val(qty);
					$('#frm-data-expense #price').val(price);
					$('#frm-data-expense #amount').val(amount);
					$('#frm-data-expense #narration').val(narration);
					
					$.ajax({
						url: "{{ route('updatePaymentImport')}}",
						data: new FormData($("#frm-data-expense")[0]),
						async:false,
						type:'post',
						processData: false,
						contentType: false,
						success:function(data){
							
							if(Object.keys(data).length > 0){
								//remove the row
								//$(this).remove();
								$(this).closest('tr').remove();
							}						},
						error: function(jqXHR, exception) { 
							return false;
							return_error(jqXHR, exception);
						}
					});
				}
			});
			
		}catch(err){
			return false; 
			alert(err.message); 
		}
	});
	$('#btn_import_expense').on('click', function(e){
		e.preventDefault();
		
		document.getElementById("btn_import_expense").innerHTML = 
			'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
				
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to upload this file?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				if(result) {
					$.ajax({
						url: "{{ route('expenseImport')}}",
						data: new FormData($("#frm-import-expense")[0]),
						async:false,
						type:'post',
						processData: false,
						contentType: false,
						success:function(data){
							$('#table_div_expense').children().remove();
							$('#table_div_expense').append(data);
							document.getElementById("btn_import_expense").innerHTML = 
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
	$("#exp-search-table tbody").on("click", ".remove-this", function(e){
		e.preventDefault();
		var txn_id = $(this).val();
		//NB: bring up all the transactions in the batch in a dialog form, to ensure proper information before deletion
		$.get("{{ route('delExpBatch') }}", {txn_id: txn_id}, function(data){
			alert(txn_id);
			var count = 0;
			$('#deleteForm #txn_id').val(txn_id);
			var voucher = '';
			var beneficiary = '';
			$.each(data, function(i,l){
				alert(count);
				if(count == 0){
					$('#deleteForm #txn_date').val(l.txn_date);
					$('#deleteForm #pay_channel').val(l.channel);
					$('#deleteForm #bank_id').val(l.bank);
					$('#deleteForm #bank_ref').val(l.reference);
					$('#deleteForm #description').val(l.description);
					$('#deleteForm #total_amount').val(l.total_amount);
				}else{
					//fill in the table with data
					voucher = l.voucher;
					beneficiary = l.beneficiary;
					var row_data = '<tr>' +
							'<td>' + l.expense +  '</td>'+
							'<td>' + l.qty +  '</td>'+
							'<td>' + l.price +  '</td>'+
							'<td>' + l.amount +  '</td>'+
							'<td>' + l.narration +  '</td>'+
						'</tr>';
					$('#deleteForm #table-input tbody').append(row_data);
				}
				count = count + 1;
			});
			$('#deleteForm #beneficiary').val(beneficiary);
			$('#deleteForm #voucher_no').val(voucher);
			$('#delete_batch_show').modal();
		});	
	});
	$("#deleteForm").on("click", "#btn-delete", function(e){
		e.preventDefault();
		
		var operator = $('#frm-create-exp-txn #operator').val();
		var txn_id = $('#deleteForm #txn_id').val();
		
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delExpense') }}", {txn_id: txn_id, operator: operator}).done(function(data){ 
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
	
</script>
@endsection