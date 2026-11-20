<?php
declare(strict_types=1);

$adminPageTitle = 'Hizmetler ve Uzmanlık Alanları Yönetimi';
require_once __DIR__ . '/includes/auth.php';

$db = get_db();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Silme İşlemi
if ($action === 'delete' && $id > 0) {
    if (!validate_csrf($_GET['token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) geçersiz.');
    } else {
        $delStmt = $db->prepare("DELETE FROM services WHERE id = :id");
        $delStmt->execute([':id' => $id]);
        set_flash('success', 'Hizmet başarıyla silindi.');
    }
    header('Location: ' . url('xpanel/services.php'));
    exit;
}

// Kaydetme / Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) başarısız oldu.');
        header('Location: ' . url('xpanel/services.php'));
        exit;
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    }
    $shortDesc = trim((string)($_POST['short_description'] ?? ''));
    $fullContent = plain_text_to_html(trim((string)($_POST['full_content'] ?? '')));

    // Maddeleri satır satır JSON dizisine çevir
    $bulletsRaw = trim((string)($_POST['bullet_points'] ?? ''));
    $bulletArray = array_values(array_filter(array_map('trim', explode("\n", $bulletsRaw))));
    $bulletJson = json_encode($bulletArray, JSON_UNESCAPED_UNICODE);

    $hasBiofeedback = !empty($_POST['has_biofeedback']) ? 1 : 0;
    $bioTitle = trim((string)($_POST['biofeedback_title'] ?? ''));
    $bioContent = plain_text_to_html(trim((string)($_POST['biofeedback_content'] ?? '')));
    $bioBulletsRaw = trim((string)($_POST['biofeedback_bullets'] ?? ''));
    $bioBulletArray = array_values(array_filter(array_map('trim', explode("\n", $bioBulletsRaw))));
    $bioBulletJson = json_encode($bioBulletArray, JSON_UNESCAPED_UNICODE);

    $displayOrder = (int)($_POST['display_order'] ?? 0);
    $isActive = !empty($_POST['is_active']) ? 1 : 0;

    // Görsel yükleme / silme yönetimi
    $imageUrl = null;
    if ($id > 0) {
        $existingImg = $db->query("SELECT image_url FROM services WHERE id = " . (int)$id)->fetchColumn();
        $imageUrl = $existingImg ?: null;
    }
    
    // Görsel silme isteği
    if (!empty($_POST['delete_service_image'])) {
        if ($imageUrl && str_contains($imageUrl, 'uploads') && file_exists(__DIR__ . '/../' . ltrim($imageUrl, '/'))) {
            @unlink(__DIR__ . '/../' . ltrim($imageUrl, '/'));
        }
        $imageUrl = null;
    }

    // Yeni görsel yüklendiyse
    if (!empty($_FILES['service_image']['name'])) {
        $uploaded = handle_image_upload($_FILES['service_image'], 'hizmetler');
        if ($uploaded) {
            $imageUrl = $uploaded;
        }
    }

    if ($id > 0) {
        $stmt = $db->prepare("UPDATE services SET 
            title = :t, slug = :s, short_description = :sd, full_content = :fc, bullet_points = :bp, 
            has_biofeedback = :hb, biofeedback_title = :bt, biofeedback_content = :bc, biofeedback_bullets = :bb, 
            image_url = :img, display_order = :ord, is_active = :act WHERE id = :id");
        $stmt->execute([
            ':t' => $title, ':s' => $slug, ':sd' => $shortDesc, ':fc' => $fullContent, ':bp' => $bulletJson,
            ':hb' => $hasBiofeedback, ':bt' => $bioTitle, ':bc' => $bioContent, ':bb' => $bioBulletJson,
            ':img' => $imageUrl, ':ord' => $displayOrder, ':act' => $isActive, ':id' => $id
        ]);
        set_flash('success', 'Hizmet başarıyla güncellendi.');
    } else {
        $stmt = $db->prepare("INSERT INTO services 
            (title, slug, short_description, full_content, bullet_points, has_biofeedback, biofeedback_title, biofeedback_content, biofeedback_bullets, image_url, display_order, is_active)
            VALUES (:t, :s, :sd, :fc, :bp, :hb, :bt, :bc, :bb, :img, :ord, :act)");
        $stmt->execute([
            ':t' => $title, ':s' => $slug, ':sd' => $shortDesc, ':fc' => $fullContent, ':bp' => $bulletJson,
            ':hb' => $hasBiofeedback, ':bt' => $bioTitle, ':bc' => $bioContent, ':bb' => $bioBulletJson,
            ':img' => $imageUrl, ':ord' => $displayOrder, ':act' => $isActive
        ]);
        set_flash('success', 'Yeni hizmet başarıyla eklendi.');
    }

    header('Location: ' . url('xpanel/services.php'));
    exit;
}

// Düzenlenecek kayıt varsa çek
$currentService = null;
if (($action === 'edit' || $action === 'new') && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $currentService = $stmt->fetch();
}

require_once __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <span class="badge-tag">Hizmet Kataloğu</span>
        <h1 style="font-size: 2.2rem; font-weight: 700;">Hizmetler ve Uzmanlık Alanları</h1>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">
            Pelvik taban, biofeedback, lenfödem, omurga ve egzersiz hizmetlerini düzenleyin, yeni hizmet ekleyin veya kaldırın.
        </p>
    </div>
    <?php if ($action === 'list'): ?>
    <a href="<?= e(url('xpanel/services.php?action=new')) ?>" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.75rem 1.5rem;">
        + Yeni Hizmet Ekle
    </a>
    <?php else: ?>
    <a href="<?= e(url('xpanel/services.php')) ?>" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.75rem 1.5rem;">
        &larr; Hizmet Listesine Dön
    </a>
    <?php endif; ?>
</div>

<?php if ($action === 'edit' || $action === 'new'): 
    $rawBullets = '';
    if (!empty($currentService['bullet_points'])) {
        $arr = json_decode($currentService['bullet_points'], true);
        if (is_array($arr)) $rawBullets = implode("\n", $arr);
    }
    $rawBioBullets = '';
    if (!empty($currentService['biofeedback_bullets'])) {
        $arrBio = json_decode($currentService['biofeedback_bullets'], true);
        if (is_array($arrBio)) $rawBioBullets = implode("\n", $arrBio);
    }
?>
<!-- Hizmet Ekleme / Düzenleme Formu -->
<form method="POST" action="" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="admin-card">
        <span class="badge-tag"><?= $id > 0 ? 'Hizmet Güncelleme' : 'Yeni Hizmet Oluşturma' ?></span>
        <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;"><?= $id > 0 ? e($currentService['title']) : 'Yeni Hizmet Tanımla' ?></h2>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Hizmet Başlığı *</label>
                <input type="text" name="title" class="form-control" value="<?= e($currentService['title'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">URL Slug (Otomatik oluşturulur)</label>
                <input type="text" name="slug" class="form-control" value="<?= e($currentService['slug'] ?? '') ?>" placeholder="pelvik-taban-fizyoterapisi">
            </div>
        </div>

        <!-- Hizmet Görseli (Önizlemeli, Yüklemeli ve Silmeli) -->
        <?= render_image_uploader('service_image', $currentService['image_url'] ?? '', 'serviceImgPreview', 'Hizmet Kartı ve Sayfa Arka Plan Görseli') ?>

        <div class="form-group">
            <label class="form-label">Kısa Açıklama (Kart ve Liste Özeti) *</label>
            <textarea name="short_description" class="form-control" rows="2" required><?= e($currentService['short_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Detaylı İçerik Metni *</label>
            <textarea name="full_content" class="form-control" rows="8" required><?= e(html_to_plain_text($currentService['full_content'] ?? '')) ?></textarea>
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.35rem; display: block;">
                Paragraflarınızı ayırmak için Enter tuşuna basmanız yeterlidir. HTML kodu yazmanıza gerek yoktur, otomatik düzenlenir.
            </small>
        </div>

        <div class="form-group">
            <label class="form-label">Destek Olunan Durumlar (Madde İşaretli Liste - Her satıra bir madde yazınız)</label>
            <textarea name="bullet_points" class="form-control" rows="6" placeholder="Kadınlarda ve erkeklerde idrar kaçırma&#10;Ani sıkışma hissi ve tuvalete yetişememe"><?= e($rawBullets) ?></textarea>
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                Her satıra bir madde yazınız. Otomatik olarak madde işaretli liste şeklinde sunulacaktır.
            </small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Sıralama Sırası (Order)</label>
                <input type="number" name="display_order" class="form-control" value="<?= e((string)($currentService['display_order'] ?? 0)) ?>">
            </div>
            <div class="form-group" style="display: flex; align-items: center; padding-top: 1.75rem;">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" <?= (!isset($currentService['is_active']) || $currentService['is_active']) ? 'checked' : '' ?>>
                    <span style="font-weight: 600; color: var(--text-dark);">Hizmet Sitede Aktif Olarak Yayınlansın</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Biofeedback Özel Bölümü Alanı -->
    <div class="admin-card" style="background-color: var(--bg-card-light);">
        <span class="badge-tag">Özel Klinik Bölümü</span>
        <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Biofeedback Destekli Fizyoterapi Bölümü</h2>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-checkbox">
                <input type="checkbox" name="has_biofeedback" value="1" <?= !empty($currentService['has_biofeedback']) ? 'checked' : '' ?>>
                <span style="font-weight: 700; color: var(--text-dark); font-size: 1rem;">Bu Hizmet Sayfasına Biofeedback Özel Bölümünü Ekle</span>
            </label>
        </div>

        <div class="form-group">
            <label class="form-label">Biofeedback Bölüm Başlığı</label>
            <input type="text" name="biofeedback_title" class="form-control" value="<?= e($currentService['biofeedback_title'] ?? 'Biofeedback Destekli Fizyoterapi') ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Biofeedback Açıklama ve Cihaz Metni</label>
            <textarea name="biofeedback_content" class="form-control" rows="5"><?= e(html_to_plain_text($currentService['biofeedback_content'] ?? '')) ?></textarea>
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.35rem; display: block;">
                Paragraflarınızı ayırmak için Enter tuşuna basmanız yeterlidir.
            </small>
        </div>

        <div class="form-group">
            <label class="form-label">Biofeedback Uygulama Maddeleri (Her satıra bir madde yazınız)</label>
            <textarea name="biofeedback_bullets" class="form-control" rows="4"><?= e($rawBioBullets) ?></textarea>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; gap: 1rem;">
        <a href="<?= e(url('xpanel/services.php')) ?>" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 0.9rem;">
            Hizmeti Kaydet &rarr;
        </button>
    </div>
</form>

<?php else: 
    $services = $db->query("SELECT * FROM services ORDER BY display_order ASC, id ASC")->fetchAll();
?>
<!-- Hizmet Listesi Tablosu -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Sıra</th>
                    <th>Hizmet Başlığı</th>
                    <th>URL Slug</th>
                    <th>Biofeedback</th>
                    <th>Durum</th>
                    <th style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $s): ?>
                <tr>
                    <td><strong><?= e((string)$s['display_order']) ?></strong></td>
                    <td>
                        <strong style="font-size: 1rem;"><?= e($s['title']) ?></strong>
                        <div style="font-size: 0.8rem; color: var(--text-muted);"><?= e(mb_substr($s['short_description'], 0, 80)) ?>...</div>
                    </td>
                    <td><code style="font-size: 0.82rem; background: #EEE; padding: 0.2rem 0.4rem;"><?= e($s['slug']) ?></code></td>
                    <td>
                        <?php if ($s['has_biofeedback']): ?>
                            <span class="badge-status" style="background-color: var(--bg-card-sage); color: #FFF;">Biofeedback Var</span>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($s['is_active']): ?>
                            <span class="badge-status badge-new">Aktif</span>
                        <?php else: ?>
                            <span class="badge-status" style="background: #FEE; color: #C0392B;">Pasif</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="<?= e(url('hizmetler/' . $s['slug'])) ?>" target="_blank" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.7rem; margin-right: 0.25rem;">Gör</a>
                        <a href="<?= e(url('xpanel/services.php?action=edit&id=' . $s['id'])) ?>" class="btn btn-primary" style="padding: 0.35rem 0.65rem; font-size: 0.7rem; margin-right: 0.25rem;">Düzenle</a>
                        <a href="<?= e(url('xpanel/services.php?action=delete&id=' . $s['id'] . '&token=' . csrf_token())) ?>" 
                           class="btn btn-outline" 
                           style="padding: 0.35rem 0.65rem; font-size: 0.7rem; color: #C0392B; border-color: #C0392B;" 
                           onclick="return confirm('Bu hizmeti silmek istediğinize emin misiniz?');">Sil</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
