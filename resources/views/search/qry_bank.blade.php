@extends('layouts.master')
@section('content')
	<div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Enquiries</a></li>
                <li><a href="#"><i class="fa fa-user"></i>Bank Transaction</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			<div class="col-md-3">
				<label for="reg_no" class="control-label col-md-4">Bank</label>
				<div class="form-group col-md-8">
					<select class="form-control" name="bank_id" id="bank_id" >
						<option value="">Please Select...</option>
						@foreach($bank as $y)
							<option value="{{ $y->bank_id}}">
							{{ $y->bank_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<label class="col-md-4" for="date_range">Date Period</label>
				<div class="col-md-8">
					<input type="" name="date_range" class="form-control" id="date_range" 
						placeholder="01/01/2018 31/12/2018">
				</div>
			</div>
            <div class="col-md-4 input-group">
                <div class="input-group-btn search-panel">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span id="search_concept">Search Criteria</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu"  id="rmenu">
                    
                      <li><a href="#" data-value="All">All</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="#" data-value="Channel">Channel</a></li>
                      <li><a href="#" data-value="Txn_Ref">Txn Ref</a></li>
                      <li><a href="#" data-value="Amount">Amount</a></li>
                      <li><a href="#" data-value="Type">Type</a></li>
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
                                    
                <input type="text" class="form-control" name="x" id="search-val" placeholder="Enter search value...">
                
                <span class="input-group-btn">
                    <button class="btn btn-default" id="searc-butt" type="button">
                    <span class="fa fa-search-plus"></span></button>
                </span>
            </div>
		</div>
		<div class="col-md-12">
			<div class="form-group table-responsive" style="overflow: auto" id="ft-search-div">
				<table class="table table-hover" id="search-table">
					<thead>
						<tr>
							<th style="width:10%;">Txn Date</th>
							<th style="width:10%;">Post Date</th>
							<th style="width:15%;">Bank</th>
                            <th style="width:10%;">Payment ID</th>
							<th style="width:15%;">Txn Ref</th>
							<th style="width:15%;">Channel</th>
							<th style="width:15%;">Narration</th>
							<th style="width:10%;">Type</th>
							<th style="width:10%;">Amount</th>
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
		var bank_id = $('#bank_id').val();
		//extract start date and end date
		var end_date = dates.slice(-10);
		var start_date = dates.slice(0,10);
		var reason = "";
		var search_operator = '';
		reason += validate_date_val(start_date);
		if( search_param !== "Balances" ){
			reason += validate_date_val(end_date);
		}
		if(reason == ""){
			if( (search_param !== "All" && search_param !== "Balances"  && search_param !== "Activities") && (search_val == null || search_val == "" )){
				alert("The search value cannot be blank");
				return;
			}
			//if the first sign in amount is < or > then extract this separately. default is =
			if( search_param == "Amount" ){
				var search_amount = search_val;
				search_operator = $('#search_operator').val();
				/*var sign_ = search_val.substring(0, 1);
				if( sign_ == "<" || sign_ == ">" || sign_ == "="){
					search_amount = search_amount.substring(2); //Begin the extraction at position 2, and extract the rest of the string:
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
			end_date = toDbaseDate(end_date);
			$('#search-table>tbody').empty();
			
			document.getElementById("searc-butt").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			$.get("{{ route('qryBank') }}", {
					start_date: start_date, 
					end_date: end_date,
					search_param: search_param,
					search_val: search_val,
					search_operator: search_operator,
					bank_id: bank_id
				}, function(data){
					if( (search_param == "Balances" || search_param == "Activities") && data.length > 0){
						$('#search-table').empty().append(data);
					}else{
						if (data.length > 0) {
							var table = $('#search-table').DataTable();
							table.clear();
							table.destroy();
							//$('#search-table').dataTable().fnDestroy();
							$('#search-table>tbody').empty();
							$.each(data, function(i,l){
								var row_data = '<tr>' +
									'<td align="right">'+ convertDate(l.txn_date) + '</td>'+
									'<td align="right">'+ convertDate(l.created_at) + '</td>'+
									'<td>' + l.bank_name +'</td>'+
									'<td>' + l.payment_id +'</td>'+
									'<td>' + l.reference +'</td>'+
									'<td>' + l.channel +'</td>'+
									'<td>' + l.narration +'</td>'+
									'<td>' + l.txn_type +'</td>'+
									'<td align="right">' + numberWithCommas(l.amount) +'</td>'+
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
					document.getElementById("searc-butt").innerHTML = 
						'<span class="fa fa-search-plus"></span>';
					if (data.length <= 0) alert('No record was returned...');
			});
		}else{
			alert('Please select a valid date range');	
		}
	});
	
</script>
@endsection