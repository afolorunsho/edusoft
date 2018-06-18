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
        }
        .photo > input[type = 'file']{
            display: none;	
        }
        .photo{
            width: 30px;
            height: 30px;
            border-radius: 100%;
        }
        .student-id{
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
                <li><i class="icon_document_alt"></i>Student</li>
                <li><i class="fa fa-file-text-o"></i><a href="{{route('getRegistration')}}">Registration</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#registration" data-toggle="tab" class="nav-tab-class">Registration</a></li>
                <li class="nav"><a href="#multiple" data-toggle="tab" class="nav-tab-class">Multiple</a></li>
				<li class="nav"><a href="#view" data-toggle="tab" class="nav-tab-class">View Registration</a></li>
                <li class="nav"><a href="#import" data-toggle="tab" class="nav-tab-class">Import Registration</a></li>
            </ul>
        
            <div class="tab-content">
                <div class="tab-pane fade in active" id="registration">
                
                	<div class="panel panel-body">
                    <form action="" method="POST" class="form-horizontal" id="frm-create-student" enctype="multipart/form-data" return false>
                        <input type="hidden" name="student_id  " id="student_id" value=""> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="row">
                            <div>
                                <div class="col-md-10" style="margin-top:5px;">
                                    <div class="form-group">
                                        <label class="required col-md-2 control-label" for="reg_no">Reg. No</label>
                                        <div class="col-md-4">
                                            <input id="reg_no" type="text" name="reg_no" placeholder="Registration Code" 
                                            class="form-control" pattern="^\S+$" onchange="getStudents()">
                                        </div>
                                        <label class="required col-md-2 control-label" for="reg_date">Reg. Date</label>
                                        <div class="col-md-4">
                                            <input id="reg_date" type="text" name="reg_date" 
                                            placeholder="Registration Date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="required col-md-2 control-label" for="last_name">Name</label>
                                        <div class="col-md-4">
                                            <input id="last_name" type="text" name="last_name" placeholder="Last Name" class="form-control"
                                            pattern="^([- \w\d\u00c0-\u024f]+)$">
                                        </div>
                                        <div class="col-md-3">
                                            <input id="first_name" type="text" name="first_name" placeholder="First Name" 
                                            class="form-control" pattern="^([- \w\d\u00c0-\u024f]+)$">
                                        </div>
                                        <div class="col-md-3">
                                        <input id="other_name" type="text" name="other_name" placeholder="Other Name(Optional)" 
                                        class="form-control" pattern="^([- \w\d\u00c0-\u024f]+)$">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        
                                        <label class="required col-md-2 control-label" for="gender">Gender</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="gender" id="gender" 
                                            	style="width: 200px;">
                                                <option value="">Please Select...</option>
                                                <option value="0">Female</option>
                                                <option value="1">Male</option>
                                            </select>
                                        </div>
                                        <label class="required col-md-2 control-label" for="dob">Date of Birth</label>
                                        <div class="col-md-4">
                                            <input id="dob" type="text" name="dob" placeholder="Date of Birth" class="form-control" pattern="^\S+$">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="required col-md-2 control-label" for="religion">Religion</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="religion" id="religion" 
                                            	style="width: 200px;">
                                                <option value="">Please select...</option>
                                                <option value="Christian">Christian</option>
                                                <option value="Muslim">Muslim</option>
                                                <option value="Buddhist">Buddhist</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Jew">Jew</option>
                                                <option value="Sikh">Sikh</option>
                                                <option value="Other">Others</option>
                                            </select>
                                        </div>
                                        <label class="required col-md-2 control-label" for="nationality">Nationality</label>
                                        <div class="col-md-4">
                                            <input id="nationality" type="text" name="nationality" placeholder="Nationality" 
                                            class="form-control" pattern="^\S+$">
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="required col-md-2 control-label" for="town">Origin</label>
                                        <div class="col-md-4">
                                            <input id="town" type="text" name="town" placeholder="Home Town" class="form-control"
                                            pattern="^([- \w\d\u00c0-\u024f]+)$">
                                        </div>
                                        <div class="col-md-3">
                                            <input id="lga" type="text" name="lga" placeholder="LGA" class="form-control"
                                            pattern="^([- \w\d\u00c0-\u024f]+)$">
                                        </div>
                                        <div class="col-md-3">
                                        <input id="state_origin" type="text" name="state_origin" placeholder="State" class="form-control"
                                            pattern="^([- \w\d\u00c0-\u024f]+)$">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <table style="margin: 0 auto;">
                                            <thead>
                                                <tr class="info"><th class="student-id">Student Photo</th></tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="photo">
                                                        <!---it is better to use the linked storage instead of img--->
                                                        <img src="{{url('/img/avatar.png')}}" 
                                                            class="student-photo" id="showPhoto">
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
                                </div>
                            </div> 
                            <div style="border-top:2px solid #ccc;clear: both;margin: 0 auto;padding:5px">   
                                <div class="col-md-6">
                                    <div class="form-group col-md-12">
                                        <label class="col-md-6" for="tribe">Tribe</label>
                                        <div class="col-md-6">
                                            <input id="tribe" type="text" name="tribe" 
                                            placeholder="Enter Tribe or Race here" class="form-control">
                                        </div>
                                	</div>
                                    <div class="form-group col-md-12">
                                        <label class="col-md-6" for="height">Height(meter)</label>
                                        <div class="col-md-6">
                                            <input id="height" type="number" step="any" min="0.10" name="height" 
                                            placeholder="Height(m)" class="form-control">
                                        </div>
                                	</div>
                                    <div class="form-group col-md-12">
                                         <label class="col-md-6" for="height">Weight(KG)</label>
                                        <div class="col-md-6">
                                            <input id="weight" type="number" step="any" name="weight" min="10" 
                                            placeholder="Weight(kg)" class="form-control">
                                        </div>
                                  	</div>
                                  	<div class="form-group col-md-12">
                                         <label class="col-md-6" for="height">Blood Type</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="blood" id="blood" 
                                            	style="width: 150px;">
                                                <option value="">Blood Type...</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                            </select>
                                        </div>
                                    </div>
                            	</div>
                                <div class="col-md-6 pull-right" style="border-left:2px solid #ccc;height:420px">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="guardian">Guardian</label>
                                        <div class="col-md-10 pull-right">
                                            <input id="guardian" type="text" name="guardian" 
                                            placeholder="Name of Parent/Guardian" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="relationship">Relationship</label>
                                        <div class="col-md-8">
                                        	<input id="relationship" type="text" name="relationship" 
                                          	placeholder="Parent, Uncle, Cousin etc" class="form-control"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="guard_home">Home</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="2" id="guard_home" name="guard_home" 
                                            placeholder="Guardian Home Address"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="guard_office">Office</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="2" id="guard_office" name="guard_office" 
                                            placeholder="Guardian Office Address"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="guard_phone">Phone</label>
                                        <div class="col-md-8">
                                          <input id="guard_phone" type="tel" name="guard_phone" 
                                          placeholder="Guardian Phone" class="form-control"> <!--pattern="[0-9]{5}[-][0-9]{7}[-][0-9]{1}"/>-->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="guard_email">Email</label>
                                        <div class="col-md-8">
                                          <input id="guard_email" type="email" name="guard_email" placeholder="Guardian Email" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-10">
                                    <div class="pull-left">
                                        <button type="submit" class="btn btn-primary" id="btn-save">
                                        <i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                 <div class="tab-pane fade" id="multiple">
                	<div class="panel panel-body" style="margin-top:5px;">
                        <form action="" method="POST" class="form-horizontal" id="frm-create-multiple" enctype="multipart/form-data" return false>
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="row">
                        
                                <div class="col-md-4">
                                	<label for="txn_date" class="col-md-4">Date</label>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="txn_date" class="form-control text-right" 
                                        	id="txn_date" placeholder="Enter registration date">
                                    </div>
                             	</div>
                                <div class="col-md-8">
                                    
                                        <button type="button" class="btn btn-default" id="add_row">
                                            <i class="fa fa-plus fa-fw fa-fw"></i>Add a Record</button>
                                            
                                     	<button type="button" class="btn btn-primary pull-right" 
                                        	id="btn-save-multiple">
                                        	<i class="fa fa-save fa-fw fa-fw"></i>Save</button>
                                    
                                </div>
                                <table class="table table-hover table-striped table-condensed" id="table_input"
                                	style="font-size:100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">Reg. No</th>
                                            <th width="8%">Last Name</th>
                                            <th width="7%">First Name</th>
                                            <th width="7%">Other Name</th>
                                            <th width="7%">DoB</th>
                                            <th width="5%">Gender</th>
                                            <th width="5%">Religion</th>
                                            <th width="10%">Guardian</th>
                                            <th width="10%">Office</th>
                                            <th width="10%">Home</th>
                                            <th width="10%">Phone</th>
                                            <th width="10%">Email</th>
                                            <th width="1%"></th>
                                     	</tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                     		</div>
                		</form>
               		</div>
             	</div>
                <div class="tab-pane fade" id="view">
                	<div class="panel panel-body" id="display" style="margin-top:5px;">
                        <div class="col-md-12">
                            <div class="col-md-5 pull-left">
                                <div class="input-group">
                                    <input type="text" class="form-control" name ="date_range" id= "date_range" 
                                        placeholder="31/12/2017 30/09/2018">
                                    <div class="input-group-btn search-panel">
                                        <button type="button" id="student-search" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <span id="search_concept">Search</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if(Auth::user()->role_id == '1' ||
                            Auth::user()->role_id == '5' ||
                            Auth::user()->role_id == '9' ||
                            Auth::user()->role_id == '8')
                                <div class="dropdown pull-right">
                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-download fa-fw fa-fw"></i>Export
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li id="export-to-excel"><a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw">
                                        </i>Export to Excel</a></li>
                                    </ul>
                                </div>
                       		@endif
                        </div>
                  		<div id="show-info">
                      		
                 		</div>
                    </div>
                </div>
                <div class="tab-pane fade in" id="import">
                	<div class="panel panel-body">
                    	<form method="post" class="form-horizontal" id="frm-import-table" name="frm-import-table"
                           role="form" enctype="multipart/form-data" return false>
                           <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                           <input type="hidden" id="token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <input type="file" id="table_file" name="table_file">
                                </div>
                                <div class="col-md-2">
                                    <button type="button"  class="btn btn-default" id="btn_import_table">
                                        <i class="fa fa-upload fa-fw"></i>Upload</button>
                                </div>
                                 <div class="col-md-4">
                                	<label for="import_date" class="col-md-2 control-label">Date:</label>
                                    <div class="form-group col-md-8">
                                        <input type="text" name="import_date" class="form-control text-right" 
                                        	id="import_date" placeholder="Enter registration date">
                                    </div>
                             	</div>
                                <div class="col-md-2 pull-right">
                                    <button onClick="update_data()" id="btn_update_table"
                                        type="button"  class="btn btn-primary" >
                                            <i class="fa fa-save fa-fw"></i>Update Database</button>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <!--<hr />-->
                                        <div id="table_div">
                                            <!--populate dynamically-->
                                            <table class="table table-hover table-striped table-condensed" id="import_input"
                                                style="font-size:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">Reg. No</th>
                                                        <th width="8%">Last Name</th>
                                                        <th width="7%">First Name</th>
                                                        <th width="7%">Other Name</th>
                                                        <th width="7%">DoB</th>
                                                        <th width="5%">Gender</th>
                                                        <th width="5%">Religion</th>
                                                        <th width="10%">Guardian</th>
                                                        <th width="10%">Office</th>
                                                        <th width="10%">Home</th>
                                                        <th width="10%">Phone</th>
                                                        <th width="10%">Email</th>
                                                        <th width="1%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    <!--<hr />-->
                                </div>
                            </div>
                        </form>
					</div>
				</div>
            </div>
    	</div>
 	</div>
     <form action="" method="POST" class="form-horizontal" id="frm-data-entry" enctype="multipart/form-data" return false>
        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
        <input type="hidden" name="reviewer" id="reviewer" value="">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="reg_date" id="reg_date" value="">
        <input type="hidden" name="first_name" id="first_name" value="">
        <input type="hidden" name="last_name" id="last_name" value="">
        <input type="hidden" name="other_name" id="other_name" value="">
        <input type="hidden" name="dob" id="dob" value="">
        <input type="hidden" name="gender" id="gender" value="">
        <input type="hidden" name="reg_no" id="reg_no" value="">
        <input type="hidden" name="religion" id="religion" value="">
        <input type="hidden" name="guardian" id="guardian" value="">
        <input type="hidden" name="home_add" id="home_add" value="">
        <input type="hidden" name="office_add" id="office_add" value="">
        <input type="hidden" name="email" id="email" value="">
        <input type="hidden" name="phone" id="phone" value="">
        <input type="hidden" name="import_date" id="import_date" value="">
     </form>
	@include('popup.ajax_wait')
@endsection
@section('scripts')
<script type="text/javascript">
	
	//showInfo();
	$(document).on('click', '#student-search', function(e){
		e.preventDefault();
		//extract the dates from the string
		var dates = $('#date_range').val();
		//extract start date and end date
		var end_date = dates.slice(-10);
		var start_date = dates.slice(0,10);
		var reason = "";
		reason += validate_date_val(start_date);
		reason += validate_date_val(end_date);
		if(reason == ""){
			start_date = toDbaseDate(start_date);
			end_date = toDbaseDate(end_date);
			var table = $('#table-class-info').DataTable();
			table.clear();
			table.destroy();
			$.get("{{ route('infoRegistration') }}", {start_date: start_date, end_date: end_date}, function(data){
				$('#show-info').empty().append(data);
				$('#table-class-info').DataTable();
			});
		}
	});
	$(document).ready(function(){
		$('#table-class-info').DataTable();
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
	$(function(){
		$('#txn_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	
	$(function(){
		$('#import_date').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
	
	$(function(){
		$('#dob').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth:true,
			changeYear:true,
			todayBtn: "linked",
        	autoclose: true,
		});
    });
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
	
	$(function(){
		$("#frm-create-student").on('submit', function(e){
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
							
							var reg_date = $('#frm-create-student #reg_date').val();
							var dob = $('#frm-create-student #dob').val();
							$('#frm-create-student #reg_date').val(toDbaseDate(reg_date));
							$('#frm-create-student #dob').val(toDbaseDate(dob));
							document.getElementById("btn-save").innerHTML = 
								'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
							$.ajax({
							  	url: "{{ route('updateRegistration')}}",
							  	data: new FormData($("#frm-create-student")[0]),
							  	async:false,
							  	type:'post',
							  	processData: false,
							  	contentType: false,
							  	success:function(data){
									alert('Update successful');
									$('#frm-create-student')[0].reset();
									/*if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-student')[0].reset();
										
									}else{
										alert('Update NOT successful');
									}*/
									document.getElementById("btn-save").innerHTML = 
										'<i class="fa fa-save fa-fw fa-fw"></i>Save';
									$('#student_id').val('');
								},
								error: function(jqXHR, exception) { 
									return_error(jqXHR, exception);
									document.getElementById("btn-save").innerHTML = 
										'<i class="fa fa-save fa-fw fa-fw"></i>Save';
								}
							});
							$('#frm-create-student #reg_date').val(reg_date);
							$('#frm-create-student #dob').val(dob);
						}
					}
				}
			});
		})
		
	});
	function validateFormOnSubmit() {
		var reason = "";
		try{
			reason += validate_empty(document.getElementById("guard_phone"));
			reason += validate_empty(document.getElementById("guard_email"));
			reason += validate_empty(document.getElementById("guard_home"));
			reason += validate_empty(document.getElementById("guard_office"));
			reason += validate_empty(document.getElementById("height"));
			reason += validate_empty(document.getElementById("weight"));
			//reason += validate_empty(document.getElementById("district"));
			
			reason += validate_code(document.getElementById("reg_no"));
			reason += validate_empty(document.getElementById("first_name"));
			//reason += validate_empty(document.getElementById("other_name"));
			reason += validate_empty(document.getElementById("nationality"));
			reason += validate_empty(document.getElementById("last_name"));
			//reason += validate_empty(document.getElementById("address"));
			reason += validate_empty(document.getElementById("guardian"));
			reason += validate_empty(document.getElementById("relationship"));
			//reason += validate_phone(document.getElementById("phone"));
			reason += validate_date(document.getElementById("reg_date"));
			reason += validate_date(document.getElementById("dob"));
			reason += validate_select(document.getElementById("religion"));
			reason += validate_select(document.getElementById("gender"));
			reason += validate_select(document.getElementById("blood"));
			reason += validate_empty(document.getElementById("lga"));
			reason += validate_empty(document.getElementById("state_origin"));
			reason += validate_empty(document.getElementById("town"));
			//reason += validate_empty(document.getElementById("region"));
			
			//if( $('#email').val() != "") reason += validate_email(document.getElementById("email"));
			if( $('#guard_email').val() != "") reason += validate_email(document.getElementById("guard_email"));
			if( isafterDate($('#reg_date').val()) == true) reason += "Reg Date cannot be after current date \n";
			if( isafterDate($('#dob').val()) == true) reason += "DoB Date cannot be after current date \n";
			
			if (reason != "") {
				alert("Some fields need correction:\n\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	$(document).on('click', '.edit-this', function(e){
		e.preventDefault();
		//get the id of the clicked 
		var student_id = $(this).data('id');
		fillStudentRecord(student_id);
		
	});
	function fillStudentRecord(student_id){
		$('#student_id').val(student_id);
		try{
			$.get("{{ route('editRegistration') }}", {student_id: student_id}, function(data){
				
				var reg_no = data.reg_no; 
				var other_name = data.other_name;
				if(data.other_name == null || data.other_name == "-") other_name = "";
				/*change tab focus*/
				$('.nav-tabs a[href="#registration"]').tab('show');
				
				$('#reg_no').val(reg_no); 
				$('#reg_date').val(data.reg_date); 
				$('#first_name').val(data.first_name); 
				$('#last_name').val(data.last_name); 
				$('#other_name').val(other_name); 
				$('#dob').val(data.dob); 
				$('#height').val(data.height); 
				$('#weight').val(data.weight); 
				$('#blood').val(data.blood);
				//the model has already converted this. Hence, the need to change it back
				if(data.gender == "Male") $('#gender').val('1'); 
				if(data.gender == "Female") $('#gender').val('0'); 
				//$('#district').val(data.district); 
				//$('#region').val(data.region); 
				$('#town').val(data.town); 
				$('#lga').val(data.lga);
				$('#state_origin').val(data.state_origin); 
				$('#nationality').val(data.nationality); 
				$('#religion').val(data.religion); 
				//$('#address').val(data.address); 
				//$('#email').val(data.email); 
				//$('#phone').val(data.phone); 
				$('#guardian').val(data.guardian); 
				//$('#guard_photo').val(data.subject); 
				$('#relationship').val(data.relationship); 
				$('#guard_office').val(data.guard_office); 
				$('#guard_home').val(data.guard_home); 
				$('#guard_email').val(data.guard_email); 
				$('#guard_phone').val(data.guard_phone);
				
				if( data.photo !== null && data.photo !== ""){
					var path = "{{url('photo/student')}}";
					var photo_image = path + '/' + data.photo;
					$('#showPhoto').removeAttr('src');
					document.getElementById("showPhoto").src = photo_image;
				}
				$('#reg_no').focus();
			});
		}catch(err){ alert(err.message); }
	}
	$(document).on('click', '.del-this', function(e){
		e.preventDefault();
		var student_id = $(this).val();
		var operator = $('#frm-create-student #operator').val();
		//alert(student_id);
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to delete this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				if(result) {
					$.post("{{ route('delRegistration') }}", {student_id: student_id, operator: operator})
					.done(function(data){ 
						if(Object.keys(data).length > 0){
							showInfo();
							alert('Update successful');
						}else{
							alert('Update NOT successful');
						}
					})
					.fail(function(jqXHR, exception){
						return_error(jqXHR, exception);
					});
				}
			}
		});
	});
	//this should be for all the records
	$(document).on('click', '#export-to-excel', function(e){
		e.preventDefault();
		$('#pleaseWaitDialog').modal();
				
		$.get("{{ route('excelRegistration') }}", function(data){
				$('#pleaseWaitDialog').modal('hide');
			
				var path = "{{url('/')}}";
				path = path + '/reports/excel/';
				
				var a = document.createElement("a");
				a.href = path + data.file;
				a.download = data.name;
				a.target = "_blank";
				a.title = data.file;
				document.body.appendChild(a);
				a.click();
				a.remove();
		});
	});
	//this should be for ecah student
	$(document).on('click', '.print-this', function(e){
		e.preventDefault();
		var student_id = $(this).val();
		$.get("{{ route('printRegistration') }}", {student_id: student_id}).done(function(data){ 
			var path = "{{url('/')}}";
			path = path + '/reports/pdf/' + data;
			openInNewTab(path);
		})
		.fail(function(xhr, status, error) {
			// error handling
			alert("data update error => " + error.responseJSON)
		});
		
	});
	//review postings made. if reviewed, then grey out that button
	$(document).on('click', '.review-this', function(e){
		e.preventDefault();
		var student_id = $(this).val();
		var reg_no = $('#frm-create-student #reg_no').val();
		var sch_name = $('#frm-create-student #sch_name').val();
		
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to review this record?',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			callback: function(result) {
				
				if(result) {
					showInfo();
					alert('Update successful');
				}
			}
		});
	});
	
	$('#add_row').on('click', function(e){
		e.preventDefault();
		add_row();
	});
	function add_row(){
		var row_data = '<tr>' +
			'<td><input type="text" class="form-control reg_no" name="reg_no[]"></td>'+
			'<td><input type="text" class="form-control last_name" name="last_name[]"></td>'+
			'<td><input type="text" class="form-control first_name" name="first_name[]"></td>'+
			'<td><input type="text" class="form-control other_name" name="other_name[]"></td>'+
			'<td><input type="text" placeholder="dd/mm/yyyy" class="form-control dob text-right" name="dob[]"></td>'+
			'<td><select class="form-control gender" name="gender[]" style="width: 100px;"><option value="">Select...</option><option value="0">Female</option><option value="1">Male</option></select></td>'+
			'<td><select class="form-control" name="religion[]" style="width: 100px;"><option value="">Select...</option><option value="Christian">Christian</option><option value="Muslim">Muslim</option><option value="Buddhist">Buddhist</option><option value="Hindu">Hindu</option><option value="Jew">Jew</option><option value="Sikh">Sikh</option><option value="Other">Others</option></select></td>'+
			'<td><input type="text" class="form-control guardian" name="guardian[]"></td>'+
			'<td><textarea rows="2" class="form-control office_add" name="office_add[]"></textarea></td>'+
			'<td><textarea rows="2" class="form-control home_add" name="home_add[]"></textarea></td>'+
			'<td><input type="text" class="form-control phone" name="phone[]"></td>'+
			'<td><input type="text" class="form-control email" name="email[]"></td>'+
			
			'<td><button class="btn btn-danger btn-sm rm-this"><i class="fa fa-trash-o fa-lg"></i></button></td>'+
		'</tr>';
		$('#table_input tbody').append(row_data);
		
		$('.dob').datepicker({dateFormat: 'dd/mm/yy'});
        $(".dob").on("blur", function (e){
            var isValidDate = validateDate(e.target.value);
            if(!isValidDate){
                 alert("Please enter a valid date in DD/MM/YYYY format");
            }
        });
	}
	$("#table_input tbody").on("click", ".rm-this", function(e){
		e.preventDefault();
		var this_ = $(this);
		del_row(this_);
	});
	function del_row(this_){
		this_.fadeOut('slow',function(){
			this_.closest('tr').remove();
		});
	}
	$('#btn-save-multiple').on('click', function(e){
		e.preventDefault();
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to create this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					if(result) {
						
						var is_valid = validateMultiple();
						if (is_valid){
							document.getElementById("btn-save-multiple").innerHTML = 
								'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
							$.ajax({
							  url: "{{ route('updateMultiple')}}",
							  data: new FormData($("#frm-create-multiple")[0]),
							  async:false,
							  type:'post',
							  processData: false,
							  contentType: false,
							  success:function(data){
									
									if(Object.keys(data).length > 0){
										showInfo();
										$('#frm-create-multiple')[0].reset();
										alert('Update successful');
									}else{
										alert('Update NOT successful');
									}
									document.getElementById("btn-save-multiple").innerHTML = 
										'<i class="fa fa-save fa-fw fa-fw"></i>Save';
								},
								error: function(jqXHR, exception) { 
									return_error(jqXHR, exception);
									document.getElementById("btn-save-multiple").innerHTML = 
										'<i class="fa fa-save fa-fw fa-fw"></i>Save';
								}
							});
							document.getElementById("btn-save-multiple").innerHTML = 
										'<i class="fa fa-save fa-fw fa-fw"></i>Save';
						}
					}
				}
			});
	});
	
	function validateMultiple() {
		try{
			var reason = "";
			//////////now check the table data for validity
			$('#table_input tbody tr').each(function(){
									
				var reg_no = trim($(this).closest('tr').find('input.reg_no').val());
				var last_name = trim($(this).closest('tr').find('input.last_name').val());
				var first_name = trim($(this).closest('tr').find('input.first_name').val());
				var other_name = trim($(this).closest('tr').find('input.other_name').val());
				var dob = trim($(this).closest('tr').find('input.dob').val());
				var guardian = trim($(this).closest('tr').find('input.guardian').val());
				var office_add = $(this).closest('tr').find('textarea.office_add').val();
				var home_add = $(this).closest('tr').find('textarea.home_add').val();
				var phone = trim($(this).closest('tr').find('input.phone').val());
				var email = trim($(this).closest('tr').find('input.email').val());
				var gender = $(this).closest('tr').find('select.gender').val();
				var religion = $(this).closest('tr').find('select.religion').val();
				if( reg_no == "" || last_name == "" || first_name == "" || other_name == "" || dob == ""
					|| guardian == "" || office_add == "" || home_add == "" || phone == "" || email == ""
					|| religion == ""){
					reason += "Empty values not allowed \n";
				}
				if( isafterDate(dob) == true) reason += "DoB Date cannot be after current date \n";
				
				reason += validate_date_val(dob);
				
			});
			reason += validate_date(document.getElementById("txn_date"));
			if( isafterDate($('#txn_date').val()) == true) reason += "Reg Date cannot be after current date \n";
				
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	
	$('#btn_import_table').on('click', function(e){
		e.preventDefault();
		
		BootstrapDialog.confirm({
			title: 'RECORD UPDATE',
			message: 'Are you sure you want to upload this file?',
			type: BootstrapDialog.TYPE_WARNING,
			closable: true,
			callback: function(result) {
				if(result) {
					document.getElementById("btn_import_table").innerHTML = 
						'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
					$.ajax({
						url: "{{ route('studentImport')}}",
						data: new FormData($("#frm-import-table")[0]),
						async:false,
						type:'post',
						processData: false,
						contentType: false,
						success:function(data){
							$('#table_div').children().remove();
							$('#table_div').append(data);
							document.getElementById("btn_import_table").innerHTML = 
								'<i class="fa fa-upload fa-fw"></i>Upload';
						},
						error: function(jqXHR, exception) { 
							return_error(jqXHR, exception);
							document.getElementById("btn_import_table").innerHTML = 
								'<i class="fa fa-upload fa-fw"></i>Upload';
						}
					});
				}
			}
		});
	});
	function validateImport() {
		try{
			var reason = "";
			
			reason += validate_date(document.getElementById("import_date"));
			if( isafterDate($('#import_date').val()) == true) reason += "Import Date cannot be after current date \n";
				
			if (reason != "") {
				alert("Some fields need correction:\n" + reason);
				return false;
			}
			return true;
		}catch(err){ alert(err.message); }
	}
	
	$('#btn_update_table').on('click', function(e){
		e.preventDefault();
		try{
			//iterate through the table and update
			var operator = $('#frm-import-table #operator').val()
			var import_date = toDbaseDate($('#import_date').val());
			var table = $('#import_input tbody');
			
			var TotalItems = [];
			
			var is_valid = true;
			if(validate_date(document.getElementById("import_date")) !== "") is_valid = false;
			if (is_valid){
				
				document.getElementById("btn_update_table").innerHTML = 
					'<i class="fa fa-refresh fa-spin fa-1x faa-fast faa-slow"></i>&nbsp Processing...';
				
				$('#pleaseWaitDialog').modal();
				table.find('tr').each(function(i, el){
					var tds = $(this).find('td');
					var reg_no = tds.eq(0).text();
					var last_name = tds.eq(1).text();
					var first_name = tds.eq(2).text();
					var other_name = tds.eq(3).text();
					var dob = toDbaseDate(tds.eq(4).text());
					var gender = tds.eq(5).text();
					var religion = tds.eq(6).text();
					var guardian = tds.eq(7).text();
					var office_add = tds.eq(8).text();
					var home_add = tds.eq(9).text();
					var phone = tds.eq(10).text();
					var email = tds.eq(11).text();
					if( reg_no == "" || last_name == "" || first_name == "" || religion == "" || guardian == "" || 
						office_add == "" || home_add == "" || phone == "" || email == "" || religion == ""){
						is_valid = false;
					}
					//if(gender == "Male") gender = "1";
					//if(gender == "Female") gender = "0";
					if (is_valid){
						$('#frm-data-entry #import_date').val(import_date);
						$('#frm-data-entry #reg_no').val(reg_no);
						$('#frm-data-entry #last_name').val(last_name);
						$('#frm-data-entry #first_name').val(first_name);
						$('#frm-data-entry #other_name').val(other_name);
						$('#frm-data-entry #dob').val(dob);
						$('#frm-data-entry #gender').val(gender);
						$('#frm-data-entry #religion').val(religion);
						$('#frm-data-entry #guardian').val(guardian);
						$('#frm-data-entry #office_add').val(office_add);
						$('#frm-data-entry #home_add').val(home_add);
						$('#frm-data-entry #phone').val(phone);
						$('#frm-data-entry #email').val(email);
						
						var $ele = $(this).closest('tr');
						$.ajax({
							url: "{{ route('updateImport')}}",
							data: new FormData($("#frm-data-entry")[0]),
							async:false,
							type:'post',
							processData: false,
							contentType: false,
							success:function(data){
								
								if(Object.keys(data).length > 0){
									//remove the row
									//$(this).remove();
									$ele.fadeOut().remove();
									//$(this).parent().parent().remove();
									//$ele.css('background-color', '#ccc')
								}else{
									$ele.css('background-color', '#ccc');	
								}
								
							},
							error: function(jqXHR, exception) { 
								return_error(jqXHR, exception);
							}
						});
					}
				});
				document.getElementById("btn_update_table").innerHTML = 
									'<i class="fa fa-save fa-fw"></i>Update Database';
				$('#pleaseWaitDialog').modal('hide');
				alert('Update successful');
			}else{
				alert("Not valid...");	
			}
		}catch(err){ alert(err.message); }
	});
	function getStudents(){
		var reg_no =  $('#reg_no').val();
		//get student_id
		$.get("{{ route('getStudentID')}}", {reg_no: reg_no}, function(data){
			if( data !== "" && data !== null && data.length > 0 )
				fillStudentRecord(data);
		});
	}
	
</script>
@endsection