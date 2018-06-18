<!------you need a define role to be able to access the system------->
<div class="modal fade" id="signup_show" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    {!! csrf_field() !!}
	<div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">User Sign Up</h4>
			</div>
            <div class="modal-body">
                 <section class="row" style="padding:10px;">
                    <form id="signupform" role="form" method="POST">
                    	<input type="hidden" name="_token" value="{{ csrf_token()}}">
						<div class="form-group">
								<div class="col-md-6">
									<label class="col-md-12 control-label">Last Name</label>
									<div class="col-md-12">
										<input type="text" name="last-name" class="form-control" id="last-name" placeholder="Enter your last name">
									</div>
								</div>
								<div class="col-md-6">
									<label class="col-md-12 control-label">Other Names</label>
									<div class="col-md-12">
										<input type="text" name="other-names" class="form-control" id="other-names" placeholder="Enter your other name(s)">
									</div>
								</div>
						</div>
                        <div class="form-group">
								<div class="col-md-6">
									<label class="col-md-12 control-label">Secret Code</label>
									<div class="col-md-12">
										<input type="text" name="secret" class="form-control" id="secret" 
											placeholder="Enter secret code e.g your mother's maiden name">
									</div>
								</div>
								<div class="col-md-6">	
									<label class="col-md-12 control-label">Username</label>
									<div class="col-md-12">
										<input type="text" name="login-name" class="form-control" id="login-name" placeholder="Enter your user name">
									</div>
								</div>
                        </div>
                        <div class="form-group">
								<div class="col-md-6">
									<label class="col-md-12 control-label">Email Address</label>
									<div class="col-md-12">
										<input type="email" name="email" class="form-control" id="email" placeholder="Enter email address">
									</div>
								</div>
								<div class="col-md-6">
									<label class="col-md-12 control-label">Repeat Email</label>
									<div class="col-md-12">
										<input type="email" name="repeat-email" class="form-control" id="repeat-email" placeholder="Repeat email addree">
									</div>
								</div>
                        </div>
                        <div class="form-group">
								<div class="col-md-12">
									<label class="col-md-12 control-label">Phone</label>
									<div class="col-md-12">
										<input type="text" name="phone" class="form-control" id="phone" placeholder="Enter gsm phone number">
									</div>
								</div>
                        </div>
                      	<div class="form-group">
								<div class="col-md-6">
									<label class="col-md-12 control-label">Password</label>
									<div class="col-md-12">
										<input type="password" name="login-password" class="form-control" id="login-password" 
											placeholder="Enter your secret password">
									</div>
								</div>
								<div class="col-md-6">
									<label class="col-md-12 control-label">Repeat Password</label>
									<div class="col-md-12">
										<input type="password" name="repeat-password" class="form-control" id="repeat-password" 
											placeholder="Enter your secret password">
									</div>
								</div>
                        </div> 
                        <div class="form-group col-md-12">
                            <h3 class="dark-grey">Terms and Conditions</h3>
                            <p>By clicking on "Register" you agree to The Company's' Terms and Conditions</p>
                            <button type="button" class="btn btn-primary" id="btn-signup">Register</button>
                    	</div>
              		</form>
             	</section>
            </div>
		</div>
	</div>
</div>
