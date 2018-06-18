
<div class="row">
   <table class="table table-hover table-striped table-condensed" id="table-class-info">
        <thead>
            <tr>
                <th style="width:20%">Class</th>
                <th style="width:30%">Fee Head</th>
                <th style="width:15%">Amount</th>
                <th style="width:20%">Date</th>
                <th style="width:15%">Optional</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $key => $c)
                <tr>
                   <td>{{ $c['class_name'] }}</td>
                   <td>{{ $c['fee_name'] }}</td>
                   <td>{{ $c['amount'] }}</td>
                   <td>{{ $c['start_date'] }}</td>
                   <td>{{ $c['optional'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
</script>
