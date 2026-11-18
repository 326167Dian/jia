@extends('backend.layout')

@section('title', 'Tambah Customer')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Tambah Customer Manual</h5>
            <p class="text-muted">Untuk pelanggan yang deal/bayar di luar form promo publik (mis. harga khusus, transfer langsung ke admin) sehingga tidak pernah mengisi form pendaftaran online.</p>

            <form method="POST" action="{{ route('admin.customers.store') }}">
                @csrf
                @include('backend.customers._form')

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
