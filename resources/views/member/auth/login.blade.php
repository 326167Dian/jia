<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Login Member - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .wrap{max-width:420px;margin:60px auto;padding:0 16px}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);overflow:hidden}
    .head{background:linear-gradient(120deg,#064da9,#05a99b);color:#fff;padding:26px 28px;text-align:center}
    .head img{height:50px;margin-bottom:10px}
    .head h1{font-size:19px;margin:0;font-weight:900}
    .head p{margin:4px 0 0;font-size:13px;opacity:.92}
    .body{padding:26px 28px}
    .field{margin-bottom:16px}
    .field label{display:block;font-weight:700;font-size:13px;margin-bottom:6px;color:var(--navy)}
    .field input{width:100%;padding:12px 14px;border:1px solid #d8e3f2;border-radius:10px;font-size:14px;font-family:inherit}
    .field input:focus{outline:none;border-color:var(--teal)}
    .submit-btn{width:100%;padding:14px;border:0;border-radius:999px;background:var(--blue);color:#fff;font-weight:800;font-size:15px;cursor:pointer;box-shadow:0 10px 24px rgba(7,85,184,.25)}
    .submit-btn:hover{background:#064da9}
    .error-list{background:#fff1f2;border:1px solid #fecdd3;color:#be123c;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:13px}
    .hint{text-align:center;margin-top:16px;font-size:13px;color:var(--muted)}
    .hint a{color:var(--blue);font-weight:700}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="box">
      <div class="head">
        <img src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}" alt="MySIFA">
        <h1>Login Member</h1>
        <p>Khusus pelanggan yang pembayarannya sudah diverifikasi</p>
      </div>
      <div class="body">
        @if ($errors->any())
          <div class="error-list">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('member.login') }}">
          @csrf
          <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
          </div>
          <div class="field">
            <label>Password</label>
            <input type="password" name="password" required>
          </div>
          <button type="submit" class="submit-btn">Masuk</button>
        </form>

        <p class="hint">
            Belum punya password? Password dibuat lewat halaman "Lengkapi Data Apotek"<br>
            yang dikirim admin via WhatsApp setelah pembayaran diverifikasi.
        </p>
      </div>
    </div>
  </div>
</body>
</html>
