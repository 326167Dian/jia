<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#0d3b7d">
    <title>My SIFA - Layanan Pengurusan Apotek</title>
    <meta name="description" content="Pengurusan izin apotek profesional oleh My SIFA" />
    <link rel="icon" type="image/png" href="{{ asset('mobilekit/assets/img/favicon.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('mobilekit/assets/img/icon/192x192.png') }}">
    <link rel="stylesheet" href="{{ asset('mobilekit/assets/css/style.css') }}">
    <style>
        html {
            font-size: 20px;
        }

        body,
        .appCapsule,
        .card-body,
        .listview .item .in,
        .form-title,
        .form-control,
        .btn,
        .badge,
        .text-small,
        .text-muted,
        .section-title {
            font-size: 18px !important;
        }

        .title {
            font-size: 2.4rem !important;
        }

        .subtitle {
            font-size: 1.25rem !important;
        }

        .appHeader .pageTitle img.logo {
            max-height: 46px;
            object-fit: contain;
        }

        .form-control {
            padding: 0.9rem 1rem !important;
        }
    </style>
</head>

<body>
    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader bg-primary scrolled">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="offcanvas" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="{{ asset('images/MySIFA.png') }}" alt="My SIFA" class="logo" />
        </div>
        <div class="right">
            <a href="https://wa.me/6281296298139?text=Halo%20My%20SIFA%2C%20saya%20ingin%20konsultasi%20pengurusan%20apotek" class="headerButton">
                <ion-icon name="call-outline"></ion-icon>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="header-large-title">
                        <h1 class="title">My SIFA</h1>
                        <h4 class="subtitle">Digital Network & Legalitas Apotek Modern</h4>
                    </div>
                    <p class="text-muted mt-2">Pengurusan perizinan apotek lengkap dan cepat untuk pemilik apotek, mulai dari PT perorangan hingga pendampingan visitas Dinkes.</p>
                    <div class="mt-3 text-center">
                        <a href="https://wa.me/6281296298139?text=Halo%20My%20SIFA%2C%20saya%20ingin%20konsultasi%20pengurusan%20apotek" class="btn btn-primary btn-block">Konsultasi WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="section mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="text-small text-muted">Biaya pengurusan apotek mulai</div>
                            <div class="title">Rp 12.000.000</div>
                        </div>
                        <div class="col-4 text-end align-self-center">
                            <span class="badge badge-success">Terjangkau</span>
                        </div>
                    </div>
                    <p class="text-small text-muted mt-2">Harga belum termasuk pembuatan akta kerjasama antara pemilik sarana dan apoteker penanggung jawab.</p>
                </div>
            </div>
        </div>

        <div class="section mt-3">
            <div class="section-title">Ringkasan Layanan</div>
            <div class="listview">
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="business-outline"></ion-icon>
                    </div>
                    <div class="in">Pembuatan PT perorangan</div>
                </a>
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="document-text-outline"></ion-icon>
                    </div>
                    <div class="in">Izin operasional</div>
                </a>
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                    <div class="in">SIP Apoteker</div>
                </a>
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                    </div>
                    <div class="in">SIP TTK</div>
                </a>
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="clipboard-outline"></ion-icon>
                    </div>
                    <div class="in">Pembuatan proposal Apotek</div>
                </a>
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="reader-outline"></ion-icon>
                    </div>
                    <div class="in">Pembuatan SOP</div>
                </a>
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="color-palette-outline"></ion-icon>
                    </div>
                    <div class="in">Desain ruangan sesuai ketentuan</div>
                </a>
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="people-circle-outline"></ion-icon>
                    </div>
                    <div class="in">Pencarian SDM wajib jika belum ada</div>
                </a>
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="location-outline"></ion-icon>
                    </div>
                    <div class="in">Pendampingan visitas Dinkes (Jabodetabek)</div>
                </a>
            </div>
        </div>

        <div class="section mt-3">
            <div class="section-title">Mengapa Pilih My SIFA?</div>
            <div class="card">
                <div class="card-body">
                    <div class="listview flush transparent">
                        <div class="item">
                            <div class="icon-box bg-secondary">
                                <ion-icon name="star-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>Pengalaman kefarmasian dan perizinan</div>
                                <span class="text-small text-muted">Tim ahli siap mendampingi sampai selesai.</span>
                            </div>
                        </div>
                        <div class="item">
                            <div class="icon-box bg-secondary">
                                <ion-icon name="timer-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>Proses cepat dan terstruktur</div>
                                <span class="text-small text-muted">Semua dokumen ditangani profesional.</span>
                            </div>
                        </div>
                        <div class="item">
                            <div class="icon-box bg-secondary">
                                <ion-icon name="shield-half-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>Legalitas lengkap</div>
                                <span class="text-small text-muted">SIP, SOP, proposal, dan pendampingan visitas.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section mt-3" id="contact">
            <div class="section-title">Kontak & Formulir</div>
            <div class="card">
                <div class="card-body">
                    <div class="item">
                        <div class="icon-box bg-success">
                            <ion-icon name="call-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>081296298139</div>
                            <span class="text-small text-muted">WhatsApp cepat</span>
                        </div>
                    </div>
                    <div class="item mt-3">
                        <div class="icon-box bg-info">
                            <ion-icon name="location-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Jl. Pangrango III No. 6 Blok 6, Kayuringin Jaya, Bekasi Selatan</div>
                            <span class="text-small text-muted">Area layanan Bekasi & Jabodetabek</span>
                        </div>
                    </div>
                    <a href="https://wa.me/6281296298139?text=Halo%20My%20SIFA%2C%20saya%20ingin%20konsultasi%20pengurusan%20apotek" class="btn btn-success btn-block mt-3">Kirim Pesan WhatsApp</a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="section mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success mb-0">{{ session('success') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="section mt-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('/mobile/contact') }}" method="POST">
                        @csrf
                        <div class="form-group basic">
                            <div class="form-title">Nama</div>
                            <div class="form-content">
                                <input type="text" class="form-control" name="name" placeholder="Nama Anda" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <div class="form-title">Nomor HP</div>
                            <div class="form-content">
                                <input type="text" class="form-control" name="phone" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <div class="form-title">Pesan</div>
                            <div class="form-content">
                                <textarea class="form-control" name="message" rows="4" placeholder="Tulis kebutuhan pengurusan apotek Anda" required>{{ old('message') }}</textarea>
                            </div>
                        </div>
                        @error('name')
                            <div class="text-danger text-small mb-2">{{ $message }}</div>
                        @enderror
                        @error('phone')
                            <div class="text-danger text-small mb-2">{{ $message }}</div>
                        @enderror
                        @error('message')
                            <div class="text-danger text-small mb-2">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary btn-block mt-3">Kirim Permintaan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="section mt-3 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="listview flush transparent no-hairlines">
                        <div class="item">
                            <div class="icon-box bg-warning">
                                <ion-icon name="shield-checkmark-outline"></ion-icon>
                            </div>
                            <div class="in">Harga tidak termasuk pembuatan akta kerjasama antara pemilik sarana dan apoteker penanggung jawab.</div>
                        </div>
                        <div class="item">
                            <div class="icon-box bg-warning">
                                <ion-icon name="people-outline"></ion-icon>
                            </div>
                            <div class="in">Pelayanan konsultasi apotek profesional dan pendampingan legal.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

    <div class="appBottomMenu">
        <a href="/mobile" class="item active">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
            </div>
        </a>
        <a href="#contact" class="item">
            <div class="col">
                <ion-icon name="call-outline"></ion-icon>
            </div>
        </a>
        <a href="https://wa.me/6281296298139?text=Halo%20My%20SIFA%2C%20saya%20ingin%20konsultasi%20pengurusan%20apotek" class="item">
            <div class="col">
                <ion-icon name="logo-whatsapp"></ion-icon>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <ion-icon name="information-circle-outline"></ion-icon>
            </div>
        </a>
        <a href="#sidebarPanel" class="item" data-bs-toggle="offcanvas">
            <div class="col">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
        </a>
    </div>

    <!-- App Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarPanel">
        <div class="offcanvas-body">
            <div class="profileBox">
                <div class="image-wrapper">
                    <img src="{{ asset('mobilekit/assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="imaged rounded">
                </div>
                <div class="in">
                    <strong>apt. Ahmad Nurdiansyah</strong>
                    <div class="text-muted">
                        <ion-icon name="location"></ion-icon>
                        Bekasi, Jabodetabek
                    </div>
                </div>
                <a href="#" class="close-sidebar-button" data-bs-dismiss="offcanvas">
                    <ion-icon name="close"></ion-icon>
                </a>
            </div>
            <ul class="listview flush transparent no-line image-listview mt-2">
                <li>
                    <a href="/mobile" class="item active">
                        <div class="icon-box bg-primary">
                            <ion-icon name="home-outline"></ion-icon>
                        </div>
                        <div class="in">Beranda</div>
                    </a>
                </li>
                <li>
                    <a href="#" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </div>
                        <div class="in">Layanan</div>
                    </a>
                </li>
                <li>
                    <a href="#contact" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="call-outline"></ion-icon>
                        </div>
                        <div class="in">Kontak</div>
                    </a>
                </li>
                <li>
                    <a href="https://wa.me/6281296298139?text=Halo%20My%20SIFA%2C%20saya%20ingin%20konsultasi%20pengurusan%20apotek" class="item">
                        <div class="icon-box bg-success">
                            <ion-icon name="logo-whatsapp"></ion-icon>
                        </div>
                        <div class="in">WhatsApp</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- * App Sidebar -->

    <!-- ============== Js Files ============== -->
    <script src="{{ asset('mobilekit/assets/js/lib/bootstrap.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('mobilekit/assets/js/plugins/splide/splide.min.js') }}"></script>
    <script src="{{ asset('mobilekit/assets/js/plugins/progressbar-js/progressbar.min.js') }}"></script>
    <script src="{{ asset('mobilekit/assets/js/base.js') }}"></script>
</body>

</html>
