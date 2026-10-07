<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Ambil Promo MySIFA - Daftar & Bayar</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .promo-wrap{max-width:560px;margin:40px auto;padding:0 16px}
    .promo-card{background:#fff;border-radius:22px;box-shadow:var(--shadow);overflow:hidden}
    .promo-head{background:linear-gradient(120deg,#064da9,#05a99b);color:#fff;padding:26px 28px}
    .promo-head h1{font-size:22px;margin:0 0 6px;font-weight:900}
    .promo-head p{margin:0;opacity:.92;font-size:14px}
    .promo-body{padding:26px 28px}
    .promo-amount{background:#f2f8ff;border:1px dashed #9fc6f2;border-radius:14px;padding:16px;text-align:center;margin-bottom:18px}
    .promo-amount .label{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:800}
    .promo-amount .value{font-size:30px;font-weight:950;color:var(--blue);margin-top:4px}
    .promo-amount .value.discounted{color:var(--green)}
    .promo-amount .original{font-size:14px;color:#9aa6bd;text-decoration:line-through}
    .bank-box{background:#fffaf0;border:1px solid #f3dca0;border-radius:12px;padding:14px 16px;font-size:13px;line-height:1.7;margin-bottom:20px}
    .bank-box b{color:#aa6b00}
    .field{margin-bottom:16px}
    .field label{display:block;font-weight:700;font-size:13px;margin-bottom:6px;color:var(--navy)}
    .field input{width:100%;padding:12px 14px;border:1px solid #d8e3f2;border-radius:10px;font-size:14px;font-family:inherit}
    .field input:focus{outline:none;border-color:var(--teal)}
    .voucher-row{display:flex;gap:8px}
    .voucher-row input{flex:1}
    .voucher-row button{white-space:nowrap;padding:0 18px;border-radius:10px;border:1px solid var(--blue);background:#fff;color:var(--blue);font-weight:800;cursor:pointer}
    .voucher-row button:hover{background:#f2f8ff}
    .voucher-msg{margin-top:6px;font-size:12.5px;font-weight:700}
    .voucher-msg.ok{color:var(--green)}
    .voucher-msg.err{color:#e11d48}
    .hint{font-size:12px;color:var(--muted);margin-top:4px}
    .submit-btn{width:100%;padding:14px;border:0;border-radius:999px;background:var(--blue);color:#fff;font-weight:800;font-size:15px;cursor:pointer;box-shadow:0 10px 24px rgba(7,85,184,.25)}
    .submit-btn:hover{background:#064da9}
    .error-list{background:#fff1f2;border:1px solid #fecdd3;color:#be123c;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:13px}
    .error-list ul{margin:0;padding-left:18px}
  </style>
</head>
<body>
  <div class="promo-wrap">
    <div class="promo-card">
      <div class="promo-head">
        <h1>🎁 Ambil Promo MySIFA</h1>
        <p>Daftar sekarang dan dapatkan akses penuh sistem MySIFA.</p>
      </div>
      <div class="promo-body">

        @if ($errors->any())
          <div class="error-list">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="promo-amount" id="amount-box">
          <div class="label">Total Pembayaran</div>
          <div class="value" id="amount-value">Rp {{ number_format($amount, 0, ',', '.') }}</div>
        </div>

        <div class="bank-box">
          Upload bukti transfer sesuai nominal di atas.<br>
          Transfer ke rekening berikut:<br>
          <b>{{ config('services.promo_bank.name') }} {{ config('services.promo_bank.account') }} a/n {{ config('services.promo_bank.holder') }}</b><br>
          Akun akan diverifikasi oleh admin setelah bukti transfer diterima.
        </div>

        <form method="POST" action="{{ route('promo.store') }}" enctype="multipart/form-data">
          @csrf

          <div class="field">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
          </div>

          <div class="field">
            <label>No. WhatsApp</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
          </div>

          <div class="field">
            <label>Alamat Email (opsional)</label>
            <input type="email" name="email" value="{{ old('email') }}">
          </div>

          <div class="field">
            <label>Kode Voucher (opsional)</label>
            <div class="voucher-row">
              <input type="text" name="voucher_code" id="voucher_code" value="{{ old('voucher_code') }}" placeholder="Masukkan kode voucher">
              <button type="button" id="btn-check-voucher">Cek Voucher</button>
            </div>
            <div id="voucher-msg" class="voucher-msg"></div>
          </div>

          <div class="field">
            <label>Upload Bukti Transfer</label>
            <input type="file" name="proof" accept=".jpg,.jpeg,.png,.webp" required>
            <div class="hint">Format gambar: JPG, JPEG, PNG, WEBP. Maksimal 5MB.</div>
          </div>

          <button type="submit" class="submit-btn">Daftar & Ambil Promo</button>
        </form>

      </div>
    </div>
  </div>

  <script>
    const amountValue = document.getElementById('amount-value');
    const amountBox = document.getElementById('amount-box');
    const voucherInput = document.getElementById('voucher_code');
    const voucherMsg = document.getElementById('voucher-msg');
    const btnCheck = document.getElementById('btn-check-voucher');
    const originalAmount = {{ $amount }};

    function formatRp(n) {
      return 'Rp ' + n.toLocaleString('id-ID');
    }

    btnCheck.addEventListener('click', async () => {
      const code = voucherInput.value.trim();
      voucherMsg.textContent = '';
      voucherMsg.className = 'voucher-msg';

      if (!code) {
        voucherMsg.textContent = 'Masukkan kode voucher terlebih dahulu.';
        voucherMsg.classList.add('err');
        return;
      }

      btnCheck.disabled = true;
      btnCheck.textContent = 'Mengecek...';

      try {
        const res = await fetch('{{ route('promo.check-voucher') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ code }),
        });
        const data = await res.json();

        if (data.valid) {
          voucherMsg.textContent = data.message;
          voucherMsg.classList.add('ok');
          amountBox.innerHTML = `
            <div class="label">Total Pembayaran</div>
            <div class="original">Rp ${originalAmount.toLocaleString('id-ID')}</div>
            <div class="value discounted">${formatRp(data.final_amount)}</div>
          `;
        } else {
          voucherMsg.textContent = data.message;
          voucherMsg.classList.add('err');
          amountBox.innerHTML = `
            <div class="label">Total Pembayaran</div>
            <div class="value">${formatRp(originalAmount)}</div>
          `;
        }
      } catch (e) {
        voucherMsg.textContent = 'Gagal mengecek voucher, coba lagi.';
        voucherMsg.classList.add('err');
      } finally {
        btnCheck.disabled = false;
        btnCheck.textContent = 'Cek Voucher';
      }
    });
  </script>
</body>
</html>
