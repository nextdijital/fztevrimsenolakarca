<?php
declare(strict_types=1);

$pageTitle = 'İletişim, Randevu ve Ulaşım';
$pageDescription = 'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi iletişim bilgileri, Adana Seyhan Central Plaza konumu ve randevu kanalları.';

require_once __DIR__ . '/includes/header.php';

$googleMapsEmbed = get_setting('google_maps_embed');
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Klinik İletişim & Ulaşım</span>
        <h1>İletişim ve <em>Randevu</em></h1>
        <p style="font-size: 1.15rem; margin-top: 1rem; color: var(--text-muted);">
            Detaylı değerlendirme, birebir fizyoterapi seansları ve klinik ziyaretleriniz için randevu oluşturabilir, harita üzerinden kliniğimize kolayca ulaşabilirsiniz.
        </p>
    </div>
</section>

<section class="service-detail-body">
    <div class="container container-wide">
        <div class="contact-grid">
            <!-- Sol Sütun: Klinik Bilgileri & Doğrudan İletişim Kanalları -->
            <div class="contact-info-panel">
                <div class="contact-header">
                    <span class="badge-tag">Resmi Hizmet Birimi</span>
                    <h2>Fizyoterapist Evrim Şenol Akarca</h2>
                    <p>Kliniğimizde seanslar kişiye özel planlandığı için görüşmeler randevu ile gerçekleştirilmektedir.</p>
                </div>
                
                <div class="contact-items-stack">
                    <!-- Klinik Adresi -->
                    <div class="contact-info-card">
                        <div class="icon-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div class="card-content">
                            <label>Klinik Adresi</label>
                            <div class="val"><?= e($address) ?></div>
                        </div>
                    </div>

                    <!-- Telefon & E-Posta -->
                    <div class="contact-item-row">
                        <div class="contact-info-card">
                            <div class="icon-wrap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </div>
                            <div class="card-content">
                                <label>Telefon Numarası</label>
                                <a href="tel:<?= e($phoneRaw) ?>" class="val"><?= e($phone) ?></a>
                            </div>
                        </div>

                        <div class="contact-info-card">
                            <div class="icon-wrap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </div>
                            <div class="card-content">
                                <label>E-Posta</label>
                                <a href="mailto:<?= e($email) ?>" class="val val-email"><?= e($email) ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Çalışma Saatleri -->
                    <div class="contact-info-card">
                        <div class="icon-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="card-content">
                            <label>Çalışma Saatleri</label>
                            <div class="val"><?= e(get_setting('working_hours')) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Sütun: Klinik Konumu & Google Harita (Kompakt ve Dengeli Boyut) -->
            <div class="contact-map-panel">
                <div style="padding: 0.85rem 1.25rem; background-color: var(--bg-card-light); border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-dark);">Klinik Konumu & Harita (Central Plaza)</span>
                    <a href="https://maps.google.com/?q=<?= rawurlencode('Hurmalı Mah. Kurtuluş Cad. No:39 Central Plaza Seyhan Adana') ?>" target="_blank" rel="noopener noreferrer" style="font-size: 0.78rem; text-decoration: underline; font-weight: 600; color: var(--text-dark);">Büyük Haritada Aç &rarr;</a>
                </div>
                <div class="contact-map-body" style="line-height: 0; overflow: hidden; flex: 1; display: flex; height: 100%;">
                    <?= $googleMapsEmbed ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
