@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Fees</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getFeeInstruct')}}">Instruction</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#form" data-toggle="tab" class="nav-tab-class">Fees Instruction</a></li>
                <li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Instruction</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="form">
                	<div class="panel panel-body">
                        <form action="" method="POST" id="frm-create-instruction" enctype="multipart/form-data" >
                            <input type="hidden" name="instruct_id" id="instruct_id"> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-8">
                                    	<label for="instruction_id">Payment Details</label>
                                        <div class="input-group">
                                        	<textarea  class="form-control" id="instruction_id" name="instruction_id"
                                            	 placeholder="Enter Fees Payment Instruction Details" rows="10" cols="200"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="submit" class="btn btn-primary" id="btn-save">
                                                    <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <table class="table table-hover table-striped table-condensed" id="table-class-info">
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
                                                        <td><input type="checkbox" value="{{ $c->class_id }}" name="classes[]" 
                                                        	class="checkbox vert-align"></td>
                                                        <td><input type="text" class="form-control" 
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
                                        <th style="width:80%">Instruction</th>
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
			$.get("{{ route('infoFeeInstruct')}}", function(data){
				if (data.length > 0) {
					$.each(data, function(i,l){
						//the default access path is public NOT storage in the root
						var row_data = '<tr>' +
							'<td>' + l.class_name +'</td>'+
							'<td>' + l.instruction +'</td>'+
							'</tr>';
						$('#table-view> tbody:last-child').append(row_data);
					});
					$('#table-view').DataTable();
				}else{
					alert('No record to display...');	
				}
			});
		}catch(err){ alert(err.message); }
	}
	
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
			
			if (reason != "") {
				alert("Some fields need correction:\n\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(function(){
		$("#frm-create-instruction").on('submit', function(e){
			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to update this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						var is_valid = validateFormOnSubmit();
						if (is_valid){
							$('#pleaseWaitDialog').modal();
							document.getElementById("btn-save").innerHTML = 
								'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
							$.ajax({
							  url: "{{ route('updateFeeInstruct')}}",
							  data: new FormData($("#frm-create-instruction")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										showInfo();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									document.getElementById("btn-save").innerHTML = 
										'<i class="fa fa-save fa-fw fa-fw"></i>Save';
									$('#pleaseWaitDialog').modal('hide');
									$('#instruct_id').val('');
								},
								error: OnError
							});
						}
					}
				}
			});
		})
		
	});
</script>
@endsection