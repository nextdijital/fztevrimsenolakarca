<?php
declare(strict_types=1);

$adminPageTitle = 'Sayfa İçerikleri Düzenleme';
require_once __DIR__ . '/includes/auth.php';

$db = get_db();
$selectedPage = $_GET['page'] ?? 'home';
$allowedPages = ['home', 'about', 'legal'];

if (!in_array($selectedPage, $allowedPages, true)) {
    $selectedPage = 'home';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) başarısız oldu.');
        header('Location: ' . url('xpanel/pages.php?page=' . $selectedPage));
        exit;
    }

    $existingSectionsStmt = $db->prepare("SELECT section_key, extra_data FROM pages_content WHERE page_key = :pk");
    $existingSectionsStmt->execute([':pk' => $selectedPage]);
    $existingMap = [];
    foreach ($existingSectionsStmt->fetchAll() as $row) {
        $existingMap[$row['section_key']] = !empty($row['extra_data']) ? json_decode($row['extra_data'], true) : [];
    }

    $sections = $_POST['sections'] ?? [];
    if (is_array($sections)) {
        $updateStmt = $db->prepare("UPDATE pages_content SET 
            title = :title, 
            subtitle = :subtitle, 
            content = :content, 
            extra_data = :extra_data 
            WHERE page_key = :pk AND section_key = :sk");

        foreach ($sections as $secKey => $data) {
            $currExtra = $existingMap[$secKey] ?? [];
            $extra = null;

            if ($secKey === 'hero') {
                $uploadedHero = handle_image_upload('hero_image');
                $heroImg = $currExtra['image_url'] ?? 'assets/img/hero-banner.jpg';
                if (!empty($_POST['delete_hero_image'])) {
                    $heroImg = 'assets/img/hero-banner.jpg';
                } elseif ($uploadedHero) {
                    $heroImg = $uploadedHero;
                }
                $extra = json_encode([
                    'image_url' => $heroImg
                ], JSON_UNESCAPED_UNICODE);
            } elseif ($secKey === 'approach') {
                $extra = json_encode([
                    'btn1_text' => trim((string)($_POST['approach_btn1_text'] ?? ($data['btn1_text'] ?? ''))),
                    'btn1_url' => trim((string)($_POST['approach_btn1_url'] ?? ($data['btn1_url'] ?? ''))),
                    'btn2_text' => trim((string)($_POST['approach_btn2_text'] ?? ($data['btn2_text'] ?? ''))),
                    'btn2_url' => trim((string)($_POST['approach_btn2_url'] ?? ($data['btn2_url'] ?? '')))
                ], JSON_UNESCAPED_UNICODE);
            } elseif (!empty($data['extra_json'])) {
                $extra = $data['extra_json'];
            } elseif (isset($data['vision']) || isset($data['mission'])) {
                $extra = json_encode([
                    'vision' => $data['vision'] ?? '',
                    'mission' => $data['mission'] ?? ''
                ], JSON_UNESCAPED_UNICODE);
            }

            $rawContent = trim((string)($data['content'] ?? ''));
            if ($selectedPage === 'home') {
                $savedContent = trim(strip_tags(html_entity_decode($rawContent, ENT_QUOTES, 'UTF-8')));
            } else {
                $savedContent = plain_text_to_html($rawContent);
            }

            $updateStmt->execute([
                ':title' => trim((string)($data['title'] ?? '')),
                ':subtitle' => trim((string)($data['subtitle'] ?? '')),
                ':content' => $savedContent,
                ':extra_data' => $extra,
                ':pk' => $selectedPage,
                ':sk' => $secKey
            ]);
        }

        set_flash('success', 'Sayfa içerikleri ve bağlantıları başarıyla kaydedildi.');
        header('Location: ' . url('xpanel/pages.php?page=' . $selectedPage));
        exit;
    }
}

