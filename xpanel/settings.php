<?php
declare(strict_types=1);

$adminPageTitle = 'Site ve Kurumsal Ayarlar';
require_once __DIR__ . '/includes/auth.php';

$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) başarısız oldu.');
        header('Location: ' . url('xpanel/settings.php'));
        exit;
    }

    $postedSettings = $_POST['settings'] ?? [];
    if (is_array($postedSettings)) {
        $upsertStmt = $db->prepare("INSERT INTO site_settings (key_name, key_value, group_name, description) 
            VALUES (:key, :val, 'contact', 'Ayar') 
            ON DUPLICATE KEY UPDATE key_value = VALUES(key_value)");
        foreach ($postedSettings as $key => $val) {
            $upsertStmt->execute([':val' => trim((string)$val), ':key' => $key]);
        }

        // Raw formatları otomatik senkronize et
        if (isset($postedSettings['phone'])) {
            $phoneClean = preg_replace('/[^0-9]/', '', $postedSettings['phone']);
            if (str_starts_with($phoneClean, '0')) {
                $phoneRaw = '+9' . $phoneClean;
            } else {
                $phoneRaw = '+' . $phoneClean;
            }
            $upsertStmt->execute([':val' => $phoneRaw, ':key' => 'phone_raw']);
        }
        if (isset($postedSettings['whatsapp'])) {
            $waClean = preg_replace('/[^0-9]/', '', $postedSettings['whatsapp']);
            if (str_starts_with($waClean, '0')) {
                $waRaw = '9' . $waClean;
            } else {
                $waRaw = $waClean;
            }
            $upsertStmt->execute([':val' => $waRaw, ':key' => 'whatsapp_raw']);
        }

        set_flash('success', 'Site ve kurumsal iletişim ayarları başarıyla güncellendi.');
        header('Location: ' . url('xpanel/settings.php'));
        exit;
    }
}

// Tüm ayarları çek
$allSettings = $db->query("SELECT * FROM site_settings ORDER BY group_name ASC, id ASC")->fetchAll();
$grouped = [];
foreach ($allSettings as $s) {
    $grouped[$s['group_name']][] = $s;
}

require_once __DIR__ . '/includes/header.php';
?>

<div style="margin-bottom: 2.5rem;">
    <span class="badge-tag">Konfigürasyon</span>
    <h1 style="font-size: 2.2rem; font-weight: 700;">Site ve Kurumsal Ayarlar</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">
        Klinik unvanı, iletişim numaraları, adres, sosyal medya ve SEO ayarlarını buradan tek tıkla güncelleyebilirsiniz.
    </p>
</div>

<form method="POST" action="">
    <?= csrf_field() ?>

    <!-- 1. Genel ve Kurumsal Bilgiler -->
    <div class="admin-card">
        <span class="badge-tag">Temel Kimlik</span>
        <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Kurumsal Bilgiler</h2>

        <div class="form-group">
            <label class="form-label">Resmi Klinik Adı (Site Başlığı)</label>
            <input type="text" name="settings[site_title]" class="form-control" value="<?= e(get_setting('site_title')) ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Yetkili Uzman Adı</label>
                <input type="text" name="settings[specialist_name]" class="form-control" value="<?= e(get_setting('specialist_name')) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Uzman Unvanı</label>
                <input type="text" name="settings[specialist_title]" class="form-control" value="<?= e(get_setting('specialist_title')) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Çalışma Saatleri & Randevu Düzeni</label>
            <input type="text" name="settings[working_hours]" class="form-control" value="<?= e(get_setting('working_hours')) ?>">
        </div>
    </div>

    <!-- 2. İletişim ve Adres Bilgileri -->
    <div class="admin-card">
        <span class="badge-tag">Ulaşım & İletişim</span>
        <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">İletişim ve Konum Bilgileri</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Telefon Numarası</label>
                <input type="text" name="settings[phone]" class="form-control" value="<?= e(get_setting('phone')) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">WhatsApp Randevu Hattı</label>
                <input type="text" name="settings[whatsapp]" class="form-control" value="<?= e(get_setting('whatsapp')) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">E-Posta Adresi</label>
            <input type="email" name="settings[email]" class="form-control" value="<?= e(get_setting('email')) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Klinik Tam Adresi</label>
            <textarea name="settings[address]" class="form-control" rows="2" required><?= e(get_setting('address')) ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Şehir</label>
                <input type="text" name="settings[city]" class="form-control" value="<?= e(get_setting('city')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">İlçe</label>
                <input type="text" name="settings[district]" class="form-control" value="<?= e(get_setting('district')) ?>">
            </div>
        </div>
    </div>

    <!-- 3. Sosyal Medya ve Google Haritalar -->
    <div class="admin-card">
        <span class="badge-tag">Sosyal Ağlar & Harita</span>
        <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Instagram & Harita Konumu</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Instagram Profil URL</label>
                <input type="url" name="settings[instagram_url]" class="form-control" value="<?= e(get_setting('instagram_url')) ?>" placeholder="https://instagram.com/fzt.evrimsenol">
            </div>
            <div class="form-group">
                <label class="form-label">Facebook Profil URL (Boş bırakılırsa gizlenir)</label>
                <input type="url" name="settings[facebook_url]" class="form-control" value="<?= e(get_setting('facebook_url')) ?>" placeholder="https://facebook.com/sayfa-adi">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Google Haritalar Doğrudan Konum / Navigasyon Linki</label>
            <input type="url" name="settings[google_maps_link]" class="form-control" value="<?= e(get_setting('google_maps_link', 'https://maps.google.com/?q=' . rawurlencode('Hurmalı Mah. Kurtuluş Cad. No:39 Central Plaza Seyhan Adana'))) ?>" placeholder="https://maps.google.com/?q=...">
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                İletişim sayfasındaki harita kartının üst sağındaki "Büyük Haritada Aç" bağlantısının ve harita butonunun yönleneceği doğrudan Google Haritalar navigasyon linki.
            </small>
        </div>

        <div class="form-group">
            <label class="form-label">Google Maps Embed HTML Kodu (Görselli Harita Alanı)</label>
            <textarea name="settings[google_maps_embed]" class="form-control" rows="3"><?= e(get_setting('google_maps_embed')) ?></textarea>
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                Google Haritalar'dan "Haritayı Yerleştir" seçeneğindeki `&lt;iframe...&gt;` kodunu buraya yapıştırabilirsiniz.
            </small>
        </div>
    </div>

    <!-- 4. SEO ve Meta Açıklamaları -->
    <div class="admin-card">
        <span class="badge-tag">Arama Motoru Optimizasyonu</span>
        <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">SEO Meta Bilgileri</h2>

        <div class="form-group">
            <label class="form-label">Genel Meta Açıklama (Description)</label>
            <textarea name="settings[meta_description]" class="form-control" rows="2"><?= e(get_setting('meta_description')) ?></textarea>
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                Google arama sonuçlarında görünen 150-160 karakterlik özet tanıtım metni.
            </small>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 0.9rem;">
            Tüm Ayarları Kaydet &rarr;
        </button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
