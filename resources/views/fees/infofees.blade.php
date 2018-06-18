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
</style>
<div class="row">
    <div class="dropdown pull-right" style="margin-top:5px;">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            <i class="fa fa-download fa-fw fa-fw"></i>Export
            <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li id="export-to-excel"><a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw"></i>Export to Excel</a></li>
        </ul>
    </div>
    <table class="table table-hover table-striped table-condensed" id="table-class-info">
        <thead>
            <tr>
                <th>Fees Name</th>
                <th>Account Group</th>
                <th colspan="2" style="text-align:center" width="50px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $c)
                <tr>
                    <td>
                        <a href="#" data-id="{{ $c->fee_id }}" id="edit-this" class="edit-this"> 
                            {{ $c->fee_name }}
                        </a>
                    </td>
                    <td>{{ $c->group_name }}</td>
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->fee_id }}" class="btn btn-info btn-sm review-this"><i class="fa fa-check-square fa-lg"></i></button>
                    </td>
                    <td style="vertical-align: middle; width: 25px";>
                        <button value="{{ $c->fee_id }}" class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
</script>
