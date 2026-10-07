@extends('backend.layout')

@section('title', 'Tambah Voucher')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Tambah Voucher Baru</h5>
            <form method="POST" action="{{ route('admin.vouchers.store') }}">
                @csrf
                @include('backend.vouchers._form', ['voucher' => null])
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
