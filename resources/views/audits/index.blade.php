@extends('layouts.main')

@section('additionalStyles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header bg-info">
                <h1 class="card-title">Urzul Stats</h1>
            </div>
            <div class="card-body">
                <table id="auditTable" class="table table-active table-bordered">
                    <thead>
                    <tr>
                        <th>Ran At</th>
                        <th>Translated From</th>
                        <th>Translated To</th>
                        <th>Bot</th>
                        <th>OS</th>
                        <th>Browser</th>
                        <th>Device</th>
                        <th>File Size</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($audits as $audit)
                        <tr>
                            <td>{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $audit->source_language ?? '—' }}</td>
                            <td>{{ $audit->destination_language ?? '—' }}</td>
                            <td>{{ $audit->bot ? 'Yes' : 'No' }}</td>
                            <td>{{ $audit->os ?? '—' }}</td>
                            <td>{{ $audit->browser ?? '—' }}</td>
                            <td>{{ $audit->device ?? '—' }}</td>
                            <td>{{ $audit->file_size ? $audit->file_size .'B' : '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('additionalScripts')
    <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
@endsection
