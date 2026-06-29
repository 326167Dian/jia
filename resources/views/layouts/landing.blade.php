<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MySIFA Official - Solusi Omzet Apotek')</title>
    <meta name="description" content="@yield('meta_description', 'MySIFA membantu apotek meningkatkan omzet dan profit melalui Smart Kasir, Smart Inventory, Smart Order, dan Smart Report.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="@yield('og_title', 'MySIFA Official - Tingkatkan Omzet dan Profit Apotek')">
    <meta property="og:description" content="@yield('og_description', 'Sistem teruji yang membantu apotek meningkatkan transaksi penjualan, menjaga stok, dan memaksimalkan margin profit.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="MySIFA Official">
    <meta property="og:image" content="https://mysifa.my.id/wp-content/uploads/2024/06/mysifaweb.png">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'MySIFA Official - Tingkatkan Omzet dan Profit Apotek')">
    <meta name="twitter:description" content="@yield('og_description', 'Sistem teruji yang membantu apotek meningkatkan transaksi penjualan, menjaga stok, dan memaksimalkan margin profit.')">
    <meta name="twitter:image" content="https://mysifa.my.id/wp-content/uploads/2024/06/mysifaweb.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&family=Lato:wght@400;700;900&family=Montserrat:wght@500;700;800&family=Nunito:wght@500;700;800&family=Nunito+Sans:wght@600;700;900&family=Raleway:wght@700;800&family=Roboto:wght@500;700&family=Roboto+Condensed:wght@500;600;700&family=Varela+Round&family=Yaldevi:wght@600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-cream: #fff8de;
            --bg-warm: #fff0d8;
            --bg-soft: #fffef9;
            --ink: #101213;
            --orange: #f76922;
            --orange-dark: #d65516;
            --green: #008c0e;
            --green-soft: #0e5f21;
            --blue: #0783c1;
            --card: #ffffff;
            --shadow: 0 12px 28px rgba(19, 13, 5, 0.13);
            --radius: 18px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            color: var(--ink);
            background: linear-gradient(180deg, #ffffff 0%, #fff8de 100%);
            font-family: "Nunito", "Segoe UI", Tahoma, sans-serif;
        }

        img {
            max-width: 100%;
            display: block;
            height: auto;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1140px, 92vw);
            margin-inline: auto;
        }

        .section {
            padding: 56px 0;
        }

        .section-soft {
            background: linear-gradient(180deg, #ffffff 0%, #fff8de 100%);
        }

        .section-cream {
            background: var(--bg-cream);
        }

        .section-white {
            background: #ffffff;
        }

        .title-center {
            text-align: center;
            margin: 0 0 14px;
        }

        .subtitle-center {
            text-align: center;
            max-width: 76ch;
            margin: 0 auto;
            line-height: 1.65;
        }

        .display {
            font-family: "Bubblegum Sans", cursive;
            letter-spacing: 0.5px;
        }

        .caps {
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .card {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid #f4e6cd;
            box-shadow: var(--shadow);
            padding: 20px;
        }

        .card-title {
            margin: 0 0 10px;
            font-family: "Montserrat", sans-serif;
        }

        .grid-2,
        .grid-3 {
            display: grid;
            gap: 18px;
        }

        .grid-2 {
            grid-template-columns: 1fr 1fr;
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .list {
            margin: 0;
            padding-left: 1.1rem;
            line-height: 1.75;
        }

        .check-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .check-list li {
            margin-bottom: 10px;
            padding-left: 30px;
            position: relative;
            line-height: 1.65;
        }

        .check-list li::before {
            content: "\2714";
            position: absolute;
            left: 0;
            top: 0;
            color: var(--orange);
            font-weight: 800;
        }

        .btn-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 18px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 30px;
            border-radius: 999px;
            font-weight: 800;
            border: 1px solid transparent;
            font-family: "Varela Round", "Nunito", sans-serif;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.16);
        }

        .btn-orange {
            background: var(--orange);
            color: #fff;
        }

        .btn-orange:hover {
            background: var(--orange-dark);
        }

        .btn-green {
            background: var(--green);
            color: #fff;
        }

        .btn-green:hover {
            background: #00a515;
        }

        .hero {
            background:
                radial-gradient(circle at 6% 10%, rgba(255, 149, 0, 0.24) 0, transparent 36%),
                radial-gradient(circle at 94% 18%, rgba(7, 131, 193, 0.2) 0, transparent 34%),
                linear-gradient(180deg, #fff9ea 0%, #fff1cf 100%);
            padding: 68px 0 42px;
            border-bottom: 2px solid #ffe6b3;
        }

        .hero h1 {
            margin: 10px 0 14px;
            font-family: "Roboto Condensed", sans-serif;
            text-align: center;
            font-size: clamp(1.9rem, 4.8vw, 3.9rem);
            line-height: 1.08;
        }

        .hero-kicker {
            text-align: center;
            font-family: "Lato", sans-serif;
            font-weight: 900;
            font-size: clamp(1.3rem, 3.3vw, 2.25rem);
            margin: 0;
            color: #aa250d;
        }

        .hero-number {
            text-align: center;
            font-family: "Raleway", sans-serif;
            font-size: clamp(1.3rem, 3vw, 2.25rem);
            margin: 10px 0 8px;
        }

        .hero-number + .hero-number {
            margin-top: 16px;
        }

        .hero-image {
            margin: 12px auto;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 2px solid #ffffff;
            overflow: hidden;
            max-width: 760px;
        }

        .anchor-offset {
            scroll-margin-top: 78px;
        }

        .band {
            background: linear-gradient(150deg, #fff8de 0%, #ffffff 100%);
            border-top: 2px solid #f0dfb4;
            border-bottom: 2px solid #f0dfb4;
        }

        .hl-red {
            color: #b71c1c;
            font-weight: 800;
        }

        .hl-orange {
            color: #f96300;
            font-weight: 800;
        }

        .hl-green {
            color: #0e5f21;
            font-weight: 800;
        }

        .stat-box {
            border: 2px dashed #f1bf8b;
            background: #fff;
            border-radius: 14px;
            padding: 12px;
            margin-top: 12px;
            text-align: center;
            font-weight: 700;
        }

        .feature-card {
            border: 2px solid #f45809;
            border-radius: 10px;
            background: #fffdf8;
            padding: 18px;
        }

        .feature-card h3 {
            color: #562a04;
            margin-top: 0;
            margin-bottom: 8px;
            font-family: "Nunito Sans", sans-serif;
            font-size: 1.25rem;
        }

        .feature-card p {
            margin: 0;
            line-height: 1.6;
        }

        .testimonial {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px;
            border: 1px solid #dcead7;
            box-shadow: var(--shadow);
        }

        .testimonial h3 {
            margin: 0 0 8px;
            color: #0e5f21;
            font-family: "Montserrat", sans-serif;
        }

        .testimonial p {
            margin: 0;
            line-height: 1.62;
        }

        .cta-final {
            background: linear-gradient(180deg, #fff8de 0%, #ffffff 100%);
            border-top: 2px solid #f4dfb0;
            border-bottom: 2px solid #f4dfb0;
        }

        .copyright {
            background: #000;
            color: #d1d1d1;
            text-align: center;
            font-size: 0.95rem;
            padding: 20px 12px;
        }

        .reveal {
            opacity: 0;
            transform: translateY(26px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger > * {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.45s ease, transform 0.45s ease;
        }

        .stagger.show > * {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger.show > *:nth-child(1) { transition-delay: 0.03s; }
        .stagger.show > *:nth-child(2) { transition-delay: 0.09s; }
        .stagger.show > *:nth-child(3) { transition-delay: 0.15s; }
        .stagger.show > *:nth-child(4) { transition-delay: 0.21s; }
        .stagger.show > *:nth-child(5) { transition-delay: 0.27s; }
        .stagger.show > *:nth-child(6) { transition-delay: 0.33s; }

        @media (prefers-reduced-motion: reduce) {
            .reveal,
            .stagger > * {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }

        @media (max-width: 980px) {
            .section {
                padding: 48px 0;
            }

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            .hero {
                padding-top: 44px;
            }

            .btn {
                width: 100%;
            }

            .container {
                width: min(1140px, 94vw);
            }
        }
    </style>

    @yield('head')

    <script type="application/ld+json">
    {
            "@@context": "https://schema.org",
            "@@type": "Organization",
      "name": "MySIFA Official",
      "url": "{{ url('/') }}",
      "logo": "https://mysifa.my.id/wp-content/uploads/2024/06/mysifaweb.png",
      "contactPoint": {
                "@@type": "ContactPoint",
        "telephone": "+62-812-9629-8139",
        "contactType": "sales",
        "areaServed": "ID",
        "availableLanguage": ["Indonesian"]
      }
    }
    </script>
    <script type="application/ld+json">
    {
            "@@context": "https://schema.org",
            "@@type": "WebPage",
      "name": "MySIFA - Landing Page",
      "description": "Sistem MySIFA untuk membantu peningkatan omzet dan profit apotek.",
      "url": "{{ url()->current() }}"
    }
    </script>
</head>

<body>
    @yield('content')

    <script>
        (function () {
            const elements = document.querySelectorAll('.reveal, .stagger');
            if (!('IntersectionObserver' in window)) {
                elements.forEach(el => el.classList.add('show'));
                return;
            }

            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                        io.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -60px 0px'
            });

            elements.forEach(el => io.observe(el));
        })();
    </script>

    @yield('scripts')
</body>

</html>
