@extends('layouts.master')
@section('content')
	<div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Enquiries</a></li>
                <li><a href="#"><i class="fa fa-user"></i>Posting Journal</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			
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
                    
                      <li><a href="#" data-value="Registration">Registration</a></li>
                      <li><a href="#" data-value="Enrolment">Enrolment</a></li>
                      <li><a href="#" data-value="Academic-Year">Academic Year</a></li>
                      <li><a href="#" data-value="Academic-Term">Academic Term</a></li>
                      <li><a href="#" data-value="Other-Events">Other Events</a></li>
                      <li><a href="#" data-value="School">School</a></li>
                      <li><a href="#" data-value="Class">Class</a></li>
                      <li><a href="#" data-value="Assessment">Assessment</a></li>
                      <li><a href="#" data-value="Assessment-Parameter">Assessment Parameter</a></li>
                      <li><a href="#" data-value="Subject">Subject</a></li>
                      <li><a href="#" data-value="Class-Subject">Class Subject</a></li>
                      <li><a href="#" data-value="Exam">Exam</a></li>
                      <li><a href="#" data-value="Class-Exam">Class Exam</a></li>
                      <li><a href="#" data-value="Score-Grade">Score Grade</a></li>
                      <li><a href="#" data-value="Fees">Fees</a></li>
                      <li><a href="#" data-value="Fees-Instruct">Fees Instruction</a></li>
                      <li><a href="#" data-value="Class-Fees">Class Fees</a></li>
                      <li><a href="#" data-value="Group-Code">Group Code</a></li>
                      <li><a href="#" data-value="Expense-Code">Expense Code</a></li>
                      <li><a href="#" data-value="Bank-Code">Bank Code</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="#" data-value="Student-Fees">Student Fees</a></li>
                      <li><a href="#" data-value="Fees-Payments">Fees Payments</a></li>
                      <li><a href="#" data-value="Bank-Payments">Bank Payments</a></li>
                      <li><a href="#" data-value="Fees-Refunds">Fees Refunds</a></li>
                      <li><a href="#" data-value="Fees-Discounts">Discount/Schorlarship</a></li>
                      <li><a href="#" data-value="Expenses">Expenses</a></li>
                      <li><a href="#" data-value="Funds-Transfer">Funds Transfer</a></li>
                      <li><a href="#" data-value="Exam-Score">Exam Score</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="#" data-value="Teachers-Comments">Teachers Comments</a></li>
                      <li><a href="#" data-value="Result-Assessments">Result Assessments</a></li>
                    </ul>
                </div>
                
                <input type="hidden" name="search_param" value="all" id="search_param"> 
                <input type="text" class="form-control" name="x" id="search-val" disabled="disabled">
                <span class="input-group-btn">
                    <button class="btn btn-default" id="searc-butt" type="button">
                    <span class="fa fa-search-plus"></span></button>
                </span>
            </div>
		</div>
		<div class="col-md-12">
			<div class="form-group" id="table-search-div">
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
		//var search_val = $('#search-val').val();  //search value
		var dates = $('#date_range').val();
		//var bank_id = $('#bank_id').val();
		//extract start date and end date
		var end_date = dates.slice(-10);
		var start_date = dates.slice(0,10);
		var reason = "";
		reason += validate_date_val(start_date);
		reason += validate_date_val(end_date);
		if(reason == ""){
			
			//proceed to the server after this validation
			start_date = toDbaseDate(start_date);
			end_date = toDbaseDate(end_date);
			var table = $('#search-table').DataTable();
			table.clear();
			table.destroy();
			$('#table-search-div').empty();
			
			document.getElementById("searc-butt").innerHTML = 
				'<span class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></span>';
			
			$.get("{{ route('qryJournal') }}", {
					start_date: start_date, 
					end_date: end_date,
					search_param: search_param
				}, function(data){
					
					if( data == null || data == ""){
						alert('No record to display...');	
					}else{
						$('#table-search-div').append(data);
						
						$('#search-table').DataTable({
							//"scrollX": true,
							dom: 'Bfrtip',
							buttons: [
								'copyHtml5',
								'excelHtml5',
								'csvHtml5'
							]
						});
						$("table").resizableColumns();
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