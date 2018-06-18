
<table class="table table-hover table-striped table-condensed" id="table-exit-info">
    <thead>
        <tr>
            <th>Date</th>
            <th>RegNo</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Other Name</th>
            <th>Class</th>
            <th>Reasons</th>
            <th>Remarks</th>
            <th width="25px">Reverse</th>
         </tr>
    </thead>
    <tbody>
    	@foreach($records as $c)
        	<tr>
            	<td>{{ date("d/m/Y", strtotime($c->exit_date)) }}</td>
                <td>{{ $c->reg_no }}</td>
                <td>{{ $c->last_name }}</td>
                <td>{{ $c->first_name }}</td>
                <td>{{ $c->other_name }}</td>
                <td>{{ $c->class_name }}</td>
                <td>{{ $c->reason }}</td>
                <td>{{ $c->remarks }}</td>
                <td style="vertical-align: middle; width: 25px;">
                    <button value="{{ $c->student_id }}" class="btn btn-danger btn-sm del-this"><i class="fa fa-undo fa-lg"></i></button>
                </td>
    		</tr>
   		@endforeach
    </tbody>
</table>