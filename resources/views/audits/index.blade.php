@extends('layouts.main')

@section('additionalStyles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <table id="auditTable" class="table table-active table-bordered">
        <thead>
        <tr>
            <th>Translated From</th>
            <th>Translated To</th>
            <th>Bot</th>
            <th>OS</th>
            <th>Browser</th>
            <th>Device</th>
            <th>File Size</th>
            <th>Ran At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($audits as $audit)
            <tr>
                <td>{{ $audit->source_language ?? '—' }}</td>
                <td>{{ $audit->destination_language ?? '—' }}</td>
                <td>{{ $audit->bot ? 'Yes' : 'No' }}</td>
                <td>{{ $audit->os ?? '—' }}</td>
                <td>{{ $audit->browser ?? '—' }}</td>
                <td>{{ $audit->device ?? '—' }}</td>
                <td>{{ $audit->file_size ?? '—' }}</td>
                <td>{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('additionalScripts')
    <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
@endsection
