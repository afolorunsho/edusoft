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
            <li class="divider"></li>
            <li id="export-to-pdf"><a role="menuitem"><i class="fa fa-file-pdf-o fa-fw fa-fw"></i>Export to PDF</a></li>
        </ul>
    </div>
    <table class="table table-hover table-striped table-condensed" id="table-class-info">
        <thead>
            <tr>
                <th>Academic Year</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th colspan="2" style="text-align:center" width="50px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $c)
                <tr>
                    <td>
                        <a href="#" data-id="{{ $c->academic_id }}" id="academic-edit" class="edit-this"> 
                            {{ $c->academic }}
                        </a>
                    </td>
                    <td>{{ $c->date_from }}</td>
                    <td>{{ $c->date_to }}</td>
                    
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->academic_id }}" class="btn btn-info btn-sm review-this">
                        	<i class="fa fa-check-square fa-lg"></i></button>
                    </td>
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->academic_id }}" class="btn btn-danger btn-sm del-this">
                        	<i class="fa fa-trash-o fa-lg"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
	/*
	$(document).ready(function() {
    	$('#table-class-info').DataTable();
	});
	$(document).ready(function() {
		$('#table-class-info').dataTable({
		   data: msg.virtualTable,
		   columns: [
			  { data: "Academic Year" },
			  { data: "Date From" },
			  { data: "Date To" },
			  { data: "Action 1" },
			  { data: "Action 2" }
		   ]
		});
	});*/
</script>
