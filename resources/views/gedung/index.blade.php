@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'gedung',
])

@section('content')
<div class="content">
    <h3>Gedung Polinema</h3>
    <div class="card p-4">
        <div class="card-header">
            <button onclick="modalAction('{{ url('/gedung/create') }}')" class="btn btn-sm btn-primary mt-1">Tambah Data</button>
        </div>

        <div class="row mt-3"> {{-- Tambahkan row --}}
            @foreach ($gedung as $index => $g)
            <div class="col-md-3 mb-4"> {{-- 4 kolom per baris (12/3=4) --}}
                <div class="card h-100">
                    <img class="card-img-top" src="{{ asset('gedung/gedung1.jpg') }}" alt="Card image cap">
                    <div class="card-body">
                        <ul class="list-group list-group-flush text-start mt-3">
                            <li class="list-group-item"><strong>Nama:</strong> {{ $g->nama_gedung }}</li>
                            <li class="list-group-item"><strong>Kode:</strong> {{ $g->kode_gedung }}</li>
                            <li class="list-group-item"><strong>Deskripsi:</strong> {{ $g->deskripsi }}</li>
                        </ul>
                        <a href="#" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
