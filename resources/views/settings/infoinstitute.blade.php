<style>
	.institute-detail{
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
            <th>Name</th>
            <th>Reg No</th>
            <th>Reg Date</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Other Details</th>
            <th colspan="3" style="text-align:center" width="75px">Action</th>
      	</tr>
  	</thead>
    <tbody>
    	@foreach($records as $c)
        	<tr>
            	<td>
                	<a href="#" data-id="{{ $c->institute_id }}" id="institute-edit" class="edit-this"> 
                    	{{ $c->sch_name }}
                    </a>
                </td>
                <td>{{ $c->reg_no }}</td>
                <td>{{ $c->reg_date }}</td>
                <td>{{ $c->phone }}</td>
                <td>{{ $c->email }}</td>
                <td class="institute-detail">
                	Address: {{ $c->address }} ,
                    State/Region: {{ $c->region }} ,
                    Country: {{ $c->country }} ,
                    Website: {{ $c->website }}
                </td>
                {{----for review, enable button if it is not reviewed, otherwise disable button----}}
                <td style="vertical-align: middle; width: 25px;">
                	<button value="{{ $c->institute_id }}" class="btn btn-info btn-sm review-this"><i class="fa fa-check-square fa-lg"></i></button>
                </td>
                <td style="vertical-align: middle; width: 25px;">
                	<button value="{{ $c->institute_id }}" class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button>
                </td>
                <td style="vertical-align: middle; width: 25px;">
                	<button value="{{ $c->institute_id }}" class="btn btn-sm print-this"><i class="fa fa-print fa-lg"></i></button>
                </td>
    		</tr>
   		@endforeach
    </tbody>
</table>