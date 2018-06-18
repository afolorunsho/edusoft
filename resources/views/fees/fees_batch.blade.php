<!------you need a define role to be able to access the system------->
<div class="modal fade" id="fee_batch_show" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    {!! csrf_field() !!}
	<div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List of Fees for Deletion</h4>
			</div>
            <div class="modal-body">
                 <section class="row" style="padding:10px;">
                    <form id="deleteForm" role="form" method="POST">
						<input type="hidden" name="payment_id" id="payment_id"> 
                        <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                        <input type="hidden" name="reviewer" id="reviewer" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="col-md-12">
                                    
									<label for="txn_date">Date</label>
									<div class="form-group">
										<input type="text" name="txn_date" class="form-control text-right" id="txn_date">
									</div>
                                 </div>
                                 <div class="col-md-12">
									<label for="semester_id">Term</label>
									<div class="form-group">
										<input type="text" name="semester_id" class="form-control" id="semester_id">
									</div>
								</div>
                                <div class="col-md-12">
									<label for="total_amount">Total Amount</label>
									<div class="form-group">
										<input type="text" name="total_amount" class="form-control text-right" id="total_amount">
									</div>
								</div>
								<div class="col-md-12">
									<label for="channel">Pay Channel</label>
								   <div class="form-group">
										<input type="text" name="channel" class="form-control" id="channel">
									</div>
								</div>
                                <div class="col-md-12">
									<label for="reference">Payment Reference</label>
									<div class="form-group">
										<input type="text" name="reference" class="form-control" id="reference">
									</div>
								</div>
								<div class="col-md-12">
									<label for="bank_id">Bank</label>
									<div class="form-group">
										<input type="text" name="bank_id" class="form-control" id="bank_id">
									</div>
								</div>
                                <div class="col-md-12">
                                    <label for="narration">Narration</label>
                                    <div class="form-group">
                                        <input type="text" name="narration" class="form-control" id="narration">
                                    </div>
                                </div>
                                <div></div>
                                <div class="col-md-12">
                                    <div class="form-row pull-right">
                                        <button type="button" class="btn btn-primary" id="btn-delete">
                                        	<i class="fa fa-trash-o fa-lg"></i>Delete</button>
                                    </div>
                                </div>
                            </div>    
                            <div class="col-md-7 pull-right" style="border-left:2px solid #ccc;height:300px">
                                
                                <table class="table table-hover table-striped table-condensed" id="table-input">
                                    <thead>
                                        <tr>
                                            <th width="15%">Reg. No</th>
                                            <th width="20%">Last Name</th>
                                            <th width="25%">First Name</th>
                                            <th width="20%">Fees Type</th>
                                            <th width="20%">Amount Paid</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
              		</form>
             	</section>
            </div>
		</div>
	</div>
</div>
