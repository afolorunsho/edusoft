
<div class="row">
    @if (count($backups))
        {!! csrf_field() !!}
        <table class="table table-hover table-striped table-condensed" id="table-class-info">
            <thead>
            <tr>
                <th>File Name</th>
                <th>File Size</th>
                <th>Backup Date</th>
                <th>Backup Age</th>
                <th colspan="3" style="text-align:center" width="75px">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($backups as $backup)
                    <tr>
                        <td>{{ $backup['file_name'] }}</td>
                        <td>{{ $backup['file_size'] }}</td>
                        <td>
                            {{ $backup['last_modified'] }}
                        </td>
                        <td>
                            {{ $backup['file_age'] }}
                        </td>
                        <td style="vertical-align: middle; width: 25px;">
                            <button value="{{ $backup['file_name'] }}" class="btn btn-sm download-this">
                                <i class="fa fa-download fa-lg"></i></button>
                        </td>
                        <td style="vertical-align: middle; width: 25px;">
                            <button value="{{ $backup['file_name'] }}" class="btn btn-sm restore-this">
                                <i class="fa fa-upload fa-lg"></i></button>
                        </td>
                        <td style="vertical-align: middle; width: 25px;">
                            <button value="{{ $backup['file_name'] }}" class="btn btn-danger btn-sm del-this">
                                <i class="fa fa-trash-o fa-lg"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="well">
            <h4>There are no backups</h4>
        </div>
    @endif
</div>
