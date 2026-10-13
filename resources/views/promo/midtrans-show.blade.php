<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Bayar QRIS (Midtrans) - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .wrap{max-width:420px;margin:40px auto;padding:0 16px}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);overflow:hidden}
    .head{background:linear-gradient(120deg,#064da9,#05a99b);color:#fff;padding:22px 24px;text-align:center}
    .head h1{font-size:18px;margin:0;font-weight:900}
    .body{padding:24px;text-align:center}
    .qr-img{width:240px;height:240px;margin:0 auto 16px;background:#fff;border:1px solid #e2eaf3;border-radius:14px;overflow:hidden;display:flex;align-items:center;justify-content:center}
    .qr-img img{max-width:100%;max-height:100%}
    .amount{font-size:26px;font-weight:950;color:var(--blue);margin-bottom:6px}
    .countdown{font-size:13px;color:var(--muted);margin-bottom:18px}
    .status-badge{display:inline-block;padding:8px 18px;border-radius:999px;font-weight:800;font-size:13px}
    .status-badge.pending{background:#fff7e6;color:#aa6b00}
    .status-badge.paid{background:#ecfdf5;color:#047857}
    .status-badge.failed,.status-badge.expired{background:#fff1f2;color:#be123c}
    .hint{font-size:12.5px;color:var(--muted);margin-top:16px;line-height:1.7}
    .sandbox-note{margin-top:16px;background:#f2f8ff;border:1px dashed #9fc6f2;border-radius:10px;padding:10px 12px;font-size:12px;text-align:left}
    .sandbox-note a{color:var(--blue);font-weight:700}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="box">
      <div class="head"><h1>🎁 Bayar QRIS MySIFA</h1></div>
      <div class="body">
        <div class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>

        <div class="qr-img">
          <img src="{{ $payment->qr_image_url }}" alt="QRIS">
        </div>

        <div id="status-area">
          <span class="status-badge pending" id="status-badge">Menunggu Pembayaran</span>
        </div>
        <div class="countdown" id="countdown"></div>

        <p class="hint">
          Scan QR di atas pakai aplikasi e-wallet / m-banking apa pun yang mendukung QRIS.<br>
          Halaman ini akan otomatis beralih begitu pembayaran terkonfirmasi.
        </p>

        @unless (config('midtrans.is_production'))
          <div class="sandbox-note">
            Mode sandbox: simulasikan pembayaran lewat
            <a href="https://simulator.sandbox.midtrans.com/v2/qris/index" target="_blank" rel="noopener">Midtrans QRIS Simulator</a>.
          </div>
        @endunless
      </div>
    </div>
  </div>

  <script>
    const expiresAt = new Date(@json($payment->expires_at->toIso8601String()));
    const statusBadge = document.getElementById('status-badge');
    const countdownEl = document.getElementById('countdown');
    const statusUrl = @json(route('promo.midtrans.status', $payment->order_id));
    const successUrl = @json(route('promo.success', '__CODE__'));

    function tickCountdown() {
      const diff = Math.max(0, Math.floor((expiresAt - new Date()) / 1000));
      const m = String(Math.floor(diff / 60)).padStart(2, '0');
      const s = String(diff % 60).padStart(2, '0');
      countdownEl.textContent = diff > 0 ? `Berlaku ${m}:${s} lagi` : 'QR kedaluwarsa';
    }
    tickCountdown();
    setInterval(tickCountdown, 1000);

    async function poll() {
      try {
        const res = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        if (data.status === 'paid') {
          statusBadge.textContent = 'Pembayaran Berhasil!';
          statusBadge.className = 'status-badge paid';
          window.location.href = successUrl.replace('__CODE__', data.order_code);
          return;
        }

        if (data.status === 'expired') {
          statusBadge.textContent = 'QR Kedaluwarsa';
          statusBadge.className = 'status-badge expired';
          return;
        }

        if (data.status === 'failed') {
          statusBadge.textContent = 'Pembayaran Gagal';
          statusBadge.className = 'status-badge failed';
          return;
        }

        setTimeout(poll, 4000);
      } catch (e) {
        setTimeout(poll, 5000);
      }
    }

    poll();
  </script>
</body>
</html>
