<table class="table table-hover table-striped table-condensed" id="table-report">
    <thead>
        <tr>
            <th>Class</th>
            <th>Assessment</th>
            <th>Parameter</th>
         </tr>
    </thead>
    <tbody>
    	@foreach($records as $c)
        	<tr>
            	<td>{{ $c->class_name }}</td>
                <td>{{ $c->assessment }}</td>
                <td>{{ $c->parameter }}</td>
    		</tr>
   		@endforeach
    </tbody>
</table>