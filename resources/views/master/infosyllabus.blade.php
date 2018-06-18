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
	table {
        border-collapse: collapse;
    }
    
    .hidden{
        display:none;
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
    <table class="table table-hover table-striped table-condensed" id="table-class-info">
        <thead>
            <tr>
                <th width="15%">Class</th>
                <th width="20%">Subject</th>
                <th width="50%">Syllabus</th>
                <th width="15%">Section</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $c)
                <tr>
                    <td>{{ $c->class_name  }}</td>
                    <td>
                        <a href="#" data-id="{{ $c->syllabus_id }}" id="syllabus_id" class="edit-this"> 
                            {{ $c->subject }}
                       	</a>
                   	</td>
                            
                    <td><pre style="border:none;background-color:transparent;">{{ $c->syllabus }}</pre></td>
                    <td>{{ $c->class_div }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>