<?php
declare(strict_types=1);

$pageTitle = 'KVKK Aydınlatma Metni';
$pageDescription = '6698 Sayılı Kişisel Verilerin Korunması Kanunu uyarınca Fzt. Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi aydınlatma metni.';

require_once __DIR__ . '/includes/header.php';

$content = get_page_content('legal', 'kvkk');
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Yasal Uyumluluk (6698 Sayılı Kanun)</span>
        <h1>Kişisel Verilerin Korunması <em>Aydınlatma Metni</em></h1>
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
