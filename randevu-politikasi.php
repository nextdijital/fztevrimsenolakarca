<?php
declare(strict_types=1);

$pageTitle = 'Randevu, Seans ve İptal Politikası';
$pageDescription = 'Klinik randevu kuralları, seans iptal bildirim süreleri ve danışan bilgilendirme standartları.';

require_once __DIR__ . '/includes/header.php';

$content = get_page_content('legal', 'appointment_policy');
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Danışan Standartları</span>
        <h1>Randevu, Seans ve <em>İptal Politikası</em></h1>
    </div>
</section>

<section class="service-detail-body">
    <div class="container container-narrow">
        <div style="background-color: #FFFFFF; border: 1px solid var(--border-subtle); padding: 3.5rem 3rem; font-size: 1.05rem; line-height: 1.85; color: var(--text-dark);">
            <?= $content['content'] ?? '' ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
