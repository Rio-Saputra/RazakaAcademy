<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAZAKA ACADEMY - Platform Belajar Modern</title>
    <!-- Fonts: Inter & Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #243A5E;
            --primary-light: #2F4F7F;
            --primary-gradient: linear-gradient(135deg, #243A5E 0%, #2F4F7F 100%);
            --accent: #38BDF8;
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --text: #1F2937;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --radius: 20px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            color: var(--text);
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* NAVBAR */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 5%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            z-index: 100;
            transition: var(--transition);
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .brand i {
            color: var(--primary);
            font-size: 1.75rem;
        }

        .brand span {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links a {
            font-weight: 500;
            color: var(--text-muted);
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        /* BUTTONS */
        .btn {
            padding: 0.75rem 1.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white !important;
            box-shadow: 0 4px 15px rgba(36, 58, 94, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(36, 58, 94, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary) !important;
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: rgba(36, 58, 94, 0.05);
        }

        /* HERO SECTION */
        .hero {
            padding: 10rem 5% 6rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            background: var(--bg);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(36, 58, 94, 0.1) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .hero-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
            gap: 4rem;
        }

        .hero-content {
            flex: 1;
            max-width: 600px;
        }

        .badge-pill {
            background: rgba(36, 58, 94, 0.1);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .hero h1 span {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.15rem;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
        }

        .hero-image {
            flex: 1;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .hero-img-box {
            position: relative;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 8px solid white;
            transform: perspective(1000px) rotateY(-5deg);
            transition: var(--transition);
        }

        .hero-img-box:hover {
            transform: perspective(1000px) rotateY(0deg);
        }

        .hero-img-box img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* FLOATING STATS */
        .floating-stat {
            position: absolute;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: float 6s ease-in-out infinite;
        }

        .stat-1 {
            top: -20px;
            right: -30px;
            animation-delay: 0s;
        }

        .stat-2 {
            bottom: 40px;
            left: -40px;
            animation-delay: 2s;
        }

        .floating-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* FEATURES SECTION */
        .features {
            padding: 6rem 5%;
            background: white;
            text-align: center;
        }

        .section-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 4rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--bg);
            padding: 2.5rem;
            border-radius: var(--radius);
            text-align: center;
            transition: var(--transition);
            border: 1px solid var(--border);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            background: white;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
            transform: rotate(-5deg);
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon {
            transform: rotate(0deg) scale(1.1);
        }

        .feature-card h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--text-muted);
        }

        /* CTA SECTION */
        .cta-section {
            padding: 5rem 5%;
            background: var(--bg);
        }

        .cta-container {
            max-width: 1000px;
            margin: 0 auto;
            background: var(--primary-gradient);
            border-radius: 30px;
            padding: 4rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .cta-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }

        .cta-container h2 {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .cta-container p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 1;
        }

        .btn-white {
            background: white;
            color: var(--primary) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        /* FOOTER */
        footer {
            background: white;
            padding: 3rem 5%;
            text-align: center;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
        }

        @media (max-width: 900px) {
            .hero-container {
                flex-direction: column;
                text-align: center;
                gap: 3rem;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero-content {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .hero-buttons {
                justify-content: center;
            }

            .floating-stat {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .nav-links {
                display: none;
            }
            .hero-buttons {
                flex-direction: column;
                width: 100%;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="/" class="brand">
            <i class="fas fa-graduation-cap"></i> <span>RAZAKA</span>
        </a>
        <div class="nav-links">
            <a href="#fitur">Fitur</a>
            <a href="{{ url('auth/login') }}">Masuk</a>
            <a href="{{ url('auth/register') }}" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Daftar Gratis</a>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="badge-pill">
                    <i class="fas fa-star"></i> Platform Tryout No. 1
                </div>
                <h1>Persiapkan Ujian dengan <span>Cerdas & Terukur</span></h1>
                <p>Platform tryout online modern dengan ribuan latihan soal berkualitas, pembahasan lengkap, dan analisis statistik pintar untuk memantau perkembanganmu.</p>
                <div class="hero-buttons">
                    <a href="{{ url('auth/register') }}" class="btn btn-primary">
                        Mulai Belajar Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('auth/login') }}" class="btn btn-outline">
                        Masuk ke Dashboard
                    </a>
                </div>
            </div>
            
            <div class="hero-image">
                <div class="hero-img-box">
                    <!-- Replace with an actual dashboard preview screenshot or illustration -->
                    <img src="https://placehold.co/800x500/F8FAFC/243A5E?text=Dashboard+Preview" alt="Dashboard Razaka">
                </div>
                
                <!-- Floating Stats -->
                <div class="floating-stat stat-1">
                    <div class="floating-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text);">Analisis Detail</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Grafik Performa</div>
                    </div>
                </div>

                <div class="floating-stat stat-2">
                    <div class="floating-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text);">Ribuan Soal</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Update Berkala</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features" id="fitur">
        <h2 class="section-title">Kenapa Memilih Razaka?</h2>
        <p class="section-subtitle">Kami menyediakan semua alat yang kamu butuhkan untuk meraih hasil ujian terbaik dan masuk ke kampus impian.</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3>Sistem Ujian Modern</h3>
                <p>Simulasi tryout dengan antarmuka yang sangat mirip dengan ujian aslinya. Membantu kamu terbiasa dengan suasana ujian.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3>Analisis Statistik Pintar</h3>
                <p>Pantau perkembangan belajarmu dengan grafik yang detail. Ketahui kelemahan dan tingkatkan nilai dengan cepat.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>Pembahasan Lengkap</h3>
                <p>Setiap soal dilengkapi dengan pembahasan yang mudah dipahami, langkah demi langkah, dan trik cepat menjawab soal.</p>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <div class="cta-container">
            <h2>Siap Meraih Mimpimu?</h2>
            <p>Bergabunglah dengan ribuan siswa lainnya yang telah membuktikan kualitas platform RAZAKA ACADEMY. Daftar sekarang, gratis!</p>
            <a href="{{ url('auth/register') }}" class="btn btn-white">
                Buat Akun Gratis <i class="fas fa-rocket"></i>
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 RAZAKA ACADEMY. Semua Hak Cipta Dilindungi.</p>
    </footer>

</body>
</html>
