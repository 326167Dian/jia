<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Daftar Reseller - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .wrap{max-width:480px;margin:40px auto;padding:0 16px}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);overflow:hidden}
    .head{background:linear-gradient(120deg,#064da9,#05a99b);color:#fff;padding:26px 28px;text-align:center}
    .head img{height:50px;margin-bottom:10px}
    .head h1{font-size:19px;margin:0;font-weight:900}
    .head p{margin:4px 0 0;font-size:13px;opacity:.92}
    .body{padding:26px 28px}
    .group-title{font-size:13px;font-weight:900;color:var(--blue);text-transform:uppercase;letter-spacing:.05em;margin:20px 0 10px;padding-bottom:6px;border-bottom:2px solid #eaf2fb}
    .group-title:first-child{margin-top:0}
    .field{margin-bottom:16px}
    .field label{display:block;font-weight:700;font-size:13px;margin-bottom:6px;color:var(--navy)}
    .field input,.field textarea{width:100%;padding:12px 14px;border:1px solid #d8e3f2;border-radius:10px;font-size:14px;font-family:inherit}
    .field input:focus,.field textarea:focus{outline:none;border-color:var(--teal)}
    .field textarea{resize:vertical;min-height:70px}
    .submit-btn{width:100%;padding:14px;border:0;border-radius:999px;background:var(--blue);color:#fff;font-weight:800;font-size:15px;cursor:pointer;box-shadow:0 10px 24px rgba(7,85,184,.25)}
    .submit-btn:hover{background:#064da9}
    .error-list{background:#fff1f2;border:1px solid #fecdd3;color:#be123c;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:13px}
    .error-list ul{margin:0;padding-left:18px}
    .hint{text-align:center;margin-top:16px;font-size:13px;color:var(--muted)}
    .hint a{color:var(--blue);font-weight:700}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="box">
      <div class="head">
        <img src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}" alt="MySIFA">
        <h1>Daftar Reseller MySIFA</h1>
        <p>Ajak apotek lain bergabung & dapatkan komisi</p>
      </div>
      <div class="body">
        @if ($errors->any())
          <div class="error-list">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('reseller.register.submit') }}">
          @csrf

          <div class="group-title">Akun Login</div>

          <div class="field">
            <label>Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required>
          </div>

          <div class="field">
            <label>Password</label>
            <input type="password" name="password" required>
          </div>

          <div class="field">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>
          </div>

          <div class="group-title">Data Diri</div>

          <div class="field">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
          </div>

          <div class="field">
            <label>No. Telepon / WhatsApp</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
          </div>

          <div class="field">
            <label>Alamat (opsional)</label>
            <textarea name="address">{{ old('address') }}</textarea>
          </div>

          <div class="group-title">Rekening Penerima Komisi</div>

          <div class="field">
            <label>Nama Bank</label>
            <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Contoh: BCA" required>
          </div>

          <div class="field">
            <label>Nomor Rekening</label>
            <input type="text" name="bank_account" value="{{ old('bank_account') }}" required>
          </div>

          <button type="submit" class="submit-btn">Daftar Reseller</button>
        </form>

        <p class="hint">
          Sudah punya akun?<br>
          <a href="{{ route('reseller.login') }}">Masuk di sini</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
