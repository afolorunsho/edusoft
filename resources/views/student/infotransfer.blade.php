<style>
	.student-detail{
		white-space: normal;
		width:300px;	
	}
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
<table class="table table-hover table-striped table-condensed" id="table-class-info">
    <thead>
        <tr>
            <th>Date</th>
            <th>Class-From</th>
            <th>Class-To</th>
            <th>RegNo</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Other Name</th>
         </tr>
    </thead>
    <tbody>
    	@foreach($records as $c)
        	<tr>
            	<td>{{ date("d/m/Y", strtotime($c->transfer_date)) }}</td>
                <td>{{ $c->div_from }}</td>
                <td>{{ $c->div_to }}</td>
                <td>{{ $c->reg_no }}</td>
                <td>{{ $c->last_name }}</td>
                <td>{{ $c->first_name }}</td>
                <td>{{ $c->other_name }}</td>
    		</tr>
   		@endforeach
    </tbody>
</table>