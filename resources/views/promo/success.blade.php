<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Pendaftaran Berhasil - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .wrap{max-width:520px;margin:60px auto;padding:0 16px;text-align:center}
    .card-box{background:#fff;border-radius:22px;box-shadow:var(--shadow);padding:36px 28px}
    .check{font-size:56px}
    h1{font-size:22px;color:var(--navy);margin:10px 0 6px}
    .code{display:inline-block;background:#f2f8ff;border:1px dashed #9fc6f2;border-radius:10px;padding:8px 16px;font-weight:900;color:var(--blue);margin:14px 0}
    .amount{font-size:22px;font-weight:900;color:var(--green);margin-bottom:10px}
    p{color:var(--muted);line-height:1.7}
    .btn{margin-top:18px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card-box">
      <div class="check">{{ $order->status === 'verified' ? '🎉' : '✅' }}</div>
      <h1>
        @if ($order->status === 'verified')
          Pembayaran Terverifikasi!
        @else
          Pendaftaran Berhasil Dikirim!
        @endif
      </h1>
      <p>Terima kasih, {{ $order->name }}.
        @if ($order->status === 'verified')
          Pembayaran Anda sudah diverifikasi oleh admin kami.
        @else
          Bukti transfer Anda sedang diverifikasi oleh admin kami.
        @endif
      </p>
      <div class="code">{{ $order->order_code }}</div>
      <div class="amount">Rp {{ number_format($order->final_amount, 0, ',', '.') }}</div>

      @if ($order->status === 'verified')
        <p>Langkah selanjutnya, silakan lengkapi data apotek Anda untuk aktivasi akun MySIFA.</p>
        <a class="btn btn-primary" href="{{ route('pharmacy.edit', $order->order_code) }}">🏪 Lengkapi Data Apotek</a>
      @else
        <p>Simpan kode pendaftaran di atas. Admin akan menghubungi Anda melalui WhatsApp setelah pembayaran diverifikasi.</p>
        <a class="btn btn-primary" href="https://wa.me/{{ config('services.promo_bank.whatsapp') }}?text=Halo%20MySIFA,%20saya%20sudah%20daftar%20promo%20dengan%20kode%20{{ $order->order_code }}." target="_blank" rel="noopener">💬 Konfirmasi via WhatsApp</a>
      @endif
    </div>
  </div>
</body>
</html>
