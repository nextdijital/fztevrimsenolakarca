<?php
declare(strict_types=1);

$pageTitle = 'Çerez (Cookie) Politikası';
$pageDescription = 'Web sitemizde kullanılan çerezler, kullanım amaçları ve çerez tercihlerinizi yönetme yöntemleri hakkında bilgilendirme.';

require_once __DIR__ . '/includes/header.php';

$content = get_page_content('legal', 'cookie_policy');
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Gizlilik ve Veri Güvenliği</span>
        <h1>Çerez <em>(Cookie)</em> Politikası</h1>
    </div>
</section>

<section class="service-detail-body">
    <div class="container container-narrow">
        <div style="background-color: #FFFFFF; border: 1px solid var(--border-subtle); padding: 3.5rem 3rem; font-size: 1.05rem; line-height: 1.85; color: var(--text-dark);">
            <?= $content['content'] ?? '' ?>
            
            <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <strong style="display: block; font-size: 1rem; margin-bottom: 0.25rem;">Çerez Tercihlerinizi Değiştirin:</strong>
                    <span style="font-size: 0.88rem; color: var(--text-muted);">Dilediğiniz zaman çerez kullanım izninizi güncelleyebilir veya tercihinizi değiştirebilirsiniz.</span>
                </div>
                <button type="button" class="btn btn-primary" id="manageCookiesBtn" onclick="document.getElementById('cookieBanner').classList.add('show');" style="padding: 0.85rem 1.6rem; font-size: 0.82rem;">
                    Çerez Tercihlerini Yönet
                </button>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
