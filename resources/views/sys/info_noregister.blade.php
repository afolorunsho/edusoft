<style>
	td.del{
		text-align: center;
		vertical-align:middle;
		width: 50px;	
	}
	table tbody > tr > td{
		vertical-align: middle;	
	}
	#table-class-info{
		width: 100%;
	}
	.photo{
		height: 50px;
		width: 50px;
		margin: 0 auto;
	}
	.colored{
		background-color:#FCC;
	}
	.plain{
		background-color:#FFF;
	}
</style>
<div class="row">
    <div class="dropdown pull-right" style="margin-top:5px;">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            <i class="fa fa-download fa-fw fa-fw"></i>Export
            <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li id="export-to-excel"><a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw"></i>Export to Excel</a></li>
            <li class="divider"></li>
            <li id="export-to-pdf"><a role="menuitem"><i class="fa fa-file-pdf-o fa-fw fa-fw"></i>Export to PDF</a></li>
        </ul>
    </div>
   <table class="table" id="table-class-info">
        <thead>
            <tr>
                <th>Email</th> 
                <th>Username</th> 
                <th>Name</th>
                <th>Phone</th> 
                <th>Role</th>
                <th>Reg Date</th>
                <th>Last Signon</th> 
                <th>Count</th> 
                <th>Photo</th>
                <th style="text-align:center" width="25px">X</th>
                <th style="text-align:center" width="25px">X</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record as $key => $c)
            	@if( trim($c->role) == 'New User')
                    <tr class="colored">
                @else
                    <tr>
                @endif
                        
                    <td>
                        <a href="#" data-id="{{ $c->user_id }}" id="edit-this" class="edit-this"> 
                            {{ $c->email }}
                        </a>
                    </td>
                    <td>{{ $c->username }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->phone }}</td>
                    <td>{{ $c->role }}</td>
                    
                    <td>{{ $c->created_at }}</td>
                    <td>{{ $c->last_signon }}</td>
                    <td>{{ $c->signon_cnt }}</td>
                    <td><img src="{{url('photo/user').'/'.$c->photo }}" class="photo"></td>
                    
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->user_id }}" class="btn btn-info btn-sm review-this"><i class="fa fa-check-square fa-lg"></i></button>
                    </td>
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->user_id }}" class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
