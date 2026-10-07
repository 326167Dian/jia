<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="MySIFA E-Commerce dan 51 fitur aplikasi inventory apotek dalam satu sistem.">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">
  <title>MySIFA E-Commerce — Menjangkau Lebih Banyak Pelanggan</title>

  <meta property="og:type" content="website">
  <meta property="og:locale" content="id_ID">
  <meta property="og:title" content="MySIFA E-Commerce — Menjangkau Lebih Banyak Pelanggan">
  <meta property="og:description" content="Kelola apotek Anda lebih mudah dengan sistem inventory modern dan toko online yang terintegrasi dalam satu aplikasi.">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:site_name" content="MySIFA Official">

  <link rel="icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
  <link rel="stylesheet" href="{{ asset('mysifa-ecommerce-site/css/style.css') }}">
</head>
<body>
<header class="site-header">
  <div class="container nav">
    <a class="brand" href="#beranda"><img src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}" alt="MySIFA"></a>
    <button class="menu-toggle" aria-label="Buka menu">☰</button>
    <nav id="mainNav">
      <a href="#beranda">Beranda</a>
      <a href="#keunggulan">Keunggulan</a>
      <a href="#fitur">51 Fitur</a>
      <a href="#cta">Daftar</a>
    </nav>
    <a class="btn btn-outline" href="{{ route('member.login') }}">Login Member</a>
  </div>
</header>

<main>
<section id="beranda" class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <span class="eyebrow">APOTEK MAJU BERSAMA TEKNOLOGI</span>
      <h1>MySIFA <span>E-Commerce</span></h1>
      <h2>Menjangkau Lebih Banyak Pelanggan</h2>
      <p class="lead">Kelola apotek Anda lebih mudah dengan sistem inventory modern dan toko online yang terintegrasi dalam satu aplikasi.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="#cta">Daftar Sekarang</a>
        <a class="btn btn-outline" href="#fitur">Lihat 51 Fitur</a>
      </div>
      <div class="stats">
        <div><strong>51</strong><span>Fitur MySIFA</span></div>
        <div><strong>1</strong><span>Sistem Terintegrasi</span></div>
        <div><strong>24/7</strong><span>Toko Online</span></div>
      </div>
    </div>

    <div class="device-scene" aria-label="Ilustrasi dashboard MySIFA">
      <div class="dashboard-card">
        <div class="dash-top"><span>MySIFA</span><span>Dashboard</span></div>
        <div class="dash-body">
          <aside>
            <b>☰</b><span>Dashboard</span><span>Data Obat</span><span>Transaksi</span><span>Stok & Laporan</span><span>Pengaturan</span>
          </aside>
          <div class="dash-content">
            <h3>Dashboard</h3>
            <div class="metric-row">
              <div><small>Total Penjualan</small><b>Rp 712.841.000</b></div>
              <div><small>Pertumbuhan</small><b class="green">+181%</b></div>
            </div>
            <div class="chart">
              <span style="height:28%"></span><span style="height:42%"></span><span style="height:35%"></span>
              <span style="height:54%"></span><span style="height:63%"></span><span style="height:73%"></span>
              <span style="height:84%"></span><span style="height:95%"></span>
            </div>
            <div class="stock"><b>Stok Obat</b><span>✓ Tersedia 1.248</span><span>⚠ Hampir Habis 37</span><span>✕ Habis 12</span></div>
          </div>
        </div>
      </div>
      <div class="phone">
        <div class="phone-notch"></div>
        <b>MySIFA</b>
        <div class="phone-grid">
          <i>Rx<br><small>Resep</small></i><i>🛒<br><small>Kasir</small></i>
          <i>▣<br><small>Inventory</small></i><i>▤<br><small>Laporan</small></i>
        </div>
        <div class="online">🛍 Pesanan Online</div>
      </div>
      <div class="cart">🛒</div>
    </div>
  </div>
</section>

<section id="keunggulan" class="benefits section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">KEUNGGULAN</span>
      <h2>E-Commerce yang Membantu Apotek Tumbuh</h2>
      <p>Bukan sekadar toko online — pelanggan tetap terhubung dengan apotek Anda.</p>
    </div>
    <div class="benefit-grid">
      <article class="benefit"><div class="icon blue">▣</div><div><h3>Payment Gateway</h3><p>Pembayaran online aman dan mudah.</p></div></article>
      <article class="benefit"><div class="icon green">👥</div><div><h3>Pelanggan Get Pelanggan</h3><p>Tingkatkan loyalitas dan rekomendasi pelanggan.</p></div></article>
      <article class="benefit"><div class="icon orange">🎁</div><div><h3>Program Diskon</h3><p>Berikan promo menarik untuk pelanggan.</p></div></article>
      <article class="benefit"><div class="icon purple">✚</div><div><h3>History Pengobatan</h3><p>Catat riwayat obat dan pengobatan pelanggan.</p></div></article>
    </div>
  </div>
</section>

