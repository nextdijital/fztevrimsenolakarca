<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

/**
 * Güvenli PDO Veritabanı Bağlantısı (Singleton)
 */
function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('Veritabanı bağlantı hatası: ' . $e->getMessage());
            die('Veritabanı bağlantısı kurulamadı. Lütfen daha sonra tekrar deneyiniz.');
        }
    }
    return $pdo;
}

/**
 * XSS Koruması için HTML Çıktı Filtreleme
 */
function e(?string $string): string {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Site Ayarlarını Getir (Hafıza Önbellekli)
 */
function get_setting(string $key, ?string $default = ''): string {
    static $settingsCache = null;
    if ($settingsCache === null) {
        try {
            $stmt = get_db()->query("SELECT key_name, key_value FROM site_settings");
            $settingsCache = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Exception $e) {
            $settingsCache = [];
        }
    }
    return $settingsCache[$key] ?? (string)$default;
}

/**
 * Sayfa İçeriklerini Getir (pages_content tablosundan)
 */
function get_page_content(string $pageKey, string $sectionKey): ?array {
    static $pagesCache = [];
    $cacheKey = $pageKey . '_' . $sectionKey;
    if (!isset($pagesCache[$cacheKey])) {
        try {
            $stmt = get_db()->prepare("SELECT * FROM pages_content WHERE page_key = :pk AND section_key = :sk LIMIT 1");
            $stmt->execute([':pk' => $pageKey, ':sk' => $sectionKey]);
            $res = $stmt->fetch();
            $pagesCache[$cacheKey] = $res ?: null;
        } catch (Exception $e) {
            $pagesCache[$cacheKey] = null;
        }
    }
    return $pagesCache[$cacheKey];
}

/**
 * Tüm Aktif Hizmetleri Getir
 */
function get_all_services(bool $onlyActive = true): array {
    try {
        $sql = "SELECT * FROM services";
        if ($onlyActive) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY display_order ASC, id ASC";
        $stmt = get_db()->query($sql);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Tek Bir Hizmeti Slug İle Getir
 */
function get_service_by_slug(string $slug): ?array {
    try {
        $stmt = get_db()->prepare("SELECT * FROM services WHERE slug = :slug AND is_active = 1 LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Sıkça Sorulan Soruları Getir
 */
function get_all_faqs(bool $onlyActive = true): array {
    try {
        $sql = "SELECT * FROM faqs";
        if ($onlyActive) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY display_order ASC, id ASC";
        $stmt = get_db()->query($sql);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Tüm Aktif Blog Yazılarını Getir
 */
function get_all_blog_posts(bool $onlyActive = true, ?int $limit = null): array {
    try {
        $sql = "SELECT * FROM blog_posts";
        if ($onlyActive) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY published_at DESC, id DESC";
        if ($limit !== null && $limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = get_db()->query($sql);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Tek Bir Blog Yazısını Slug ile Getir
 */
function get_blog_post_by_slug(string $slug): ?array {
    try {
        $stmt = get_db()->prepare("SELECT * FROM blog_posts WHERE slug = :slug AND is_active = 1 LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Blog Okunma Sayısını Artır
 */
function increment_blog_views(int $id): void {
    try {
        $stmt = get_db()->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } catch (Exception $e) {
        // Sessizce geç
    }
}

/**
 * CSRF Token Oluştur ve Getir
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF Gizli Input Alanı
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * CSRF Token Doğrulama
 */
function validate_csrf(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * İstemci IP Adresini Al
 */
function get_client_ip(): string {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ipList[0]);
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';
}

/**
 * Rate Limiting (İstek Sınırlandırma / Brute Force Koruması)
 */
function check_rate_limit(string $action, string $ip, int $maxAttempts = 5, int $decayMinutes = 15): bool {
    try {
        $db = get_db();
        $cutoff = date('Y-m-d H:i:s', time() - ($decayMinutes * 60));
        
        $stmt = $db->prepare("SELECT COUNT(*) FROM rate_limits WHERE action_key = :act AND ip_address = :ip AND attempted_at > :cutoff");
        $stmt->execute([':act' => $action, ':ip' => $ip, ':cutoff' => $cutoff]);
        $count = (int)$stmt->fetchColumn();

        return $count < $maxAttempts;
    } catch (Exception $e) {
        return true;
    }
}

/**
 * Rate Limit Denemesi Kaydet
 */
function record_rate_limit_attempt(string $action, string $ip): void {
    try {
        $db = get_db();
        $stmt = $db->prepare("INSERT INTO rate_limits (action_key, ip_address, attempted_at) VALUES (:act, :ip, NOW())");
        $stmt->execute([':act' => $action, ':ip' => $ip]);
    } catch (Exception $e) {
        // Sessiz hata
    }
}

/**
 * Flash Bildirim Mesajı Ayarla
 */
function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

/**
 * Flash Bildirim Mesajını Oku ve Temizle
 */
function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Site İçi Güvenli URL Oluşturucu
 */
function url(string $path = ''): string {
    $path = ltrim($path, '/');
    return BASE_URL . ($path ? '/' . $path : '');
}

/**
 * Menü Aktifliği Kontrolü
 */
function is_active_nav(string $pattern): string {
    $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Doğrudan PHP dosyası çağrısı veya temiz URL kontrolü
    if ($pattern === 'home' && ($currentUri === '/' || str_ends_with($scriptName, 'index.php') && empty($_GET['slug']))) {
        return 'active';
    }
    if ($pattern === 'hakkimizda' && (str_contains($currentUri, 'hakkimizda') || str_ends_with($scriptName, 'hakkimizda.php'))) {
        return 'active';
    }
    if ($pattern === 'hizmetlerimiz' && (str_contains($currentUri, 'hizmet') || str_ends_with($scriptName, 'hizmetlerimiz.php') || str_ends_with($scriptName, 'hizmet-detay.php'))) {
        return 'active';
    }
    if ($pattern === 'sss' && (str_contains($currentUri, 'sikca-sorulan-sorular') || str_ends_with($scriptName, 'sss.php'))) {
        return 'active';
    }
    if ($pattern === 'blog' && (str_contains($currentUri, 'blog') || str_ends_with($scriptName, 'blog.php') || str_ends_with($scriptName, 'blog-detay.php'))) {
        return 'active';
    }
    if ($pattern === 'tarifler' && (str_contains($currentUri, 'tarifler') || str_ends_with($scriptName, 'tarifler.php'))) {
        return 'active';
    }
    if ($pattern === 'iletisim' && (str_contains($currentUri, 'iletisim') || str_ends_with($scriptName, 'iletisim.php'))) {
        return 'active';
    }
    return '';
}

/**
 * HTML İçeriği Düz Metne Çevirici (Panel Textarea için)
 * Kullanıcıların <p>, <strong> gibi etiketler görmesini engeller.
 */
function html_to_plain_text(?string $html): string {
    if ($html === null || $html === '') {
        return '';
    }
    // Paragraf ve blok sonlarını çift satırbaşına çevir
    $text = preg_replace('/<\/p>\s*<p[^>]*>/i', "\n\n", $html);
    $text = preg_replace('/<\/(p|div|h[1-6]|li)>/i', "\n\n", $text);
    $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
    $text = strip_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    return trim($text);
}

/**
 * Düz Metni Temiz ve Düzenli Paragraflara (HTML) Çevirici
 * Panel kullanıcılarının HTML bilmeden sadece Enter tuşuyla paragraf oluşturmasını sağlar.
 */
function plain_text_to_html(?string $text): string {
    if ($text === null || trim($text) === '') {
        return '';
    }
    $text = trim($text);
    // Eğer kullanıcı zaten HTML yazmamışsa paragraflara böl
    if (strpos($text, '<p>') === false && strpos($text, '<div>') === false) {
        $paragraphs = preg_split('/\r\n\r\n|\n\n/', $text);
        $html = '';
        foreach ($paragraphs as $p) {
            $p = trim($p);
            if ($p !== '') {
                $html .= '<p>' . nl2br(htmlspecialchars($p, ENT_QUOTES, 'UTF-8')) . '</p>';
            }
        }
        return $html;
    }
    return $text;
}

/**
 * Güvenli Görsel Yükleme Fonksiyonu (Panel için)
 */
function handle_image_upload(array|string $fileInfo, string $subfolder = 'uploads'): ?string {
    if (is_string($fileInfo)) {
        if (!isset($_FILES[$fileInfo])) {
            return null;
        }
        $fileInfo = $_FILES[$fileInfo];
    }
    if (empty($fileInfo['name']) || empty($fileInfo['tmp_name']) || ($fileInfo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts, true)) {
        return null;
    }

    // MIME type kontrolü
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $fileInfo['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowedMimes, true)) {
        return null;
    }

    $targetDir = __DIR__ . '/../assets/img/' . trim($subfolder, '/');
    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0755, true);
    }

    // Her zaman optimize edilmiş .webp olarak kaydet
    $filename = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.webp';
    $targetPath = $targetDir . '/' . $filename;

    // Otomatik WebP çevirisi ve boyut optimizasyonu
    if (extension_loaded('gd') && function_exists('imagewebp')) {
        $img = null;
        switch ($mime) {
            case 'image/jpeg':
                $img = @imagecreatefromjpeg($fileInfo['tmp_name']);
                break;
            case 'image/png':
                $img = @imagecreatefrompng($fileInfo['tmp_name']);
                break;
            case 'image/webp':
                $img = @imagecreatefromwebp($fileInfo['tmp_name']);
                break;
        }

        if ($img) {
            $srcWidth = imagesx($img);
            $srcHeight = imagesy($img);
            $maxWidth = 1920;

            if ($srcWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) round(($srcHeight * $newWidth) / $srcWidth);
                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
                imagedestroy($img);
                $img = $resized;
            } else {
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
            }

            if (imagewebp($img, $targetPath, 85)) {
                imagedestroy($img);
                @unlink($fileInfo['tmp_name']);
                return 'assets/img/' . trim($subfolder, '/') . '/' . $filename;
            }
            imagedestroy($img);
        }
    }

    // GD yoksa veya çeviri başarısız olursa orijinal dosyayı taşı
    $fallbackFilename = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
    $fallbackPath = $targetDir . '/' . $fallbackFilename;
    if (move_uploaded_file($fileInfo['tmp_name'], $fallbackPath)) {
        return 'assets/img/' . trim($subfolder, '/') . '/' . $fallbackFilename;
    }

    return null;
}

/**
 * Sitedeki Tüm Sayfaların Kategorize Listesi
 * Buton ve sayfa linki seçimlerinde otomatik dropdown oluşturur.
 */
function get_categorized_site_links(): array {
    $db = get_db();
    $categories = [];

    // 1. Ana Sayfalar & Kurumsal
    $categories['Ana Sayfalar'] = [
        '/' => 'Ana Sayfa',
        '/hakkimizda' => 'Hakkımızda & Yaklaşımımız',
        '/hizmetlerimiz' => 'Tüm Hizmetlerimiz',
        '/blog' => 'Blog & Sağlık Rehberi',
        '/tarifler' => 'Klinik Sağlıklı Tarifler',
        '/sss' => 'Sıkça Sorulan Sorular (SSS)',
        '/iletisim' => 'İletişim & Konum'
    ];

    // 2. Uzmanlık Hizmetleri (Dinamik DB)
    try {
        $services = $db->query("SELECT title, slug FROM services WHERE is_active = 1 ORDER BY display_order ASC")->fetchAll();
        if (!empty($services)) {
            $categories['Uzmanlık Alanlarımız'] = [];
            foreach ($services as $s) {
                $categories['Uzmanlık Alanlarımız']['/hizmetler/' . $s['slug']] = $s['title'];
            }
        }
    } catch (Exception $e) {}

    // 3. Blog Makaleleri (Dinamik DB)
    try {
        $posts = $db->query("SELECT title, slug FROM blog_posts WHERE is_active = 1 ORDER BY id DESC LIMIT 20")->fetchAll();
        if (!empty($posts)) {
            $categories['Blog Yazıları'] = [];
            foreach ($posts as $p) {
                $categories['Blog Yazıları']['/blog/' . $p['slug']] = $p['title'];
            }
        }
    } catch (Exception $e) {}

    // 4. Hukuki & Sözleşmeler
    $categories['Yasal & KVKK Sayfaları'] = [
        '/kvkk' => 'KVKK Aydınlatma Metni',
        '/cerez-politikasi' => 'Çerez Politikası',
        '/yasal-uyari' => 'Yasal Uyarı & Tıbbi Teşhis Reddi'
    ];

    // 5. Doğrudan İletişim Kanalları
    $waRaw = get_setting('whatsapp_raw');
    $phoneRaw = get_setting('phone_raw');
    $phone = get_setting('phone');
    $categories['Hızlı İletişim Kanalları'] = [
        'https://wa.me/' . $waRaw => 'WhatsApp Danışma & Randevu Hattı',
        'tel:' . $phoneRaw => 'Klinik Telefon Araması (' . $phone . ')'
    ];

    return $categories;
}

/**
 * Kategorize Sayfa ve Buton Linki Seçim Dropdown'ı
 */
function render_page_selector_dropdown(string $targetInputId, ?string $currentValue = ''): string {
    $categories = get_categorized_site_links();
    $html = '<div class="page-selector-box" style="margin-bottom: 0.5rem;">';
    $html .= '<select class="form-control" style="font-size: 0.82rem; background-color: #FAFBFB; border-color: var(--border-subtle);" onchange="if(this.value){ document.getElementById(\'' . e($targetInputId) . '\').value = this.value; }">';
    $html .= '<option value="">-- Sitedeki Bir Sayfayı Seçerek Otomatik Doldurun --</option>';
    
    foreach ($categories as $catTitle => $links) {
        $html .= '<optgroup label="' . e($catTitle) . '">';
        foreach ($links as $url => $label) {
            $selected = ($currentValue === $url) ? ' selected' : '';
            $html .= '<option value="' . e($url) . '"' . $selected . '>' . e($label) . ' &rarr; ' . e($url) . '</option>';
        }
        $html .= '</optgroup>';
    }
    $html .= '</select>';
    $html .= '</div>';
    return $html;
}

/**
 * Yönetim Paneli Görsel Yükleme, Önizleme ve Silme Bileşeni
 */
function render_image_uploader(string $fieldName, ?string $currentImageUrl, string $previewId, string $label = 'Görsel Yükle'): string {
    $hasImage = !empty($currentImageUrl) && file_exists(__DIR__ . '/../' . ltrim($currentImageUrl, '/'));
    $displayUrl = $hasImage ? url($currentImageUrl) : '';

    $html = '<div class="admin-image-uploader-card" style="border: 1px dashed #BAC5C9; border-radius: 10px; padding: 1.25rem; background-color: #FAFBFB; margin-bottom: 1.5rem;">';
    $html .= '<label class="form-label" style="margin-bottom: 0.75rem; font-weight: 700;">' . e($label) . '</label>';

    $html .= '<div style="display: flex; gap: 1.25rem; align-items: flex-start; flex-wrap: wrap;">';
    $html .= '<div style="width: 140px; height: 100px; border-radius: 8px; border: 1px solid var(--border-subtle); background-color: #FFFFFF; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative;">';
    
    if ($hasImage) {
        $html .= '<img id="' . e($previewId) . '" src="' . e($displayUrl) . '" alt="Önizleme" style="width: 100%; height: 100%; object-fit: cover;">';
    } else {
        $html .= '<img id="' . e($previewId) . '" src="" alt="Önizleme" style="width: 100%; height: 100%; object-fit: cover; display: none;">';
        $html .= '<span id="' . e($previewId) . '_placeholder" style="font-size: 0.75rem; color: var(--text-muted); text-align: center; padding: 0.5rem;">Görsel Yok</span>';
    }
    $html .= '</div>';

    $html .= '<div style="flex-grow: 1; min-width: 200px;">';
    $html .= '<input type="file" name="' . e($fieldName) . '" id="' . e($fieldName) . '" accept="image/jpeg,image/png,image/webp" class="form-control" style="font-size: 0.85rem; padding: 0.55rem; background: #fff;" onchange="previewImageFile(this, \'' . e($previewId) . '\')">';
    $html .= '<small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.4rem;">Desteklenen Formatlar: JPG, PNG, WebP (Maksimum 8 MB). Seçtiğiniz anda anında solda önizlenir.</small>';

    if ($hasImage) {
        $html .= '<div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.75rem;">';
        $html .= '<label style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: #C0392B; cursor: pointer; font-weight: 700;">';
        $html .= '<input type="checkbox" name="delete_' . e($fieldName) . '" value="1"> Mevcut Görseli Sil';
        $html .= '</label>';
        $html .= '<span style="font-size: 0.72rem; color: var(--text-muted);">(Mevcut dosya: ' . e(basename($currentImageUrl)) . ')</span>';
        $html .= '</div>';
    }

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 * Kullanıcı adını ilk harfi büyük olacak şekilde biçimlendirir
 */
function format_user_display_name(string $name): string {
    $trimmed = trim($name);
    if ($trimmed === '') {
        return 'Yönetici';
    }
    if (strcasecmp($trimmed, 'suleyman') === 0 || strcasecmp($trimmed, 'süleyman') === 0) {
        return 'Süleyman';
    }
    return mb_convert_case($trimmed, MB_CASE_TITLE, 'UTF-8');
}

