@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Transaction</li>
                <li><a href="{{route('getTxnFT')}}"><i class="fa fa-bank"></i>Funds Transfer</a></li
            ></ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#transfer" data-toggle="tab" class="nav-tab-class">Transfer Input</a></li>
                <!--<li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Transfers</a></li>-->
                <li class="nav"><a href="#import-lodgement" data-toggle="tab" class="nav-tab-class">Import Lodgements</a></li>
                <li class="nav"><a href="#view-lodgement" data-toggle="tab" class="nav-tab-class">View Lodgement</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="transfer">
                    <div class="panel panel-body">
                    <form action="" method="POST" id="frm-create-ft-txn" enctype="multipart/form-data" >
                        <input type="hidden" name="txn_id" id="txn_id"> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="row">
                            <div class="form-group col-md-12">
                                
                                <div class="pull-left">
                                    <button type="button" class="btn btn-default" id="add_row">
                                        <i class="fa fa-plus fa-fw fa-fw"></i>Add a Record</button>
                                </div><div class="col-md-3">
                                    <input type="text" class="form-control number-input text-right" id="control" 
                                    placeholder="enter control amount" onkeyup="return allow_number(this)">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control number-input text-right" id="cumulative" 
                                    placeholder="cumulative amount" disabled>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary" id="btn-save">
                                        <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                </div>
                                
                            </div>
                            <div class="col-md-12">
                                <table class="table table-hover table-striped table-condensed" id="table-input">
                                    <thead>
                                        <tr>
                                            <th style="width:10%;">Date</th>
                                            <th style="width:10%;">Channel</th>
                                            <th style="width:15%;">Paying(Credit)</th>
                                            <th style="width:15%;">Receiving(Debit)</th>
                                            <th style="width:10%;">Txn Ref</th>
                                            <th style="width:15%;">Amount</th>
                                            <th style="width:20%;">Narration</th>
                                            <th style="width:5%;">Action</th>
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
                <div class="tab-pane fade in" id="view">
                    <div class="panel panel-body" style="margin-top:5px;">
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
                                      <li><a href="#" data-value="Bank_From">Bank From</a></li>
                                      <li><a href="#" data-value="Bank_To">Bank To</a></li>
									  <li><a href="#" data-value="Channel">Channel</a></li>
                                      <li><a href="#" data-value="Txn_Ref">Txn Ref</a></li>
                                      <li><a href="#" data-value="Amount">Amount</a></li>
                                    </ul>
                                </div>
                                
                                <input type="hidden" name="search_param" value="all" id="search_param">         
                                <input type="text" class="form-control" name="x" id="search-val" placeholder="Search value...">
                                
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="searc-butt" type="button">
                                    <span class="fa fa-search-plus"></span></button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="export-to-excel">
										<i class="fa fa-file-excel-o fa-fw fa-fw"></i>Export to Excel
									</button>
                                </span>
                            </div>
                        </div>
                        <!---the action should allow for print a single record and delete, if not enrolled(enrol=0)---->
                        <div class="form-group" id="ft-search-div">
                            <table class="table table-hover" id="ft-search-table">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">ID</th>
                                        <th style="width:10%;">Date</th>
                                        <th style="width:10%;">Paying(CR)</th>
                                        <th style="width:10%;">Receiving(DR)</th>
                                        <th style="width:10%;">Channel</th>
                                        <th style="width:15%;">Amount</th>
                                        <th style="width:10%;">Txn Ref</th>
                                        <th style="width:10%;">Narration</th>
										<th style="width:10%;">Batch Ref</th>
                                        <th style="width:5%;">Action</th>
                                     </tr>
                                </thead>
                                <tbody id="table-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade in" id="view-lodgement">
                    <div class="panel panel-body" style="margin-top:1px;">
                        <div class="panel panel-body" style="margin-top:1px;">
                            <div class="col-md-5 pull-left">
                                <div class="input-group">
                                    <input type="text" class="form-control" name ="lodge-input" id= "lodge-input" 
                                        placeholder="31/12/2017 30/09/2018">
                                    <div class="input-group-btn search-panel">
                                        <button type="button" id="lodge-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
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
                                    <li id="export-to-excel"><a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw">
                                        </i>Export to Excel</a></li>
                                    </ul>
                            </div>
                            @endif
                        </div>
                        
                        <!---the action should allow for print a single record and delete, if not enrolled(enrol=0)---->
                        <div class="form-group">
                           
                            <table class="table table-hover table-striped table-condensed" id="lodge-table"
                                style="font-size:100%">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">Date</th>
                                        <th style="width:15%;">Channel</th>
                                        <th style="width:15%;">Paying(Credit)</th>
                                        <th style="width:15%;">Receiving(Debit)</th>
                                        <th style="width:10%;">Txn Ref</th>
                                        <th style="width:15%;">Amount</th>
                                        <th style="width:20%;">Narration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
            	</div>
                <div class="tab-pane fade in" id="import-lodgement">
                    <div class="panel panel-body" style="margin-top:5px;">
                        <div class="panel panel-body" style="margin-top:5px;">
                            <form method="post" class="form-horizontal" id="frm-import-lodgement" name="frm-import-lodgement"
                               role="form" enctype="multipart/form-data" return false>
                               
                               <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                               <input type="hidden" id="token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <input type="file" id="table_file" name="table_file">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-default" id="btn_import_lodgement">
                                            <i class="fa fa-upload fa-fw"></i>Upload</button>
                                    </div>
                                    <div class="col-md-2 pull-right">
                                        <button id="btn_update_lodgement"
                                            type="button"  class="btn btn-primary" >
                                                <i class="fa fa-save fa-fw"></i>Update Database</button>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <!--<hr />-->
                                            <div id="table_div_lodgement">
                                                <!--populate dynamically-->
                                                <table class="table table-hover table-striped table-condensed" id="import_lodgement_table"
                                                    style="font-size:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:10%;">Date</th>
                                                            <th style="width:15%;">Channel</th>
                                                            <th style="width:15%;">Paying(Credit)</th>
                                                            <th style="width:15%;">Receiving(Debit)</th>
                                                            <th style="width:10%;">Txn Ref</th>
                                                            <th style="width:15%;">Amount</th>
                                                            <th style="width:20%;">Narration</th>
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
    <form action="" method="POST" class="form-horizontal" name= "frm-data-lodgement" id="frm-data-lodgement" 
     	enctype="multipart/form-data" return false>
        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
        <input type="hidden" name="reviewer" id="reviewer" value="">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="lodgement_date" id="lodgement_date" value="">
        <input type="hidden" name="channel" id="channel" value="">
        <input type="hidden" name="paying" id="paying" value="">
        <input type="hidden" name="receiving" id="receiving" value="">
        <input type="hidden" name="reference" id="reference" value="">
        <input type="hidden" name="narration" id="narration" value="">
        <input type="hidden" name="amount" id="amount" value="">
        <iframe id="my_iframe" style="display:none;"></iframe>
     </form>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">

	var bank_to = '<select class="form-control bank_to" name="bank_to[]">';
	var bank_from = '<select class="form-control bank_from" name="bank_from[]">';
	var html = "";
	$.get("{{ route('getBankList') }}", function(data){
		
		$.each(data, function(i,l){
			var bank_name = l.bank_name;
			var bank_id = l.bank_id;
			html = html + "<option value='" + bank_id + "'>" + bank_name + "</option>";
		});
		html = html + '</select>';
		bank_to = bank_to + html;
		bank_from = bank_from + html;
	});
	var channel = '<select class="form-control" name="pay_channel[]" style="width: 175px;">'+
		'<option value="Cash">Cash</option>' +
		'<option value="Cheque">Cheque</option>' +
		'<option value="POS">POS</option>' +
		'<option value="Transfer">Transfer</option>' +
		'</select>';
	
	$('#add_row').on('click', function(e){
		e.preventDefault();
		add_row();
	});
	function add_row(){
		
		var row_data = '<tr>' +
			'<td><input type="text" placeholder="dd/mm/yyyy" onkeyup="this.value = fixDatePattern(this.value)" class="form-control txn_date text-right" name="txn_date[]" value=""></td>'+
			'<td>' + channel +  '</td>'+
			'<td>' + bank_from +  '</td>'+
			'<td>' + bank_to +  '</td>'+
			'<td><input type="text" class="form-control txn_ref" name="txn_ref[]" value=""></td>'+
			'<td align="right"><input type="text" class="form-control amount text-right" name="amount[]" onkeyup="return allow_number(this)"></td>'+
			'<td><input type="text" class="form-control narration" name="narration[]" value=""></td>'+
			'<td><button class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
		'</tr>';
		
		$('#table-input tbody').append(row_data);
	}
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
		
		//////////now check the table data for validity
		$('#table-input tbody tr').each(function(){
								
			var td_txn_date = $(this).closest('tr').find('input.txn_date').val();
			var td_bank_from = $(this).closest('tr').find('select.bank_from').val();
			var td_bank_to = $(this).closest('tr').find('select.bank_to').val();
			var td_amount = $(this).closest('tr').find('input.amount').val();
			var td_narration = $(this).closest('tr').find('input.narration').val();
			reason += validate_date_val(td_txn_date);
			
			td_amount = parseFloat(convertToNumbers(td_amount));
			if(isNaN(td_amount) === true) td_amount = 0.00;
			if( td_amount == 0 ) {
				reason += "Amount in the table should not be blank \n";
			}
			if( td_narration == null || td_narration == "") reason += "Narration should not be blank \n";
			if( td_bank_from == td_bank_to ) reason += "You cannot transfer to the same account \n";
			if( td_bank_from == null ||  td_bank_from == "" || td_bank_to == null || td_bank_to == "") 
				reason += "Bank account cannot be empty \n";
			reason += validate_date_val(td_txn_date);
		});
		var cum_amount = $('#control').val();
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
		return true;
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
	$("#table-input tbody").on("change", ".amount", function(){
		refresh_cum();
	});
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
						  	url: "{{ route('updateTxnFT')}}",
						    data: new FormData($("#frm-create-ft-txn")[0]),
						  	async:false,
						  	type:'post',
						  	processData: false,
						  	contentType: false,
						  	success:function(data){
								
								if(Object.keys(data).length > 0){
									$('#frm-create-ft-txn')[0].reset();
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
	////////////////////////////////////////This is the search area
	
	/*$('#searc-butt').on('click', function(e){
		e.preventDefault();
		var search_param = $('#search_param').val();
		var search_val = $('#search_val').val()
		$.get("{{ route('searchFT') }}", {search_param: search_param, search_val: search_val}, function(data){
			$('#ft-search-div').empty().append(data);
		});
	});*/
	
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
			if( search_param !== "All" && (search_val == null || search_val == "" )){
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
			var table = $('#ft-search-table').DataTable();
			table.clear();
			table.destroy();
			
			$('#ft-search-table> tbody').empty();
			document.getElementById("searc-butt").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			$.get("{{ route('queryFT') }}", {
					start_date: start_date, 
					end_date: end_date,
					search_param: search_param,
					search_val: search_val
				}, function(data){
					
					$('#ft-search-div').empty().append(data);
					$('#ft-search-table').DataTable({
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
			alert('Please select a valid date range');	
		}
	});
	$("#ft-search-table tbody").on("click", ".del-this", function(e){
		e.preventDefault();
		var txn_id = $(this).val();
		var operator = $('#frm-create-ft-txn #operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delTransfer') }}", {txn_id: txn_id, operator: operator}).done(function(data){ 
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
			
			$.get("{{ route('excelTransfer') }}", {start_date: start_date, end_date: end_date}, function(data){
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
	$(document).on('click', '#lodge-search', function(e){
		e.preventDefault();
		//extract the dates from the string
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
			$('#lodge-table> tbody').empty();
			
			$.get("{{ route('searchLodge') }}", {start_date: start_date, end_date: end_date}, function(data){
				if (data.length > 0) {
					$.each(data, function(i,l){
						var row_data = '<tr>' +
							'<td align="right">'+ convertDate(l.txn_date) + '</td>'+
							'<td>' + l.pay_channel +'</td>'+
							'<td>' + l.bank_from +'</td>'+
							'<td>' + l.bank_to +'</td>'+
							'<td>' + l.bank_ref +'</td>'+
							'<td align="right">' + numberWithCommas(l.amount) +'</td>'+
							'<td>' + l.narration +'</td>'
							'</tr>';
						$('#lodge-table> tbody:last-child').append(row_data);
					});
				}else{
					alert('No record to display...');	
				}
			});
		}else{
			alert('Please select valid date range');	
		}
	});
	$('#btn_import_lodgement').on('click', function(e){
		e.preventDefault();
				
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to upload this file?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				if(result) {
					document.getElementById("btn_import_lodgement").innerHTML = 
						'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
					$.ajax({
						url: "{{ route('lodgementImport')}}",
						data: new FormData($("#frm-import-lodgement")[0]),
						async:false,
						type:'post',
						processData: false,
						contentType: false,
						success:function(data){
							$('#table_div_lodgement').children().remove();
							$('#table_div_lodgement').append(data);
							document.getElementById("btn_import_lodgement").innerHTML = 
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
	
	$('#btn_update_lodgement').on('click', function(e){
		e.preventDefault();
		try{
			BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to update this file?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				if(result) {
					
					var table = $('#import_lodgement_table tbody');
					
					document.getElementById("btn_update_lodgement").innerHTML = 
						'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
					
					table.find('tr').each(function(i, el){
						var is_valid = true;
						var tds = $(this).find('td');
						var lodgement_date = toDbaseDate(tds.eq(0).text());
						//return false;
						//alert(lodgement_date);
						var channel = tds.eq(1).text();
						var paying = tds.eq(2).text();
						var receiving = tds.eq(3).text();
						var reference = tds.eq(4).text();
						var amount = tds.eq(5).text();
						var narration = tds.eq(6).text();
						if( channel == "" || paying == "" || lodgement_date == "" || receiving == "" || amount == "" || 
							reference == "" || narration == ""){
							is_valid = false;
						}
						var amount = parseFloat(convertToNumbers(amount));
						if(isNaN(amount) === true) amount = 0.00;
						if( amount == 0 ) is_valid = false;
						
						if (is_valid){
							$('#frm-data-lodgement #lodgement_date').val(lodgement_date);
							$('#frm-data-lodgement #channel').val(channel);
							$('#frm-data-lodgement #paying').val(paying);
							$('#frm-data-lodgement #receiving').val(receiving);
							$('#frm-data-lodgement #reference').val(reference);
							$('#frm-data-lodgement #amount').val(amount);
							$('#frm-data-lodgement #narration').val(narration);
							var $ele = $(this).closest('tr');
							$.ajax({
								url: "{{ route('updateImportLodgement') }}",
								data: new FormData($("#frm-data-lodgement")[0]),
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
					document.getElementById("btn_update_lodgement").innerHTML = 
						'<i class="fa fa-save fa-fw"></i>Update Database';
				}
			}
			});
		}catch(err){
			return false; 
			alert(err.message); 
		}
	});
	
	
</script>
@endsection