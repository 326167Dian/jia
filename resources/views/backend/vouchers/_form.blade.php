@php
    $v = $voucher ?? null;
@endphp

<div class="form-group mb-3">
    <label class="form-label">Kode Voucher</label>
    <input type="text" name="code" value="{{ old('code', $v->code ?? '') }}" class="form-control @error('code') is-invalid @enderror" style="text-transform:uppercase" required>
    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">Contoh: PROMO10, HEMAT50K</div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Tipe Potongan</label>
            <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="fixed" {{ old('type', $v->type ?? 'fixed') === 'fixed' ? 'selected' : '' }}>Potongan Nominal (Rp)</option>
                <option value="percent" {{ old('type', $v->type ?? '') === 'percent' ? 'selected' : '' }}>Potongan Persen (%)</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Nilai Potongan</label>
            <input type="number" name="value" value="{{ old('value', $v->value ?? '') }}" class="form-control @error('value') is-invalid @enderror" min="1" required>
            @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Untuk tipe nominal: Rp. Untuk tipe persen: maksimal 100.</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Kuota Pemakaian (opsional)</label>
            <input type="number" name="quota" value="{{ old('quota', $v->quota ?? '') }}" class="form-control @error('quota') is-invalid @enderror" min="1" placeholder="Kosongkan jika tanpa batas">
            @error('quota') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">Berlaku Sampai (opsional)</label>
            <input type="date" name="expires_at" value="{{ old('expires_at', optional($v->expires_at ?? null)->format('Y-m-d')) }}" class="form-control @error('expires_at') is-invalid @enderror">
            @error('expires_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-check form-switch mb-4">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $v->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Voucher Aktif</label>
</div>