<section id="fitur" class="features section">
  <div class="container">
    <div class="feature-title">
      <span class="eyebrow">APLIKASI PASTI BIKIN UNTUNG · KAS ANTI BOCOR</span>
      <strong>51 FITUR</strong> <span>MySIFA</span><small>Semua yang Anda butuhkan dalam 1 aplikasi!</small>
    </div>
    <div class="feature-grid">
      <div class="feature-group-title">Fitur Inti</div>
      <article class="feature blue-card"><h3>▣ Transaksi Kasir <b>11</b></h3><ul><li>Kasir OTC & Resep</li><li>Barcode Support</li><li>Pemilihan obat berdasarkan komposisi obat</li><li>Pemilihan obat berdasarkan indikasi obat</li><li>Print struk</li><li>Print kwitansi</li><li>Print Invoice</li><li>Print Etiket</li><li>Manajemen shift</li><li>Undo transaksi terdelete</li><li>History transaksi termodifikasi</li></ul></article>
      <article class="feature green-card"><h3>✚ Rekam Medis <b>8</b></h3><ul><li>Data lengkap pelanggan</li><li>Konseling</li><li>MESO (Monitoring Efek Samping Obat)</li><li>Pelayanan Informasi Obat (PIO)</li><li>Pemantauan Terapi Obat (PTO)</li><li>Catatan Pengobatan Pasien (CPP)</li><li>Home Care</li><li>Swamedikasi</li></ul></article>
      <article class="feature orange-card"><h3>▣ Manajemen Inventory <b>7</b></h3><ul><li>Produk laku</li><li>Slow moving</li><li>Produk macet</li><li>Kartu stok</li><li>Stok opname</li><li>Stok kritis</li><li>Jurnal kas</li></ul></article>
      <article class="feature red-card"><h3>🛒 Pengadaan Obat <b>6</b></h3><ul><li>Surat pesanan Reguler, Prekursor, OOT digital</li><li>Input otomatis obat masuk</li><li>Evaluasi pembelian</li><li>Filter jatuh tempo</li><li>Filter hutang supplier</li><li>Manajemen pelunasan</li></ul></article>
      <article class="feature purple-card"><h3>🎁 Program Promo <b>2</b></h3><ul><li>Sistem bundling</li><li>Sistem poin pelanggan</li></ul></article>
      <article class="feature teal-card"><h3>📊 Laporan dan Administrasi <b>5</b></h3><ul><li>Log aktivitas</li><li>Laporan penjualan</li><li>Laporan laba penjualan</li><li>Neraca laba rugi</li><li>Jurnal kas</li></ul></article>

      <div class="feature-group-title">New Fitur</div>
      <article class="feature cyan-card"><h3>🛍 E-Commerce <b>5</b></h3><ul><li>Pembelian online</li><li>Payment gateway</li><li>Sistem ekspedisi</li><li>Sistem reseller</li><li>GPS tracking</li></ul></article>
      <article class="feature amber-card"><h3>🕒 Sistem Kehadiran <b>5</b></h3><ul><li>Absensi</li><li>Cuti</li><li>Lembur</li><li>Slip gaji</li><li>GPS tracking</li></ul></article>
      <article class="feature rose-card"><h3>📋 SKP Satu Sehat Ranah B <b>2</b></h3><ul><li>Rekap pelayanan administratif keprofesian</li><li>Laporan pelayanan keprofesian</li></ul></article>
    </div>
  </div>
</section>

<section class="growth section">
  <div class="container growth-box">
    <div>
      <span class="eyebrow">BELANJA OBAT LEBIH MUDAH</span>
      <h2>Jangan hanya menunggu pelanggan datang.</h2>
      <p>Hadir di HP pelanggan, permudah pemesanan, bangun loyalitas, dan buat apotek Anda lebih dekat dengan konsumen.</p>
    </div>
    <div class="mini-flow"><span>📱</span><b>Pesan Online</b><span>→</span><span>💳</span><b>Bayar</b><span>→</span><span>🏪</span><b>Apotek</b></div>
  </div>
</section>

<section id="cta" class="cta section">
  <div class="container cta-box">
    <div>
      <span class="eyebrow">MY SIFA — SMART INVENTORY FOR APOTEK</span>
      <h2>Aplikasi Paling Profesional Untuk Apotek</h2>
      <p>Aplikasi Inventory dan E-Commerce untuk Apotek dalam satu aplikasi profesional.</p>
    </div>
    <a class="btn btn-white" href="{{ route('promo.create') }}">🎁 Ambil Promo Sekarang</a>
  </div>
</section>
</main>

<footer>
  <div class="container footer">
    <div><b>MySIFA</b><span>Smart Inventory For Apotek</span></div>
    <span>© <span id="year"></span> MySIFA. Aplikasi profesional untuk apotek.</span>
  </div>
</footer>

<a class="wa-float" href="https://wa.me/6281296298139?text=Halo%20MySIFA,%20saya%20ingin%20konsultasi." target="_blank" rel="noopener" aria-label="Konsultasi via WhatsApp">
  <span class="wa-float-icon">
    <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16.004 3C9.377 3 4 8.373 4 15c0 2.386.698 4.611 1.903 6.48L4 29l7.72-1.86A11.93 11.93 0 0 0 16.004 27C22.63 27 28 21.627 28 15S22.63 3 16.004 3Zm6.98 17.07c-.3.84-1.74 1.62-2.4 1.68-.62.06-1.37.3-4.56-1.02-3.86-1.6-6.33-5.46-6.52-5.72-.19-.26-1.56-2.07-1.56-3.95s.98-2.81 1.33-3.2c.34-.37.75-.47 1-.47.25 0 .5.002.72.012.23.01.54-.088.84.65.3.74 1.02 2.57 1.11 2.76.09.19.15.41.03.67-.12.26-.18.42-.36.65-.18.23-.38.5-.54.68-.18.19-.37.4-.16.78.21.37.93 1.57 2.01 2.55 1.38 1.26 2.55 1.65 2.92 1.84.37.19.59.16.81-.1.22-.26.93-1.1 1.18-1.48.25-.37.5-.31.84-.19.34.12 2.16 1.04 2.53 1.23.37.19.62.28.71.44.09.16.09.93-.21 1.77Z"/></svg>
  </span>
  <span class="wa-float-label">Konsultasi</span>
</a>

<script src="{{ asset('mysifa-ecommerce-site/js/app.js') }}"></script>
</body>
</html>
