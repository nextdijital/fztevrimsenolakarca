<?php
declare(strict_types=1);

$pageTitle = 'Hakkımızda & Klinik Yaklaşımımız';
$pageDescription = 'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi. Bilimsel temelli değerlendirme ve bütüncül fizyoterapi yaklaşımı.';

require_once __DIR__ . '/includes/header.php';

$aboutContent = get_page_content('about', 'main');
$extraData = !empty($aboutContent['extra_data']) ? json_decode($aboutContent['extra_data'], true) : [];
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag"><?= e($aboutContent['subtitle'] ?? 'Özel Sağlık Meslek Hizmet Birimi — Adana') ?></span>
        <h1>Fizyoterapist <em>Evrim Şenol Akarca</em></h1>
        <p style="font-size: 1.15rem; margin-top: 1rem; color: var(--text-muted);">
            Bilimsel temelli klinik değerlendirme, manuel terapi ve insan odaklı bütüncül fizyoterapi yaklaşımı.
        </p>
    </div>
</section>

<section class="service-detail-body">
    <div class="container container-narrow">
        <!-- Klinik Manifestosu -->
        <div style="font-size: 1.15rem; line-height: 1.85; color: var(--text-dark); margin-bottom: 3.5rem;">
            <?= $aboutContent['content'] ?? '' ?>
        </div>

        <!-- Vizyon ve Misyon Kartları -->
        <div class="vm-grid" style="margin-bottom: 4rem;">
            <div class="vm-box">
                <span class="badge-tag">Gelecek Bakışımız</span>
                <h3>Vizyonum</h3>
                <p><?= e($extraData['vision'] ?? 'Bilimsel bilgi ve bütüncül değerlendirme yaklaşımıyla kişiye özel, sürdürülebilir ve güvenilir fizyoterapi hizmeti sunmak.') ?></p>
            </div>
            <div class="vm-box">
                <span class="badge-tag">Hizmet Amacımız</span>
                <h3>Misyonum</h3>
                <p><?= e($extraData['mission'] ?? 'Her bireyin ihtiyaçlarını ve yaşam koşullarını dikkate alarak hareket özgürlüğünü, fonksiyonel bağımsızlığını ve yaşam kalitesini desteklemek.') ?></p>
            </div>
        </div>

        <!-- Ön Görüşme ve Randevu Çağrısı -->
        <div class="editorial-card sage" style="min-height: auto; text-align: center; padding: 3rem 2rem;">
            <span class="badge-tag light">Sağlıklı Hareket ve Beden Farkındalığı</span>
            <h2 style="margin-bottom: 1.25rem;">Bedeninizi Yeniden Dinlemeye <em>Hazır Mısınız?</em></h2>
            <p style="max-width: 600px; margin: 0 auto 2rem auto;">
                Şikâyetlerinizin kaynağını birlikte değerlendirmek ve size en uygun fizyoterapi yol haritasını belirlemek için randevu oluşturabilirsiniz.
            </p>
            <div>
                <a href="<?= e(url('iletisim')) ?>" class="btn btn-white">İletişime Geçin & Randevu Alın</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
