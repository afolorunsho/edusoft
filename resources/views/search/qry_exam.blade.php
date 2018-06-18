@extends('layouts.master')
@section('content')
	<div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Enquiries</a></li>
                <li><a href="#"><i class="fa fa-user"></i>Exams</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
		<div class="col-md-12">
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
						  <li><a href="#" data-value="Exam">Exam</a></li>
						  <li><a href="#" data-value="Subject">Subject</a></li>
						  <li><a href="#" data-value="Student">Student</a></li>
						  <li><a href="#" data-value="Term">Term</a></li>
						  <li><a href="#" data-value="Class">Class</a></li>
						  <li><a href="#" data-value="Section">Section</a></li>
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
							<th style="width:10%;">Date</th>
							<th style="width:10%;">Term</th>
							<th style="width:10%;">Reg No</th>
							<th style="width:10%;">Student</th>
							<th style="width:10%;">Class</th>
							<th style="width:10%;">Exam</th>
							<th style="width:10%;">Subject</th>
							<th style="width:15%;">Score</th>
							<th style="width:5%;">Max Score</th>
							<th style="width:10%;">Exam Weight</th>
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
			
			//proceed to the server after this validation
			start_date = toDbaseDate(start_date);
			end_date = toDbaseDate(end_date);
			var table = $('#search-table').DataTable();
			table.clear();
			table.destroy();
			$('#search-table>tbody').empty();
			//$('#search-table').dataTable().fnDestroy();
			
			document.getElementById("searc-butt").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			$.get("{{ route('qryExam') }}", {
					start_date: start_date, 
					end_date: end_date,
					search_param: search_param,
					search_val: search_val
				}, function(data){
					//$('#ft-search-div').empty().append(data);
					if (data.length > 0) {
						$('#ft-search-div').empty().append(data);
						$('#table-class-info').DataTable({
							dom: 'Bfrtip',
							buttons: [
								'copyHtml5',
								'excelHtml5',
								'csvHtml5'
								
							]
						})
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