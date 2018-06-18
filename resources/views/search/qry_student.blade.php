@extends('layouts.master')
@section('content')
	<style type="text/css">
        .student-photo{
            height: 125px;
            padding-left: 1px;
            padding-right: 1px;
            border: 1px solid #ccc;
            background: #eee;
            width: 125px;
            margin: 0 auto;
			border:none;
        }
	</style>
	<div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Enquiries</a></li>
                <li><a href="#"><i class="fa fa-user"></i>Students</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			{{----all active students in drop down with reg no: student name on change of value and current class----}}
			<div class="col-md-8">
				<div class="col-md-12">
					<div class="col-md-4 pull-left">
						<label for="reg_no" class="control-label col-md-12">Student</label>
						<div class="form-group">
							<select class="form-control" name="reg_no" id="reg_no" onchange="changeRegNo()">
								<option value="">Please Select...</option>
								@foreach($student as $y)
									<option value="{{ $y->reg_no}}">
									{{ $y->reg_no }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label col-md-12">Name</label>
						<div class="form-group">
							<input type="text" class="form-control" name="full_name" id="full_name" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label col-md-12">Class</label>
						<div class="form-group">
							<input type="text" class="form-control" name="current_class" id="current_class" readonly>
						</div>
					</div>
				</div>
				<div class="col-md-12 h-divider">
					<div class="col-md-7 pull-left">
						<label class="col-md-4" for="date_range">Date Period</label>
						<div class="col-md-8 pull-left">
							<input type="" name="date_range" class="form-control" id="date_range" 
								placeholder="01/01/2018 31/12/2018">
						</div>
					</div>
					<div class="col-md-5 pull-right">
						<div class="input-group">
							<div class="input-group-btn search-panel">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									<span id="search_concept">Search Criteria</span> <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu"  id="rmenu">
								  <li><a href="#" data-value="Details">Details</a></li>
								  <li role="separator" class="divider"></li>
								  <li><a href="#" data-value="Enrolment">Enrolment</a></li>
								  <li><a href="#" data-value="Fees">Fees</a></li>
								  <li><a href="#" data-value="Scholarship">Scholarship</a></li>
								  <li><a href="#" data-value="Exam">Exam</a></li>
								  <li><a href="#" data-value="Attendance">Attendance</a></li>
								  <li><a href="#" data-value="Discipline">Discipline</a></li>
								  <li><a href="#" data-value="Awards">Awards</a></li>
								</ul>
							</div>
                            
							<input type="hidden" name="search_param" value="all" id="search_param">         
							<input type="text" class="form-control" name="x" id="search-val">
                             <span class="input-group-btn">
								<button class="btn btn-default" id="searc-butt" type="button">
								<span class="fa fa-search-plus"></span></button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="pull-right">
					<img src="{{url('/img/avatar.png')}}" class="student-photo" id="showPhoto">
				</div>
			</div>
		</div>
    	
		<div class="col-md-12">
        	<div id="table-view">
         	</div>
		</div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
	
	function changeRegNo(){
		var reg_no = $("#reg_no").val();
		$.get("{{ route('getActiveStudent') }}", {
			reg_no: reg_no
		}, function(data){
			$('#full_name').val(data.last_name + ', ' + data.first_name);
			
			$('#current_class').val(data.class_name);
			//show image
			if( data.photo !== null && data.photo !==""){
				var path = "{{url('photo/student')}}";
				var photo_image = path + '/' + data.photo;
				$('#showPhoto').removeAttr('src');
				document.getElementById("showPhoto").src = photo_image;
			}
		});
	}
	$("#rmenu").on("click", "a", function(e){
		e.preventDefault();
		document.getElementById("searc-butt").innerHTML = 
							'<span class="fa fa-search-plus"></span>';
		var $this = $(this);
		$("#search_param").val($this.data("value"));
		$("#search_param").attr('name',$this.data("value"));
	});
	$(document).on('click', '#searc-butt', function(e){
		e.preventDefault();
		try{
			var search_param = $('#search_param').val();  //search parameter
			//var search_val = $('#search-val').val();  //search value
			var reg_no = $('#reg_no').val();
			if( search_param !=="Details"){ 
				var dates = $('#date_range').val();
				//extract start date and end date
				var end_date = dates.slice(-10);
				var start_date = dates.slice(0,10);
				var reason = "";
				reason += validate_date_val(start_date);
				reason += validate_date_val(end_date);
			
				if(reason !== ""){
					alert('Please select valid date range');
					return;
				}
				//proceed to the server after this validation
				start_date = toDbaseDate(start_date);
				end_date = toDbaseDate(end_date);
			}
		}catch(err){ alert(err.message);}
		
		var table = $('#table-class-info').DataTable();
			table.clear();
			table.destroy();
			
		$('#search-table').empty();
		
		document.getElementById("searc-butt").innerHTML = 
				'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>';
				
		try{
			$.get("{{ route('searchStudent') }}", {
					start_date: start_date, 
					end_date: end_date,
					search_param: search_param,
					reg_no: reg_no
				}, function(data){
					
					$('#table-view').empty().append(data);
					if( search_param !== "Details" ){
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
		}catch(err){ alert(err.message);}
		
	});
	
</script>
@endsection