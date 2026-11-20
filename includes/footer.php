<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';

$siteTitle = get_setting('site_title', 'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi');
$phone = get_setting('phone', '0532 470 04 80');
$phoneRaw = get_setting('phone_raw', '+905324700480');
$whatsappRaw = get_setting('whatsapp_raw', '905324700480');
$address = get_setting('address', 'Hurmalı Mah. Kurtuluş Cad. No:39 Central Plaza Kat:8/81 Seyhan / Adana');
$email = get_setting('email', 'evrimmsenol@yahoo.com.tr');
$instagram = get_setting('instagram', '@fzt.evrimsenol');
$instagramUrl = get_setting('instagram_url', 'https://instagram.com/fzt.evrimsenol');
$facebookUrl = get_setting('facebook_url', '');
$workingHours = get_setting('working_hours', 'Pazartesi - Cumartesi: 09:00 - 18:30 (Ön Randevu ile)');
?>

<!-- Alt Bilgi (Footer) -->
<footer class="site-footer">
    <div class="container">
        <!-- 4 Kolonlu Kurumsal Bilgi Alanı -->
        <div class="footer-grid">
            <!-- Kolon 1: Kurumsal Tanıtım -->
            <div class="footer-col">
                <a href="<?= e(url()) ?>" style="display: inline-block; margin-bottom: 1.25rem;">
                    <img src="<?= e(url('assets/img/logo-full.png')) ?>" alt="<?= e($siteTitle) ?>" class="footer-logo-img">
                </a>
                <p style="margin-bottom: 1.25rem;">Özel Sağlık Meslek Hizmet Birimi olarak; bireyin bedenini tanımasını, fonksiyonel hareket kapasitesini geliştirmesini ve yaşam kalitesini artırmasını bilimsel temelli fizyoterapi yaklaşımlarıyla destekliyoruz.</p>
                <?php if (!empty($instagramUrl)): ?>
                <div class="footer-social-wrap" style="margin-top: 1.25rem;">
                    <a href="<?= e($instagramUrl) ?>" target="_blank" rel="noopener noreferrer" class="footer-social-link" title="Instagram: <?= e($instagram) ?>" aria-label="Instagram" style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; color: #FFFFFF; background: var(--bg-card-sage); border-radius: 50%; box-shadow: 0 4px 14px rgba(79, 127, 136, 0.25); transition: all 0.25s ease;">
                        <svg style="width: 22px; height: 22px; fill: currentColor;" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Kolon 2: Hızlı Menü -->
            <div class="footer-col">
                <h4>Kurumsal</h4>
                <ul class="footer-links">
                    <li><a href="<?= e(url()) ?>">Ana Sayfa</a></li>
                    <li><a href="<?= e(url('hakkimizda')) ?>">Hakkımızda & Yaklaşımımız</a></li>
                    <li><a href="<?= e(url('hizmetlerimiz')) ?>">Tüm Hizmetlerimiz</a></li>
                    <li><a href="<?= e(url('blog')) ?>">Blog & Sağlık Rehberi</a></li>
                    <li><a href="<?= e(url('tarifler')) ?>">Sağlıklı Tarifler</a></li>
                    <li><a href="<?= e(url('sikca-sorulan-sorular')) ?>">Sıkça Sorulan Sorular</a></li>
                    <li><a href="<?= e(url('iletisim')) ?>">İletişim & Konum</a></li>
                </ul>
            </div>

            <!-- Kolon 3: Hizmetlerimiz -->
            <div class="footer-col">
                <h4>Uzmanlık Alanları</h4>
                <ul class="footer-links">
                    <li><a href="<?= e(url('hizmetler/pelvik-taban-fizyoterapisi')) ?>">Pelvik Taban & Biofeedback</a></li>
                    <li><a href="<?= e(url('hizmetler/lipodem-lenfodem-rehabilitasyonu')) ?>">Lipödem & Lenfödem</a></li>
                    <li><a href="<?= e(url('hizmetler/bel-boyun-sirt-agrilari')) ?>">Bel, Boyun & Sırt Ağrıları</a></li>
                    <li><a href="<?= e(url('hizmetler/omuz-kalca-diz-problemleri')) ?>">Omuz, Kalça ve Diz</a></li>
                    <li><a href="<?= e(url('hizmetler/cene-bas-boyun-problemleri')) ?>">Çene & TME Problemleri</a></li>
                    <li><a href="<?= e(url('hizmetler/kisiye-ozel-egzersiz-egitimi')) ?>">Kişiye Özel Egzersiz Eğitimi</a></li>
                </ul>
            </div>

            <!-- Kolon 4: İletişim Bilgileri -->
            <div class="footer-col">
                <h4>Klinik & İletişim</h4>
                <p style="margin-bottom: 0.75rem;"><strong>Adres:</strong><br><?= e($address) ?></p>
                <p style="margin-bottom: 0.75rem;"><strong>Telefon:</strong><br><a href="tel:<?= e($phoneRaw) ?>" style="color: var(--text-dark); font-weight: 600;"><?= e($phone) ?></a></p>
                <p style="margin-bottom: 0.75rem;"><strong>E-Posta:</strong><br><a href="mailto:<?= e($email) ?>" style="color: var(--text-dark); font-weight: 600;"><?= e($email) ?></a></p>
                <p><strong>Çalışma Saatleri:</strong><br><?= e($workingHours) ?></p>
            </div>
        </div>

        <!-- Alt Telif & Yasal Linkler (Yasal Bilgilendirme metni kullanıcı talebiyle footer'dan kaldırılıp yasal uyarı sayfasına taşındı) -->
        <div class="footer-bottom">
            <div>
                &copy; <?= date('Y') ?> <?= e($siteTitle) ?>. Tüm hakları saklıdır.
            </div>
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <a href="<?= e(url('kvkk')) ?>" style="color: inherit;">KVKK Aydınlatma Metni</a>
                <a href="<?= e(url('cerez-politikasi')) ?>" style="color: inherit;">Çerez Politikası</a>
                <a href="<?= e(url('yasal-uyari')) ?>" style="color: inherit;">Yasal Uyarı & Tıbbi Teşhis Reddi</a>
                <a href="<?= e(url('randevu-politikasi')) ?>" style="color: inherit;">Randevu Politikası</a>
            </div>
        </div>
    </div>
</footer>

<!-- SABİT SOSYAL MEDYA & İLETİŞİM BUTONLARI (SOL İNSTAGRAM, SAĞ WP - SİTE RENKLERİNE UYGUN) -->
<div class="floating-social-actions">
    <?php if (!empty($instagramUrl)): ?>
    <a href="<?= e($instagramUrl) ?>" target="_blank" rel="noopener noreferrer" class="floating-social-btn floating-btn-instagram" title="Instagram">
        <svg class="floating-social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        <span>INSTAGRAM</span>
    </a>
    <?php endif; ?>

    <?php if (!empty($whatsappRaw)): ?>
    <a href="https://wa.me/<?= e($whatsappRaw) ?>?text=<?= rawurlencode('Merhaba Fzt. Evrim Şenol Akarca, web sitenizden ulaşıyorum.') ?>" target="_blank" rel="noopener noreferrer" class="floating-social-btn floating-btn-whatsapp" title="WhatsApp">
        <svg class="floating-social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        <span>WHATSAPP</span>
    </a>
    <?php endif; ?>
</div>

<!-- Çerez Onay Bannerı -->
<div class="cookie-banner" id="cookieBanner" role="region" aria-label="Çerez Kullanım Onayı">
    <div class="cookie-banner-inner">
        <div class="cookie-text">
            Web sitemizde deneyiminizi iyileştirmek, güvenliği sağlamak ve site kullanım istatistiklerini anonim olarak analiz edebilmek amacıyla çerezler kullanılmaktadır. 
            Detaylı bilgi için <a href="<?= e(url('cerez-politikasi')) ?>" style="text-decoration: underline; font-weight: 600;">Çerez Politikamızı</a> inceleyebilirsiniz.
        </div>
        <div class="cookie-actions">
            <button type="button" class="btn btn-outline" id="btnRejectCookies" style="padding: 0.65rem 1.25rem; font-size: 0.75rem;">Yalnızca Zorunlu</button>
            <button type="button" class="btn btn-primary" id="btnAcceptCookies" style="padding: 0.65rem 1.25rem; font-size: 0.75rem;">Tümünü Kabul Et</button>
        </div>
    </div>
</div>

<!-- KVKK Hızlı Önizleme Modalı -->
<div class="modal-overlay" id="kvkkModal" role="dialog" aria-modal="true" aria-labelledby="kvkkModalTitle">
    <div class="modal-card">
        <div class="modal-card-header">
            <h3 id="kvkkModalTitle">KVKK Aydınlatma Metni</h3>
            <button type="button" class="modal-close-btn" data-close-modal aria-label="Kapat">&times;</button>
        </div>
        <div class="modal-card-body">
            <p><strong>6698 Sayılı Kişisel Verilerin Korunması Kanunu Kapsamında Bilgilendirme:</strong></p>
            <p>Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi olarak; danışanlarımızın kişisel verilerinin korunması ve gizliliğinin sağlanması en temel önceliklerimizdendir.</p>
            <p>İletişim ve randevu formlarında paylaştığınız ad-soyad, telefon ve mesaj bilgileriniz yalnızca fizyoterapi ön bilgilendirmesi ve randevu koordinasyonu amacıyla işlenmektedir. Bilgileriniz kesinlikle üçüncü şahıslara aktarılmaz veya ticari amaçla satılmaz.</p>
            <p>KVKK'nın 11. maddesi kapsamındaki haklarınızı kullanmak için dilediğiniz zaman <strong>evrimmsenol@yahoo.com.tr</strong> adresine başvurabilirsiniz.</p>
            <p><a href="<?= e(url('kvkk')) ?>" style="text-decoration: underline; font-weight: 600;">Aydınlatma Metninin Tamamını Okumak İçin Tıklayınız &rarr;</a></p>
        </div>
        <div class="modal-card-footer">
            <button type="button" class="btn btn-primary" data-close-modal style="padding: 0.65rem 1.25rem; font-size: 0.75rem;">Anladım, Kapat</button>
        </div>
    </div>
</div>

<!-- JS Dosyası -->
<script src="<?= e(url('assets/js/main.js')) ?>?v=<?= filemtime(__DIR__ . '/../assets/js/main.js') ?>" defer></script>
</body>
</html>
