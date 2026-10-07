<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Dashboard Member - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .topbar{background:#fff;border-bottom:1px solid #e7eef7;padding:14px 0}
    .topbar .container{display:flex;align-items:center;justify-content:space-between}
    .topbar img{height:40px}
    .topbar form{margin:0}
    .topbar button{background:none;border:1px solid #d8e3f2;border-radius:999px;padding:8px 18px;font-weight:700;cursor:pointer;color:var(--ink)}
    .topbar button:hover{background:#f2f8ff}
    .wrap{max-width:720px;margin:36px auto;padding:0 16px}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);padding:28px;margin-bottom:20px}
    .box h2{font-size:18px;margin:0 0 4px;color:var(--navy)}
    .box .sub{color:var(--muted);font-size:13px;margin-bottom:16px}
    .group-title{font-size:13px;font-weight:900;color:var(--blue);text-transform:uppercase;letter-spacing:.05em;margin:22px 0 8px;padding-bottom:6px;border-bottom:2px solid #eaf2fb}
    .group-title:first-of-type{margin-top:0}
    .row-item{display:flex;justify-content:space-between;gap:16px;border-bottom:1px dashed #eaf2fb;padding:9px 0;font-size:14px}
    .row-item span:first-child{color:var(--muted)}
    .row-item span:last-child{text-align:right}
    .alert-ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:13.5px;font-weight:700}
    .badge{display:inline-block;padding:4px 14px;border-radius:999px;font-weight:800;font-size:12.5px}
    .badge.complete{background:#ecfdf5;color:#047857}
    .badge.incomplete{background:#fff7e6;color:#aa6b00}
    .logo-box{display:flex;align-items:center;gap:14px;margin-bottom:6px}
    .logo-box img{width:64px;height:64px;object-fit:cover;border-radius:12px;border:1px solid #e2eaf3}
    .empty-note{background:#fff7e6;border:1px solid #f3dca0;color:#aa6b00;border-radius:10px;padding:12px 14px;font-size:13.5px}
  </style>
</head>
<body>
  <div class="topbar">
    <div class="container">
      <img src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}" alt="MySIFA">
      <form method="POST" action="{{ route('member.logout') }}">
        @csrf
        <button type="submit">Keluar</button>
      </form>
    </div>
  </div>

  <div class="wrap">
    @if (session('success'))
      <div class="alert-ok">{{ session('success') }}</div>
    @endif

    <div class="box">
      <h2>Halo, {{ $order->name }} 👋</h2>
      <p class="sub">Berikut data yang sudah Anda daftarkan.</p>

      <div class="group-title">Data Pendaftaran</div>
      <div class="row-item"><span>Kode Pendaftaran</span><span><b>{{ $order->order_code }}</b></span></div>
      <div class="row-item"><span>No. HP</span><span>{{ $order->phone }}</span></div>
      <div class="row-item"><span>Email</span><span>{{ $order->email ?: '-' }}</span></div>
      <div class="row-item"><span>Total Pembayaran</span><span>Rp {{ number_format($order->final_amount, 0, ',', '.') }}</span></div>
      @if ($order->voucher_code)
        <div class="row-item"><span>Kode Voucher</span><span>{{ $order->voucher_code }}</span></div>
      @endif
      <div class="row-item"><span>Status Pembayaran</span><span><span class="badge complete">Terverifikasi</span></span></div>
      <div class="row-item"><span>Tanggal Daftar</span><span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>

      @if ($order->pharmacy)
        @php $p = $order->pharmacy; @endphp

        <div class="group-title">Data Apotek</div>
        @if ($p->logo_path)
          <div class="logo-box">
            <img src="{{ route('media.show', $p->logo_path) }}" alt="Logo apotek">
            <span class="text-muted" style="font-size:13px">Logo Apotek</span>
          </div>
        @endif
        <div class="row-item"><span>Nama Apotek</span><span><b>{{ $p->nama_apotek }}</b></span></div>
        <div class="row-item"><span>SIA / Sertifikat Standar</span><span>{{ $p->nomor_izin_apotek ?: '-' }}</span></div>
        <div class="row-item"><span>Alamat Apotek</span><span>{{ $p->alamat_apotek ?: '-' }}</span></div>
        <div class="row-item"><span>No. Telp Apotek</span><span>{{ $p->telp_apotek ?: '-' }}</span></div>

        <div class="group-title">Data Apoteker</div>
        <div class="row-item"><span>Nama Apoteker</span><span>{{ $p->nama_apoteker ?: '-' }}</span></div>
        <div class="row-item"><span>SIPA</span><span>{{ $p->nomor_sipa ?: '-' }}</span></div>
        <div class="row-item"><span>Alamat Apoteker</span><span>{{ $p->alamat_apoteker ?: '-' }}</span></div>
        <div class="row-item"><span>No. Telp Apoteker</span><span>{{ $p->telp_apoteker ?: '-' }}</span></div>

        <div class="group-title">Data Pemilik Apotek</div>
        <div class="row-item"><span>Nama Pemilik</span><span><b>{{ $p->nama_pemilik }}</b></span></div>
        <div class="row-item"><span>No. Telp Pemilik</span><span>{{ $p->telp_pemilik }}</span></div>
        <div class="row-item"><span>Alamat Pemilik</span><span>{{ $p->alamat_pemilik ?: '-' }}</span></div>

        <div class="row-item" style="border-bottom:none;padding-top:14px">
          <span>Kelengkapan Data</span>
          <span>
            @if ($p->nomor_izin_apotek && $p->nama_apoteker && $p->logo_path)
              <span class="badge complete">Lengkap</span>
            @else
              <span class="badge incomplete">Belum Lengkap</span>
            @endif
          </span>
        </div>
      @else
        <div class="group-title">Data Apotek</div>
        <div class="empty-note">Anda belum mengisi data apotek. Silakan lengkapi lewat tombol di bawah.</div>
      @endif

      <div class="btn-row" style="margin-top:18px">
        <a class="btn btn-primary" href="{{ route('pharmacy.edit', $order->order_code) }}">
          {{ $order->pharmacy ? '✏️ Update Data Apotek' : '📝 Lengkapi Data Apotek' }}
        </a>
      </div>
    </div>
  </div>
</body>
</html>
