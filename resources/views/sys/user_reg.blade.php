@extends('layouts.master')
@section('content')
	<style type="text/css">
        .institute-photo{
            height: 75px;
            background: #eee;
            width: 100px;
            margin: 0 auto;
        }
        .photo > input[type = 'file']{
            display: none;	
        }
        .photo{
            width: 25px !important;
            height: 25px !important;
            border-radius: 100%;
        }
        .institute-id{
            background-repeat: repeat-x;
            border-color: #ccc;;
            padding: 5px;
            text-align: center;
            background: #eee;
            border-bottom: 1px solid #ccc;
        }
        .btn-browse{
            border-color: #ccc;
            padding: 5px;
            text-align: center;
            background: #eee;
            border: none;
            border-bottom: 1px solid #ccc;
        }
    </style>
	<div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>System</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getUsers')}}">Manage User</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <header class="panel-heading">
                Manage Users
            </header>
            <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                <div class="col-md-2 pull-left">
                    <form action="" method="POST" id="frm-create-users" enctype="multipart/form-data" return false>
                        <input type="hidden" name="user_id" id="user_id" value=""> 
                        <input type="hidden" name="active" id="active" value="1"> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        
                        <div class="form-group">
                            <table style="margin: 0 auto;">
                                <thead>
                                    <tr class="info"><th class="institute-id">User Photo</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="photo">
                                            <!---it is better to use the linked storage instead of img--->
                                            <img src="{{url('/img/avatar.png')}}" 
                                                class="institute-photo" id="showPhoto">
                                            <input type="file" name="photo_" id="photo_" 
                                                accept="image/*">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center; background:#ddd;">
                                            <input type="button" name="browse_photo" id="browse_photo"
                                             class="form-control btn-browse" value="Browse" >
                                        </td>
                                    </tr>                                    
                                </tbody>                                
                            </table>                                
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 control-label">Username</label>
                            <div class="col-md-12">
                                <input type="text" name="username" class="form-control" id="username">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-12 control-label">Name</label>
                            <div class="col-md-12">
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>          
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="role_id">User Role</label>
                            <div class="col-md-12">
                                <select class="form-control" name="role_id" id="role_id" style="width: 125px;">
                                    @foreach($roles as $key=>$y)
                                        <option value="{{ $y->role_id}}">
                                        {{ $y->role}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 pull-right">
                                <button type="submit" class="btn btn-primary" id="btn-save">
                                    <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-10 pull-right" style="border-left:1px solid #ccc;height:500px">
                    <div class="panel panel-default">
                        <div class="panel-heading">Registered Users</div>
                        <div class="panel-body" id="show-info">
                            
                        </div>
                    </div>
                </div>
            </div>
  		</div>
 	</div>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		jQuery('#browse_photo').on('click', function(e){
			e.preventDefault();
			jQuery('#photo_').click();
		});
	});
	$(document).ready( function() {
		$('#photo_').on('change', function(){
			showFile(this, '#showPhoto');
		});
	});
	
	function showFile(fileInput, img, showName){
		if(fileInput.files[0]){
			var reader = new FileReader();	
			reader.onload = function(e){
				$(img).attr('src', e.target.result);
			}
			reader.readAsDataURL(fileInput.files[0]);
		}
		$(showName).text(fileInput.files[0].name)
	}
	$(document).ready( function() {
		showInfo();
	});
	function showInfo(){
		
		$.get("{{ route('noRegister')}}", function(data){
			$('#show-info').empty().append(data);
			
			$('#table-class-info').DataTable( {
              //"pageLength": 5
                "iDisplayLength": 5,
	            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            } );
			
		});
	}
	$(document).on('click', '#edit-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var user_id = $(this).data('id');
		$('#user_id').val(user_id); //update the hidden field with the bank id
		
		$.get("{{ route('editUser') }}", {user_id: user_id}, function(data){
			$.each(data, function(i,l){
				
				if( l.photo !== null && l.photo !== ""){
					var path = "{{url('photo/user')}}";
					var photo_image = path + '/' + l.photo;
					document.getElementById("showPhoto").src = photo_image;
				}
				$('#username').val(l.username); //this uses the progam_id from level file
				$('#role_id').val(l.role_id);
				$('#name').val(l.name);
			});
		});
	});
	
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var user_id = $(this).val();
		var operator = $('#operator').val();
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delUser') }}", {user_id: user_id, operator: operator}).done(function(data){ 
						//update the log at the server end
						if(Object.keys(data).length > 0){
							showInfo();
							alert('Update successful');
						}else{
							alert('Update NOT successful');
						}
					})
					.fail(function(jqXHR, exception) { 
						return_error(jqXHR, exception);
					});
				}
			}
		});
	});
	
	$(function(){
		$("#frm-create-users").on('submit', function(e){
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
							  	url: "{{ route('updateUser')}}",
							  	data: new FormData($("#frm-create-users")[0]),
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
									$('#pleaseWaitDialog').modal('hide');
								},
								error: function(jqXHR, exception) { 
									return_error(jqXHR, exception);
								}
							});
						}
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		
		reason += validate_code(document.getElementById("username"));
		reason += validate_empty(document.getElementById("name"));
		reason += validate_empty(document.getElementById("user_id"));
		reason += validate_select(document.getElementById("role_id"));
		
		if (reason != "") {
			alert("You cannot update with this information:\n" + reason);
			return false;
		}
		return true;
	}
	
</script>
@endsection