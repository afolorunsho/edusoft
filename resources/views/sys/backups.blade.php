@extends('layouts.master')
@section('content')
	<div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>System</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('viewBackup')}}">Manage Backup</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#bk_create" data-toggle="tab" class="nav-tab-class">Create Backup</a></li>
                <li class="nav"><a href="#bk_restore" data-toggle="tab" class="nav-tab-class">Restore Backup</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade in active" id="bk_create">
                    <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                		<button type="button"  class="btn btn-default pull-right" id="create_backup">
                    	<i class="fa fa-plus fa-fw"></i>Create New Backup</button>
                
                    	<div class="col-xs-12" id="show-info"></div>
                  	</div>
              	</div>
                <div class="tab-pane fade in" id="bk_restore">
                    <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                		<div id="external_restore">
                            <form method="post" class="form-horizontal" id="frm-import-bkup" name="frm-import-bkup"
                               role="form" enctype="multipart/form-data" return false>
                               
                               <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                               <input type="hidden" id="token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <input type="file" id="backup_file" name="backup_file">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-default" id="btn_upload">
                                            <i class="fa fa-upload fa-fw"></i>Upload</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-xs-12" id="show-restore"></div>
                  	</div>
              	</div>
          	</div>
    	</div>
    </div>
    @include('popup.ajax_wait')

@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		showInfo();
	});
	
	function showInfo(){
		$.get("{{ route('infoBackup')}}", function(data){
			$('#show-info').empty().append(data);
		});
	}
	$(document).on('click', '#create_backup', function(e){
		e.preventDefault();
		document.getElementById("create_backup").innerHTML = 
			'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
					
		 $.ajax({
            type: "POST",
            url: "{{ route('createBackup')}}",
            success: function (result) {
				  alert("Backup Successful!");
				  showInfo();
				  document.getElementById("create_backup").innerHTML = 
					'<i class="fa fa-plus fa-fw"></i>&nbspCreate New Backup';
				},
				error: function (errors) {
				   alert("Backup error");
				   document.getElementById("create_backup").innerHTML = 
					'<i class="fa fa-plus fa-fw"></i>&nbspCreate New Backup';
				}
			});
	});
	
	$(document).on('click', '#btn_upload', function(e){
		e.preventDefault();
					
		BootstrapDialog.confirm({
			title: 'File Restore',
			message: 'Are you sure you want to restore this file?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					
					//$.get("{{ route('createBackup') }}", function(data){
						
						$.ajax({
							url: "{{ route('restoreExtBackup')}}",
							data: new FormData($("#frm-import-bkup")[0]),
							async:false,
							type:'post',
							processData: false,
							contentType: false,
							success:function(data){
								alert('Restore successful');
								$('#pleaseWaitDialog').modal('hide');
							},
							error: function(jqXHR, exception) { 
								alert("data update error");
							}
						});
					//});	
				}
			}
		});
	});
	
	/*
	$(document).on('click', '.download-this', function(e){
		e.preventDefault();
		var record_id = $(this).val();
		
		
		$.get("{{ route('emailBackup') }}", {record_id: record_id}).done(function(data){ 
			
			
			alert('done');
		})
		.fail(function(xhr, status, error) {
			// error handling
			
			alert("data update error => " + error.responseJSON);
		});
	});*/
	
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var record_id = $(this).val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this backup?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					
					$.post("{{ route('delBackup') }}", {record_id: record_id}).done(function(data){ 
						alert(data);
						showInfo();
						
					})
					.fail(function(xhr, status, error) {
						// error handling
						alert("data update error => " + error.responseJSON)
					});
				}
			}
		});
	});
	$(document).on('click', '.download-this', function(e){
		e.preventDefault();
		var record_id = $(this).val();
		var destination = prompt("Enter destination folder", "/Volumes/Macintosh HD/Applications/");
		
		if (destination != null) {
			$.post("{{ route('downloadBackup') }}", {record_id: record_id, destination: destination}).done(function(data){ 
	
				alert(data);
				
			})
			.fail(function(xhr, status, error) {
				// error handling
				alert("data update error => " + error.responseJSON)
			});
		}
	});
	$(document).on('click', '.restore-this', function(e){
		e.preventDefault();
		var record_id = $(this).val();
		
		BootstrapDialog.confirm({
			title: 'File Restore',
			message: 'Are you sure you want to restore this file?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					//do a backup first, before restore
					$.get("{{ route('createBackup') }}", function(data){
						//now go and restore
						$.get("{{ route('restoreIntBackup') }}", {record_id: record_id}).done(function(data){ 
							alert('Restore successful');
						})
						.fail(function(xhr, status, error) {
							alert("data update error => " + error.responseJSON);
						});
					});
				}
			}
		});
		
	});
</script>
@endsection