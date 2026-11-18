@extends('backend.layout')

@section('title', 'Edit Customer')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Edit Data Customer</h5>

            <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                @csrf
                @method('PUT')
                @include('backend.customers._form')

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
