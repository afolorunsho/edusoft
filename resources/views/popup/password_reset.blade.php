<!------you need your user ID, email address and mother's maiden name to be able to reset password------->            
<div class="modal fade" id="pass_reset_show" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Password Reset</h4>
			</div>
            <div class="modal-body">
                 <section class="row" style="padding:10px;">
                    <form id="signupform" role="form">
                        <div class="form-group col-md-12">
                            <label for="username" class="col-md-4 control-label">Username</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="rst-username" id="rst-username" 
                                	placeholder="Enter your registered username">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email" class="col-md-4 control-label">Registered Email</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="rst-email" id="rst-email" 
                                	placeholder="Enter your email address that you used to register">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="secret" class="col-md-4 control-label">Secret Word</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="rst-secret" id="rst-secret" 
                                	placeholder="Enter mother's maiden name">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="phone" class="col-md-4 control-label">Phone</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="rst-phone" id="rst-phone" 
                                	placeholder="Enter your registered phone number">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                        	<label for="password" class="col-md-4 control-label">New Password</label>
                            <div class="col-md-8">
                            	<input type="password" name="rst-password" class="form-control" id="rst-password" 
                                	placeholder="Enter your new password">
                         	</div>
                        </div>
                        <div class="form-group col-md-12">
                        	<label for="repeat-password" class="col-md-4 control-label">Repeat Password</label>
                            <div class="col-md-8">
                            	<input type="password" name="rst-repeat-password" class="form-control" id="rst-repeat-password" 
                                	placeholder="Repeat your new password">
                          	</div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="col-md-offset-3 col-md-8">
                                <button id="btn-reset" type="button" class="btn btn-warning">Submit</button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
		</div>
	</div>
</div>