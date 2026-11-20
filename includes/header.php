<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';

$siteTitle = get_setting('site_title', 'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi');
$pageTitleFull = isset($pageTitle) ? e($pageTitle) . ' | ' . e($siteTitle) : e($siteTitle);
$pageDescription = isset($pageDescription) ? e($pageDescription) : e(get_setting('meta_description'));
$canonicalUrl = isset($pageCanonical) ? e($pageCanonical) : e(url(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH)));
$phone = get_setting('phone', '0532 470 04 80');
$phoneRaw = get_setting('phone_raw', '+905324700480');
$whatsappRaw = get_setting('whatsapp_raw', '905324700480');
$address = get_setting('address', 'Hurmalı Mah. Kurtuluş Cad. No:39 Central Plaza Kat:8/81 Seyhan / Adana');
$email = get_setting('email', 'evrimmsenol@yahoo.com.tr');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title><?= $pageTitleFull ?></title>
    <meta name="description" content="<?= $pageDescription ?>">
    <link rel="canonical" href="<?= $canonicalUrl ?>">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Fzt. Evrim Şenol Akarca">

    <!-- Open Graph (Facebook / WhatsApp / LinkedIn) -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:title" content="<?= $pageTitleFull ?>">
    <meta property="og:description" content="<?= $pageDescription ?>">
    <meta property="og:url" content="<?= $canonicalUrl ?>">
    <meta property="og:site_name" content="<?= e($siteTitle) ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitleFull ?>">
    <meta name="twitter:description" content="<?= $pageDescription ?>">

    <!-- Schema.org JSON-LD (MedicalBusiness & PhysiotherapyClinic) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": ["MedicalBusiness", "PhysiotherapyClinic"],
      "name": "<?= e($siteTitle) ?>",
      "legalName": "<?= e($siteTitle) ?>",
      "description": "<?= e($pageDescription) ?>",
      "url": "<?= e(url()) ?>",
      "telephone": "<?= e($phoneRaw) ?>",
      "email": "<?= e($email) ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Hurmalı Mah. Kurtuluş Cad. No:39 Central Plaza Kat:8/81",
        "addressLocality": "Seyhan",
        "addressRegion": "Adana",
        "postalCode": "01060",
        "addressCountry": "TR"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 36.9944,
        "longitude": 35.3182
      },
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
          "opens": "09:00",
          "closes": "18:30"
        }
      ],
      "medicalSpecialty": "Physiotherapy",
      "priceRange": "$$"
    }
    </script>

    <!-- Google Fonts: Urbanist -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <!-- CSS Stilleri -->
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>">
</head>
<?php
$currentPageSlug = pathinfo($_SERVER['SCRIPT_NAME'] ?? '', PATHINFO_FILENAME);
$pageBodyClass = !empty($bodyClass) ? $bodyClass : ('page-' . $currentPageSlug);
?>
<body class="<?= e($pageBodyClass) ?>">

<!-- Site Başlığı & Navigasyon -->
<header class="site-header">
    <div class="container container-wide">
        <div class="header-inner">
            <!-- Sol: Logonun Yazı Kısmı (En Solda) -->
            <div class="brand-typography">
                <a href="<?= e(url()) ?>" style="display: flex; align-items: center;">
                    <img src="<?= e(url('assets/img/logo-text.png')) ?>" alt="<?= e($siteTitle) ?>" class="header-logo-img">
                </a>
            </div>

            <!-- Orta / Sağ: Navigasyon Menüsü -->
            <nav class="main-nav">
                <ul>
                    <li><a href="<?= e(url()) ?>" class="nav-link <?= is_active_nav('home') ?>">Ana Sayfa</a></li>
                    <li><a href="<?= e(url('hakkimizda')) ?>" class="nav-link <?= is_active_nav('hakkimizda') ?>">Hakkımızda</a></li>
                    <li><a href="<?= e(url('hizmetlerimiz')) ?>" class="nav-link <?= is_active_nav('hizmetlerimiz') ?>">Hizmetlerimiz</a></li>
                    <li><a href="<?= e(url('blog')) ?>" class="nav-link <?= is_active_nav('blog') ?>">Blog</a></li>
                    <li><a href="<?= e(url('tarifler')) ?>" class="nav-link <?= is_active_nav('tarifler') ?>">Tarifler</a></li>
                    <li><a href="<?= e(url('sikca-sorulan-sorular')) ?>" class="nav-link <?= is_active_nav('sss') ?>">SSS</a></li>
                    <li><a href="<?= e(url('iletisim')) ?>" class="nav-link <?= is_active_nav('iletisim') ?>">İletişim</a></li>
                </ul>
            </nav>

            <!-- En Sağ: İletişim / Randevu CTA (Mobilde Sadece Menü Butonu Gösterilir) -->
            <div class="header-cta">
                <a href="https://wa.me/<?= e($whatsappRaw) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline header-cta-btn" style="padding: 0.65rem 1.25rem; font-size: 0.75rem;">Randevu & Danışma</a>
                <button type="button" class="mobile-toggle" id="mobileMenuOpenBtn" aria-label="Menüyü Aç">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- ==============================================================================
     TAM EKRAN MOBİL MENÜ MODAL / OVERLAY (Mobilde tıklandığında açılır, logo ve menü yer alır)
