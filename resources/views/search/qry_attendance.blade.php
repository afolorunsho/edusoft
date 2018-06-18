@extends('layouts.master')
@section('content')
	<div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Enquiries</a></li>
                <li><a href="#"><i class="fa fa-user"></i>Attendance</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			<div class="col-md-4 pull-left">
				{{----attendance is possible just for a single day-----}}
				<label class="col-md-3" for="date_range">Date</label>
				<div class="col-md-9 pull-left">
					<input type="" name="date_range" class="form-control" id="date_range" 
						placeholder="01/01/2018">
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
						  <li><a href="#" data-value="Bank_From">Class</a></li>
						  <li><a href="#" data-value="Bank_To">Section</a></li>
						</ul>
					</div>
					
					<input type="hidden" name="search_param" value="all" id="search_param">         
					<input type="text" class="form-control" name="x" id="search-val" placeholder="Enter search value...">
					
					<span class="input-group-btn">
						<button class="btn btn-default" id="searc-butt" type="button">
						<span class="fa fa-search-plus"></span></button>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group table-responsive" style="overflow: auto" id="ft-search-div">
				<table class="table table-hover" id="search-table">
					<thead>
						<tr>
							<th style="width:5%;">Date</th>
							<th style="width:5%;">Reg No</th>
							<th style="width:15%;">Student</th>
							<th style="width:5%;">Class</th>
                            <th style="width:5%;">Remarks</th>
                            <th style="width:5%;">Time</th>
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
	});
	$(document).on('click', '#searc-butt', function(e){
		e.preventDefault();
		var search_param = $('#search_param').val();  //search parameter
		var search_val = $('#search-val').val();  //search value
		var dates = $('#date_range').val();
		
		//extract start date and end date
		var start_date = dates.slice(0,10);
		var reason = "";
		reason += validate_date_val(start_date);
		var search_operator = '';
		if(reason == ""){
			if( search_param !== "All" && (search_val == null || search_val == "" )){
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
				}*/
				//check if it is well formatted
				var td_amount = parseFloat(convertToNumbers(search_amount));
				if(isNaN(td_amount) === true){
					alert("Invalid value in the amount field: <, >, = are the only allowed signs and 0 should be entered as 0.00");
					return;
				}
			}
			//proceed to the server after this validation
			start_date = toDbaseDate(start_date);
			var table = $('#search-table').DataTable();
				table.clear();
				table.destroy();
			$('#search-table>tbody').empty();
			//$('#search-table').dataTable().fnDestroy();
			
			document.getElementById("searc-butt").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			$.get("{{ route('qryAttend') }}", {
					start_date: start_date, 
					search_param: search_param,
					search_operator: search_operator,
					search_val: search_val
				}, function(data){
					if (data.length > 0) {
						if (data.length > 0) {
							$.each(data, function(i,l){
								var row_data = '<tr>' +
									'<td align="right">'+ convertDate(l.attendance_date) + '</td>'+
									'<td>' + l.reg_no +'</td>'+
									'<td>' + l.last_name + ', ' + l.first_name + '</td>'+
									'<td>' + l.class_div +'</td>'+
									'<td>' + l.remarks +'</td>'+
									'<td>' + l.arrival_time +'</td>'+
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
							})
						}else{
							alert('No record to display...');	
						}
					}else{
						alert('No record to display...');	
					}
					document.getElementById("searc-butt").innerHTML = 
						'<span class="fa fa-search-plus"></span>';
			});
			
		}else{
			alert('Please select a valid date range');	
		}
	});
	
</script>
@endsection