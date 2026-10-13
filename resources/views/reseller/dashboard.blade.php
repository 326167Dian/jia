<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Dashboard Reseller - MySIFA</title>
  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
  <style>
    body{background:linear-gradient(135deg,#dff3ff,#eafcff 45%,#f7fbff);min-height:100vh}
    .topbar{background:#fff;border-bottom:1px solid #e7eef7;padding:14px 0}
    .topbar .container{display:flex;align-items:center;justify-content:space-between}
    .topbar img{height:40px}
    .topbar form{margin:0}
    .topbar button{background:none;border:1px solid #d8e3f2;border-radius:999px;padding:8px 18px;font-weight:700;cursor:pointer;color:var(--ink)}
    .topbar button:hover{background:#f2f8ff}
    .wrap{max-width:820px;margin:36px auto;padding:0 16px}
    .box{background:#fff;border-radius:22px;box-shadow:var(--shadow);padding:28px;margin-bottom:20px}
    .box h2{font-size:18px;margin:0 0 16px;color:var(--navy)}
    .alert-ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:13.5px;font-weight:700}
    .referral-box{background:#f2f8ff;border:1px dashed #9fc6f2;border-radius:14px;padding:16px;font-size:13.5px;line-height:1.9}
    .referral-box .code{font-weight:900;color:var(--blue);font-size:18px}
    .referral-row{display:flex;gap:8px;align-items:center;margin-top:6px;flex-wrap:wrap}
    .referral-row input{flex:1;min-width:200px;padding:10px 12px;border:1px solid #d8e3f2;border-radius:10px;font-size:13px;background:#fff;color:var(--ink)}
    .referral-row button{padding:10px 16px;border-radius:10px;border:1px solid var(--blue);background:#fff;color:var(--blue);font-weight:800;cursor:pointer;white-space:nowrap}
    .referral-row button:hover{background:#eef6ff}
    .stat-row{display:flex;gap:14px;margin-bottom:6px;flex-wrap:wrap}
    .stat{flex:1;min-width:140px;background:#f7fbff;border:1px solid #e2eaf3;border-radius:12px;padding:14px;text-align:center}
    .stat b{display:block;font-size:20px;color:var(--blue)}
    .stat span{font-size:12px;color:var(--muted)}
    table{width:100%;border-collapse:collapse;font-size:13.5px}
    table th{text-align:left;color:var(--muted);font-weight:700;padding:8px 6px;border-bottom:2px solid #eaf2fb;white-space:nowrap}
    table td{padding:10px 6px;border-bottom:1px solid #f0f4fa;vertical-align:middle}
    .badge{display:inline-block;padding:3px 12px;border-radius:999px;font-weight:800;font-size:11.5px}
    .badge.pending{background:#fff7e6;color:#aa6b00}
    .badge.verified{background:#ecfdf5;color:#047857}
    .badge.rejected{background:#fff1f2;color:#be123c}
    .proof-thumb{width:40px;height:40px;object-fit:cover;border-radius:8px;border:1px solid #e2eaf3}
    .text-muted{color:var(--muted)}
    .empty-note{color:var(--muted);font-size:13.5px;padding:10px 0}
  </style>
</head>
<body>
  <div class="topbar">
    <div class="container">
      <img src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}" alt="MySIFA">
      <form method="POST" action="{{ route('reseller.logout') }}">
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
      <h2>Halo, {{ $reseller->name }} 👋</h2>

      <div class="referral-box">
        Kode Referral Anda: <span class="code">{{ $reseller->id }}</span><br>
        Bagikan link ini ke calon pelanggan — kode referral otomatis terisi saat mereka daftar:
        <div class="referral-row">
          <input type="text" id="referral-link" value="{{ $reseller->referralUrl() }}" readonly>
          <button type="button" onclick="copyReferral()">Salin Link</button>
        </div>
      </div>
    </div>

    <div class="box">
      <div class="stat-row">
        <div class="stat"><b>{{ $orders->count() }}</b><span>Total Pendaftar</span></div>
        <div class="stat"><b>{{ $orders->where('status', 'verified')->count() }}</b><span>Terverifikasi</span></div>
        <div class="stat"><b>Rp {{ number_format($orders->sum('commission_amount'), 0, ',', '.') }}</b><span>Total Komisi</span></div>
      </div>
    </div>

    <div class="box">
      <h2>Pendaftar Lewat Referral Anda</h2>

      @if ($orders->isEmpty())
        <p class="empty-note">Belum ada pendaftar lewat link referral Anda. Bagikan link di atas untuk mulai mengajak.</p>
      @else
        <div style="overflow-x:auto">
          <table>
            <thead>
              <tr>
                <th>Nama</th>
                <th>Paket</th>
                <th>Status</th>
                <th>Komisi</th>
                <th>Bukti Komisi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($orders as $order)
                <tr>
                  <td>{{ $order->name }}</td>
                  <td>{{ $order->billingPeriodLabel() }}</td>
                  <td><span class="badge {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                  <td>
                    @if ($order->commission_amount !== null)
                      Rp {{ number_format($order->commission_amount, 0, ',', '.') }}
                      @if ($order->commission_paid_at)
                        <br><small class="text-muted">{{ $order->commission_paid_at->format('d M Y') }}</small>
                      @endif
                    @else
                      <span class="text-muted">Belum tersedia</span>
                    @endif
                  </td>
                  <td>
                    @if ($order->commission_proof_path)
                      <a href="{{ route('media.show', $order->commission_proof_path) }}" target="_blank" rel="noopener">
                        <img src="{{ route('media.show', $order->commission_proof_path) }}" alt="Bukti komisi" class="proof-thumb">
                      </a>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  <script>
    function copyReferral() {
      const input = document.getElementById('referral-link');
      input.select();
      input.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(input.value).catch(() => document.execCommand('copy'));
    }
  </script>
</body>
</html>
