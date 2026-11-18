@php
    $c = $customer ?? null;
@endphp

@if ($c)
    <div class="form-group mb-3">
        <label class="form-label">Kode Pendaftaran</label>
        <input type="text" value="{{ $c->order_code }}" class="form-control" disabled>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $c->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">No. WhatsApp</label>
            <input type="text" name="phone" value="{{ old('phone', $c->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx" required>
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">Alamat Email (opsional)</label>
    <input type="email" name="email" value="{{ old('email', $c->email ?? '') }}" class="form-control @error('email') is-invalid @enderror">
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Paket</label>
            <select name="billing_period" class="form-control @error('billing_period') is-invalid @enderror" required>
                <option value="yearly" {{ old('billing_period', $c->billing_period ?? 'yearly') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                <option value="monthly" {{ old('billing_period', $c->billing_period ?? '') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
            </select>
            @error('billing_period') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Total Bayar (Rp)</label>
            <input type="number" name="final_amount" value="{{ old('final_amount', $c->final_amount ?? '') }}" class="form-control @error('final_amount') is-invalid @enderror" min="0" required>
            @error('final_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Isi nominal yang benar-benar disepakati/dibayar (mis. harga promo khusus).</div>
        </div>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
        <option value="verified" {{ old('status', $c->status ?? 'verified') === 'verified' ? 'selected' : '' }}>Verified</option>
        <option value="pending" {{ old('status', $c->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="rejected" {{ old('status', $c->status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ $c ? 'Password Baru (opsional)' : 'Password Login Member (opsional)' }}</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">
                @if ($c)
                    Kosongkan jika tidak ingin mengubah password.
                @else
                    Isi agar customer bisa langsung login ke halaman Member tanpa perlu klik "Kirim Link". Kosongkan jika akan dikirim link "Data Apotek" seperti alur normal.
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" minlength="8">
        </div>
    </div>
</div>
