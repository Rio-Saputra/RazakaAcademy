<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAZAKA ACADEMY</title>
    <!-- Fonts: Inter & Poppins for modern SaaS look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
:root

{
    --primary: #243A5E;
    --primary-light: #2F4F7F;
    --primary-gradient: linear-gradient(135deg, #243A5E 0%, #2F4F7F 100%);
    --bg: #F8FAFC;
    --card: #FFFFFF;
    --text: #1F2937;
    --text-muted: #64748B;
    --border: #E2E8F0;
    --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    --radius: 16px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.select2-container .select2-selection--single {
    height: 54px !important;
    border-radius: 12px !important;
    border: 1.5px solid var(--border) !important;
    background: #F8FAFC !important;
    display: flex !important;
    align-items: center !important;
    padding: 0 12px !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text) !important;
    line-height: 52px !important;
    font-weight: 500;
}

.select2-dropdown {
    border-radius: 12px !important;
    border: 1px solid var(--border) !important;
    overflow: hidden;
}

.select2-search__field {
    border-radius: 8px !important;
    padding: 8px !important;
}

.select2-results__option--highlighted {
    background: var(--primary) !important;
    color: white !important;
}

/* RESET */
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
}

a {
    text-decoration: none;
    color: inherit;
}

/* LAYOUT */
.dashboard-container {
    display: flex;
    min-height: 100vh;
}

/* SIDEBAR */
.sidebar {
    width: 280px;
    background: var(--card);
    border-right: 1px solid var(--border);
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100vh;
    z-index: 50;
    transition: var(--transition);
}

.sidebar-brand {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 2.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0 0.5rem;
}

.sidebar-brand i {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 1.75rem;
}

/* NAV ITEM */
.nav-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem 1rem;
    color: var(--text-muted);
    border-radius: 12px;
    margin-bottom: 0.5rem;
    transition: var(--transition);
    font-weight: 500;
}

.nav-item i {
    font-size: 1.25rem;
    width: 24px;
    text-align: center;
    transition: var(--transition);
}

/* HOVER SOFT */
.nav-item:hover {
    background: var(--bg);
    color: var(--primary);
    transform: translateX(4px);
}

/* ACTIVE */
.nav-item.active {
    background: var(--primary-gradient);
    color: white;
    box-shadow: var(--shadow-sm);
}

.nav-item.active i {
    color: white;
}

/* MAIN */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    margin-left: 280px;
    min-width: 0; /* Fix flex overflow */
}

/* TOPBAR */
.topbar {
    background: var(--card);
    padding: 1.25rem 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 40;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.hamburger {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text);
    cursor: pointer;
}

/* USER PROFILE */
.user-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem 1rem;
    background: var(--bg);
    border-radius: 50px;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: var(--transition);
}

.user-profile:hover {
    box-shadow: var(--shadow-sm);
}

.user-profile span {
    font-weight: 600;
    font-size: 0.95rem;
}

/* CONTENT AREA */
.content-area {
    padding: 2.5rem;
}

/* GRADIENT BANNER */
.page-header {
    background: var(--primary-gradient);
    border-radius: var(--radius);
    padding: 2.5rem;
    color: white;
    margin-bottom: 2.5rem;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.page-header::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
    pointer-events: none;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--primary);
}

.page-header .page-title {
    color: white;
}

.subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
}

.page-header .subtitle {
    color: rgba(255, 255, 255, 0.8);
}

/* CARD GLOBAL */
.card {
    background: var(--card);
    border-radius: var(--radius);
    padding: 2rem;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    margin-bottom: 2rem;
}

.card:hover {
    box-shadow: var(--shadow-hover);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
}

/* STATS GRID */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

