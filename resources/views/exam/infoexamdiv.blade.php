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
    <div class="dropdown pull-right">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            <i class="fa fa-download fa-fw fa-fw"></i>Export
            <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li id="export-to-excel"><a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw"></i>Export to Excel</a></li>
        </ul>
    </div>
    <table class="table table-hover table-striped table-condensed" id="table-report">
        <thead>
            <tr>
                <th>Class</th>
                <th>Score Division</th>
                <th>Score From</th>
                <th>Score To</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $c)
                <tr>
                    <td>{{ $c->class_name }}</td>
                    <td>{{ $c->score_div }}</td>
                    <td>{{ $c->score_from }}</td>
                    <td>{{ $c->score_to }}</td>
                    <td>{{ $c->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>