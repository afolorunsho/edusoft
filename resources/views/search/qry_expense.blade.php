@extends('layouts.master')
@section('content')
	
	<div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Enquiries</a></li>
                <li><a href="#"><i class="fa fa-user"></i>Expenses</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			<div class="col-md-5 pull-left">
				
				<label class="col-md-4" for="date_range">Date Period</label>
				<div class="col-md-8 pull-left">
					<input type="" name="date_range" class="form-control" id="date_range" 
						placeholder="01/01/2018 31/12/2018">
				</div>
			</div>
			<div class="col-md-7 pull-right">
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
						  <li><a href="#" data-value="Reference">Reference</a></li>
						  <li><a href="#" data-value="Beneficiary">Beneficiary</a></li>
						  <li><a href="#" data-value="Channel">Channel</a></li>
						  <li><a href="#" data-value="Amount">Amount</a></li>
						  <li><a href="#" data-value="Activities">Activities</a></li>
						  <li><a href="#" data-value="Balances">Balances</a></li>
						</ul>
                        <select name="search_operator" id="search_operator" style="display:none; border:none;">
                            <option value="=">Equal</option>
                            <option value=">=">Greater Equal</option>
                            <option value="<=">Less Equal</option>
                            <option value="<>">Not Equal</option>
                            <option value="<">Less Than</option>
                            <option value=">">Greater Than</option>
                        </select>
					</div>
					<input type="hidden" name="search_param" value="all" id="search_param">
					
					<input type="text" class="form-control" name="x" id="search-val" 
						placeholder="Enter search value...">
					
					<span class="input-group-btn">
						<button class="btn btn-default" id="searc-butt" type="button">
						<span class="fa fa-search-plus"></span></button>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-12">
        	<div class="form-group table-responsive" style="overflow: auto" id="exp-search-div">
				<table class="table table-hover" id="search-table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Voucher</th>
							<th>Expense</th>
							<th>Narration</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Amount</th>
							<th>Bank</th>
							<th>Bank Ref</th>
							<th>Channel</th>
							<th>Batch Ref</th>
							<th>Beneficiary</th>
						 </tr>
					</thead>
					<tbody id="table-body">
					</tbody>
				</table>
			</div>
		</div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
	$("#rmenu").on("click", "a", function(e){
		e.preventDefault();
		var $this = $(this);
		document.getElementById("searc-butt").innerHTML = 
							'<span class="fa fa-search-plus"></span>';
		$("#search_param").val($this.data("value"));
		$("#search_param").attr('name',$this.data("value"));
		var search_param = $('#search_param').val();
		if( search_param == "Activities" ){
			$("search-val").attr("placeholder", "Enter Actual Expense Name to search...");
		}else{
			$("search-val").attr("placeholder", "Enter search value...");
		}
		if( search_param == "Amount" ){
			$('#search_operator').show();
		}else{
			$('#search_operator').hide();
		}
	});
	$(document).on('click', '#searc-butt', function(e){
		e.preventDefault();
		
		var search_param = $('#search_param').val();  //search parameter
		var search_val = $('#search-val').val();  //search value
		var dates = $('#date_range').val();
		//extract start date and end date
		var end_date = '';
		var start_date = dates.slice(0,10);
		var search_operator = '';
		var reason = "";
		reason += validate_date_val(start_date);
		if( search_param !== "Balances" ){
			end_date = dates.slice(-10);
			reason += validate_date_val(end_date);
		}
		if(reason == ""){
			if( search_param !== "All" && search_param !== "Balances" && (search_val == null || search_val == "")){
				alert("The search value cannot be blank");
				return;
			}
			//if the first sign in amount is < or > then extract this separately. default is =
			if( search_param == "Amount" ){
				search_operator = $('#search_operator').val();
				var search_amount = search_val;
				/*var sign_ = search_val.substring(0, 1);
				if( sign_ == "<" || sign_ == ">" || sign_ == "="){
					search_amount = search_amount.substring(2); //Begin the extraction at position 2, and extract the rest of the string:
				}else{
					sign = '=';
				}*/
				//check if it is well formatted
				var td_amount = parseFloat(convertToNumbers(search_amount));
				if(isNaN(td_amount) === true){
					alert("Invalid value in the amount field: 0 should be entered as 0.00");
					return;
				}
			}
			//proceed to the server after this validation
			start_date = toDbaseDate(start_date);
			if( search_param !== "Balances" ){
				end_date = toDbaseDate(end_date);
			}
			$('#search-table>tbody').empty();
			document.getElementById("searc-butt").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			$.get("{{ route('qryExpense') }}", {
					start_date: start_date, 
					end_date: end_date,
					search_param: search_param,
					search_operator: search_operator,
					search_val: search_val
				}, function(data){
					if( (search_param == "Balances" || search_param == "Activities") && data.length > 0){
						$('#exp-search-div').empty().append(data);
							
					}else{
						if (data.length > 0) {
							//$('#search-table').dataTable().fnDestroy();
							var table = $('#search-table').DataTable();
							table.clear();
							table.destroy();
							$('#search-table> tbody').empty();
							
							$.each(data, function(i,l){
								var row_data = '<tr>' +
									'<td align="right">'+ convertDate(l.txn_date) + '</td>'+
									'<td>' + l.voucher_no +'</td>'+
									'<td>' + l.expense_name +'</td>'+
									'<td>' + l.narration +'</td>'+
									'<td>' + l.qty +'</td>'+
									'<td>' + l.price +'</td>'+
									'<td align="right">' + numberWithCommas(l.amount) +'</td>'+
									'<td>' + l.bank_name +'</td>'+
									'<td>' + l.bank_ref +'</td>'+
									'<td>' + l.pay_channel +'</td>'+
									'<td>' + l.bank_payment_id +'</td>'+
									'<td>' + l.beneficiary +'</td>'+
									'</tr>';
								$('#search-table> tbody:last-child').append(row_data);
							});
							$('#search-table').DataTable({
								dom: 'Bfrtip',
								buttons: [
									'copyHtml5',
									'excelHtml5',
									'csvHtml5'
								]
							});
						}
					}
					if (data.length <= 0) alert('No record was returned...');
					document.getElementById("searc-butt").innerHTML = 
						'<span class="fa fa-search-plus"></span>';
			});
			
		}else{
			alert('Please select valid date range');	
		}
	});
	
</script>
@endsection