@extends('backend.layout')

@section('title', 'Pembayaran')

@section('content')
    <div class="card" style="max-width:680px;">
        <div class="card-body">
            <h5 class="mb-3">Pengaturan Harga Produk</h5>
            <p class="text-muted">Nilai ini menentukan nominal pembayaran yang tampil di halaman "Ambil Promo" customer.</p>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.payment.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="product_name" value="{{ old('product_name', $setting->product_name) }}" class="form-control @error('product_name') is-invalid @enderror" required>
                    @error('product_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Harga Tahunan (Rp)</label>
                            <input type="number" name="amount" value="{{ old('amount', $setting->amount) }}" class="form-control @error('amount') is-invalid @enderror" min="1000" step="1000" required>
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Saat ini: Rp {{ number_format($setting->amount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Harga Bulanan (Rp)</label>
                            <input type="number" name="monthly_amount" value="{{ old('monthly_amount', $setting->monthly_amount) }}" class="form-control @error('monthly_amount') is-invalid @enderror" min="1000" step="1000" required>
                            @error('monthly_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Saat ini: Rp {{ number_format($setting->monthly_amount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <p class="text-muted" style="margin-top:-8px;font-size:13px">Catatan: kode voucher hanya berlaku untuk paket Tahunan.</p>

                <hr class="my-4">
                <h6 class="mb-3">Metode Pembayaran Aktif</h6>
                <p class="text-muted" style="margin-top:-8px">Pilih satu metode yang tampil ke customer di halaman "Ambil Promo".</p>

                @php
                    $gdcReady = $setting->isGatewayConfigured('gdcpay');
                    $midtransReady = $setting->isGatewayConfigured('midtrans');
                @endphp

                <div class="form-group mb-2">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="qris_static" id="method_qris_static" {{ old('payment_method', $setting->payment_method) === 'qris_static' ? 'checked' : '' }}>
                        <label class="form-check-label" for="method_qris_static">
                            <b>QRIS Statis</b> — gambar QRIS tetap + upload bukti transfer manual, diverifikasi admin.
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="gdcpay" id="method_gdcpay" {{ old('payment_method', $setting->payment_method) === 'gdcpay' ? 'checked' : '' }} {{ $gdcReady ? '' : 'disabled' }}>
                        <label class="form-check-label" for="method_gdcpay">
                            <b>QRIS Dinamis — GDC Pay</b> — nominal otomatis terisi di QR, pembayaran terverifikasi otomatis.
                            @if ($gdcReady)
                                <span class="badge bg-success ms-1">Terkonfigurasi</span>
                            @else
                                <span class="badge bg-secondary ms-1">Belum Dikonfigurasi</span>
                            @endif
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="midtrans" id="method_midtrans" {{ old('payment_method', $setting->payment_method) === 'midtrans' ? 'checked' : '' }} {{ $midtransReady ? '' : 'disabled' }}>
                        <label class="form-check-label" for="method_midtrans">
                            <b>QRIS Dinamis — Midtrans</b> — nominal otomatis terisi di QR, pembayaran terverifikasi otomatis.
                            @if ($midtransReady)
                                <span class="badge bg-success ms-1">Terkonfigurasi</span>
                            @else
                                <span class="badge bg-secondary ms-1">Belum Dikonfigurasi</span>
                            @endif
                        </label>
                    </div>
                    @error('payment_method') <div class="text-danger" style="font-size:13px">{{ $message }}</div> @enderror
                    <p class="text-muted" style="font-size:12.5px;margin-top:6px">
                        GDC Pay / Midtrans baru bisa dipilih setelah kredensial API diisi di file <code>.env</code> hosting (GDCPAY_* / MIDTRANS_*).
                    </p>
                </div>

                <hr class="my-4">
                <h6 class="mb-3">QRIS Statis</h6>
                <p class="text-muted" style="margin-top:-8px">Gambar ini tampil ke customer saat metode QRIS Statis aktif.</p>

                @if ($setting->qris_image_path)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ route('media.show', $setting->qris_image_path) }}" alt="QRIS" style="width:120px;height:120px;object-fit:contain;border:1px solid #e2eaf3;border-radius:10px;background:#fff;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remove_qris_image" value="1" id="remove_qris_image">
                            <label class="form-check-label" for="remove_qris_image">Hapus gambar QRIS ini</label>
                        </div>
                    </div>
                @endif

                <div class="form-group mb-3">
                    <label class="form-label">{{ $setting->qris_image_path ? 'Ganti Gambar QRIS' : 'Upload Gambar QRIS' }}</label>
                    <input type="file" name="qris_image" accept=".jpg,.jpeg,.png,.webp" class="form-control @error('qris_image') is-invalid @enderror">
                    @error('qris_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Format JPG, PNG, atau WEBP. Maksimal 3MB.</div>
                </div>

                <hr class="my-4">
                <h6 class="mb-3">Rekening Transfer Manual</h6>
                <p class="text-muted" style="margin-top:-8px">Tampil di halaman "Ambil Promo" sebagai instruksi transfer untuk customer.</p>

                <div class="form-group mb-3">
                    <label class="form-label">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $setting->bank_name) }}" class="form-control @error('bank_name') is-invalid @enderror" placeholder="Contoh: BCA DIGITAL / BLU BCA" required>
                    @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Nomor Rekening</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account', $setting->bank_account) }}" class="form-control @error('bank_account') is-invalid @enderror" required>
                    @error('bank_account') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Atas Nama</label>
                    <input type="text" name="bank_holder" value="{{ old('bank_holder', $setting->bank_holder) }}" class="form-control @error('bank_holder') is-invalid @enderror" required>
                    @error('bank_holder') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
