<?php
declare(strict_types=1);

$pageTitle = 'Sıkça Sorulan Sorular';
$pageDescription = 'Fizyoterapi değerlendirme süreci, biofeedback, çocuklarda alt ıslatma, pelvik taban ve seans süreçleri hakkında merak edilenler.';

require_once __DIR__ . '/includes/header.php';

$faqs = get_all_faqs(true);
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Danışan Bilgilendirme</span>
        <h1>Sıkça Sorulan <em>Sorular</em></h1>
        <p style="font-size: 1.15rem; margin-top: 1rem; color: var(--text-muted);">
            Klinik değerlendirme süreci, pelvik taban fizyoterapisi, biofeedback seansları ve randevu yönetimi hakkında danışanlarımızın en çok merak ettiği soruların yanıtları.
        </p>
    </div>
</section>

<section class="service-detail-body">
    <div class="container container-narrow">
        <div class="accordion-wrapper">
            <?php 
            $i = 1;
            foreach ($faqs as $f): 
            ?>
            <div class="accordion-item <?= $i === 1 ? 'active' : '' ?>">
                <button type="button" class="accordion-header" aria-expanded="<?= $i === 1 ? 'true' : 'false' ?>">
                    <span><?= sprintf('%02d', $i++) ?>. <?= e($f['question']) ?></span>
                    <span class="accordion-toggle"><?= $i === 2 ? '—' : '+' ?></span>
                </button>
                <div class="accordion-body" style="<?= $i === 2 ? 'max-height: 800px;' : '' ?>">
                    <div class="accordion-body-inner">
                        <?= nl2br(e($f['answer'])) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Soru Sorma Kartı -->
        <div class="faq-cta-box" style="margin-top: 4.5rem; background-color: var(--bg-card-light); border: 1px solid var(--border-subtle); border-radius: var(--radius-main); padding: 3rem 2.5rem; text-align: center;">
            <span class="badge-tag">Sorunuz Burada Yer Almıyor Mu?</span>
            <h2 style="font-size: 1.85rem; margin-bottom: 1rem;">Aklınıza Takılanları <em>Bize Sorabilirsiniz</em></h2>
            <p style="max-width: 600px; margin: 0 auto 2rem auto; color: var(--text-muted);">
                Kişisel durumunuz ve şikâyetleriniz ile ilgili doğrudan kliniğimizle görüşebilir veya WhatsApp üzerinden sorularınızı yöneltebilirsiniz.
            </p>
            <div>
                <a href="https://wa.me/<?= e($whatsappRaw) ?>?text=<?= rawurlencode('Merhaba Fzt. Evrim Şenol Akarca, bir sorum olacaktı.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                    WhatsApp ile Soru Sorun
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
