@extends('backend.layout')

@section('title', 'Tambah Cabang')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Tambah Cabang Baru</h5>
            <form method="POST" action="{{ route('admin.branches.store') }}">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label">Kode Cabang</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="form-control @error('code') is-invalid @enderror" style="text-transform:uppercase" maxlength="20" required>
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Singkat, huruf/angka saja (mis. APB untuk Apotek Berlian). Jadi prefix di partnerReferenceNo GDC -- <strong>tidak bisa diubah setelah dibuat</strong>.</div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Nama Cabang</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">URL Callback</label>
                    <input type="url" name="callback_url" value="{{ old('callback_url') }}" class="form-control @error('callback_url') is-invalid @enderror" placeholder="https://domain-cabang.my.id/webhooks/hub-qris/notification" required>
                    @error('callback_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">URL endpoint di situs cabang yang menerima notifikasi pembayaran (route <code>hub-qris.webhook</code> di proyek cabang).</div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Cabang Aktif</label>
                </div>

                <div class="alert alert-info">API Key akan dibuat otomatis begitu disimpan, dan hanya ditampilkan penuh sekali di halaman berikutnya -- siapkan file <code>.env</code> cabang untuk langsung disalin.</div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
