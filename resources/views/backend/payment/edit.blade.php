@extends('backend.layout')

@section('title', 'Pembayaran')

@section('content')
    <div class="card" style="max-width:560px;">
        <div class="card-body">
            <h5 class="mb-3">Pengaturan Harga Produk</h5>
            <p class="text-muted">Nilai ini menentukan nominal pembayaran yang tampil di halaman "Ambil Promo" customer.</p>

            <form method="POST" action="{{ route('admin.payment.update') }}">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="product_name" value="{{ old('product_name', $setting->product_name) }}" class="form-control @error('product_name') is-invalid @enderror" required>
                    @error('product_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="amount" value="{{ old('amount', $setting->amount) }}" class="form-control @error('amount') is-invalid @enderror" min="1000" step="1000" required>
                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Saat ini: Rp {{ number_format($setting->amount, 0, ',', '.') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
