@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Fees</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getFeeStruct')}}">Class Fees</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#form" data-toggle="tab" class="nav-tab-class">Fee Structure</a></li>
                <li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Structure</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="form">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-structure" enctype="multipart/form-data" >
                            <input type="hidden" name="struct_id" id="struct_id"> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <!--the effective date of fees is the start of the next semester
                            	there should be another screen for student services i.e students benefitting from optional services
                                service name at the top and select those benfitting per class(e.g hostel, transport, feeding etc) in a table
                            -->
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="col-md-12">
                                    	<label for="start_date" class="col-md-2">Start Date</label>
                                        <div class="form-group col-md-5">
                                            <input type="text" class="form-control" id="start_date" name="start_date"
                                            	 placeholder="Fees Effective Date">
                                        </div>
                                    </div>	
                                    <div class="col-md-7">
                                    	<table class="table table-hover table-striped table-condensed" id="table-class-fees">
                                        	<caption>Fees</caption>
                                            <thead>
                                                <tr>
                                                    <th style="width:60%">Fees</th>
                                                    <th style="width:30%">Amount</th>
                                                    <th style="width:10%">Optional</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($fees as $c)
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="fees[]" 
                                                            value="{{ $c->fee_name }}" readonly="readonly" /></td>
                                                        <td align="right"><input type="text" class="form-control amount text-right" 
                                                            name="amount[]" onkeyup="return allow_number(this)"></td>
                                                        <td>
                                                            <select class="form-control" name="optional[]">
                                                                <option value="No">No</option>
                                                                <option value="Yes">Yes</option>
                                                                
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="submit" class="btn btn-primary" id="btn-save">
                                                    <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-5">
                                    	
                                        <table class="table table-striped" id="table-class-info">
                                        	<caption>Classes</caption>
                                            <thead>
                                                <tr>
                                                    <th style="width:40%">Select</th>
                                                    <th style="width:60%">Classes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($classes as $c)
                                                    <tr>
                                                        <td><input type="checkbox" class="vert-align"></td>
                                                        <td><input type="text" name="classes[]" 
                                                            value="{{ $c->class_name }}" readonly="readonly" 
                                                            style="border:none;background-color:transparent;" /></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                	</div>
               	</div>
                <div class="tab-pane fade in" id="view">
                	<div class="panel panel-body">
                        <div class="panel-body" id="show-info">
                        	<table class="table table-hover table-striped table-condensed" id="table-view">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Class</th>
                                        <th style="width:30%">Fee Head</th>
                                        <th style="width:15%">Amount</th>
                                        <th style="width:20%">Date</th>
                                        <th style="width:8%">Optional</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
              	</div>
         	</div>
        </div>
        <div id="dialog" style="display: none"></div>
    </div>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		showInfo();
	});
	function showInfo(){
		try{
			var table = $('#table-view').DataTable();
				table.clear();
				table.destroy();
			$('#table-view> tbody').empty();
			
			$('#pleaseWaitDialog').modal();
			$.get("{{ route('infoFeeStruct')}}", function(data){
				
				if (data.length > 0) {
					$.each(data, function(i,l){
						//the default access path is public NOT storage in the root
						var row_data = '<tr>' +
							'<td>' + l.class_name +'</td>'+
							'<td>' + l.fee_name +'</td>'+
							'<td>' + l.amount +'</td>'+
							'<td>' + l.start_date +'</td>'+
							'<td>' + l.optional +'</td>'+
							'</tr>';
						$('#table-view> tbody:last-child').append(row_data);
					});
					
				}else{
					alert('No record to display...');	
				}
				$('#table-view').DataTable({
					dom: 'Bfrtip',
					buttons: [
						'copyHtml5',
						'excelHtml5',
						'csvHtml5'
					]
				})
				$('#pleaseWaitDialog').modal('hide');
			});
		}catch(err){ alert(err.message); }
	}
	
	$(function(){
		$('#start_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	function validateFormOnSubmit() {
		try{
			var reason = "";
			var selected = false;
			$("#table-class-info tbody tr td:nth-child(1) input").each(function() {
				var checked = $(this).is(":checked");
				if( checked == true) {
					selected = true;
				}
			});
			if( selected == false ) reason += "No class was selected \n";
			reason += validate_date(document.getElementById("start_date"));
			//date can not be less than current date
			
			if (reason != "") {
				alert("Some fields need correction: \n\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(function(){
		$("#frm-create-structure").on('submit', function(e){
			e.preventDefault();
			
			if( isbeforeDate($('#start_date').val()) == true){
				alert("Note that this operation, back-dating, is only restricted to edit of previous record");
			}
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to update this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						var is_valid = validateFormOnSubmit();
						
						if (is_valid){
							$('#start_date').val(toDbaseDate($('#start_date').val()));
							$('#pleaseWaitDialog').modal();
							//remove the rows that are NOT selected as this seems to update just all the classes in the table
							$("#table-class-info tbody tr td:nth-child(1) input").each(function() {
								var checked = $(this).is(":checked");
								if( checked == false) {
									$(this).closest('tr').remove();
								}
							});
							$.ajax({
							  url: "{{ route('updateFeeStruct')}}",
							  data: new FormData($("#frm-create-structure")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										showInfo();
										alert('Update successful');
										//update the table with the classes
										$('#table-class-info tbody').empty();
										
										$.get("{{ route('listSchClass') }}",function(record){
											if (record.length > 0) {
												$.each(record, function(i,l){
													//get all the students for this class and the maximum score for the selected subject
													var row_data = '<tr>' +
														'<td><input type="checkbox" class="vert-align"></td>' +
														'<td><input name="classes[]" type="text" value="'+ l.class_name +'" readonly="readonly" style="border:none;background-color:transparent;" ></td>'+
														'</tr>';
													$('#table-class-info> tbody:last-child').append(row_data);
												});
											}
										});
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
								},
								error: OnError
							});
							$('#start_date').val( fromDbaseDate($('#start_date').val()) );
						}
					}
				}
			});
		})
		
	});
</script>
@endsection