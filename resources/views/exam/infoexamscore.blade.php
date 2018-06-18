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
	@if(Auth::user()->role_id == '1' ||
        Auth::user()->role_id == '5' ||
        Auth::user()->role_id == '9' ||
        Auth::user()->role_id == '8')
    <div class="dropdown pull-right">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            <i class="fa fa-download fa-fw fa-fw"></i>Export
            <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li id="export-to-excel"><a role="menuitem"><i class="fa fa-file-excel-o fa-fw fa-fw"></i>Export to Excel</a></li>
    </div>
    @endif
    <table class="table table-hover table-striped table-condensed" id="exam-table-view">
        <thead>
            <tr>
                <th>Exam</th>
                <th>Date</th>
                <th>Subject</th>
                <th>Reg No</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Class</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $c)
                <tr>
                    <td>{{ $c->exam_name }}</td>
                    <td>{{ date("d/m/Y", strtotime($c->exam_date)) }}</td>
                    <td>{{ $c->subject }}</td>
                    <td>{{ $c->reg_no }}</td>
                    <td>{{ $c->last_name }}</td>
                    <td>{{ $c->first_name }}</td>
                    <td>{{ $c->class_div }}</td>
                    <td>{{ $c->exam_score }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>