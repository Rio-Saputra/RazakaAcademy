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
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

        /* ABOUT SECTION */
        .about {
            padding: 8rem 5% 6rem;
            background: var(--bg);
            display: flex;
            align-items: center;
        }

        .about-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            gap: 4rem;
            width: 100%;
        }

        .about-visual {
            flex: 1;
        }

        .about-content {
            flex: 1;
        }

        .about h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }

        .about h2 span {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .about p {
            color: var(--text-muted);
            font-size: 1.05rem;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .about-stat-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            text-align: left;
            transition: var(--transition);
        }

        .about-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .about-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .about-stat-card h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .about-stat-card p {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .about-check-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--primary);
            font-size: 0.95rem;
        }

        .about-check-item i {
            color: #10B981;
            font-size: 1.1rem;
        }

        /* TESTIMONIALS SECTION */
        .testimonials {
            padding: 6rem 5%;
            background: white;
            text-align: center;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .testimonial-card {
            background: var(--bg);
            padding: 2.5rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            text-align: left;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            background: white;
        }

        .stars {
            color: #F59E0B;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
        }

        .comment {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 2rem;
            font-style: italic;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar-initial {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .profile h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }

        .profile span {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* FAQ SECTION */
        .faq-section {
            padding: 6rem 5%;
            background: var(--bg);
            text-align: center;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .faq-item {
            background: white;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 1.25rem 2rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .faq-item:hover {
            box-shadow: var(--shadow-sm);
            border-color: var(--primary-light);
        }

        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }

        .faq-question h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--primary);
        }

        .faq-question i {
            font-size: 1rem;
            color: var(--text-muted);
            transition: transform 0.3s ease;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, margin-top 0.3s ease;
            margin-top: 0;
        }

        .faq-answer p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .faq-item.active .faq-question i {
            transform: rotate(45deg);
            color: var(--primary);
        }

        .faq-item.active .faq-answer {
            margin-top: 1rem;
        }

        /* CONTACT SECTION */
        .contact-section {
            padding: 6rem 5%;
            background: white;
        }

        .contact-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            gap: 4rem;
            width: 100%;
        }

        .contact-info {
            flex: 1;
        }

        .contact-form-container {
            flex: 1;
            background: var(--bg);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 3rem;
            box-shadow: var(--shadow-sm);
        }

        .contact-info h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }

        .contact-info h2 span {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .contact-info p {
            color: var(--text-muted);
            font-size: 1.05rem;
        }

        .contact-detail-card {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .contact-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .contact-detail-card h4 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.15rem;
        }

        .contact-detail-card p {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--primary);
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.85rem 1.25rem;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            background: white;
            color: var(--text);
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(36, 58, 94, 0.08);
        }

        .send-icon {
            transition: transform 0.3s ease;
        }

        .btn-primary:hover .send-icon {
            transform: translateX(4px) translateY(-2px);
        }

        .contact-success-alert {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            color: #065F46;
            animation: fadeIn 0.4s ease forwards;
        }

        .contact-success-alert i {
            font-size: 1.5rem;
            color: #10B981;
            margin-top: 0.15rem;
        }

        .contact-success-alert h4 {
            font-size: 1rem;
            color: #065F46;
            margin-bottom: 0.25rem;
        }

        .contact-success-alert p {
            font-size: 0.875rem;
            color: #047857;
            line-height: 1.5;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
            padding: 5rem 5% 2rem;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            text-align: left;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 4rem;
        }

        .footer-about p {
            font-size: 0.95rem;
            line-height: 1.6;
            margin-top: 1rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.1rem;
            transition: var(--transition);
            border: 1px solid var(--border);
        }

        .social-btn:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
            border-color: transparent;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-links h3, .footer-newsletter h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.25rem;
        }

        .footer-links a {
            font-size: 0.95rem;
            color: var(--text-muted);
            transition: var(--transition);
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--primary);
            transform: translateX(4px);
        }

        .footer-newsletter p {
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 4rem auto 0;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 0.9rem;
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

            .about-container, .contact-container {
                flex-direction: column;
                gap: 3rem;
            }

            .footer-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 3rem;
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

            .about-grid {
                grid-template-columns: 1fr;
            }

            .contact-form-container {
                padding: 1.75rem;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="/" class="brand">
            <img src="{{ asset('images/razakalogo.jpg') }}" alt="Razaka Logo" style="height: 42px; width: 42px; border-radius: 12px; object-fit: cover; box-shadow: var(--shadow-sm); border: 2px solid var(--border);">
            <span>RAZAKA</span>
        </a>
        <div class="nav-links">
            <a href="#fitur">Fitur</a>
            <a href="#tentang">Tentang</a>
            <a href="#testimoni">Testimoni</a>
            <a href="#faq">FAQ</a>
            <a href="#kontak">Kontak</a>
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
                <!-- Modern neon glowing blur circles behind image -->
                <div style="position: absolute; width: 130%; height: 130%; background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(255,255,255,0) 70%); top: -15%; left: -15%; z-index: 0; filter: blur(30px); border-radius: 50%; pointer-events: none;"></div>
                
                <div class="hero-img-box" style="z-index: 2;">
                    <img src="{{ asset('images/students_taking_exam.png') }}" alt="Siswa Mengerjakan Ujian di Razaka Academy">
                </div>
                
                <!-- Floating Stats -->
                <div class="floating-stat stat-1" style="z-index: 3;">
                    <div class="floating-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text);">Analisis Detail</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Grafik Performa</div>
                    </div>
                </div>
 
                <div class="floating-stat stat-2" style="z-index: 3;">
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

    <!-- ABOUT SECTION -->
    <section class="about" id="tentang">
        <div class="about-container">
            <div class="about-visual">
                <div class="about-grid">
                    <div class="about-stat-card">
                        <div class="about-stat-icon" style="background: rgba(56, 189, 248, 0.1); color: #38BDF8;">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>10K+</h3>
                        <p>Siswa Aktif</p>
                    </div>
                    <div class="about-stat-card">
                        <div class="about-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>98%</h3>
                        <p>Tingkat Kelulusan</p>
                    </div>
                    <div class="about-stat-card">
                        <div class="about-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>500+</h3>
                        <p>Paket Ujian</p>
                    </div>
                    <div class="about-stat-card">
                        <div class="about-stat-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366F1;">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>24/7</h3>
                        <p>Bimbingan Mentor</p>
                    </div>
                </div>
            </div>
            
            <div class="about-content">
                <div class="badge-pill">
                    <i class="fas fa-info-circle"></i> Tentang Razaka Academy
                </div>
                <h2>Membuka Jalan Menuju <span>Masa Depan Impian Anda</span></h2>
                <p>Razaka Academy didirikan untuk menjadi pionir bimbingan ujian online terpercaya yang fokus pada peningkatan kompetensi siswa secara terstruktur dan terukur. Kami menggabungkan materi ujian berbasis kisi-kisi terupdate dengan teknologi analitik modern.</p>
                <p style="margin-top: 1rem;">Misi utama kami adalah menyediakan akses pendidikan berkualitas tinggi bagi setiap pejuang impian—baik calon mahasiswa baru (UTBK/Mandiri), calon aparatur sipil negara (CPNS/PPPK), maupun sertifikasi profesional internasional.</p>
                <div style="margin-top: 2rem; display: flex; gap: 1.5rem; flex-wrap: wrap;">
                    <div class="about-check-item">
                        <i class="fas fa-check-circle"></i> Kurikulum Kisi-Kisi Terbaru
                    </div>
                    <div class="about-check-item">
                        <i class="fas fa-check-circle"></i> Simulasi CAT Realistis
                    </div>
                    <div class="about-check-item">
                        <i class="fas fa-check-circle"></i> Rapor Statistik Pintar
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="testimonials" id="testimoni">
        <h2 class="section-title">Kisah Sukses Alumni</h2>
        <p class="section-subtitle">Ribuan alumni Razaka Academy telah berhasil menembus perguruan tinggi negeri impian dan instansi kementerian pilihan mereka.</p>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="comment">"Sistem CAT di Razaka Academy sangat mirip dengan aslinya saat tes CPNS kemarin. Analisis statistiknya sangat membantu saya mengetahui kelemahan materi TIU saya sehingga saya bisa mengasah materi tersebut secara fokus. Alhamdullilah tahun ini lolos!"</p>
                </div>
                <div class="profile">
                    <div class="avatar-initial" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);">AP</div>
                    <div>
                        <h4>Adi Pratama</h4>
                        <span>PNS di Kemenkumham RI</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="comment">"Tryout UTBK-nya sangat menantang! Pembahasan soalnya sangat detail dan ada rumus-rumus cepat yang ga diajarin di sekolah. Terutama pengerjaan TPS yang butuh waktu cepat. Sangat merekomendasikan Razaka bagi yang pejuang kampus!"</p>
                </div>
                <div class="profile">
                    <div class="avatar-initial" style="background: linear-gradient(135deg, #EC4899 0%, #BE185D 100%);">NS</div>
                    <div>
                        <h4>Nabila Salsabila</h4>
                        <span>Alumni Sukses (Universitas Indonesia)</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="comment">"Dari semua platform tryout online, Razaka yang paling user-friendly dan responsif di handphone. Pembahasan soal dalam bentuk tulisan yang rapi sangat gampang dipahami. Score prediksi kelulusan akurat dengan nilai UTBK asli saya!"</p>
                </div>
                <div class="profile">
                    <div class="avatar-initial" style="background: linear-gradient(135deg, #10B981 0%, #047857 100%);">RK</div>
                    <div>
                        <h4>Rian Kurniawan</h4>
                        <span>Alumni Sukses (ITB - Teknik Informatika)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="faq-section" id="faq">
        <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
        <p class="section-subtitle">Butuh informasi cepat tentang Razaka Academy? Temukan jawaban atas pertanyaan umum seputar program kami di bawah ini.</p>
        
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Bagaimana cara mendaftar dan mengikuti tryout di Razaka?</h3>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    <p>Sangat mudah! Anda hanya perlu mengeklik tombol "Daftar Gratis" di navbar, mengisi formulir pendaftaran singkat, lalu masuk ke Dashboard. Di sana, Anda dapat langsung memilih paket tryout gratis yang tersedia atau membeli paket ujian premium untuk koleksi soal yang lebih lengkap.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Apakah paket tryout yang dibeli memiliki masa aktif?</h3>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    <p>Semua paket tryout premium di Razaka Academy memiliki masa aktif yang bervariasi dari 6 bulan hingga 1 tahun penuh tergantung pada paket yang Anda beli. Selama masa aktif masih berlaku, Anda bebas mengulang pengerjaan soal dan meninjau pembahasan kapan saja.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Apakah pembahasan soal langsung dapat diakses setelah ujian selesai?</h3>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    <p>Ya! Segera setelah Anda menekan tombol "Selesai" dan mengirimkan jawaban Anda, sistem kami akan langsung menyajikan skor pencapaian beserta rapor performa. Di halaman riwayat, Anda bisa membuka review pembahasan lengkap (HTML-safe) step-by-step untuk seluruh butir soal.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Metode pembayaran apa saja yang didukung untuk paket premium?</h3>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    <p>Kami mendukung berbagai jenis metode pembayaran yang instan dan aman, mencakup Transfer Bank Manual, Virtual Account Bank (Mandiri, BNI, BRI, BCA), Dompet Digital (Gopay, OVO, Dana), serta pembayaran QRIS.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact-section" id="kontak">
        <div class="contact-container">
            <div class="contact-info">
                <div class="badge-pill">
                    <i class="fas fa-envelope"></i> Hubungi Kami
                </div>
                <h2>Kami Senang <span>Mendengar dari Anda</span></h2>
                <p>Memiliki kendala teknis, pertanyaan seputar paket, atau tawaran kolaborasi? Layanan bantuan pelanggan kami siap merespon pertanyaan Anda dalam waktu singkat.</p>
                
                <div class="contact-details" style="margin-top: 2.5rem; display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="contact-detail-card">
                        <div class="contact-icon" style="background: rgba(36, 58, 94, 0.1); color: var(--primary);">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4>Alamat Kantor</h4>
                            <p>Jl. Jenderal Sudirman No. 45, Kuningan, Jakarta Selatan, 12920</p>
                        </div>
                    </div>
                    
                    <div class="contact-detail-card">
                        <div class="contact-icon" style="background: rgba(56, 189, 248, 0.1); color: var(--accent);">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h4>Email Bantuan</h4>
                            <p>support@razakaacademy.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-detail-card">
                        <div class="contact-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h4>WhatsApp Hotline</h4>
                            <a href="https://wa.me/6281234567890" target="_blank" style="color: #10B981; font-weight: 700; display: flex; align-items: center; gap: 0.25rem;">
                                +62 812-3456-7890 <i class="fas fa-external-link-alt" style="font-size: 0.75rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-container">
                <form id="contactForm" onsubmit="handleContactSubmit(event)">
                    <div class="form-group">
                        <label for="c_name">Nama Lengkap</label>
                        <input type="text" id="c_name" required placeholder="Masukkan nama Anda">
                    </div>
                    <div class="form-group">
                        <label for="c_email">Alamat Email</label>
                        <input type="email" id="c_email" required placeholder="Masukkan email Anda">
                    </div>
                    <div class="form-group">
                        <label for="c_subject">Subjek Pesan</label>
                        <input type="text" id="c_subject" required placeholder="Pertanyaan umum, kendala teknis, dll.">
                    </div>
                    <div class="form-group">
                        <label for="c_message">Isi Pesan</label>
                        <textarea id="c_message" required rows="4" placeholder="Tuliskan pesan atau pertanyaan Anda disini..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.9rem;">
                        Kirim Pesan <i class="fas fa-paper-plane send-icon"></i>
                    </button>
                </form>
                
                <div id="contactSuccess" class="contact-success-alert" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <h4>Pesan Berhasil Terkirim!</h4>
                        <p>Terima kasih telah menghubungi kami. Tim kami akan segera menanggapi melalui email dalam waktu 1x24 jam.</p>
                    </div>
                </div>
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

    <!-- MULTI-COLUMN FOOTER -->
    <footer>
        <div class="footer-container">
            <div class="footer-about">
                <a href="/" class="brand" style="margin-bottom: 1.5rem; display: inline-flex;">
                    <img src="{{ asset('images/razakalogo.jpg') }}" alt="Razaka Logo" style="height: 42px; width: 42px; border-radius: 12px; object-fit: cover; box-shadow: var(--shadow-sm); border: 2px solid var(--border);">
                    <span style="font-size: 1.5rem;">RAZAKA</span>
                </a>
                <p>Platform tryout dan bimbingan belajar online CPNS, UTBK, Mandiri, dan TOEFL terpercaya dengan ribuan latihan soal berkualitas tinggi.</p>
                <div class="social-links">
                    <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
            
            <div class="footer-links">
                <h3>Navigasi Cepat</h3>
                <a href="#fitur">Fitur Utama</a>
                <a href="#tentang">Tentang Kami</a>
                <a href="#testimoni">Kisah Alumni</a>
                <a href="#faq">Tanya Jawab FAQ</a>
                <a href="#kontak">Hubungi Kami</a>
            </div>
            
            <div class="footer-links">
                <h3>Program Tryout</h3>
                <a href="{{ url('auth/login') }}">Tryout SKD CPNS</a>
                <a href="{{ url('auth/login') }}">Tryout UTBK-SNBT</a>
                <a href="{{ url('auth/login') }}">Tryout Mandiri PTN</a>
                <a href="{{ url('auth/login') }}">Tryout TOEFL Prep</a>
            </div>
            
            <div class="footer-newsletter">
                <h3>Newsletter</h3>
                <p>Berlangganan tips pengerjaan soal ujian tercepat dan update kisi-kisi langsung di inbox email Anda.</p>
                <form id="newsletterForm" onsubmit="handleNewsletterSubmit(event)" style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                    <input type="email" required placeholder="Alamat email Anda" style="padding: 0.75rem 1rem; border-radius: 50px; border: 1.5px solid var(--border); background: var(--bg); outline: none; font-size: 0.9rem; flex: 1;">
                    <button type="submit" class="btn btn-primary" style="padding: 0 1.25rem; border-radius: 50px; justify-content: center;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <div id="newsletterSuccess" style="display: none; color: #10B981; font-size: 0.85rem; font-weight: 600; margin-top: 0.5rem; text-align: left;">
                    <i class="fas fa-check-circle"></i> Berhasil Berlangganan!
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2026 RAZAKA ACADEMY. Semua Hak Cipta Dilindungi. Dibuat dengan dedikasi di Indonesia.</p>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        // Smooth scrolling to anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - navbarHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // FAQ Accordion Interactivity
        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                // Close all other FAQ items
                document.querySelectorAll('.faq-item').forEach(otherItem => {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.faq-answer').style.maxHeight = null;
                });
                
                // Toggle active item
                if (!isActive) {
                    item.classList.add('active');
                    const answer = item.querySelector('.faq-answer');
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        });

        // Handle Contact Form Submit Mock
        function handleContactSubmit(event) {
            event.preventDefault();
            const form = document.getElementById('contactForm');
            const alertSuccess = document.getElementById('contactSuccess');
            
            // Hide form and show success alert
            form.style.display = 'none';
            alertSuccess.style.display = 'flex';
            
            // Log for verification
            console.log('Form Kontak Terkirim:', {
                nama: document.getElementById('c_name').value,
                email: document.getElementById('c_email').value,
                subjek: document.getElementById('c_subject').value,
                pesan: document.getElementById('c_message').value
            });
        }

        // Handle Newsletter Form Submit Mock
        function handleNewsletterSubmit(event) {
            event.preventDefault();
            const form = document.getElementById('newsletterForm');
            const success = document.getElementById('newsletterSuccess');
            
            form.style.display = 'none';
            success.style.display = 'block';
        }

        // Scroll Spy active navigation highlight
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const scrollPos = window.scrollY + document.querySelector('.navbar').offsetHeight + 100;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');
                
                if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                    document.querySelectorAll('.nav-links a').forEach(link => {
                        if (link.getAttribute('href') === '#' + sectionId) {
                            link.style.color = 'var(--primary)';
                            link.style.fontWeight = '700';
                        } else {
                            // Only target sections in nav menu
                            const href = link.getAttribute('href');
                            if (href && href.startsWith('#')) {
                                link.style.color = 'var(--text-muted)';
                                link.style.fontWeight = '500';
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