============================================================================== -->
<div id="fullScreenMobileNav" class="mobile-nav-fullscreen" aria-hidden="true">
    <div class="mobile-nav-fullscreen-inner">
        <!-- Üst Bar: Sol Logo, Sağ Kapat Butonu -->
        <div class="mobile-nav-header">
            <a href="<?= e(url()) ?>" class="mobile-nav-logo" onclick="closeFullScreenMobileNav()">
                <img src="<?= e(url('assets/img/logo-text.png')) ?>" alt="<?= e($siteTitle) ?>">
            </a>
            <button type="button" class="mobile-nav-close" id="mobileMenuCloseBtn" aria-label="Menüyü Kapat" onclick="closeFullScreenMobileNav()">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <!-- Menü Linkleri -->
        <div class="mobile-nav-links-wrap">
            <ul class="mobile-nav-links">
                <li><a href="<?= e(url()) ?>" class="<?= is_active_nav('home') ?>" onclick="closeFullScreenMobileNav()">Ana Sayfa</a></li>
                <li><a href="<?= e(url('hakkimizda')) ?>" class="<?= is_active_nav('hakkimizda') ?>" onclick="closeFullScreenMobileNav()">Hakkımızda</a></li>
                <li><a href="<?= e(url('hizmetlerimiz')) ?>" class="<?= is_active_nav('hizmetlerimiz') ?>" onclick="closeFullScreenMobileNav()">Hizmetlerimiz</a></li>
                <li><a href="<?= e(url('blog')) ?>" class="<?= is_active_nav('blog') ?>" onclick="closeFullScreenMobileNav()">Blog & Sağlık Rehberi</a></li>
                <li><a href="<?= e(url('tarifler')) ?>" class="<?= is_active_nav('tarifler') ?>" onclick="closeFullScreenMobileNav()">Sağlıklı Tarifler</a></li>
                <li><a href="<?= e(url('sikca-sorulan-sorular')) ?>" class="<?= is_active_nav('sss') ?>" onclick="closeFullScreenMobileNav()">Sıkça Sorulan Sorular</a></li>
                <li><a href="<?= e(url('iletisim')) ?>" class="<?= is_active_nav('iletisim') ?>" onclick="closeFullScreenMobileNav()">İletişim</a></li>
            </ul>
        </div>

        <!-- Alt Bölüm: WhatsApp Randevu ve Adres -->
        <div class="mobile-nav-bottom">
            <a href="https://wa.me/<?= e($whatsappRaw) ?>?text=<?= rawurlencode('Merhaba Fzt. Evrim Şenol Akarca, randevu ve bilgi almak istiyorum.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="width: 100%; text-align: center; justify-content: center; padding: 0.95rem 1rem; font-size: 0.88rem;">
                WhatsApp ile Randevu & Danışma &rarr;
            </a>
            <div class="mobile-nav-meta">
                <span>Central Plaza Kat:8 No:81 &bull; Seyhan / Adana</span>
            </div>
        </div>
    </div>
</div>
