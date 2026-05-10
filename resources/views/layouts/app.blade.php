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
</style>
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
                <!-- Admin Navigation -->
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.soal.index') }}" class="nav-item {{ request()->routeIs('admin.soal.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Kelola Soal
                </a>
                <a href="{{ route('admin.kategori-bank-soal.index') }}" class="nav-item {{ request()->routeIs('admin.kategori-bank-soal.*') || request()->routeIs('admin.bank-soal.*') ? 'active' : '' }}">
                    <i class="fas fa-database"></i> Bank Soal
                </a>
                <a href="{{ route('admin.paket.index') }}" class="nav-item {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Kelola Paket
                </a>
                <a href="{{ route('admin.tryout.index') }}" class="nav-item {{ request()->routeIs('admin.tryout.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Kelola Tryout
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
            
            <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;" data-confirm="Yakin ingin logout?" data-type="warning" data-title="Logout">
                @csrf
                <button type="submit" class="nav-item" style="width:100%; border:none; background:none; cursor:pointer; text-align:left; color: #EF4444;">
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
                <div class="user-profile">
                    <span>{{ request()->is('admin/*') ? 'Admin RAZAKA' : 'User' }}</span>
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-user"></i>
                    </div>
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
            sidebar.classList.toggle('show');
            if (sidebar.classList.contains('show')) {
                overlay.style.display = 'block';
            } else {
                overlay.style.display = 'none';
            }
        }

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
    @stack('scripts')
</body>
</html>
