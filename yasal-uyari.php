<?php
declare(strict_types=1);

$pageTitle = 'Yasal Uyarı ve Tıbbi Teşhis Reddi';
$pageDescription = 'Fizyoterapist Evrim Şenol Akarca web sitesi yasal kullanım şartları ve tıbbi tanı/tedavi sorumluluk reddi beyanı.';

require_once __DIR__ . '/includes/header.php';

$content = get_page_content('legal', 'terms');
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Mevzuat ve Sorumluluk Reddi</span>
        <h1>Yasal Uyarı ve <em>Tıbbi Teşhis Reddi</em></h1>
    </div>
</section>

<section class="service-detail-body">
    <div class="container container-narrow">
        <div style="background-color: #FFFFFF; border: 1px solid var(--border-subtle); padding: 3.5rem 3rem; font-size: 1.05rem; line-height: 1.85; color: var(--text-dark);">
            
            <div style="background-color: var(--bg-card-light); border-left: 4px solid var(--bg-card-sage); padding: 1.75rem 2rem; margin-bottom: 2.5rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem; color: var(--text-dark);">Yasal Bilgilendirme ve Tıbbi Teşhis Reddi</h3>
                <p style="margin-bottom: 0; font-size: 0.95rem; line-height: 1.7; color: var(--text-dark);">
                    Bu web sitesinde yer alan tüm yazılar, makaleler, fotoğraflar ve içerikler yalnızca toplumu bilgilendirme amacıyla hazırlanmıştır. Sitemizdeki hiçbir bilgi hekim muayenesi, tıbbi tanı ve hekimce reçete edilen tedavi yerine geçmez. Sağlık sorunlarınızda lütfen öncelikle yetkili uzman hekimlere başvurunuz. Kliniğimizdeki fizyoterapi ve rehabilitasyon hizmetleri, 1219 sayılı Kanun ve ilgili mevzuat uyarınca fizyoterapistlik meslek yetkileri dahilinde uygulanmaktadır.
                </p>
            </div>

            <?= $content['content'] ?? '' ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
