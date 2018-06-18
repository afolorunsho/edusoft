
<table class="table table-hover table-striped table-condensed" id="table-class-info">
    <thead>
        <tr>
            <th>#</th>
            <th>Reg. No</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Other Name</th>
            <th>Class</th>
            <th>Section</th>
            <th>Reg Date</th>
            <th>Enrol Date</th>
            <th width="50px">Photo</th>
         </tr>
    </thead>
    <tbody>
    	@foreach($records as $key => $c)
        	<tr> 
            	<td>{{ ++$key }}</td>
                <td><a href="#" data-id="{{ $c['student_id'] }}" id="view-this" class="view-this"> 
                    {{ $c['reg_no'] }}
                </a></td>
                <td>{{ $c['last_name'] }}</td>
                <td>{{ $c['first_name'] }}</td>
                <td>{{ $c['other_name'] }}</td>
                <td>{{ $c['class_name'] }}</td>
                <td>{{ $c['section'] }}</td>
                <td>{{ date("d/m/Y", strtotime($c['reg_date'])) }}</td>
                <td>{{ date("d/m/Y", strtotime($c['enrol_date'])) }}</td>
                <td><img src="{{url('photo/student'.'/'.$c['photo']) }}" width="50" height="35" /></td>
                
    		</tr>
   		@endforeach
    </tbody>
</table>