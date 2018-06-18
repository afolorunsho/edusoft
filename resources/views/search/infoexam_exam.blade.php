<style>
	
</style>
<div class="container table-responsive" style="overflow: auto">
    <table class="table table-hover table-striped table-condensed" id="table-class-info">
    
         <thead>
            <tr>
                <th style="width:10%;">Date</th>
                <th style="width:10%;">Term</th>
                <th style="width:10%;">Reg No</th>
                <th style="width:10%;">Student</th>
                <th style="width:10%;">Class</th>
                <th style="width:10%;">Exam</th>
                <th style="width:10%;">Subject</th>
                <th style="width:15%;">Score</th>
                <th style="width:5%;">Max Score</th>
                <th style="width:10%;">Exam Weight</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($records) && $records->count())
                @foreach($records as $c)
                    <tr>
                        <td>{{ date("d/m/Y", strtotime($c->exam_date)) }}</td>
                        <td>{{ $c->semester }}</td>
                        <td>{{ $c->reg_no }}</td>
                        <td>{{ $c->last_name .', '.$c->first_name }}</td>
                        <td>{{ $c->class_div }}</td>
                        <td>{{ $c->exam_name }}</td>
                        <td>{{ $c->subject }}</td>
                        <td>{{ $c->exam_score }}</td>
                        <td>{{ $c->max_score }}</td>
                        <td>{{ $c->exam_weight }}</td>
                    </tr>
                @endforeach 
            @else
                <tr>
                    <td colspan="10">There are no data.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
