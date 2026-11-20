<?php
declare(strict_types=1);

$adminPageTitle = 'Yönetim Özeti & Dashboard';
require_once __DIR__ . '/includes/auth.php';

$db = get_db();

// İstatistikleri çek
$totalServices = (int)$db->query("SELECT COUNT(*) FROM services")->fetchColumn();
$totalBlogPosts = (int)$db->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
$totalFaqs = (int)$db->query("SELECT COUNT(*) FROM faqs")->fetchColumn();

// Son eklenen hizmetler & son blog yazıları
$recentServices = $db->query("SELECT id, title, slug, is_active FROM services ORDER BY display_order ASC LIMIT 5")->fetchAll();
$recentPosts = $db->query("SELECT id, title, slug, published_at, is_active FROM blog_posts ORDER BY id DESC LIMIT 5")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div style="margin-bottom: 2.5rem;">
    <span class="badge-tag">Sistem Özeti</span>
    <h1 style="font-size: 2.2rem; font-weight: 700; color: var(--text-dark);">Hoş Geldiniz, <span style="color: var(--bg-card-sage);"><?= e($adminUsername) ?></span></h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">
        Web sitenizin tüm içeriklerini, uzmanlık hizmetlerini, sağlık rehberi yazılarını ve klinik ayarlarını buradan yönetebilirsiniz.
    </p>
</div>

<!-- İstatistik Kartları (4 Kolon) -->
<div class="dual-cards-grid" style="grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 3rem;">
    <div class="admin-card" style="margin-bottom: 0; padding: 1.75rem 1.5rem;">
        <span class="badge-tag" style="margin-bottom: 0.5rem;">Aktif Hizmetler</span>
        <div style="font-size: 2.4rem; font-weight: 700; color: var(--text-dark);">
            <?= $totalServices ?>
        </div>
        <p style="font-size: 0.8rem; margin-top: 0.4rem; color: var(--text-muted);">Biofeedback & Rehabilitasyon</p>
        <a href="<?= e(url('xpanel/services.php')) ?>" class="btn btn-outline btn-block" style="margin-top: 1.25rem; padding: 0.5rem; font-size: 0.75rem;">Hizmetleri Yönet &rarr;</a>
    </div>

    <div class="admin-card" style="margin-bottom: 0; padding: 1.75rem 1.5rem;">
        <span class="badge-tag" style="margin-bottom: 0.5rem;">Blog Makaleleri</span>
        <div style="font-size: 2.4rem; font-weight: 700; color: var(--text-dark);">
            <?= $totalBlogPosts ?>
        </div>
        <p style="font-size: 0.8rem; margin-top: 0.4rem; color: var(--text-muted);">Sağlık rehberi yazıları</p>
        <a href="<?= e(url('xpanel/blog.php')) ?>" class="btn btn-outline btn-block" style="margin-top: 1.25rem; padding: 0.5rem; font-size: 0.75rem;">Yazıları Yönet &rarr;</a>
    </div>

    <div class="admin-card" style="margin-bottom: 0; padding: 1.75rem 1.5rem;">
        <span class="badge-tag" style="margin-bottom: 0.5rem;">Soru & Cevap</span>
        <div style="font-size: 2.4rem; font-weight: 700; color: var(--text-dark);">
            <?= $totalFaqs ?>
        </div>
        <p style="font-size: 0.8rem; margin-top: 0.4rem; color: var(--text-muted);">Sıkça Sorulan Sorular</p>
        <a href="<?= e(url('xpanel/faqs.php')) ?>" class="btn btn-outline btn-block" style="margin-top: 1.25rem; padding: 0.5rem; font-size: 0.75rem;">Soruları Düzenle &rarr;</a>
    </div>

    <div class="admin-card" style="margin-bottom: 0; padding: 1.75rem 1.5rem;">
        <span class="badge-tag" style="margin-bottom: 0.5rem;">Kurumsal Ayarlar</span>
        <div style="font-size: 1.45rem; font-weight: 700; line-height: 1.6; color: var(--text-dark);">
            Adana / Seyhan
        </div>
        <p style="font-size: 0.8rem; margin-top: 0.4rem; color: var(--text-muted);">İletişim, Saatler & Konum</p>
        <a href="<?= e(url('xpanel/settings.php')) ?>" class="btn btn-outline btn-block" style="margin-top: 1.25rem; padding: 0.5rem; font-size: 0.75rem;">Site Ayarları &rarr;</a>
    </div>
</div>

<!-- Hızlı Sayfa İçerik Düzenleme Kısayolları -->
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <span class="badge-tag">Hızlı Düzenleme</span>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-top: 0.35rem;">Sayfa Metinlerini ve Klinik Manifestolarını Düzenle</h2>
        </div>
        <a href="<?= e(url('xpanel/pages.php')) ?>" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.6rem 1.25rem;">Tüm Sayfalar &rarr;</a>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
        <div style="border: 1px solid var(--border-subtle); padding: 1.5rem; border-radius: 8px;">
            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem;">Ana Sayfa</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem; line-height: 1.5;">Hero başlıkları, rozet, klinik manifestosu ve felsefe metinleri.</p>
            <a href="<?= e(url('xpanel/pages.php?page=home')) ?>" class="btn btn-outline btn-block" style="font-size: 0.75rem; padding: 0.5rem;">Düzenle &rarr;</a>
        </div>
        <div style="border: 1px solid var(--border-subtle); padding: 1.5rem; border-radius: 8px;">
            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem;">Hakkımızda</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem; line-height: 1.5;">Biyografi, klinik yaklaşım felsefesi, vizyon ve misyon alanları.</p>
            <a href="<?= e(url('xpanel/pages.php?page=about')) ?>" class="btn btn-outline btn-block" style="font-size: 0.75rem; padding: 0.5rem;">Düzenle &rarr;</a>
        </div>
        <div style="border: 1px solid var(--border-subtle); padding: 1.5rem; border-radius: 8px;">
            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem;">Yasal & KVKK</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem; line-height: 1.5;">KVKK Aydınlatma Metni, Çerez Politikası ve Tıbbi Teşhis Reddi.</p>
            <a href="<?= e(url('xpanel/pages.php?page=legal')) ?>" class="btn btn-outline btn-block" style="font-size: 0.75rem; padding: 0.5rem;">Düzenle &rarr;</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
