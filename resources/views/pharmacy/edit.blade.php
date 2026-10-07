<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Lengkapi Data Apotek - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .wrap{max-width:680px;margin:40px auto;padding:0 16px}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);overflow:hidden}
    .head{background:linear-gradient(120deg,#064da9,#05a99b);color:#fff;padding:26px 28px}
    .head h1{font-size:21px;margin:0 0 6px;font-weight:900}
    .head p{margin:0;opacity:.92;font-size:13.5px}
    .body{padding:26px 28px}
    .group-title{font-size:13px;font-weight:900;color:var(--blue);text-transform:uppercase;letter-spacing:.05em;margin:22px 0 12px;padding-bottom:6px;border-bottom:2px solid #eaf2fb}
    .group-title:first-child{margin-top:0}
    .field{margin-bottom:14px}
    .field label{display:block;font-weight:700;font-size:13px;margin-bottom:6px;color:var(--navy)}
    .field label .req{color:#e11d48}
    .field input,.field textarea{width:100%;padding:11px 13px;border:1px solid #d8e3f2;border-radius:10px;font-size:14px;font-family:inherit}
    .field input:focus,.field textarea:focus{outline:none;border-color:var(--teal)}
    .field textarea{resize:vertical;min-height:70px}
    .hint{font-size:12px;color:var(--muted);margin-top:4px}
    .logo-preview{display:flex;align-items:center;gap:12px;margin-bottom:10px}
    .logo-preview img{width:64px;height:64px;object-fit:cover;border-radius:10px;border:1px solid #e2eaf3}
    .submit-btn{width:100%;padding:14px;border:0;border-radius:999px;background:var(--blue);color:#fff;font-weight:800;font-size:15px;cursor:pointer;box-shadow:0 10px 24px rgba(7,85,184,.25)}
    .submit-btn:hover{background:#064da9}
    .alert-ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:13.5px;font-weight:700}
    .error-list{background:#fff1f2;border:1px solid #fecdd3;color:#be123c;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:13px}
    .error-list ul{margin:0;padding-left:18px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="box">
      <div class="head">
        <h1>🏪 Lengkapi Data Apotek</h1>
        <p>Kode pendaftaran: {{ $order->order_code }} — data wajib di awal, sisanya bisa menyusul lewat halaman ini kapan saja.</p>
      </div>
      <div class="body">

        @if (session('success'))
          <div class="alert-ok">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
          <div class="error-list">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('pharmacy.update', $order->order_code) }}" enctype="multipart/form-data">
          @csrf

          <div class="group-title">Data Apotek</div>

          <div class="field">
            <label>Nama Apotek <span class="req">*</span></label>
            <input type="text" name="nama_apotek" value="{{ old('nama_apotek', $pharmacy->nama_apotek) }}" required>
          </div>

          <div class="field">
            <label>SIA (Surat Izin Apotek) / Sertifikat Standar</label>
            <input type="text" name="nomor_izin_apotek" value="{{ old('nomor_izin_apotek', $pharmacy->nomor_izin_apotek) }}">
          </div>

          <div class="field">
            <label>Alamat Apotek</label>
            <textarea name="alamat_apotek">{{ old('alamat_apotek', $pharmacy->alamat_apotek) }}</textarea>
          </div>

          <div class="field">
            <label>No. Telp Apotek</label>
            <input type="text" name="telp_apotek" value="{{ old('telp_apotek', $pharmacy->telp_apotek) }}">
          </div>

          <div class="group-title">Data Apoteker</div>

          <div class="field">
            <label>Nama Apoteker</label>
            <input type="text" name="nama_apoteker" value="{{ old('nama_apoteker', $pharmacy->nama_apoteker) }}">
          </div>

          <div class="field">
            <label>SIPA (Surat Izin Praktek Apoteker)</label>
            <input type="text" name="nomor_sipa" value="{{ old('nomor_sipa', $pharmacy->nomor_sipa) }}">
          </div>

          <div class="field">
            <label>Alamat Apoteker</label>
            <textarea name="alamat_apoteker">{{ old('alamat_apoteker', $pharmacy->alamat_apoteker) }}</textarea>
          </div>

          <div class="field">
            <label>No. Telp Apoteker</label>
            <input type="text" name="telp_apoteker" value="{{ old('telp_apoteker', $pharmacy->telp_apoteker) }}">
          </div>

          <div class="group-title">Data Pemilik Apotek</div>

          <div class="field">
            <label>Nama Pemilik Apotek <span class="req">*</span></label>
            <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $pharmacy->nama_pemilik) }}" required>
          </div>

          <div class="field">
            <label>No. Telp Pemilik Apotek <span class="req">*</span></label>
            <input type="text" name="telp_pemilik" value="{{ old('telp_pemilik', $pharmacy->telp_pemilik) }}" required>
          </div>

          <div class="field">
            <label>Alamat Pemilik Apotek</label>
            <textarea name="alamat_pemilik">{{ old('alamat_pemilik', $pharmacy->alamat_pemilik) }}</textarea>
          </div>

          <div class="group-title">Logo Apotek</div>

          <div class="field">
            @if ($pharmacy->logo_path)
              <div class="logo-preview">
                <img src="{{ asset('storage/'.$pharmacy->logo_path) }}" alt="Logo saat ini">
                <span class="hint">Logo saat ini. Upload file baru untuk mengganti.</span>
              </div>
            @endif
            <input type="file" name="logo" accept=".jpg,.jpeg,.png">
            <div class="hint">Format PNG atau JPG, maksimal 2MB.</div>
          </div>

          <div class="group-title">Akun Login Member {{ $order->password ? '' : '(opsional)' }}</div>

          <div class="field">
            <label>{{ $order->password ? 'Password Baru' : 'Buat Password' }}</label>
            <input type="password" name="password" placeholder="{{ $order->password ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}">
            <div class="hint">
                @if ($order->password)
                    Login member Anda sudah aktif. Isi field ini hanya jika ingin mengganti password.
                @else
                    Buat password untuk bisa login ke area member kapan saja dan update data apotek tanpa link ini.
                @endif
            </div>
          </div>

          <div class="field">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation">
          </div>

          <button type="submit" class="submit-btn">Simpan Data</button>
        </form>

      </div>
    </div>
  </div>
</body>
</html>
