@extends('layouts.master')
@section('content')
<!---execute a route on loading-->
	<style type="text/css">
        .institute-photo{
            height: 200px;
            padding-left: 1px;
            padding-right: 1px;
            border: 1px solid #ccc;
            background: #eee;
            width: 250px;
            margin: 0 auto;
        }
		.institute-logo{
            height: 100px;
            padding-left: 1px;
            padding-right: 1px;
            border: 1px solid #ccc;
            background: #eee;
            width: 150px;
            margin: 0 auto;
        }
		.institute-header{
            height: 100px;
            padding-left: 1px;
            padding-right: 1px;
            border: 1px solid #ccc;
            background: #eee;
            width: 600px;
            margin: 0 auto;
        }
        .photo > input[type = 'file']{
            display: none;	
        }
		.letter-head > input[type = 'file']{
            display: none;	
        }
        .photo{
            width: 30px;
            height: 30px;
            border-radius: 100%;
        }
		.letter-head{
            width: 100%;
            height: 15px;
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
            {{---<h3 class="page-header"><i class="fa fa-file-text-o"></i>Settings</h3>----}}
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{route('dashboard')}}">Home</a></li>
                <li><i class="icon_document_alt"></i>Settings</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getInstitute')}}">Institute</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Manage Institute
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                
                	<form action="" method="POST" class="form-horizontal" id="frm-create-institute" enctype="multipart/form-data" >
                    	<input type="hidden" name="institute_id" id="institute_id"> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                   		<div class="row">
                       		<div class="col-md-8">
                          		<div class="form-group">
                              		<label class="col-sm-2 control-label" for="sch_name">Name</label>
                                 	<div class="col-sm-10">
                                      <input id="sch_name" type="text" name="sch_name" placeholder="Name of Institute" 
                                      	class="form-control"
                                        pattern="^([- \w\d\u00c0-\u024f]+)$">
                                    </div>
                              	</div>
                    		  	<div class="form-group">
                                	<label class="col-sm-2 control-label" for="reg_no">Reg. Code</label>
                                	<div class="col-sm-4">
                                  	<input id="reg_no" type="text" name="reg_no" 
                                  		placeholder="Enter Registration Code here" class="form-control" pattern="^\S+$">
                                	</div>
                    			
                                	<label class="col-sm-2 control-label" for="reg_date">Reg. Date</label>
                                    <div class="input-group date col-sm-4" data-provide="datepicker">
                                        <input id="reg_date" type="text" name="reg_date" 
                                            placeholder="Registration Date" class="form-control text-right">
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                    </div>
                              	</div>
                             	<!-- Text input-->
                              	<div class="form-group">
                                	<label class="col-sm-2 control-label" for="address">Address</label>
                                	<div class="col-sm-10">
                                  		<textarea class="form-control" rows="3" id="address" name="address" 
                                        placeholder="Enter School Address here"></textarea>
                                	</div>
                              	</div>
                    
                              	<!-- Text input- <textarea class="form-control" rows="5" id="comment"></textarea>-->
                              	<div class="form-group">
                                	<label class="col-sm-2 control-label" for="region">State/Region</label>
                                	<div class="col-sm-4">
                                  		<input id="region" type="text" name="region" placeholder="State/Region where the school is" class="form-control">
                                	</div>
                    
                                	<label class="col-sm-2 control-label" for="country">Country</label>
                                	<div class="col-sm-4">
                                		<input id="country" type="text" name="country" placeholder="Country" class="form-control">
                                	</div>
                           		</div>
                              	<!-- Text input-->
                              	<div class="form-group">
                                	<label class="col-sm-2 control-label" for="phone">Phone</label>
                                	<div class="col-sm-10">
                                  		<input id="phone" type="tel" name="phone" placeholder="Enter Contact Phone here" class="form-control">
                                	</div>
                              	</div>
                              	<!-- Text input-->
                             	<div class="form-group">
                             
                                	<label class="col-sm-2 control-label" for="email">Email</label>
                                	<div class="col-sm-4">
                                  	<input id="email" type="email" name="email" placeholder="Enter Contact Email here" class="form-control"
                                  		pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" >
                                	</div>
                    
                                	<label class="col-sm-2 control-label" for="website">Website</label>
                                	<div class="col-sm-4">
                                  		<input id="website" type="text" name="website" placeholder="Enter School Website address here" class="form-control"
                                  			pattern="^(http[s]?:\/\/)?([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2}(\/([-~%\.\(\)\w\d]*\/*)*(#[-\w\d]+)?)?$">
                                	</div>
                           		</div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="motto">Motto</label>
                                    <div class="col-sm-10">
                                      <input id="motto" type="motto" name="motto" placeholder="Enter School Motto here" class="form-control">
                                    </div>
                                </div>
                   			</div>
                            <div class="col-md-4">
                            	<div class="form-group">
                                    <table style="margin: 0 auto;">
                                        <thead>
                                            <tr class="info"><th class="institute-id">Institute Photo</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="photo">
                                                	<!---it is better to use the linked storage instead of img--->
                                                	<img src="{{url('/img/book_pict.jpg')}}" 
                                                    	class="institute-photo" id="showPhoto">
                                                    <input type="file" name="photo_" id="photo_" 
                                                    	accept="image/gif,image/jpg,image/jpeg" onchange="previewImage();">
                                                        
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
                                    <table style="margin: 0 auto;">
                                        <thead>
                                             <tr class="info"><th class="institute-id">Institute Logo</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="photo">
                                                	{{----we could actually have a default photo here-----}}
                                                    <img src="{!! asset('img/logo.png') !!}" 
                                                    	class="institute-logo" id="showLogo">
                                                    <input type="file" name="logo_" id="logo_" 
                                                    	accept="image/*" onchange="previewImage();">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center; background:#ddd;">
                                                    <input type="button" name="browse_logo" id="browse_logo" 
                                                    	class="form-control btn-browse" value="Browse" >
                                                </td>
                                            </tr>                                    
                           				</tbody>                                
   									</table>                                
   								</div>
                            </div>
                    	</div>
                        <div class="form-group">
                            <table class="pull-right">
                                <thead>
                                     <tr class="info"><th class="institute-id">Institute Header</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="letter-head" width="85%">
                                            {{----we could actually have a default photo here-----}}
                                            <img src="{!! asset('img/letter_head.jpg') !!}" 
                                                class="institute-header" id="showHeader">
                                            <input type="file" name="header_" id="header_" 
                                                accept="image/*" onchange="previewImage();">
                                        </td>
                                        <td style="text-align:center; background:#ddd;" width="15%">
                                            <input type="button" name="browse_header" id="browse_header" 
                                                class="form-control btn-browse" value="Browse" >
                                        </td>
                                    </tr>                               
                                </tbody>                                
                            </table>                                
                        </div>
                
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary" id="btn-save">
                                        <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                </div>
                            </div>
                        </div>
               		</form>
               	</div>
                {{---------this is the table area for defined records: bring the classInfo.blade.php into this div-----------}}
                <div class="panel panel-default">
                    <div class="panel-heading">Institute Information</div>
                    <div class="panel-body" id="show-info">
                    
                    </div>
                </div>
                {{--------------------}}
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
		$('#reg_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	//photo
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
	//logo
	$(document).ready( function() {
		jQuery('#browse_logo').on('click', function(e){
			e.preventDefault();
			jQuery('#logo_').click();
		});
	});
	$(document).ready( function() {
		$('#logo_').on('change', function(){
			showFile(this, '#showLogo');
		});
	});
	//header
	$(document).ready( function() {
		jQuery('#browse_header').on('click', function(e){
			e.preventDefault();
			jQuery('#header_').click();
		});
	});
	$(document).ready( function() {
		$('#header_').on('change', function(){
			showFile(this, '#showHeader');
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
	$(function(){
		$("#frm-create-institute").on('submit', function(e){
			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to create this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					if(result) {
						var is_valid = validateFormOnSubmit();
						if (is_valid){
							
							var reg_date = $('#frm-create-institute #reg_date').val();
							$('#frm-create-institute #reg_date').val(toDbaseDate(reg_date));
							$('#pleaseWaitDialog').modal();
							$.ajax({
							  url: "{{ route('updateInstitute')}}",
							  data: new FormData($("#frm-create-institute")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-institute')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									$('#pleaseWaitDialog').modal('hide');
								},
								error: OnError
							});
							$('#frm-create-institute #reg_date').val(reg_date);
						}
					}
				}
			});
		})
		
	});
	function showInfo(){
		
		var data = "{}";
		$.get("{{ route('infoInstitute') }}", data, function(data){
			//$('#pleaseWaitDialog').modal();
			$('#show-info').empty().append(data);
			//$('#pleaseWaitDialog').modal('hide');
		});
	}
	$(document).on('click', '#institute-edit', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var institute_id = $(this).data('id');
		
		$.get("{{ route('editInstitute') }}", {institute_id: institute_id}, function(l){
			
			$('#frm-create-institute #institute_id').val(l.institute_id);
			$('#frm-create-institute #sch_name').val(l.sch_name); //this uses the progam_id from level file
			$('#frm-create-institute #motto').val(l.motto);
			$('#frm-create-institute #reg_no').val(l.reg_no);
			$('#frm-create-institute #phone').val(l.phone);
			$('#frm-create-institute #email').val(l.email);
			$('#frm-create-institute #website').val(l.website);
			$('#frm-create-institute #country').val(l.country);
			$('#frm-create-institute #region').val(l.region);
			$('#frm-create-institute #address').val(l.address);
			$('#frm-create-institute #reg_date').val(l.reg_date);
			//use route to get the image for this record based on the ID
			
			var path = "{{url('photo/institute')}}";
			//var path = l.storage_path;
			var logo_image = path + '/' + l.logo_image;
			var photo_image = path + '/' + l.photo_image;
			var header_image = path + '/' + l.header_image;
			if( l.logo_image !== null && l.logo_image !==""){
				$('#showLogo').removeAttr('src');
				$('#showLogo').attr('src', logo_image);
			}
			if( l.photo_image !== null && l.photo_image !==""){
				$('#showPhoto').removeAttr('src');
				$('#showPhoto').attr('src', photo_image);
			}
			if( l.header_image !== null && l.header_image !==""){
				$('#showHeader').removeAttr('src');
				$('#showHeader').attr('src', header_image);
			}
		});
	});
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var institute_id = $(this).val();
		var operator = $('#operator').val();
		//alert(institute_id);
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delInstitute') }}", {institute_id: institute_id, operator: operator})
						.done(function(data){ 
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
	//print-this
	$(document).on('click', '.print-this', function(e){
		e.preventDefault();
		var institute_id = $(this).val();
		$.get("{{ route('printInstitute') }}", {institute_id: institute_id}).done(function(data){ 
		    try{
    		    var path = "{{url('/')}}";
    			path = path + '/reports/pdf/' + data;
    			openInNewTab(path);
		    }catch(err){alert(err.message);}
		})
		.fail(function(xhr, status, error) {
			// error handling
			alert("data update error => " + error.responseJSON)
		});
		
	});
	$(document).on('click', '.review-this', function(e){
		e.preventDefault();
		var institute_id = $(this).val();
		var reg_no = $('#frm-create-institute #reg_no').val();
		var sch_name = $('#frm-create-institute #sch_name').val();
		
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to review this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				
				if(result) {
					//NB: 
				}
			}
		});
	});
	/*$(document).on('click', '.edit-this', function(e){
		var institute_id = $(this).val();
		$.post("{{ route('editInstitute') }}", {institute_id: institute_id}).done(function(data){ 
			$('#edit-show').modal('show');
		});
	});*/
	
	function validateFormOnSubmit() {
		var institute_id = $('#institute_id').val();
		var reason = "";
		
		reason += validate_code(document.getElementById("reg_no"));
		reason += validate_empty(document.getElementById("sch_name"));
		reason += validate_empty(document.getElementById("address"));
		reason += validate_empty(document.getElementById("region"));
		reason += validate_empty(document.getElementById("country"));
		reason += validate_phone(document.getElementById("phone"));
		reason += validate_date(document.getElementById("reg_date"));
		//the file extension for the photo should be in the format .jpg since the file name is img/bg-1.jpg in public folder
		//applies if it is a new record
		if( institute_id == null || institute_id == ""){
			var extension = $('#photo_').val().split('.').pop();
			//if (['bmp', 'gif', 'png', 'jpg', 'jpeg'].indexOf(extension) > -1) {
			if (['jpg'].indexOf(extension) > -1) {} 
			else {
				reason += "Invalid photo image file extension \n";
			}
			if( extension == ""){
				reason += "No photo image was attached \n";
			}
			extension = $('#logo_').val().split('.').pop();
			if (['bmp', 'gif', 'png', 'jpg', 'jpeg'].indexOf(extension) > -1) {}
			else {
				reason += "Invalid logo image file extension \n";
			}
			extension = $('#header_').val().split('.').pop();
			if (['bmp', 'gif', 'png', 'jpg', 'jpeg'].indexOf(extension) > -1) {}
			else {
				reason += "Invalid header image file extension \n";
			}
			if( extension == ""){
				reason += "No logo image was attached \n";
			}
		}
		if( $('#email').val() != "") reason += validate_email(document.getElementById("email"));
		if( $('#website').val() != "") reason += validate_url(document.getElementById("website"));
		if( isafterDate($('#reg_date').val()) == true) reason += "Reg Date cannot be after current date \n";
		
		if (reason != "") {
			alert("Some fields need correction:\n" + reason);
			return false;
		}
		return true;
	}
	/*
	//////////////////////////////ERROR CATCHING
		var jqxhr = $.ajax("some_unknown_page.html")
		.done(function (response) {
			// success logic here
			$('#post').html(response.responseText);
		})
		.fail(function (jqXHR, exception) {
			// Our error logic here
			var msg = '';
			if (jqXHR.status === 0) {
				msg = 'Not connect.\n Verify Network.';
			} else if (jqXHR.status == 404) {
				msg = 'Requested page not found. [404]';
			} else if (jqXHR.status == 500) {
				msg = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msg = 'Requested JSON parse failed.';
			} else if (exception === 'timeout') {
				msg = 'Time out error.';
			} else if (exception === 'abort') {
				msg = 'Ajax request aborted.';
			} else {
				msg = 'Uncaught Error.\n' + jqXHR.responseText;
			}
			$('#post').html(msg);
		})
		.always(function () {
			alert("complete");
		});
	
	*/
	
</script>
@endsection