	<style>
		h3 span { font-size:18px; }
	</style>
    <div class="modal fade" id="pleaseWaitDialog" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    	data-toggle="modal" data-backdrop="static" data-keyboard="false">
        
   		<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Please Wait<span> ...processing in progress!</span></h3>
                </div>
                <div class="modal-body">
                	<img src="{{url('/images/ajax-loader.gif')}}" style="display: block; margin-left: auto; margin-right: auto;"
                        	height="50" width="125">
                </div>
          	</div>
     	</div>
    </div>