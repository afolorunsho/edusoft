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
	table.table td{
		padding-right:2px !important;
	}
</style>

	<table class="table table-hover" id="search-table">
        <thead>
            <tr>
            	<th style="width:5%;">ID</th>
                <th style="width:10%;">Date</th>
                <th style="width:12%;">Paying(CR)</th>
                <th style="width:12%;">Receiving(DR)</th>
                <th style="width:10%;">Channel</th>
                <th style="width:15%;">Amount</th>
                <th style="width:10%;">Txn Ref</th>
                <th style="width:20%;">Narration</th>
                <th style="width:3%;"></th>
                <th style="width:3%;"></th>
             </tr>
        </thead>
        <tbody>
            @foreach($records as $c)
                <tr>
                    <td><!-----this is to edit the whole batch of data------>
                        <a href="#" data-id="{{ $c->txn_id }}" class="edit-this"> 
                            {{ $c->txn_id }}
                        </a>
                    </td>
                    <td class="text-right">{{ date("d/m/Y", strtotime($c->txn_date)) }}</td>
                    <td>{{ $c->bank_from  }}</td>
                    <td>{{ $c->bank_to  }}</td>
                    <td>{{ $c->pay_channel }}</td>
                    <td class="text-right">{{ number_format($c->amount, 2, '.', ',') }}</td>
                    <td>{{ $c->bank_ref }}</td>
                    <td>{{ $c->narration }}</td>
                    <!-----to review and delete is one by one------>
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->txn_id }}" class="btn btn-info btn-sm review-this"><i class="fa fa-check-square fa-lg"></i></button>
                    </td>
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->txn_id }}" class="btn btn-danger btn-sm del-this"><i class="fa fa-trash-o fa-lg"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
