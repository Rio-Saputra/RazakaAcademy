<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAZAKA ACADEMY - Auth</title>
    <!-- Fonts: Inter & Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
     :root {
        --primary: #243A5E;
        --primary-light: #2F4F7F;
        --primary-gradient: linear-gradient(135deg, #243A5E 0%, #2F4F7F 100%);
        --bg: #F8FAFC;
        --card: #FFFFFF;
        --text: #1F2937;
        --text-muted: #64748B;
        --border: #E2E8F0;
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --radius: 24px;
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
        background: var(--bg);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .container-auth {
        display: flex;
        width: 100%;
        max-width: 1100px;
        min-height: 600px;
        background: var(--card);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    /* LEFT SIDE - BRANDING & GRADIENT */
    .auth-left {
        flex: 1;
        background: var(--primary-gradient);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    /* Decorative circles */
    .auth-left::before {
        content: '';
        position: absolute;
        top: -10%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }

    .auth-left::after {
        content: '';
        position: absolute;
        bottom: -20%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }

    .logo-text {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 10;
    }

    .image-box {
        margin: 2rem 0;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .image-box img {
        max-width: 80%;
        border-radius: var(--radius);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        transition: var(--transition);
    }

    .image-box img:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }

    .left-text {
        z-index: 10;
    }

    .left-text h2 {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        color: white;
        line-height: 1.2;
    }

    .left-text p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.8);
    }

    /* RIGHT SIDE - FORM */
    .auth-right {
        flex: 1;
        padding: 4rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: var(--card);
    }

    .auth-right h2 {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .auth-right p {
        font-size: 1rem;
        color: var(--text-muted);
        margin-bottom: 2.5rem;
    }

    /* FORM COMPONENTS */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
        color: var(--text);
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border-radius: 12px;
        border: 1.5px solid var(--border);
        background: #F8FAFC;
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary);
        background: white;
        outline: none;
        box-shadow: 0 0 0 4px rgba(36, 58, 94, 0.1);
    }

    /* BUTTON */
    .btn {
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 12px;
        background: var(--primary-gradient);
        color: white;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(36, 58, 94, 0.2);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(36, 58, 94, 0.3);
    }

    /* FOOTER */
    .footer {
        margin-top: 2rem;
        font-size: 0.95rem;
        text-align: center;
        color: var(--text-muted);
    }

    .footer a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .footer a:hover {
        color: var(--primary-light);
        text-decoration: underline;
    }

    /* ALERTS */
    .alert {
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .alert-danger {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #F87171;
    }

    /* MOBILE */
    @media(max-width: 900px) {
        .container-auth {
            flex-direction: column;
            max-width: 500px;
        }

        .auth-left {
            padding: 2.5rem;
            text-align: center;
        }

        .logo-text {
            justify-content: center;
        }

        .image-box {
            display: none;
        }

        .auth-right {
            padding: 2.5rem;
        }
    }
    </style>
</head>
<body>

<div class="container-auth">
    <!-- LEFT -->
    <div class="auth-left">
        <div class="logo-text">
            <i class="fas fa-graduation-cap"></i> RAZAKA
        </div>

        <div class="image-box">
            <img src="{{ asset('images/razakalogo.jpg') }}" alt="Ilustrasi" onerror="this.src='https://placehold.co/400x300/243A5E/FFFFFF?text=Razaka+Academy'">
        </div>

        <div class="left-text">
            <h2>Platform Belajar Modern</h2>
            <p>Raih mimpimu dengan fitur simulasi tryout terbaik dan terstruktur.</p>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        @yield('content')
    </div>
</div>

</body>
</html>