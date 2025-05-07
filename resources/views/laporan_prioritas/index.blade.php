@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'profile',
])

@section('content')
    <div class="content">
        <h3>Data Prioritas Perbaikan</h3>
        <div class="card p-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                        <thead>
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Laporan</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranked as $r)
                                <tr>
                                    <th scope="row">{{ $r['rank'] }}</th>
                                    <td>{{ $r['id_laporan'] }}</td>
                                    <td>{{ number_format($r['S'], 4) }}</td>
                                    <td>{{ $r['status'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
