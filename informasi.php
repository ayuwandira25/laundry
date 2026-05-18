<?php
session_start();

// Jika user sudah login
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        // Redirect admin ke dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // Redirect user ke halaman user
        header('Location: user_dashboard.php');
        exit;
    }
}

// Jika user belum login, tampilkan halaman informasi/landing page
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KykaLaundry – Bersih, Harum, Tepat Waktu</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sky: #0ea5e9;
            --sky-dark: #0369a1;
            --sky-light: #e0f2fe;
            --foam: #f0f9ff;
            --accent: #06b6d4;
            --fresh: #10b981;
            --text: #0f172a;
            --muted: #64748b;
            --white: #ffffff;
            --bubble: rgba(255, 255, 255, 0.15);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--white);
            overflow-x: hidden;
        }

        /* NAV */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(14, 165, 233, 0.1);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--sky-dark);
            letter-spacing: -0.5px;
        }

        .logo span {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--muted);
            font-size: 0.95rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--sky);
        }

        .nav-cta {
            background: var(--sky);
            color: white;
            border: none;
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            text-decoration: none;
        }

        .nav-cta:hover {
            background: var(--sky-dark);
            transform: translateY(-1px);
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 50%, #38bdf8 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 6rem 2rem 4rem;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='30' cy='30' r='20' fill='none' stroke='rgba(255,255,255,0.06)' stroke-width='1'/%3E%3C/svg%3E") repeat;
            pointer-events: none;
        }

        /* floating bubbles */
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            animation: float 8s ease-in-out infinite;
        }

        .bubble:nth-child(1) {
            width: 200px;
            height: 200px;
            top: 10%;
            right: 5%;
            animation-delay: 0s;
        }

        .bubble:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 50%;
            right: 20%;
            animation-delay: 2s;
        }

        .bubble:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            right: 10%;
            animation-delay: 4s;
        }

        .bubble:nth-child(4) {
            width: 150px;
            height: 150px;
            top: 20%;
            left: 5%;
            animation-delay: 1s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-badge::before {
            content: '✦';
            font-size: 0.7rem;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 5vw, 3.8rem);
            font-weight: 800;
            color: white;
            line-height: 1.15;
            margin-bottom: 1.5rem;
        }

        .hero h1 em {
            font-style: normal;
            color: #bae6fd;
        }

        .hero p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
            font-weight: 300;
        }

        .hero-btns {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: white;
            color: var(--sky-dark);
            padding: 0.9rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.6);
            padding: 0.9rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: white;
        }

        .hero-image {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .hero-image img {
            width: 100%;
            height: 420px;
            object-fit: cover;
            display: block;
        }

        .hero-stats {
            position: absolute;
            bottom: 1.5rem;
            left: 1.5rem;
            right: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-around;
        }

        .stat {
            text-align: center;
        }

        .stat-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--sky-dark);
            display: block;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--muted);
            font-weight: 500;
        }

        /* SERVICES */
        .services {
            padding: 6rem 2rem;
            background: var(--foam);
        }

        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .section-tag {
            display: inline-block;
            background: var(--sky-light);
            color: var(--sky);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            margin-bottom: 1rem;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }

        .section-header p {
            color: var(--muted);
            font-size: 1.05rem;
            margin-top: 0.75rem;
        }

        .services-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid rgba(14, 165, 233, 0.08);
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(14, 165, 233, 0.15);
        }

        .service-icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
        }

        .service-card:nth-child(1) .service-icon {
            background: #e0f2fe;
        }

        .service-card:nth-child(2) .service-icon {
            background: #d1fae5;
        }

        .service-card:nth-child(3) .service-icon {
            background: #fce7f3;
        }

        .service-card:nth-child(4) .service-icon {
            background: #fef3c7;
        }

        .service-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .service-card p {
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .service-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--sky);
        }

        /* HOW IT WORKS */
        .how {
            padding: 6rem 2rem;
            background: white;
        }

        .steps {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            position: relative;
        }

        .step {
            text-align: center;
            padding: 1.5rem;
        }

        .step-num {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--sky), var(--accent));
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.35);
        }

        .step h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .step p {
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.6;
        }

        /* GALLERY */
        .gallery {
            padding: 6rem 2rem;
            background: var(--foam);
        }

        .gallery-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: 240px 240px;
            gap: 1rem;
        }

        .gallery-item {
            border-radius: 16px;
            overflow: hidden;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
            display: block;
        }

        .gallery-item:hover img {
            transform: scale(1.04);
        }

        .gallery-item:first-child {
            grid-row: span 2;
        }

        /* TESTIMONIAL */
        .testimonials {
            padding: 6rem 2rem;
            background: linear-gradient(135deg, #0369a1, #0ea5e9);
            position: relative;
            overflow: hidden;
        }

        .testimonials::before {
            content: '"';
            font-family: 'Playfair Display', serif;
            font-size: 30rem;
            color: rgba(255, 255, 255, 0.04);
            position: absolute;
            top: -6rem;
            left: -2rem;
            line-height: 1;
            pointer-events: none;
        }

        .testimonials .section-tag {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .testimonials .section-header h2 {
            color: white;
        }

        .testimonials .section-header p {
            color: rgba(255, 255, 255, 0.7);
        }

        .testi-grid {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .testi-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 1.75rem;
            transition: background 0.3s;
        }

        .testi-card:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        .stars {
            color: #fbbf24;
            font-size: 1rem;
            margin-bottom: 0.75rem;
            letter-spacing: 2px;
        }

        .testi-card p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 1.25rem;
            font-style: italic;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 0.9rem;
        }

        .testi-name {
            font-weight: 600;
            color: white;
            font-size: 0.9rem;
        }

        .testi-loc {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        /* PROMO BANNER */
        .promo {
            background: var(--text);
            padding: 5rem 2rem;
            text-align: center;
        }

        .promo h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 3rem);
            color: white;
            margin-bottom: 0.75rem;
        }

        .promo h2 span {
            color: #7dd3fc;
        }

        .promo p {
            color: rgba(255, 255, 255, 0.65);
            font-size: 1.05rem;
            margin-bottom: 2rem;
        }

        .promo-btn {
            background: var(--sky);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.05rem;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 8px 30px rgba(14, 165, 233, 0.4);
        }

        .promo-btn:hover {
            background: var(--sky-dark);
            transform: translateY(-2px);
        }

        /* CONTACT */
        .contact {
            padding: 6rem 2rem;
            background: white;
        }

        .contact-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        .contact-info h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .contact-info p {
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .contact-item {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            margin-bottom: 1.25rem;
        }

        .contact-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--sky-light);
            color: var(--sky);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .contact-item strong {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .contact-item span {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-form input,
        .contact-form textarea,
        .contact-form select {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            color: var(--text);
            background: white;
            transition: border-color 0.2s;
            outline: none;
        }

        .contact-form input:focus,
        .contact-form textarea:focus,
        .contact-form select:focus {
            border-color: var(--sky);
        }

        .contact-form textarea {
            height: 120px;
            resize: vertical;
        }

        .submit-btn {
            background: var(--sky);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .submit-btn:hover {
            background: var(--sky-dark);
        }

        /* FOOTER */
        footer {
            background: #0f172a;
            padding: 3rem 2rem 1.5rem;
        }

        .footer-top {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-brand .logo {
            color: white;
            margin-bottom: 1rem;
            display: block;
        }

        .footer-brand .logo span {
            color: #38bdf8;
        }

        .footer-brand p {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.88rem;
            line-height: 1.7;
        }

        .footer-col h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 0.6rem;
        }

        .footer-col ul li a {
            color: rgba(255, 255, 255, 0.45);
            text-decoration: none;
            font-size: 0.87rem;
            transition: color 0.2s;
        }

        .footer-col ul li a:hover {
            color: #38bdf8;
        }

        .footer-bottom {
            max-width: 1100px;
            margin: 0 auto;
            padding-top: 1.5rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.82rem;
        }

        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .hero-image {
                display: none;
            }

            .contact-wrapper {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .gallery-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: auto;
            }

            .gallery-item:first-child {
                grid-row: span 1;
                grid-column: span 2;
            }

            .footer-top {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- NAV -->
    <nav>
        <div class="logo">Kyka<span>Laundry</span></div>
        <ul class="nav-links">
            <li><a href="#layanan">Layanan</a></li>
            <li><a href="#cara-kerja">Cara Kerja</a></li>
            <li><a href="#galeri">Galeri</a></li>
            <li><a href="#ulasan">Ulasan</a></li>
            <li><a href="#kontak">Kontak</a></li>
        </ul>
        <a href="form_chek_pesanan.php" class="nav-cta">Chek Pesanan Anda</a>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="hero-content">
            <div>
                <div class="hero-badge">Laundry Terpercaya #1 di Kota Anda</div>
                <h1>Pakaian Bersih &<br><em>Harum Segar</em><br>Tepat Waktu</h1>
                <p>KykaLaundry hadir untuk memudahkan hari-harimu. Antar–jemput, proses cepat, hasil maksimal — pakaianmu akan kembali seperti baru!</p>
                <div class="hero-btns">
                    <a href="#kontak" class="btn-primary">🧺 Pesan Sekarang</a>
                    <a href="#layanan" class="btn-outline">Lihat Layanan</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=700&q=80" alt="Laundry bersih" loading="lazy">
                <div class="hero-stats">
                    <div class="stat"><span class="stat-num">2000+</span><span class="stat-label">Pelanggan Puas</span></div>
                    <div class="stat"><span class="stat-num">5 Thn</span><span class="stat-label">Pengalaman</span></div>
                    <div class="stat"><span class="stat-num">⭐ 4.9</span><span class="stat-label">Rating</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- LAYANAN -->
    <section class="services" id="layanan">
        <div class="section-header">
            <span class="section-tag">Layanan Kami</span>
            <h2>Semua Kebutuhan Laundry<br>Ada di KykaLaundry</h2>
            <p>Pilih paket yang sesuai kebutuhanmu, harga terjangkau kualitas premium.</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">👕</div>
                <h3>Cuci + Setrika</h3>
                <p>Pakaian dicuci bersih dengan deterjen premium, disetrika rapi siap pakai.</p>
                <div class="service-price">Rp 7.000/kg</div>
            </div>
            <div class="service-card">
                <div class="service-icon">⚡</div>
                <h3>Express (6 Jam)</h3>
                <p>Butuh cepat? Layanan kilat 6 jam kami siap membantu di saat mendesak.</p>
                <div class="service-price">Rp 12.000/kg</div>
            </div>
            <div class="service-card">
                <div class="service-icon">🛏️</div>
                <h3>Cuci Bed Cover</h3>
                <p>Sprei, bed cover, selimut tebal — kami cuci bersih dengan mesin kapasitas besar.</p>
                <div class="service-price">Mulai Rp 25.000</div>
            </div>
            <div class="service-card">
                <div class="service-icon">🚗</div>
                <h3>Antar Jemput</h3>
                <p>Kami jemput pakaian kotor dan antar kembali ke rumahmu. Tanpa keluar rumah!</p>
                <div class="service-price">Gratis radius 2km</div>
            </div>
        </div>
    </section>

    <!-- CARA KERJA -->
    <section class="how" id="cara-kerja">
        <div class="section-header">
            <span class="section-tag">Cara Kerja</span>
            <h2>Mudah Hanya 4 Langkah</h2>
            <p>Proses pesan laundry semudah chat WhatsApp!</p>
        </div>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h3>Pesan via WhatsApp</h3>
                <p>Chat kami dan tentukan layanan yang kamu butuhkan beserta estimasi berat.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h3>Penjemputan</h3>
                <p>Kami datang menjemput pakaianmu di waktu yang sudah disepakati.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h3>Proses Pencucian</h3>
                <p>Pakaianmu dicuci dengan mesin modern dan deterjen premium pilihan.</p>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <h3>Antar ke Rumah</h3>
                <p>Pakaian bersih, harum, dan rapi diantar langsung ke depan pintu rumahmu.</p>
            </div>
        </div>
    </section>

    <!-- GALERI -->
    <section class="gallery" id="galeri">
        <div class="section-header">
            <span class="section-tag">Galeri</span>
            <h2>Hasil Kerja Kami</h2>
            <p>Kepuasan kamu adalah prioritas kami.</p>
        </div>
        <div class="gallery-grid" style="max-width:1100px;margin:0 auto;">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1610557892470-55d9e80c0bce?w=600&q=80" alt="Laundry proses">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=400&q=80" alt="Pakaian bersih">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?w=400&q=80" alt="Setrika rapi">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1604335398980-ededb9b4b9e3?w=400&q=80" alt="Mesin cuci">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1521656693074-0ef32e80a5d5?w=400&q=80" alt="Hasil laundry">
            </div>
        </div>
    </section>

    <!-- TESTIMONIAL -->
    <section class="testimonials" id="ulasan">
        <div class="section-header">
            <span class="section-tag">Ulasan Pelanggan</span>
            <h2>Kata Mereka tentang KykaLaundry</h2>
            <p>Ribuan pelanggan telah mempercayakan pakaian mereka kepada kami.</p>
        </div>
        <div class="testi-grid">
            <div class="testi-card">
                <div class="stars">★★★★★</div>
                <p>"Sudah 2 tahun langganan di sini. Hasilnya selalu bersih dan wangi banget! Antar jemputnya juga on time, gak pernah kecewa."</p>
                <div class="testi-author">
                    <div class="avatar">AS</div>
                    <div>
                        <div class="testi-name">Ayu Safitri</div>
                        <div class="testi-loc">Pelanggan Setia</div>
                    </div>
                </div>
            </div>
            <div class="testi-card">
                <div class="stars">★★★★★</div>
                <p>"Paling suka layanan express-nya! Butuh baju buat acara mendadak, 6 jam langsung beres dan hasilnya keren. Highly recommended!"</p>
                <div class="testi-author">
                    <div class="avatar">RD</div>
                    <div>
                        <div class="testi-name">Rizky Darmawan</div>
                        <div class="testi-loc">Pelanggan Express</div>
                    </div>
                </div>
            </div>
            <div class="testi-card">
                <div class="stars">★★★★★</div>
                <p>"Bed cover saya yang tebal banget dicuci bersih di sini. Hasilnya luar biasa! Harga juga sangat terjangkau untuk kualitas sebagus ini."</p>
                <div class="testi-author">
                    <div class="avatar">LN</div>
                    <div>
                        <div class="testi-name">Linda Novita</div>
                        <div class="testi-loc">Pelanggan Baru</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROMO BANNER -->
    <section class="promo">
        <h2>Diskon <span>20%</span> untuk Pelanggan Baru! 🎉</h2>
        <p>Gunakan kode promo <strong style="color:#7dd3fc;">KYKANEW</strong> saat pertama kali pesan dan dapatkan diskon spesial.</p>
        <a href="https://wa.me/628xxxxxxxxxx?text=Halo%20KykaLaundry%2C%20saya%20mau%20pesan%20dengan%20kode%20KYKANEW" class="promo-btn">💬 Klaim Diskon via WhatsApp</a>
    </section>

    <!-- KONTAK -->
    <section class="contact" id="kontak">
        <div class="contact-wrapper">
            <div class="contact-info">
                <h2>Hubungi KykaLaundry</h2>
                <p>Punya pertanyaan atau siap memesan? Kami siap melayani kamu setiap hari!</p>
                <div class="contact-item">
                    <div class="contact-icon">📍</div>
                    <div><strong>Alamat</strong><span>Jl. Contoh No. 123, Kota Anda</span></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📱</div>
                    <div><strong>WhatsApp</strong><span>0812-XXXX-XXXX</span></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🕐</div>
                    <div><strong>Jam Operasional</strong><span>Senin–Sabtu: 07.00–20.00<br>Minggu: 08.00–17.00</span></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📸</div>
                    <div><strong>Instagram</strong><span>@kykalaundry</span></div>
                </div>
            </div>
            <form class="contact-form" onsubmit="event.preventDefault(); alert('Terima kasih! Kami akan segera menghubungi Anda.')">
                <input type="text" placeholder="Nama lengkap kamu" required>
                <input type="tel" placeholder="Nomor WhatsApp" required>
                <select>
                    <option value="">Pilih Layanan</option>
                    <option>Cuci + Setrika (Regular)</option>
                    <option>Express 6 Jam</option>
                    <option>Cuci Bed Cover / Selimut</option>
                    <option>Antar Jemput</option>
                </select>
                <textarea placeholder="Pesan atau pertanyaanmu..."></textarea>
                <button type="submit" class="submit-btn">🧺 Kirim Pesanan</button>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-top">
            <div class="footer-brand">
                <span class="logo">Kyka<span>Laundry</span></span>
                <p>Laundry terpercaya dengan pengalaman 5 tahun. Bersih, harum, dan tepat waktu adalah janji kami untuk kamu.</p>
            </div>
            <div class="footer-col">
                <h4>Layanan</h4>
                <ul>
                    <li><a href="#">Cuci + Setrika</a></li>
                    <li><a href="#">Express 6 Jam</a></li>
                    <li><a href="#">Bed Cover</a></li>
                    <li><a href="#">Antar Jemput</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Perusahaan</h4>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Karir</a></li>
                    <li><a href="#">Promo</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Ikuti Kami</h4>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">TikTok</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">WhatsApp</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 KykaLaundry. Dibuat dengan ❤️ untuk pakaian bersihmu.</p>
        </div>
    </footer>

</body>

</html>