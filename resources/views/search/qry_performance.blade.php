@extends('layouts.master')
@section('content')
	<div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Enquiries</a></li>
                <li><a href="#"><i class="fa fa-user"></i>Performance</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			<div class="col-md-8 pull-left">
				<label class="col-md-2" for="month">Month</label>
				<div class="col-md-3 pull-left">
					<select class="form-control" name="month" id="month" style="width: 150px;">
                        <option value="">Please Select</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
				</div>
                <div class="col-md-6 pull-right">
                    <label class="col-md-4" for="year">Year</label>
                    <div class="col-md-8">
                        <input name="year" class="form-control" id="year" type="number" min="2010" max="2100" value="{{ date('Y') }}" />
                    </div>
            	</div>
			</div>
			<div class="col-md-4 pull-right">
				<div class="input-group">
					<div class="input-group-btn search-panel">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span id="search_concept">Search Criteria</span> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu"  id="rmenu">
						  {{--<li><a href="#" data-value="Detail">Detail</a></li>
						  <li><a href="#" data-value="Type">Type</a></li>
                          6months performance prior to the month indicated---}}
                          <li><a href="#" data-value="Monthly-Detail">Monthly Detail</a></li>
                          <li><a href="#" data-value="Monthly-Summary">Monthly Summary</a></li>
                          {{--3 years including the indicated year---}}
                          <li><a href="#" data-value="Yearly-Detail">Yearly Detail</a></li>
                          <li><a href="#" data-value="Yearly-Summary">Yearly Summary</a></li>
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
		</div>
		<div class="col-md-12">
			<div class="form-group table-responsive" style="overflow: auto" id="ft-search-div">
				<table class="table table-hover" id="search-table">
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
		var sel_year = $('#year').val();
		var sel_month = $('#month').val();
		try{
			document.getElementById("searc-butt").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
			$.get("{{ route('qryPerformance') }}", {
				search_param: search_param,
				sel_year: sel_year, 
				sel_month: sel_month

			}, function(data){
				if (data.length > 0) {
					$('#ft-search-div').empty().append(data);
				}
				document.getElementById("searc-butt").innerHTML = 
					'<span class="fa fa-search-plus"></span>';
				
			});
		}catch(err){ alert(err.message);}
	});
	
</script>
@endsection