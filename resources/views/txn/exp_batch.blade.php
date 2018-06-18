<!------you need a define role to be able to access the system------->
<div class="modal fade" id="delete_batch_show" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    {!! csrf_field() !!}
	<div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List of Expenses for Deletion</h4>
			</div>
            <div class="modal-body">
                 <section class="row" style="padding:10px;">
                    <form id="deleteForm" role="form" method="POST">
                    	<div class="col-md-4 pull-left" style="border-right:2px solid #ccc;height:450px">
                            <input type="hidden" name="txn_id" id="txn_id"> 
                            <input type="hidden" name="operator" id="operator" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="reviewer" id="reviewer" value="">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="col-md-12">
                                <label for="txn_date">Transaction Date</label>
                                <div class="form-group">
                                    <input type="text" name="txn_date" class="form-control text-right" width="10px" id="txn_date">
                                </div>
                            </div>
                        
                            <div class="col-md-12">
                                <label for="voucher_no">Voucher No.</label>
                                <div class="form-group">
                                    <input type="text" name="voucher_no" class="form-control" id="voucher_no">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="beneficiary">Beneficiary</label>
                                <div class="form-group">
                                    <input type="text" name="beneficiary" class="form-control" id="beneficiary">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="total_amount">Total Amount</label>
                                <div class="form-group">
                                    <input type="text" name="total_amount" class="form-control text-right" id="total_amount">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="pay_channel">Payment Channel</label>
                                <div class="form-group">
                               		<input type="text" name="pay_channel" class="form-control" id="pay_channel">
                               	</div>
                            </div>
                            <div class="col-md-12">
                                <label for="bank_id">Bank Name</label>
                                <div class="form-group">
                               		<input type="text" name="bank_id" class="form-control" id="bank_id">
                               	</div>
                            </div>
                        
                            <div class="col-md-12">
                                <label for="bank_ref">Bank Payment Ref</label>
                                <div class="form-group">
                                    <input type="text" name="bank_ref" class="form-control" id="bank_ref">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="description">Narration</label>
                                <div class="form-group">
                                    <input type="text" name="description" class="form-control" id="description">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 pull-right">       
                            <div class="form-group col-md-12">
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary" id="btn-delete">
                                        <i class="fa fa-trash-o fa-lg"></i>Delete</button>
                                </div>
                                
                            </div>
                            <div class="col-md-12">
                                <table class="table" id="table-input">
                                    <thead>
                                        <tr>
                                            <th width="25%">Expense</th>
                                            <th width="10%">Qty</th>
                                            <th width="15%">Price</th>
                                            <th width="20%">Amount</th>
                                            <th width="30%">Narration</th>
                                        </tr>
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
