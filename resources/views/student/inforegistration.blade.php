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
            <th>Reg. No</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Reg. Date</th>
            <th>Enrol Date</th>
            <th>Photo</th>
            {{-- <th>Others</th> --}}
            <th width="75px" colspan = "3"></th>
         </tr>
    </thead>
    <tbody>
    	@foreach($records as $c)
        	@if( $c->enrol_date == "")
            	<tr class="colored">
        	@else
            	<tr>
        	@endif
            	<td>
                	<a href="#" data-id="{{ $c->student_id }}" id="student-edit" class="edit-this"> 
                    	{{ $c->reg_no }}
                    </a>
                </td>
                <td>{{ $c->last_name }}</td>
                <td>{{ $c->first_name }}</td>
                <td>{{ $c->gender }}</td>
                <td>{{ $c->dob }}</td>
                <td>{{ $c->reg_date }}</td>
               	<td>{{ $c->enrol_date }}</td>
                <td><img src="{{url('photo/student'.'/'.$c->photo)}}" width="50" height="35" /></td>
                {{---- <td class="student-detail"> <pre>
                	State/Region: {{ $c->region }},
                    Guardian: {{ $c->guardian }},
                    Relationship: {{ $c->relationship }},
                    Office: {{ $c->guard_office }},
                    Phone: {{ $c->guard_phone }},
                    Email: {{ $c->guard_email }},
                    Home: {{ $c->guard_home }}</pre>
                </td> -----}}
                {{----for review, enable button if it is not reviewed, otherwise disable button----}}
                <td style="vertical-align: middle; width: 25px;">
                	<button value="{{ $c->student_id }}" class="btn btn-info btn-sm review-this"><i class="fa fa-check-square fa-lg"></i></button>
                </td>
                <td style="vertical-align: middle; width: 25px;">
                	<button value="{{ $c->student_id }}" class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button>
                </td>
                <td style="vertical-align: middle; width: 25px;">
                	<button value="{{ $c->student_id }}" class="btn btn-sm print-this"><i class="fa fa-print fa-lg"></i></button>
                </td>
    		</tr>
   		@endforeach
    </tbody>
</table>