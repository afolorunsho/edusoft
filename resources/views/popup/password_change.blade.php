<!------you need your user ID, email address and mother's maiden name to be able to reset password------->            
<div class="modal fade" id="pass_change_show" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Password Change</h4>
			</div>
            <div class="modal-body">
                <section class="row" style="padding:10px;">
                    <p class="text-center">Use the form below to change your password. Your password cannot be the same as your username.</p>
                    <form method="post" id="passwordForm">
                    	 <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                         <input type="password" class="form-control" name="old_password" id="old_password" 
                            placeholder="Old Password" autocomplete="off">
                         
                         <input type="password" class="form-control" name="password1" id="password1" 
                            placeholder="New Password" autocomplete="off">
                        <div class="row">
                            <div class="col-sm-6">
                                <span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Characters Long<br>
                                <span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Uppercase Letter
                            </div>
                            <div class="col-sm-6">
                                <span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Lowercase Letter<br>
                                <span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Number
                            </div>
                        </div>
                        <input type="password" class="form-control" name="password2" id="password2" 
                            placeholder="Repeat Password" autocomplete="off">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords Match
                            </div>
                        </div>
                        <input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" 
                            data-loading-text="Changing Password..." value="Change Password">
                    </form>
                </section>
            </div>
		</div>
	</div>
</div>
<script>
	$("input[type=password]").keyup(function(){
		var ucase = new RegExp("[A-Z]+");
		var lcase = new RegExp("[a-z]+");
		var num = new RegExp("[0-9]+");
		
		if($("#password1").val().length >= 8){
			$("#8char").removeClass("glyphicon-remove");
			$("#8char").addClass("glyphicon-ok");
			$("#8char").css("color","#00A41E");
		}else{
			$("#8char").removeClass("glyphicon-ok");
			$("#8char").addClass("glyphicon-remove");
			$("#8char").css("color","#FF0004");
		}
		
		if(ucase.test($("#password1").val())){
			$("#ucase").removeClass("glyphicon-remove");
			$("#ucase").addClass("glyphicon-ok");
			$("#ucase").css("color","#00A41E");
		}else{
			$("#ucase").removeClass("glyphicon-ok");
			$("#ucase").addClass("glyphicon-remove");
			$("#ucase").css("color","#FF0004");
		}
		
		if(lcase.test($("#password1").val())){
			$("#lcase").removeClass("glyphicon-remove");
			$("#lcase").addClass("glyphicon-ok");
			$("#lcase").css("color","#00A41E");
		}else{
			$("#lcase").removeClass("glyphicon-ok");
			$("#lcase").addClass("glyphicon-remove");
			$("#lcase").css("color","#FF0004");
		}
		
		if(num.test($("#password1").val())){
			$("#num").removeClass("glyphicon-remove");
			$("#num").addClass("glyphicon-ok");
			$("#num").css("color","#00A41E");
		}else{
			$("#num").removeClass("glyphicon-ok");
			$("#num").addClass("glyphicon-remove");
			$("#num").css("color","#FF0004");
		}
		
		if($("#password1").val() == $("#password2").val()){
			$("#pwmatch").removeClass("glyphicon-remove");
			$("#pwmatch").addClass("glyphicon-ok");
			$("#pwmatch").css("color","#00A41E");
		}else{
			$("#pwmatch").removeClass("glyphicon-ok");
			$("#pwmatch").addClass("glyphicon-remove");
			$("#pwmatch").css("color","#FF0004");
		}
	});
	$(function(){
		$("#passwordForm").on('submit', function(e){
			e.preventDefault();
			
			BootstrapDialog.confirm({
				title: 'RECORD UPDATE',
				message: 'Are you sure you want to update this record?',
				type: BootstrapDialog.TYPE_WARNING,
				closable: true,
				callback: function(result) {
					
					if(result) {
						
						$.ajax({
						  url: "{{ route('changePassword')}}",
						  data: new FormData($("#passwordForm")[0]),
						  async:false,
						  type:'post',
						  processData: false,
						  contentType: false,
						  success:function(data){
								if(Object.keys(data).length > 0){
									$('#passwordForm')[0].reset();
									alert('Update successful');
								}else{
									alert('Update NOT successful');
								}
								
							},
							error: OnError
						});
					}
				}
			});
		})
		
	});
</script>