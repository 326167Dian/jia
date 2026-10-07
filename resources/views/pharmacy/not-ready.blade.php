<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Menunggu Verifikasi - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .wrap{max-width:480px;margin:60px auto;padding:0 16px;text-align:center}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);padding:36px 28px}
    .ico{font-size:52px}
    h1{font-size:20px;color:var(--navy);margin:10px 0 6px}
    p{color:var(--muted);line-height:1.7}
    .status{display:inline-block;margin-top:10px;padding:6px 16px;border-radius:999px;font-weight:800;font-size:13px}
    .status.pending{background:#fff7e6;color:#aa6b00}
    .status.rejected{background:#fff1f2;color:#be123c}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="box">
      <div class="ico">{{ $order->status === 'rejected' ? '❌' : '⏳' }}</div>
      <h1>
        @if ($order->status === 'rejected')
          Pembayaran Ditolak
        @else
          Menunggu Verifikasi Pembayaran
        @endif
      </h1>
      <p>
        @if ($order->status === 'rejected')
          Mohon maaf, bukti transfer untuk pendaftaran <b>{{ $order->order_code }}</b> belum bisa diverifikasi. Silakan hubungi admin kami via WhatsApp untuk bantuan lebih lanjut.
        @else
          Bukti transfer Anda untuk pendaftaran <b>{{ $order->order_code }}</b> masih dalam proses verifikasi oleh admin. Halaman ini akan bisa diisi setelah pembayaran diverifikasi.
        @endif
      </p>
      <span class="status {{ $order->status }}">{{ ucfirst($order->status) }}</span>
    </div>
  </div>
</body>
</html>
