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
            <li class="divider"></li>
            <li id="export-to-pdf"><a role="menuitem"><i class="fa fa-file-pdf-o fa-fw fa-fw"></i>Export to PDF</a></li>
        </ul>
    </div>
    <table class="table table-hover table-striped table-condensed" id="table-report">
        <thead>
            <tr>
                <th>Class</th>
                <th>Exam Name</th>
                <th>Max Score</th>
                <th>Exam Weight</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $c)
                <tr>
                    <td>{{ $c->class_name }}</td>
                    <td>{{ $c->exam_name  }}</td>
                    <td>{{ $c->max_score }}</td>
                    <td>{{ $c->exam_weight }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>