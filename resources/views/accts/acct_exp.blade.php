@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Accounts</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getExp')}}">Expenses</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage Expenses
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                	<div class="row">
                    	<div class="col-md-4">
                            <form action="" method="POST" id="frm-create-expense" enctype="multipart/form-data" >
                                <input type="hidden" name="expense_id" id="expense_id"> 
                                <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                                <input type="hidden" name="reviewer" id="reviewer" value="">
                                <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                
                                <div class="col-md-12">
                                    <label for="expense_name">Expenses</label>
                                    <div class="form-group">
                                        <input type="" name="expense_name" class="form-control" id="expense_name" placeholder="Enter Expense Name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="group_id"class="control-label">Account Group</label>
                                    <div class="form-group">
                                        <!----this should show all the groups under expenses------>
                                        
                                        <select class="form-control" name="group_id" id="group_id" style="width: 200px;">
                                            <option value="">Please Select...</option>
                                            @foreach($group as $key=>$y)
                                                <option value="{{ $y->group_id}}">
                                                {{ $y->group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            	</div>
                                <div class="col-md-12">
                                    <div class="form-group pull-right">
                                        <button type="submit" class="btn btn-primary" id="btn-save">
                                            <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                    </div>
                                </div>
                            </form>
                       	</div>
                        <div class="col-md-8 pull-right v-divider">
                            <div class="panel panel-default">
                                <div class="panel-heading">Expense Information</div>
                                <div class="panel-body" id="show-info">
                                </div>
                            </div>
                        </div>
                	</div>
               	</div>
            </section>
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
	$(function(){
		$("#frm-create-expense").on('submit', function(e){
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
							$.ajax({
							  url: "{{ route('updateExp')}}",
							  data: new FormData($("#frm-create-expense")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
								  
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-expense')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									$('#expense_id').val(''); 
								},
								error: OnError
							});
						}
					}
				}
			});
		})
		
	});
	
	function showInfo(){
		$('#pleaseWaitDialog').modal();
		$.get("{{ route('infoExp')}}", function(data){
			$('#show-info').empty().append(data);
			$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("expense_name"));
			reason += validate_select(document.getElementById("group_id"));
			
			if (reason != "") {
				alert("Some fields need correction:\n\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(document).on('click', '#edit-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var expense_id = $(this).data('id');
		$('#expense_id').val(expense_id); //update the hidden field with the group id
		$.get("{{ route('editExp') }}", {expense_id: expense_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					$('#frm-create-expense #expense_name').val(l.expense_name); //this uses the progam_id from level file
					$('#frm-create-expense #group_id').val(l.group_id);
				});
			}else{
				alert('No record to display...');	
			}
		});
	});
	
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var expense_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delExp') }}", {expense_id: expense_id, operator: operator}).done(function(data){ 
						if(Object.keys(data).length > 0){
							showInfo();
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

	
</script>
@endsection