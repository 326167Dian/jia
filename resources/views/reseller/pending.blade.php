<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Menunggu Persetujuan - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .wrap{max-width:460px;margin:60px auto;padding:0 16px;text-align:center}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);padding:34px 28px}
    .ico{font-size:52px}
    h1{font-size:19px;margin:10px 0 18px;color:var(--navy)}
    .status-note{text-align:left;border-radius:12px;background:#f2f8ff;border:1px solid #9fc6f2;padding:14px 16px;font-size:13.5px;line-height:1.8;margin-bottom:18px}
    .status-note b{color:var(--navy)}
    form button{width:100%;padding:12px;border:1px solid #d8e3f2;border-radius:999px;background:#fff;font-weight:700;cursor:pointer;color:var(--ink)}
    form button:hover{background:#f2f8ff}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="box">
      <div class="ico">⏳</div>
      <h1>Menunggu Persetujuan Admin</h1>
      <div class="status-note">
        <div><b>Nama:</b> {{ $reseller->name }}</div>
        <div><b>Username:</b> {{ $reseller->username }}</div>
        <div style="margin-top:8px">Akun reseller Anda sedang ditinjau admin. Anda akan bisa mulai membagikan link referral setelah akun disetujui.</div>
      </div>
      <form method="POST" action="{{ route('reseller.logout') }}">
        @csrf
        <button type="submit">Keluar</button>
      </form>
    </div>
  </div>
</body>
</html>
