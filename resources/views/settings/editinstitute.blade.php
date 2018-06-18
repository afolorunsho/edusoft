
	<style type="text/css">
        .institute-photo{
            height: 200px;
            padding-left: 1px;
            padding-right: 1px;
            border: 1px solid #ccc;
            background: #eee;
            width: 300px;
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
        .photo > input[type = 'file']{
            display: none;	
        }
        .photo{
            width: 30px;
            height: 30px;
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
    
    <div class="modal fade" id="edit-show" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit Institute</h4>
                </div>
                <form action="" method="POST" class="form-horizontal" id="frm-edit-institute" enctype="multipart/form-data" >
                    <input type="hidden" name="institute_id" id="institute_id"> 
                    <input type="hidden" name="username" id="username" value="{{ Auth::user()->username }}">
                    <input type="hidden" name="_token" value="{{ csrf_token()}}">
                    <div class="modal-body">
                    @foreach($record as $c)
                    <div class="row">
                        <div class="col-md-8">
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="sch_name">Name</label>
                            <div class="col-sm-10">
                              <input id="sch_name" type="text" name="sch_name" placeholder="Name of Institute" class="form-control"
                                pattern="^([- \w\d\u00c0-\u024f]+)$" value="{{ $c->sch_name }}">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="reg_no">Reg. Code</label>
                            <div class="col-sm-4">
                              <input id="reg_no" type="text" name="reg_no" placeholder="Registration Code" class="form-control" pattern="^\S+$"
                                value="{{ $c->reg_no }}">
                            </div>
                
                            <label class="col-sm-2 control-label" for="reg_date">Reg. Date</label>
                            <div class="col-sm-4">
                              <input id="reg_date" type="text" name="reg_date" placeholder="Registration Date" class="form-control"
                                value="{{ $c->reg_date }}">
                            </div>
                          </div>
                          <!-- Text input-->
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="address">Address</label>
                            <div class="col-sm-10">
                              <textarea class="form-control" rows="3" id="address" name="address" placeholder="School Address"
                                value="{{ $c->address }}"></textarea>
                            </div>
                          </div>
                
                          <!-- Text input- <textarea class="form-control" rows="5" id="comment"></textarea>-->
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="region">State/Region</label>
                            <div class="col-sm-4">
                              <input id="region" type="text" name="region" placeholder="State/Region" class="form-control"
                                value="{{ $c->region }}">
                            </div>
                
                            <label class="col-sm-2 control-label" for="country">Country</label>
                            <div class="col-sm-4">
                              <input id="country" type="text" name="country" placeholder="Country" class="form-control"
                                value="{{ $c->country }}">
                            </div>
                          </div>
                          
                          <!-- Text input-->
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="phone">Phone</label>
                            <div class="col-sm-10">
                              <input id="phone" type="text" name="phone" placeholder="Contact Phone" class="form-control"
                                value="{{ $c->phone }}">
                            </div>
                          </div>
                          <!-- Text input-->
                         <div class="form-group">
                         
                            <label class="col-sm-2 control-label" for="email">Email</label>
                            <div class="col-sm-4">
                              <input id="email" type="email" name="email" placeholder="Contact Email" class="form-control"
                                pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" 
                                    value="{{ $c->email }}">
                            </div>
                
                            <label class="col-sm-2 control-label" for="website">Website</label>
                            <div class="col-sm-4">
                              <input id="website" type="text" name="website" placeholder="Website" class="form-control"
                              pattern="^(http[s]?:\/\/)?([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2}(\/([-~%\.\(\)\w\d]*\/*)*(#[-\w\d]+)?)?$"
                                value="{{ $c->website }}">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                              <div class="pull-right">
                                <button type="submit" class="btn btn-primary" id="btn-save">
                                    <i class="fa fa-save fa-fw fa-fw"></i>Edit</button>
                              </div>
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
                                                {{-----this is using a linked file to the storage/app/institute------}}
                                                 <img src="{!! asset('img/institute/'.{{ $c->photo_image }}.'') !!}"
                                                    class="institute-photo" id="showPhoto">
                                                <input type="file" name="photo_" id="photo_" 
                                                    accept="image/x-png, image/png,image/jpg,image/jpeg" onchange="previewImage();">
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
                                                <img src="{!! asset('img/institute/'.{{ $c->logo_image }}.'') !!}" 
                                                    class="institute-logo" id="showLogo">
                                                <input type="file" name="logo_" id="logo_" 
                                                    accept="image/x-png, image/png,image/jpg,image/jpeg">
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
                    @endforeach
                    </div>
                </form>
        	</div>
     	</div>
 	</div>