<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$currentFile = basename($_SERVER['PHP_SELF']);
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminPageTitle ?? 'Yönetim Paneli') ?> | Fzt. Evrim Şenol Akarca</title>
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
    <style>
        /* ==============================================================================
           YÖNETİM PANELİ SOL MENÜ (SIDEBAR) & PANEL DÜZENİ
           Tüm yazılar düz (eğik / italic olmayan), menü sol tarafta
        ============================================================================== */
        *, *::before, *::after {
            font-style: normal !important;
        }

        body.xpanel-body {
            margin: 0;
            padding: 0;
            background-color: #F6F8F9;
            color: #1A2024;
            font-family: var(--font-main);
            overflow-x: hidden;
        }

        .xpanel-body em,
        .xpanel-body i,
        .xpanel-body cite {
            font-style: normal !important;
            font-family: var(--font-main) !important;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* SOL SIDEBAR MENÜ */
        .admin-sidebar {
            width: 270px;
            min-width: 270px;
            background-color: #141A1C;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 1000;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            transition: transform 0.3s ease, left 0.3s ease;
        }

        .sidebar-brand {
            padding: 2.25rem 1.5rem 1.75rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .sidebar-logo-img {
            max-width: 195px;
            width: 100%;
            height: auto;
            max-height: 56px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: 0.98;
            display: block;
        }

        /* SIDEBAR NAVİGASYON */
        .sidebar-nav {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem 0.9rem;
        }

        .sidebar-section-title {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #6C7C82;
            padding: 0 0.6rem 0.6rem 0.6rem;
            margin-top: 0.25rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.72rem 0.95rem;
            color: #A4B2B6;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sidebar-menu a .link-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .sidebar-menu a svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            stroke: currentColor;
            opacity: 0.85;
            transition: transform 0.2s;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.06);
            color: #FFFFFF;
        }

        .sidebar-menu a:hover svg {
            opacity: 1;
            transform: translateX(2px);
        }

        .sidebar-menu a.active {
            background-color: var(--bg-card-sage);
            color: #FFFFFF;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(79, 127, 136, 0.4);
        }

        .sidebar-menu a.active svg {
            opacity: 1;
        }

        .sidebar-footer {
            padding: 1.25rem 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.95rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-footer .link-site {
            color: var(--bg-card-sage);
        }

        .sidebar-footer .link-site:hover {
            background: rgba(79, 127, 136, 0.15);
            color: #FFFFFF;
        }

        .sidebar-footer .link-logout {
            color: #E57373;
        }

        .sidebar-footer .link-logout:hover {
            background: rgba(229, 115, 115, 0.12);
            color: #FF8A80;
        }

        /* SAĞ ANA İÇERİK */
        .admin-main {
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-topbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--border-subtle);
            padding: 1rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 1px 4px rgba(0,0,0,0.02);
        }

        .admin-topbar .page-title-crumb {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-topbar .page-title-crumb span {
            color: var(--text-muted);
            font-weight: 400;
            font-size: 0.85rem;
        }

        .admin-topbar .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-content {
            flex-grow: 1;
            padding: 2.5rem;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .admin-card {
            background-color: #FFFFFF;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-main);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .admin-footer {
            background-color: #FFFFFF;
            border-top: 1px solid var(--border-subtle);
            padding: 1.25rem 2.5rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: center;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.92rem;
            min-width: 600px;
        }

        .admin-table th, .admin-table td {
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        .admin-table th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            background-color: #FAFBFB;
            font-weight: 700;
        }

        .badge-status {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.08em;
            border-radius: 4px;
        }
        .badge-new { background-color: #FDE8E8; color: #9B1C1C; }
        .badge-reviewed { background-color: #E1EFFE; color: #1E429F; }
        .badge-completed { background-color: #EDF2F7; color: #4A5568; }

        .sidebar-toggle-btn {
            display: none;
            background: none;
            border: 1px solid var(--border-subtle);
            border-radius: 6px;
            padding: 0.45rem 0.75rem;
            cursor: pointer;
            color: var(--text-dark);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            div[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
        }

        @media (max-width: 1200px) {
            .dual-cards-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
            }
            .admin-sidebar.show {
                left: 0;
                box-shadow: 4px 0 24px rgba(0,0,0,0.3);
            }
            .sidebar-backdrop.show {
                display: block;
            }
            .sidebar-toggle-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
            }
            .admin-content {
                padding: 1.5rem 1rem;
            }
            .admin-topbar {
                padding: 1rem 1.25rem;
            }
            .dual-cards-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    <script>
        function previewImageFile(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById(previewId);
                    var ph = document.getElementById(previewId + '_placeholder');
                    if (img) {
                        img.src = e.target.result;
                        img.style.display = 'block';
                    }
                    if (ph) {
                        ph.style.display = 'none';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>
<body class="xpanel-body">

<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="document.getElementById('adminSidebar').classList.remove('show'); this.classList.remove('show');"></div>

<div class="admin-layout">
    <!-- SOL TARAFTA DİKEY MENÜ / SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <a href="<?= e(url('xpanel/index.php')) ?>" style="display: block; text-decoration: none;">
                <img src="<?= e(url('assets/img/logo-text.png')) ?>" alt="Fzt. Evrim Şenol Akarca" class="sidebar-logo-img">
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">YÖNETİM MENÜSÜ</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= e(url('xpanel/index.php')) ?>" class="<?= $currentFile === 'index.php' ? 'active' : '' ?>">
                        <span class="link-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            <span>Özet / Dashboard</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= e(url('xpanel/services.php')) ?>" class="<?= $currentFile === 'services.php' ? 'active' : '' ?>">
                        <span class="link-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                            <span>Hizmetler</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= e(url('xpanel/blog.php')) ?>" class="<?= $currentFile === 'blog.php' ? 'active' : '' ?>">
                        <span class="link-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                            <span>Blog Yazıları</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= e(url('xpanel/pages.php')) ?>" class="<?= $currentFile === 'pages.php' ? 'active' : '' ?>">
                        <span class="link-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                            <span>Sayfa İçerikleri</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= e(url('xpanel/faqs.php')) ?>" class="<?= $currentFile === 'faqs.php' ? 'active' : '' ?>">
                        <span class="link-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            <span>Soru & Cevap (SSS)</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= e(url('xpanel/users.php')) ?>" class="<?= $currentFile === 'users.php' ? 'active' : '' ?>">
                        <span class="link-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Kullanıcılar</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= e(url('xpanel/settings.php')) ?>" class="<?= $currentFile === 'settings.php' ? 'active' : '' ?>">
                        <span class="link-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            <span>Site Ayarları</span>
                        </span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= e(url()) ?>" target="_blank" rel="noopener noreferrer" class="link-site">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                <span>Siteyi Gör &rarr;</span>
            </a>
            <a href="<?= e(url('xpanel/logout.php')) ?>" class="link-logout" onclick="return confirm('Çıkış yapmak istediğinize emin misiniz?');">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                <span>Çıkış Yap</span>
            </a>
        </div>
    </aside>

    <!-- SAĞ TARAF ANA GÖVDE -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button type="button" class="sidebar-toggle-btn" onclick="document.getElementById('adminSidebar').classList.toggle('show'); document.getElementById('sidebarBackdrop').classList.toggle('show');">
                    &#9776; Menü
                </button>
                <div class="page-title-crumb">
                    <?= e($adminPageTitle ?? '') ?>
                </div>
            </div>

            <div class="topbar-actions">
                <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                    Kullanıcı: <strong style="color: var(--text-dark);"><?= e($adminUsername) ?></strong>
                </span>
                <a href="<?= e(url()) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.75rem;">
                    Siteyi Önizle &rarr;
                </a>
            </div>
        </header>

        <main class="admin-content">
            <div class="admin-container">
                <?php if ($flash): ?>
                <div class="form-alert form-alert-<?= $flash['type'] === 'danger' ? 'danger' : 'success' ?>" style="margin-bottom: 2rem;">
                    <?= e($flash['message']) ?>
                </div>
                <?php endif; ?>
