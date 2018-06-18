
	<!--<img src="{{url('/storage/institute/'.$c->logo_image)}}" width="48" height="48" alt="Click here to print." />-->
    <h4>School Details</h4>
	<table border="1" width="100%" cellpadding="10" >
    	<thead>
            <tr>
                <th>Details</th>
                <th>Value</th>
            </tr>
     	</thead>
        <tbody>
        	@foreach($records as $c)
            <tr><td>Name of School:</td><td>{{$c->sch_name}}</td></tr>
            <tr><td>Registration Date:</td><td>{{$c->reg_date}}</td></tr>
            <tr><td>Registration Code:</td><td>{{$c->reg_no}}</td></tr>
            <tr><td>Address:</td><td>{{$c->address}}</td></tr>
            <tr><td>Region/State:</td><td>{{$c->region}}</td></tr>
            <tr><td>Country:</td><td>{{$c->country}}</td></tr>
            <tr><td>Phone:</td><td>{{$c->phone}}</td></tr>
            <tr><td>Email:</td><td>{{$c->email}}</td></tr>
            <tr><td>Website:</td><td>{{$c->website}}</td></tr>
            @endforeach
   		</tbody>
	</table>