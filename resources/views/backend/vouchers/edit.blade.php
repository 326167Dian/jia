@extends('backend.layout')

@section('title', 'Edit Voucher')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Edit Voucher</h5>
            <form method="POST" action="{{ route('admin.vouchers.update', $voucher) }}">
                @csrf
                @method('PUT')
                @include('backend.vouchers._form', ['voucher' => $voucher])
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
