<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Verifikasi Invoice - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .wrap{max-width:480px;margin:50px auto;padding:0 16px;text-align:center}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);padding:34px 28px}
    .ico{font-size:56px}
    h1{font-size:20px;margin:10px 0 18px}
    h1.ok{color:#047857}
    h1.bad{color:#be123c}
    .row-item{display:flex;justify-content:space-between;border-bottom:1px dashed #eaf2fb;padding:9px 0;font-size:14px;text-align:left}
    .row-item span:first-child{color:var(--muted)}
    p.desc{color:var(--muted);line-height:1.7;margin-top:14px}
    .logo{height:50px;margin-bottom:14px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="box">
      <img src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}" alt="MySIFA" class="logo">

      @if ($order)
        <div class="ico">✅</div>
        <h1 class="ok">Invoice Terverifikasi Asli</h1>
        <div class="row-item"><span>Kode Invoice</span><span><b>{{ $order->order_code }}</b></span></div>
        <div class="row-item"><span>Nama Apotek</span><span>{{ $order->pharmacy->nama_apotek }}</span></div>
        <div class="row-item"><span>Nama Pemilik</span><span>{{ $order->pharmacy->nama_pemilik }}</span></div>
        <div class="row-item"><span>Total Pembayaran</span><span>Rp {{ number_format($order->final_amount, 0, ',', '.') }}</span></div>
        <div class="row-item"><span>Tanggal</span><span>{{ $order->created_at->format('d/m/Y') }}</span></div>
        <p class="desc">Invoice ini tercatat resmi di sistem MySIFA dan tidak dipalsukan.</p>
      @else
        <div class="ico">❌</div>
        <h1 class="bad">Invoice Tidak Ditemukan</h1>
        <p class="desc">Kode invoice ini tidak cocok dengan data di sistem MySIFA. Jika Anda menerima dokumen ini, mohon konfirmasi ulang ke pihak MySIFA sebelum melanjutkan transaksi.</p>
      @endif
    </div>
  </div>
</body>
</html>
