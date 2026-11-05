@extends('backend.layout')

@section('title', 'Edit Cabang')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Edit Cabang</h5>
            <form method="POST" action="{{ route('admin.branches.update', $branch) }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label class="form-label">Kode Cabang</label>
                    <input type="text" value="{{ $branch->code }}" class="form-control" disabled>
                    <div class="form-text">Tidak bisa diubah -- sudah terpakai sebagai prefix di pembayaran-pembayaran lama cabang ini.</div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Nama Cabang</label>
                    <input type="text" name="name" value="{{ old('name', $branch->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">URL Callback</label>
                    <input type="url" name="callback_url" value="{{ old('callback_url', $branch->callback_url) }}" class="form-control @error('callback_url') is-invalid @enderror" required>
                    @error('callback_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Cabang Aktif</label>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