/* STAT CARD */
.stat-card {
    background: var(--card);
    border-radius: var(--radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: rgba(36, 58, 94, 0.1);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-info {
    flex: 1;
}

.stat-info h3 {
    font-size: 0.875rem;
    color: var(--text-muted);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.stat-info p {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
}

/* TABLE */
.table-container {
    overflow-x: auto;
    background: white;
    border-radius: 12px;
    border: 1px solid var(--border);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 1.25rem 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border);
}

th {
    background: #F8FAFC;
    color: var(--text-muted);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

tbody tr {
    transition: var(--transition);
}

tbody tr:hover {
    background: #F8FAFC;
}

/* BUTTON */
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
    text-align: center;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 12px rgba(36, 58, 94, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(36, 58, 94, 0.3);
}

.btn-secondary {
    background: white;
    color: var(--text);
    border: 1px solid var(--border);
}

.btn-secondary:hover {
    background: var(--bg);
}

.btn-danger {
    background: #EF4444;
    color: white;
}

.btn-danger:hover {
    background: #DC2626;
}

.btn-generate {
    background: linear-gradient(135deg, #243A5E, #2F4F7F);
    color: #FFFFFF;
    border: none;
    box-shadow: 0 4px 12px rgba(36, 58, 94, 0.2);
}

.btn-generate:hover {
    background: #1e2f4d;
    transform: translateY(-2px);
    color: #FFFFFF;
}

.btn-outline-primary {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
}

.btn-outline-primary:hover {
    background: var(--primary);
    color: white;
}

/* FORM */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: var(--text);
}

.form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    transition: var(--transition);
    background: #F8FAFC;
}

.form-control:focus {
    border-color: var(--primary);
    background: white;
    outline: none;
    box-shadow: 0 0 0 4px rgba(36, 58, 94, 0.1);
}

/* BADGE */
.badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-success {
    background: #DEF7EC;
    color: #03543F;
}

.badge-warning {
    background: #FEF3C7;
    color: #92400E;
}

/* RESPONSIVE */
@media (max-width: 1024px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.show {
        transform: translateX(0);
        box-shadow: var(--shadow-hover);
    }

    .main-content {
        margin-left: 0;
    }

    .hamburger {
        display: block;
    }
}

@media (max-width: 768px) {
    .content-area {
        padding: 1.5rem;
    }
    
    .page-header {
        padding: 2rem 1.5rem;
    }
    
    .card {
        padding: 1.5rem;
    }
}

/* Premium Logout Button Styling */
.logout-btn-premium {
    width: 100%;
    border: none;
    cursor: pointer;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.95rem 1.25rem;
    border-radius: 16px;
    font-size: 0.98rem;
    font-weight: 600;
    color: #F87171 !important;
    background: rgba(239, 68, 68, 0.08) !important;
    border: 1.5px dashed rgba(239, 68, 68, 0.2) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.logout-btn-premium:hover {
    background: #EF4444 !important;
    color: white !important;
    border-color: #EF4444 !important;
    box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2) !important;
    transform: translateY(-2px);
}
</style>
    @if(!request()->is('admin*'))
    <style>
        :root {
            /* Inverted Premium Navy Theme for Students */
            --bg: #F8FAFC;
            --card: #243A5E; /* Navy is the dominant card background! */
            --text: #FFFFFF; /* White text for cards */
            --text-muted: #CBD5E1; /* Light Slate for muted text */
            --border: rgba(255, 255, 255, 0.12); /* Semi-transparent border */
            --shadow-sm: 0 10px 30px rgba(36, 58, 94, 0.08);
            --shadow-md: 0 15px 35px rgba(36, 58, 94, 0.12);
            --shadow-hover: 0 25px 45px rgba(36, 58, 94, 0.2);
            --radius: 20px;
        }

        body {
            background-color: #F1F5F9; /* Clean minimalist gray background */
            color: #1E293B; /* Global page text color */
        }

        /* Sidebar: Solid deep navy */
        .sidebar {
            background: #1E2F4D; 
            border-right: none;
            padding: 2.25rem 1.5rem;
        }

        .sidebar-brand {
            color: #FFFFFF !important;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
            margin-bottom: 3rem;
            font-weight: 800;
        }

        .sidebar-brand i {
            color: var(--accent) !important;
            -webkit-text-fill-color: initial !important;
        }

        .nav-item {
            padding: 0.95rem 1.25rem;
            border-radius: 16px;
            font-size: 0.98rem;
            margin-bottom: 0.65rem;
            color: #94A3B8;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: var(--transition);
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #FFFFFF;
            transform: translateX(6px);
        }

        .nav-item.active {
            background: #FFFFFF !important;
            color: #1E2F4D !important;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .nav-item.active i {
            color: #1E2F4D !important;
        }

        /* Topbar & User profile */
        .topbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
        }

        .user-profile {
            border: 1px solid #E2E8F0 !important;
            background: #F8FAFC !important;
            padding: 0.5rem 1rem !important;
            color: #1E293B !important;
        }

        .user-profile span {
            color: #1E293B !important;
        }

        /* Page Headers */
        .page-header {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            box-shadow: var(--shadow-sm);
            border-radius: 24px;
            padding: 2.75rem;
            color: #1E293B;
        }

        .page-header .page-title {
            color: #1E293B !important;
        }

        .page-header .subtitle {
            color: #64748B !important;
        }

        /* General Card Legibility Overrides */
        .card {
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            color: #FFFFFF !important;
            box-shadow: var(--shadow-sm);
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card h1, .card h2, .card h3, .card h4, .card h5, .card h6, .card p, .card span:not(.badge), .card label, .card strong, .card td {
            color: #FFFFFF !important;
        }

        .card .text-muted, .card p[style*="var(--text-muted)"], .card span[style*="var(--text-muted)"], .card p[style*="color: #64748B"], .card p[style*="color:var(--text-muted)"], .card small {
            color: #CBD5E1 !important;
        }

        /* Glowing Accent highlights inside cards */
        .card h1[style*="color: var(--primary)"], .card h1[style*="color:var(--primary)"], .card .rec-price, .card .greeting-stat-val {
            color: #38BDF8 !important; /* Elegant Glowing Cyan */
        }

        .card[style*="border-top: 4px solid var(--primary)"], .card[style*="border-top:4px solid var(--primary)"] {
            border-top: 4px solid #38BDF8 !important;
        }

        /* Glassmorphic overlay for internal light panels inside cards */
        .card div[style*="background: #F8FAFC"], .card div[style*="background:#F8FAFC"], .card div[style*="background: #f8fafc"] {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        /* Form Controls & Inputs legibility (White box with dark text is ultra-minimalist and readable) */
        .form-control, select.form-control, textarea.form-control {
            background: #FFFFFF !important;
            color: #1E293B !important;
            border: 1.5px solid #CBD5E1 !important;
            border-radius: 12px;
        }

        .form-control:focus {
            border-color: #38BDF8 !important;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1) !important;
            background: #FFFFFF !important;
        }

        .form-label {
            color: #FFFFFF !important;
            font-weight: 600;
        }

        /* Table modernizations inside cards */
        .table-container {
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            box-shadow: var(--shadow-sm);
        }

        table th {
            background: rgba(255, 255, 255, 0.06) !important;
            color: #FFFFFF !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            font-weight: 700;
        }

        table td {
            color: #FFFFFF !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        /* Specific modifications for user dashboard page cards */
        .minimal-greeting-card {
            background: var(--card) !important;
            color: var(--text) !important;
            border: 1px solid var(--border) !important;
        }

        .greeting-title {
            color: #FFFFFF !important;
        }

        .greeting-subtitle {
            color: var(--text-muted) !important;
        }

        .greeting-stat-val {
            color: #38BDF8 !important;
        }

        .greeting-stat-lbl {
            color: var(--text-muted) !important;
        }

        /* Quick Navigation Tiles */
        .quick-tile-card {
            background: #FFFFFF !important; /* White as secondary! */
            border: 1px solid #E2E8F0 !important;
            box-shadow: var(--shadow-sm);
        }

        .quick-tile-card:hover {
            border-color: rgba(36, 58, 94, 0.15) !important;
            box-shadow: var(--shadow-md) !important;
        }

        .quick-tile-card h4 {
            color: #1E293B !important;
        }

        .quick-tile-card p {
            color: #64748B !important;
        }

        .section-title-min {
            color: #1E293B !important;
            font-size: 1.35rem !important;
            font-weight: 700 !important;
            margin: 1.5rem 0 1rem 0 !important;
        }

        /* Recommended packages - keep Navy but use white buttons */
        .rec-package-card {
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
        }

        .rec-title-meta h4 {
            color: #FFFFFF !important;
        }

        .rec-price {
            color: #38BDF8 !important;
        }

        .rec-desc {
            color: var(--text-muted) !important;
        }

        .btn-buy-min {
            border: 1.5px solid #FFFFFF !important;
            color: #FFFFFF !important;
        }

        .btn-buy-min:hover {
            background: #FFFFFF !important;
            color: #1E2F4D !important;
        }

        /* Sidebar profile and activity cards */
        .min-profile-card {
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
        }

        .profile-name {
            color: #FFFFFF !important;
        }

        .profile-email {
            color: var(--text-muted) !important;
        }

        .profile-badge-role {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #38BDF8 !important;
        }

        .profile-avatar-circle {
            border: 4px solid var(--card) !important;
        }

        .recent-activities-card {
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
        }

        .sidebar-sec-title {
            color: #FFFFFF !important;
            border-bottom: 1px solid var(--border) !important;
        }

        .activity-item-card {
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid var(--border) !important;
        }

        .activity-tryout-name {
            color: #FFFFFF !important;
        }

        .activity-date {
            color: var(--text-muted) !important;
        }

        .activity-score-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
        }

        .activity-link-btn {
            color: #38BDF8 !important;
        }

        .activity-link-btn:hover {
            color: #FFFFFF !important;
        }

        .badge-high {
            background: rgba(16, 185, 129, 0.15) !important;
            color: #34D399 !important;
        }

        .badge-mid {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #E2E8F0 !important;
        }

        /* High-contrast and premium legibility badges on student pages */
        .card .badge-success, .badge.badge-success, .badge-success {
            background-color: #DEF7EC !important;
            color: #03543F !important;
        }

        .card .badge-warning, .badge.badge-warning, .badge-warning {
            background-color: #FEF3C7 !important;
            color: #92400E !important;
        }

        .card .badge-danger, .badge.badge-danger, .badge-danger {
            background-color: #FDE8E8 !important;
            color: #9B1C1C !important;
        }

        /* Readable secondary buttons inside navy student cards/tables */
        .btn-secondary, a.btn-secondary, .card .btn-secondary, .card a.btn-secondary {
            background: #FFFFFF !important;
            color: #1E293B !important;
            border: 1.5px solid #CBD5E1 !important;
        }

        .btn-secondary:hover, a.btn-secondary:hover, .card .btn-secondary:hover, .card a.btn-secondary:hover {
            background: #F8FAFC !important;
            color: #0F172A !important;
            border-color: #94A3B8 !important;
        }

        /* Clean and premium disabled buttons inside navy student cards */
        .btn:disabled, button:disabled, .btn[disabled], .card button:disabled, .card .btn:disabled {
            background: rgba(255, 255, 255, 0.12) !important;
            color: rgba(255, 255, 255, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            cursor: not-allowed !important;
            opacity: 0.8 !important;
        }
        /* Dynamic collapsible sidebar on student pages */
        .hamburger {
            display: block !important; /* Always show hamburger toggle on student pages */
            background: none;
            border: none;
            font-size: 1.35rem;
            color: #1E293B !important;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hamburger:hover {
            background: rgba(0, 0, 0, 0.05) !important;
        }

        .main-content {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .sidebar-collapsed .sidebar {
            transform: translateX(-280px) !important;
        }

        .sidebar-collapsed .main-content {
            margin-left: 0 !important;
        }

        @media (max-width: 1024px) {
            .sidebar-collapsed .sidebar {
                transform: translateX(-100%) !important;
            }
            .sidebar-collapsed .main-content {
                margin-left: 0 !important;
            }
        }

        /* Sticky Tryout Header Premium Dark Navy */
        .header-tryout-premium {
            background: rgba(36, 58, 94, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            backdrop-filter: blur(12px) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            color: #FFFFFF !important;
        }

        .tryout-title-text {
            color: #FFFFFF !important;
        }

        .tryout-desc-text {
            color: #CBD5E1 !important;
        }

        .timer-widget {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.12) !important;
        }

        .timer-icon {
            color: #38BDF8 !important;
        }

        #timer {
            color: #38BDF8 !important;
        }

        .progress-info span {
            color: #CBD5E1 !important;
        }

        .progress-track {
            background: rgba(255, 255, 255, 0.12) !important;
            border-radius: 50px !important;
            overflow: hidden !important;
        }

        #progressBar {
            background: linear-gradient(90deg, #38BDF8 0%, #0EA5E9 100%) !important;
        }

        /* Question Badge */
        .card .question-number-badge, .question-number-badge {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #38BDF8 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
        }

        /* Option Cards Legibility inside Navy student cards */
        .option-card-label {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 2px solid rgba(255, 255, 255, 0.12) !important;
            color: #FFFFFF !important;
        }

        .option-card-label:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: #38BDF8 !important;
        }

        .option-text-span {
            color: #FFFFFF !important;
        }

        .option-letter-badge {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #CBD5E1 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
        }

        .option-card-label:hover .option-letter-badge {
            background: rgba(56, 189, 248, 0.15) !important;
            color: #38BDF8 !important;
            border-color: #38BDF8 !important;
        }

        .option-card-label:has(input:checked) {
            border-color: #38BDF8 !important;
            background: rgba(56, 189, 248, 0.1) !important;
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.15) !important;
        }

        .option-card-label:has(input:checked) .option-letter-badge {
            background: #38BDF8 !important;
            color: #1E293B !important;
            border-color: #38BDF8 !important;
        }

        .option-card-label:has(input:checked) .option-check-icon {
            color: #38BDF8 !important;
            opacity: 1 !important;
            transform: scale(1) !important;
        }

        /* Nav Buttons inside Student Navy Sidebar Card */
        .nav-btn-premium {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 2px solid rgba(255, 255, 255, 0.12) !important;
            color: #FFFFFF !important;
        }

        .nav-btn-premium:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: #38BDF8 !important;
        }

        .nav-btn-premium.answered {
            background: #10B981 !important;
            color: #FFFFFF !important;
            border-color: #10B981 !important;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15) !important;
        }

        .nav-btn-premium.active-pos {
            background: #38BDF8 !important;
            color: #1E293B !important;
            border-color: #38BDF8 !important;
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.25) !important;
        }

        .nav-btn-premium.answered.active-pos {
            background: #059669 !important;
            border-color: #059669 !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25) !important;
        }

        /* Navigation Legend colors */
        .legend-color.unanswered {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 2px solid rgba(255, 255, 255, 0.15) !important;
        }

        .legend-color.current {
            background: #38BDF8 !important;
        }

        /* Premium Notifications Dropdown Menu */
        .notifications-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 320px;
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            display: none;
            flex-direction: column;
            z-index: 100;
            padding: 0 !important;
            transform: scale(0.95);
            opacity: 0;
            transform-origin: top right;
            transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .notifications-dropdown-menu.show {
            display: flex !important;
            opacity: 1 !important;
            transform: scale(1) !important;
        }

        .notification-item {
            border-bottom: 1px solid #F1F5F9;
        }

        .notification-item.unread {
            background-color: #F8FAFC !important;
        }

        .notification-item:hover {
            background-color: #F1F5F9 !important;
        }

        /* Premium Profile Dropdown Menu */
        .profile-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 220px;
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            display: none;
            flex-direction: column;
            z-index: 100;
            padding: 0.5rem !important;
            transform: scale(0.95);
            opacity: 0;
            transform-origin: top right;
            transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .profile-dropdown-menu.show {
            display: flex !important;
            opacity: 1 !important;
            transform: scale(1) !important;
        }

        .dropdown-header {
            padding: 0.75rem 1rem !important;
            text-align: left !important;
        }

        .dropdown-user-name {
            font-weight: 700 !important;
            color: #1E293B !important;
            font-size: 0.95rem !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-user-email {
            font-size: 0.8rem !important;
            color: #64748B !important;
            margin-top: 0.15rem !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-divider {
            height: 1px;
            background: #E2E8F0 !important;
            margin: 0.5rem 0 !important;
        }

        .dropdown-item {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            color: #475569 !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            border-radius: 10px !important;
            transition: var(--transition) !important;
            text-decoration: none !important;
            background: none !important;
            border: none !important;
            width: 100% !important;
            box-shadow: none !important;
        }

        .dropdown-item i {
            font-size: 1rem !important;
            color: #64748B !important;
            transition: var(--transition) !important;
        }

        .dropdown-item:hover {
            background: #F8FAFC !important;
            color: #243A5E !important;
        }

        .dropdown-item:hover i {
            color: #243A5E !important;
        }

        .dropdown-item.logout-btn {
            color: #EF4444 !important;
        }

        .dropdown-item.logout-btn i {
            color: #EF4444 !important;
        }

        .dropdown-item.logout-btn:hover {
            background: #FEF2F2 !important;
            color: #EF4444 !important;
        }

        /* Student specific notification dropdown style (Inverted Navy/Glassmorphic) */
        .notifications-dropdown-menu {
            background: rgba(30, 41, 59, 0.98) !important;
            backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.5) !important;
        }

        .notification-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        }

        .notification-item.unread {
            background-color: rgba(56, 189, 248, 0.08) !important;
        }

        .notification-item:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
    </style>
    @endif
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-graduation-cap"></i> RAZAKA
            </div>
            
            @if(request()->is('admin*'))
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.bank-soal.index') }}" class="nav-item {{ request()->routeIs('admin.bank-soal.*') || request()->routeIs('admin.kategori-bank-soal.*') ? 'active' : '' }}">
                    <i class="fas fa-database"></i> Bank Soal
                </a>
                <a href="{{ route('admin.paket.index') }}" class="nav-item {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Kelola Paket
                </a>
                <a href="{{ route('admin.tryout.index') }}" class="nav-item {{ request()->routeIs('admin.tryout.*') || request()->routeIs('admin.soal.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Tryout & Soal
                </a>
                <a href="{{ route('admin.kelola_user') }}" class="nav-item {{ request()->routeIs('admin.kelola_user') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Kelola User
                </a>
                <a href="{{ route('admin.transaksi') }}" class="nav-item {{ request()->routeIs('admin.transaksi') ? 'active' : '' }}">
                    <i class="fas fa-money-bill"></i> Transaksi
                </a>
                <a href="{{ route('admin.profile') }}" class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i> Profil
                </a>
            @else
                <!-- User Navigation -->
                <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('user.paket') }}" class="nav-item {{ request()->routeIs('user.paket') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> Beli Paket
                </a>
                <a href="{{ route('user.tryout.index') }}" class="nav-item {{ request()->routeIs('user.tryout.*') ? 'active' : '' }}">
                    <i class="fas fa-laptop-code"></i> Tryout Saya
                </a>
                <a href="{{ route('user.riwayat-tryout') }}" class="nav-item {{ request()->routeIs('user.riwayat-tryout') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i> Riwayat Tryout
                </a>
                <a href="{{ route('user.riwayat') }}" class="nav-item {{ request()->routeIs('user.riwayat') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Transaksi
                </a>
                <a href="{{ route('user.profile') }}" class="nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i> Profil
                </a>
            @endif
            
            <form action="{{ route('logout') }}" method="POST" style="margin-top: auto; padding: 0 0.5rem;" data-confirm="Yakin ingin keluar?" data-type="warning" data-title="Keluar">
                @csrf
                <button type="submit" class="logout-btn-premium">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div id="sidebar-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40;" onclick="toggleSidebar()"></div>

        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <button class="hamburger" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-search" style="display: flex; align-items: center; gap: 0.5rem; background: var(--bg); padding: 0.5rem 1rem; border-radius: 50px; border: 1px solid var(--border); display: none;">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                    <input type="text" placeholder="Cari..." style="border: none; background: transparent; outline: none; font-size: 0.95rem;">
                </div>
                <div style="flex:1"></div>

                @if(Auth::check() && !request()->is('admin*'))
                @php
                    $unreadNotificationsCount = Auth::user()->unreadNotificationsCount();
                    $notifications = Auth::user()->notifications()->take(5)->get();
                @endphp
                <div class="notifications-wrapper" style="position: relative; margin-right: 0.5rem; display: flex; align-items: center;">
                    <button class="notification-bell" onclick="toggleNotificationsDropdown(event)" style="background: var(--bg); border: 1px solid var(--border); border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: var(--transition); color: #1E293B; position: relative; outline: none; padding: 0;">
                        <i class="fas fa-bell"></i>
                        @if($unreadNotificationsCount > 0)
                            <span class="notification-badge" id="notification-badge-el" style="position: absolute; top: -2px; right: -2px; background: #EF4444; color: white; font-size: 0.65rem; font-weight: 700; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border: 2px solid #FFFFFF;">
                                {{ $unreadNotificationsCount }}
                            </span>
                        @endif
                    </button>
                    <!-- Notification Dropdown Menu -->
                    <div id="notifications-dropdown" class="notifications-dropdown-menu">
                        <div class="dropdown-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem;">
                            <span style="font-weight: 700; font-size: 0.95rem; color: white;">Notifikasi</span>
                            @if($unreadNotificationsCount > 0)
                                <button onclick="markAllNotificationsAsRead(event)" style="background: none; border: none; color: #38BDF8; font-size: 0.75rem; font-weight: 600; cursor: pointer; padding: 0; transition: var(--transition); text-decoration: none;">Tandai Semua Dibaca</button>
                            @endif
                        </div>
                        <div class="dropdown-divider" style="margin: 0;"></div>
                        <div class="notifications-list" style="max-height: 280px; overflow-y: auto;">
                            @if($notifications->isEmpty())
                                <div style="padding: 2rem 1.25rem; text-align: center; color: #94A3B8;">
                                    <i class="far fa-bell" style="font-size: 1.5rem; margin-bottom: 0.5rem; display: block; color: #94A3B8; opacity: 0.5;"></i>
                                    <span style="font-size: 0.85rem;">Tidak ada notifikasi baru</span>
                                </div>
                            @else
                                @foreach($notifications as $notif)
                                    <div class="notification-item {{ $notif->is_read ? 'read' : 'unread' }}" onclick="markNotificationAsRead(event, {{ $notif->id }})" style="padding: 0.9rem 1.25rem; cursor: pointer; transition: var(--transition); display: flex; gap: 0.75rem; align-items: flex-start; position: relative;">
                                        <div style="margin-top: 0.2rem; font-size: 0.95rem;">
                                            @if(str_contains(strtolower($notif->title), 'kadaluwarsa'))
                                                <i class="fas fa-exclamation-circle" style="color: #EF4444;"></i>
                                            @else
                                                <i class="fas fa-bell" style="color: #F59E0B;"></i>
                                            @endif
                                        </div>
                                        <div style="flex: 1; min-width: 0; text-align: left;">
                                            <div style="font-weight: 700; font-size: 0.85rem; color: white; margin-bottom: 0.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $notif->title }}</div>
                                            <div style="font-size: 0.78rem; color: #CBD5E1; line-height: 1.4; margin-bottom: 0.35rem;">{{ $notif->message }}</div>
                                            <div style="font-size: 0.7rem; color: #94A3B8;">{{ $notif->created_at->diffForHumans() }}</div>
                                        </div>
                                        @if(!$notif->is_read)
                                            <span class="unread-dot" style="width: 6px; height: 6px; border-radius: 50%; background: #EF4444; position: absolute; top: 1.1rem; right: 0.8rem;"></span>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="user-profile-wrapper" style="position: relative; display: flex; align-items: center; gap: 0.5rem;">
                    <div class="user-profile" onclick="toggleProfileDropdown(event)" style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 1rem; background: var(--bg); border-radius: 50px; border: 1px solid var(--border); cursor: pointer; transition: var(--transition);">
                        <span style="font-weight: 600; font-size: 0.95rem;">{{ request()->is('admin*') ? 'Admin RAZAKA' : Auth::user()->name }}</span>
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    
                    @if(!request()->is('admin*'))
                    <!-- Premium Profile Dropdown Menu -->
                    <div id="user-profile-dropdown" class="profile-dropdown-menu">
                        <div class="dropdown-header">
                            <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                            <div class="dropdown-user-email">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('user.profile') }}" class="dropdown-item">
                            <i class="fas fa-user-cog"></i> Edit Profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST" id="dropdown-logout-form" data-confirm="Yakin ingin keluar?" data-type="warning" data-title="Keluar" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer; display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    <div id="modern-alert-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
        <div id="modern-alert-content" style="background:white; border-radius:1rem; padding:2.5rem; width:90%; max-width:400px; text-align:center; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); transform:scale(0.9); transition:transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <div id="modern-alert-icon-container" style="width:80px; height:80px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; font-size:2.5rem;">
                <i id="modern-alert-icon" class="fas fa-check-circle"></i>
            </div>
            <h3 id="modern-alert-title" style="font-size:1.5rem; font-weight:700; color:#1F2937; margin-bottom:0.75rem;">Berhasil!</h3>
            <p id="modern-alert-message" style="color:#64748B; margin-bottom:2rem; font-size:1.05rem; line-height:1.5;">Data berhasil disimpan.</p>
            <div id="modern-alert-buttons" style="display:flex; gap:1rem; justify-content:center;">
                <button id="modern-alert-cancel" style="display:none; padding:0.75rem 1.5rem; border-radius:50px; border:1px solid #E2E8F0; background:white; color:#64748B; font-weight:600; cursor:pointer; transition:all 0.2s; flex:1;">Batal</button>
                <button id="modern-alert-confirm" style="padding:0.75rem 1.5rem; border-radius:50px; border:none; background:#243A5E; color:white; font-weight:600; cursor:pointer; transition:all 0.2s; flex:1;">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            @if(!request()->is('admin*'))
            if (window.innerWidth > 1024) {
                document.body.classList.toggle('sidebar-collapsed');
                return;
            }
            @endif

            sidebar.classList.toggle('show');
            if (sidebar.classList.contains('show')) {
                overlay.style.display = 'block';
            } else {
                overlay.style.display = 'none';
            }
        }

        function toggleProfileDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('user-profile-dropdown');
            if (!dropdown) return;

            // Close notification dropdown if open
            const notifDropdown = document.getElementById('notifications-dropdown');
            if (notifDropdown && notifDropdown.classList.contains('show')) {
                notifDropdown.classList.remove('show');
                setTimeout(() => { notifDropdown.style.display = 'none'; }, 200);
            }
            
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
                setTimeout(() => { dropdown.style.display = 'none'; }, 200);
            } else {
                dropdown.style.display = 'flex';
                // Trigger reflow for transition
                void dropdown.offsetWidth;
                dropdown.classList.add('show');
            }
        }

        function toggleNotificationsDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('notifications-dropdown');
            if (!dropdown) return;
            
            // Close profile dropdown if open
            const profileDropdown = document.getElementById('user-profile-dropdown');
            if (profileDropdown && profileDropdown.classList.contains('show')) {
                profileDropdown.classList.remove('show');
                setTimeout(() => { profileDropdown.style.display = 'none'; }, 200);
            }

            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
                setTimeout(() => { dropdown.style.display = 'none'; }, 200);
            } else {
                dropdown.style.display = 'flex';
                void dropdown.offsetWidth;
                dropdown.classList.add('show');
            }
        }

        function markNotificationAsRead(event, id) {
            event.stopPropagation();
            const item = event.currentTarget;
            if (item.classList.contains('read')) return;

            fetch(`/user/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    item.classList.remove('unread');
                    item.classList.add('read');
                    const dot = item.querySelector('.unread-dot');
                    if (dot) dot.remove();

                    // Update badge count
                    const badge = document.getElementById('notification-badge-el');
                    if (badge) {
                        let count = parseInt(badge.innerText.trim());
                        count = count - 1;
                        if (count <= 0) {
                            badge.remove();
                            // Also remove "Tandai Semua Dibaca" button if no unread
                            const btnAll = document.querySelector('[onclick="markAllNotificationsAsRead(event)"]');
                            if (btnAll) btnAll.remove();
                        } else {
                            badge.innerText = count;
                        }
                    }
                }
            });
        }

        function markAllNotificationsAsRead(event) {
            event.stopPropagation();
            fetch('/user/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update all items in dropdown
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        item.classList.add('read');
                        const dot = item.querySelector('.unread-dot');
                        if (dot) dot.remove();
                    });

                    // Remove badge
                    const badge = document.getElementById('notification-badge-el');
                    if (badge) badge.remove();

                    // Remove button
                    const btnAll = event.currentTarget;
                    if (btnAll) btnAll.remove();
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-profile-dropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                const isClickInside = dropdown.contains(event.target);
                if (!isClickInside) {
                    dropdown.classList.remove('show');
                    setTimeout(() => { dropdown.style.display = 'none'; }, 200);
                }
            }

            const notifDropdown = document.getElementById('notifications-dropdown');
            if (notifDropdown && notifDropdown.classList.contains('show')) {
                const isClickInside = notifDropdown.contains(event.target);
                const isBellClick = event.target.closest('.notification-bell');
                if (!isClickInside && !isBellClick) {
                    notifDropdown.classList.remove('show');
                    setTimeout(() => { notifDropdown.style.display = 'none'; }, 200);
                }
            }
        });

        window.showModernAlert = function(type, title, message, onConfirm = null) {
            const modal = document.getElementById('modern-alert-modal');
            const content = document.getElementById('modern-alert-content');
            const iconContainer = document.getElementById('modern-alert-icon-container');
            const icon = document.getElementById('modern-alert-icon');
            const titleEl = document.getElementById('modern-alert-title');
            const messageEl = document.getElementById('modern-alert-message');
            const btnCancel = document.getElementById('modern-alert-cancel');
            const btnConfirm = document.getElementById('modern-alert-confirm');

            const config = {
                success: { color: '#22C55E', bg: '#DCFCE7', icon: 'fa-check-circle' },
                error: { color: '#EF4444', bg: '#FEE2E2', icon: 'fa-times-circle' },
                warning: { color: '#F59E0B', bg: '#FEF3C7', icon: 'fa-exclamation-circle' },
                confirm: { color: '#EF4444', bg: '#FEE2E2', icon: 'fa-trash-alt' },
                buy: { color: '#3B82F6', bg: '#DBEAFE', icon: 'fa-shopping-cart' }
            };
            
            const cfg = config[type] || config.success;
            
            iconContainer.style.color = cfg.color;
            iconContainer.style.backgroundColor = cfg.bg;
            icon.className = 'fas ' + cfg.icon;
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            btnCancel.style.display = 'none';
            if (type === 'confirm') {
                btnConfirm.style.backgroundColor = '#EF4444';
                btnConfirm.textContent = 'Hapus';
            } else if (type === 'warning') {
                btnConfirm.style.backgroundColor = '#3B82F6';
                btnConfirm.textContent = 'Lanjut';
            } else if (type === 'buy') {
                btnConfirm.style.backgroundColor = '#243A5E';
                btnConfirm.textContent = 'Beli Sekarang';
            } else if (type === 'error') {
                btnConfirm.style.backgroundColor = '#EF4444';
                btnConfirm.textContent = 'Tutup';
            } else {
                btnConfirm.style.backgroundColor = '#243A5E';
                btnConfirm.textContent = 'Tutup';
            }
            
            const newBtnConfirm = btnConfirm.cloneNode(true);
            btnConfirm.parentNode.replaceChild(newBtnConfirm, btnConfirm);
            const newBtnCancel = btnCancel.cloneNode(true);
            btnCancel.parentNode.replaceChild(newBtnCancel, btnCancel);
            
            const closePopup = () => {
                modal.style.opacity = '0';
                content.style.transform = 'scale(0.9)';
                setTimeout(() => { modal.style.display = 'none'; }, 300);
            };

            if (type === 'confirm' || type === 'warning' || type === 'buy') {
                newBtnCancel.style.display = 'block';
                newBtnCancel.onclick = closePopup;
                newBtnConfirm.onclick = () => {
                    closePopup();
                    if(onConfirm) onConfirm();
                };
            } else {
                newBtnConfirm.onclick = () => {
                    closePopup();
                    if(onConfirm) onConfirm();
                };
            }

            modal.style.display = 'flex';
            void modal.offsetWidth;
            modal.style.opacity = '1';
            content.style.transform = 'scale(1)';
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Handle modern alerts on form submits based on data attributes
            document.querySelectorAll('form[data-confirm]').forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const message = form.getAttribute('data-confirm');
                    const type = form.getAttribute('data-type') || 'confirm';
                    const title = form.getAttribute('data-title') || 'Konfirmasi';
                    
                    showModernAlert(type, title, message, () => {
                        form.submit();
                    });
                });
            });

            @if(session('success'))
                showModernAlert('success', 'Berhasil', "{{ session('success') }}");
            @endif
            @if(session('error'))
                showModernAlert('error', 'Terjadi Kesalahan', "{{ session('error') }}");
            @endif
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.select-search').select2({
            placeholder: '-- Silakan Pilih Tryout --',
            allowClear: true,
            width: '100%'
        });
    });
    </script>
    @if(Auth::check() && !request()->is('admin*'))
        @if(config('midtrans.is_production'))
            <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        @else
            <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        @endif
    @endif
    @stack('scripts')
</body>
</html>
