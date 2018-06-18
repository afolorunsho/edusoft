@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Accounts</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getGroup')}}">Account Groups</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage Account Group
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                	<div class="row">
                    	<div class="col-md-4">
                            <form action="" method="POST" id="frm-create-group" enctype="multipart/form-data" >
                                <input type="hidden" name="group_id" id="group_id"> 
                                <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                                <input type="hidden" name="reviewer" id="reviewer" value="">
                                <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        
                            	<div class="col-md-12">
                                	<label for="group_name">Account Group</label>
                                    <div class="form-group">
                                       	<input type="" name="group_name" class="form-control" id="group_name" placeholder="Enter Account Group Name">
                                	</div>
                                </div>
                                <div class="col-md-12">
                                	<label for="account_category"class="control-label">Account Category</label>
                                	<div class="form-group">
                                        <select class="form-control" name="account_category" id="account_category" style="width: 200px;">
                                            <option value="">Please Select...</option>
                                            <option value="Asset">Asset</option>
                                            <option value="Liability">Liability</option>
                                            <option value="Income">Income</option>
                                            <option value="Expenses">Expenses</option>
                                            <option value="Capital">Capital</option>
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
                                <div class="panel-heading">Account Group Information</div>
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
		$("#frm-create-group").on('submit', function(e){
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
							  url: "{{ route('updateGroup')}}", 
							  data: new FormData($("#frm-create-group")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									
									
									if(Object.keys(data).length > 0){
										showInfo();
										alert('Update successful');
										$('#frm-create-group')[0].reset();
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
									//document.getElementById("group_id")
									$('#group_id').val('');
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
		$.get("{{ route('infoGroup')}}", function(data){
			$('#show-info').empty().append(data);
			$('#pleaseWaitDialog').modal('hide');
		});
	}
	function validateFormOnSubmit() {
		try{
			var reason = "";
			reason += validate_empty(document.getElementById("group_name"));
			reason += validate_select(document.getElementById("account_category"));
			
			if (reason != "") {
				alert("Some fields need correction:\n\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(document).on('click', '#group-edit', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var group_id = $(this).data('id');
		$('#group_id').val(group_id); //update the hidden field with the group id
		$.get("{{ route('editGroup') }}", {group_id: group_id}, function(data){
			if (data.length > 0) {
				$.each(data, function(i,l){
					
					$('#frm-create-group #group_name').val(l.group_name); //this uses the progam_id from level file
					$('#frm-create-group #account_category').val(l.account_category);
				});
			}else{
				alert('No record to display...');	
			}
		});
	});
	
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var group_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delGroup') }}", {group_id: group_id, operator: operator}).done(function(data){ 
						//update the log at the server end
						if(Object.keys(data).length > 0){
							showInfo();
							alert('Update successful');
						}else{
							alert('Update NOT successful');
						}
						$('#group_id').val('');
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