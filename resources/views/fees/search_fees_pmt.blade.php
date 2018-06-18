<style>
	.
	td.del{
		text-align: center;
		vertical-align:middle;
		width: 50px;	
	}
	table tbody > tr > td{
		vertical-align: middle;	
	}
	#search-table{
		width: 100%;
	}
	table.table td{
		padding-right:2px !important;
	}
</style>

	<table class="table table-hover" id="search-table">
        <thead>
            <tr>
                <th style="width:3%;">No</th>
                <th style="width:8%;">Date</th>
                <th style="width:10%;">Beneficiary</th>
                <th style="width:5%;">Voucher</th>
                <th style="width:10%;">Expense</th>
                <th style="width:15%;">Narration</th>
                <th style="width:5%;">Qty</th>
                <th style="width:7%;">Price</th>
                <th style="width:10%;">Amount</th>
                <th style="width:15%;">Bank</th>
                <th style="width:5%;">Ref</th>
                <th style="width:5%;">Channel</th>
                <th style="width:2%;"></th>
                <th style="width:2%;"></th>
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
                    <td>{{ $c->beneficiary  }}</td>
                    <td>{{ $c->voucher_no  }}</td>
                    <td>{{ $c->expense_name  }}</td>
                    <td>{{ $c->narration }}</td>
                    <td class="text-right">{{ number_format($c->qty, 2, '.', ',') }}</td>
                    <td class="text-right">{{ number_format($c->price, 2, '.', ',') }}</td>
                    <td class="text-right">{{ number_format($c->amount, 2, '.', ',') }}</td>
                    <td>{{ $c->bank_name }}</td>
                    <td>{{ $c->bank_ref }}</td>
                    <td>{{ $c->pay_channel }}</td>
                    <!-----to review and delete is one by one------>
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->txn_id }}" class="btn btn-info btn-sm review-this"><i class="fa fa-check-square fa-lg"></i></button>
                    </td>
                    <td style="vertical-align: middle; width: 25px;">
                        <button value="{{ $c->txn_id }}" class="btn btn-danger btn-sm remove-this"><i class="fa fa-trash-o fa-lg"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    		
				if (data.length > 0) {
					$.each(data, function(i,l){
						
							var row_data = '<tr>' +
								'<td>' + convertDate(l.payment_date) +'</td>'+
								'<td>' + l.reg_no +'</td>'+
								'<td>' + l.last_name +'</td>'+
								'<td>' + l.first_name +'</td>'+
								'<td>' + l.class_div +'</td>'+
								'<td>' + l.fee_name +'</td>'+
								'<td align="right">' + numberWithCommas(l.amount) +'</td>'+
								'<td>' + l.narration +'</td>'
								'</tr>';
							$('#fees-table> tbody:last-child').append(row_data);
					});
					$('#fees-table').DataTable();
				}else{
					alert('No record to display...');	
				}
				