// Seçili sayfanın bölümlerini çek
$stmt = $db->prepare("SELECT * FROM pages_content WHERE page_key = :pk ORDER BY id ASC");
$stmt->execute([':pk' => $selectedPage]);
$pageSections = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div style="margin-bottom: 2.5rem;">
    <span class="badge-tag">İçerik Yönetimi</span>
    <h1 style="font-size: 2.2rem; font-weight: 700;">Sayfa Metinlerini Düzenle</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">
        Sitenin ön yüzündeki tüm başlıkları, manifestoları ve yasal politikaları doğrudan düzenleyin.
    </p>

    <!-- Sayfa Seçim Sekmeleri -->
    <div style="display: flex; gap: 1rem; margin-top: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 1rem;">
        <a href="<?= e(url('xpanel/pages.php?page=home')) ?>" class="btn <?= $selectedPage === 'home' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 0.8rem; padding: 0.6rem 1.25rem;">
            Ana Sayfa Bölümleri
        </a>
        <a href="<?= e(url('xpanel/pages.php?page=about')) ?>" class="btn <?= $selectedPage === 'about' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 0.8rem; padding: 0.6rem 1.25rem;">
            Hakkımızda & Biyografi
        </a>
        <a href="<?= e(url('xpanel/pages.php?page=legal')) ?>" class="btn <?= $selectedPage === 'legal' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 0.8rem; padding: 0.6rem 1.25rem;">
            Hukuki Sayfalar (KVKK & Çerez)
        </a>
    </div>
</div>

<form method="POST" action="" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <?php foreach ($pageSections as $sec): 
        $extraData = !empty($sec['extra_data']) ? json_decode($sec['extra_data'], true) : [];
    ?>
    <div class="admin-card">
        <span class="badge-tag">Bölüm: <?= e($sec['section_key']) ?></span>
        <h2 style="font-size: 1.4rem; margin-bottom: 1.25rem;"><?= e($sec['title'] ?: $sec['section_key']) ?></h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Başlık</label>
                <input type="text" name="sections[<?= e($sec['section_key']) ?>][title]" class="form-control" value="<?= e($sec['title']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Üst Rozet / Alt Başlık</label>
                <input type="text" name="sections[<?= e($sec['section_key']) ?>][subtitle]" class="form-control" value="<?= e($sec['subtitle']) ?>">
            </div>
        </div>

        <?php if ($sec['section_key'] === 'hero'): ?>
        <!-- Hero Banner Arka Plan Görseli (Önizlemeli Yükle / Sil) -->
        <?= render_image_uploader('hero_image', $extraData['image_url'] ?? 'assets/img/hero-banner.jpg', 'heroBannerPreview', 'Hero Banner Arka Plan Görseli (Büyük Editorial Fotoğraf)') ?>
        <?php endif; ?>

        <?php if ($sec['section_key'] === 'approach'): ?>
        <!-- Çift Kart Buton Linkleri ve Sayfa Seçici -->
        <div style="border-top: 1px solid var(--border-subtle); padding-top: 1.25rem; margin-top: 1.25rem; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.05rem; margin-bottom: 1rem; color: var(--text-dark);">Yaklaşım Kartları Buton Bağlantıları & Otomatik Kategori Seçimi</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Sol Kart Buton Metni</label>
                    <input type="text" name="approach_btn1_text" class="form-control" value="<?= e($extraData['btn1_text'] ?? 'Yaklaşımımızı İnceleyin') ?>">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Sol Kart Bağlantısı (Kategorili Sayfa Seçimi)</label>
                    <?= render_page_selector_dropdown('approach_btn1_url', $extraData['btn1_url'] ?? url('hakkimizda'), 'Sol Kart Bağlantısı') ?>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Sağ Kart Buton Metni</label>
                    <input type="text" name="approach_btn2_text" class="form-control" value="<?= e($extraData['btn2_text'] ?? "Biofeedback'i Keşfedin") ?>">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Sağ Kart Bağlantısı (Kategorili Sayfa Seçimi)</label>
                    <?= render_page_selector_dropdown('approach_btn2_url', $extraData['btn2_url'] ?? url('hizmetler/pelvik-taban-fizyoterapisi'), 'Sağ Kart Bağlantısı') ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label class="form-label">İçerik Metni</label>
            <textarea name="sections[<?= e($sec['section_key']) ?>][content]" class="form-control" rows="8"><?= e(html_to_plain_text($sec['content'])) ?></textarea>
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.35rem; display: block;">
                Paragraflarınızı ayırmak için Enter tuşuna basmanız yeterlidir. HTML kodu gerekmez, otomatik düzenlenir.
            </small>
        </div>

        <?php if ($sec['section_key'] === 'vision_mission' || $sec['page_key'] === 'about'): ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem; border-top: 1px solid var(--border-subtle); padding-top: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Vizyon Metni</label>
                <textarea name="sections[<?= e($sec['section_key']) ?>][vision]" class="form-control" rows="3"><?= e($extraData['vision'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Misyon Metni</label>
                <textarea name="sections[<?= e($sec['section_key']) ?>][mission]" class="form-control" rows="3"><?= e($extraData['mission'] ?? '') ?></textarea>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <div style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 0.9rem;">
            Değişiklikleri Kaydet &rarr;
        </button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
