<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$slug = trim((string)($_GET['slug'] ?? ''));
if (empty($slug)) {
    header('Location: ' . url('hizmetlerimiz'));
    exit;
}

$service = get_service_by_slug($slug);
if (!$service) {
    http_response_code(404);
    $pageTitle = 'Hizmet Bulunamadı (404)';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container container-narrow" style="padding: 6rem 1rem; text-align: center;">';
    echo '<h1>Sayfa <em>Bulunamadı</em></h1>';
    echo '<p style="margin: 1.5rem 0 2rem 0;">Aradığınız fizyoterapi hizmet sayfası mevcut değil veya yayından kaldırılmış olabilir.</p>';
    echo '<a href="' . e(url('hizmetlerimiz')) . '" class="btn btn-primary">Tüm Hizmetlerimize Dönün</a>';
    echo '</div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $service['title'];
$pageDescription = $service['short_description'];
$pageCanonical = url('hizmetler/' . $service['slug']);

require_once __DIR__ . '/includes/header.php';

$bullets = !empty($service['bullet_points']) ? json_decode($service['bullet_points'], true) : [];
$bioBullets = !empty($service['biofeedback_bullets']) ? json_decode($service['biofeedback_bullets'], true) : [];
?>

<!-- Üst Başlık -->
<section class="service-detail-header">
    <div class="container container-narrow">
        <a href="<?= e(url('hizmetlerimiz')) ?>" class="badge-tag" style="border-bottom: 1px solid var(--border-strong); padding-bottom: 0.2rem;">&larr; Tüm Hizmetlerimiz</a>
        <h1 style="margin-top: 0.75rem;"><?= e($service['title']) ?></h1>
        <p style="font-size: 1.15rem; margin-top: 1rem; color: var(--text-muted);">
            <?= e($service['short_description']) ?>
        </p>
    </div>
</section>

<!-- Hizmet Detay Gövdesi -->
<section class="service-detail-body">
    <div class="container container-narrow">
        <!-- Detaylı Açıklama Metni -->
        <div style="font-size: 1.15rem; line-height: 1.85; color: var(--text-dark); margin-bottom: 3rem;">
            <?= $service['full_content'] ?>
        </div>

        <!-- Destek Olunan Durumlar / Belirtiler Kutusu -->
        <?php if (!empty($bullets)): ?>
        <div class="service-bullets-box">
            <span class="badge-tag">Klinik Değerlendirme & Belirtiler</span>
            <h2 style="font-size: 1.85rem; margin-bottom: 0.5rem;">Hangi Durumlarda <em>Destek Oluyoruz?</em></h2>
            <p style="font-size: 0.95rem; margin-bottom: 1.25rem;">Aşağıdaki semptom ve problemlerden birini veya birkaçını yaşıyorsanız, kliniğimizde kişiye özel değerlendirme planlanabilir:</p>
            
            <ul class="bullets-list">
                <?php foreach ($bullets as $item): ?>
                <li><?= e($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- ÖZEL BÖLÜM: BİOFEEDBACK DESTEKLİ FİZYOTERAPİ (Pelvik Taban Sayfası İçin) -->
        <?php if (!empty($service['has_biofeedback'])): ?>
        <div id="biofeedback" class="biofeedback-feature-box" style="scroll-margin-top: 110px;">
            <span class="badge-tag light">Özel Klinik Teknoloji</span>
            <h2 style="font-size: 2.2rem; margin-bottom: 1rem;"><?= e($service['biofeedback_title'] ?? 'Biofeedback Destekli Fizyoterapi') ?></h2>
            
            <div style="font-size: 1.05rem; line-height: 1.8; margin-bottom: 2rem;">
                <?= $service['biofeedback_content'] ?>
            </div>

            <!-- Biofeedback Cihaz & Tanıtım Kutusu -->
            <div style="background-color: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.25); padding: 2rem; margin-bottom: 2rem; border-radius: var(--radius-main);">
                <div style="margin-bottom: 1.5rem; border-radius: 6px; overflow: hidden; max-height: 440px; background-color: #0d1315;">
                    <img src="<?= e(url('assets/img/feedbackcihaz.webp')) ?>" alt="Biofeedback Cihazı ve Sensör Eğitimi" style="width: 100%; height: auto; max-height: 440px; object-fit: contain; display: block; margin: 0 auto;">
                </div>
                <h3 style="font-size: 1.35rem; margin-bottom: 0.75rem; color: #FFFFFF;">Klinikte Kullanılan Biofeedback Cihazı ve Sensör Eğitimi</h3>
                <p style="font-size: 0.92rem; line-height: 1.65; color: rgba(255, 255, 255, 0.9);">
                    Biofeedback cihazı; yüzeyel elektromiyografi (EMG) sensörleri ile pelvik taban kaslarının elektriksel aktivitesini hassas olarak ölçer. Kişi ekrandaki interaktif grafik veya çocuklara özel oyun modülleri sayesinde kas kasılma şiddetini ve gevşeme yeteneğini anlık olarak öğrenir. Kesinlikle radyasyon, elektrik şoku veya ağrı içermeyen, tamamen güvenli bir biyogeribildirim metodudur.
                </p>
            </div>

            <?php if (!empty($bioBullets)): ?>
            <h3 style="font-size: 1.4rem; margin-bottom: 1rem; color: #FFFFFF;">Biofeedback Uygulama Alanları & Kazanımları:</h3>
            <ul class="bullets-list">
                <?php foreach ($bioBullets as $bioItem): ?>
                <li><?= e($bioItem) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Randevu Çağrısı -->
        <div class="editorial-card sage" style="min-height: auto; text-align: center; padding: 3rem 2rem; margin-top: 3.5rem;">
            <span class="badge-tag light">Değerlendirme & Randevu</span>
            <h2 style="margin-bottom: 1.25rem;">Bu Sorunları Yaşıyorsanız, <em>Birlikte Çözelim</em></h2>
            <p style="max-width: 600px; margin: 0 auto 2rem auto;">
                Fzt. Evrim Şenol Akarca ile bire bir klinik değerlendirme ve hedefinize uygun fizyoterapi programı oluşturmak için iletişime geçebilirsiniz.
            </p>
            <div>
                <a href="https://wa.me/<?= e(get_setting('whatsapp_raw', '905324700480')) ?>?text=<?= rawurlencode('Merhaba, ' . $service['title'] . ' hakkında bilgi ve randevu almak istiyorum.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-white">
                    WhatsApp ile Bilgi Alın
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
