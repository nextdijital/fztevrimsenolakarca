<?php
declare(strict_types=1);

$pageTitle = 'Hizmetlerimiz — Hangi Sorunlarda Destek Oluyoruz?';
$pageDescription = 'Pelvik taban fizyoterapisi, lipödem ve lenfödem rehabilitasyonu, kas-iskelet sistemi problemleri ve kişiye özel hareket eğitimi hizmetlerimiz.';

require_once __DIR__ . '/includes/header.php';

$services = get_all_services(true);
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Klinik Uzmanlık Alanları</span>
        <h1>Hizmetlerimiz – <em>Hangi Sorunlarda</em> Destek Oluyorum?</h1>
        <p style="font-size: 1.15rem; margin-top: 1rem; color: var(--text-muted);">
            Pelvik taban fizyoterapisi, lipödem ve lenfödem rehabilitasyonu, kas-iskelet sistemi problemleri ve kişiye özel hareket eğitimi alanlarında sunduğumuz bütüncül fizyoterapi hizmetlerini inceleyebilirsiniz.
        </p>
    </div>
</section>

<section class="service-detail-body">
    <div class="container">
        <div class="services-grid" style="grid-template-columns: repeat(2, 1fr); gap: 2.5rem;">
            <?php 
            $i = 1;
            foreach ($services as $srv): 
                $bullets = !empty($srv['bullet_points']) ? json_decode($srv['bullet_points'], true) : [];
                $cardImg = !empty($srv['image_url']) ? $srv['image_url'] : 'assets/img/hizmetler/hizmet-' . $i . '.jpg';
            ?>
            <div class="service-card" style="--card-bg-img: url('<?= e(url($cardImg)) ?>'); padding: 2.5rem 2rem; border: 1.5px solid var(--bg-card-sage);">
                <div>
                    <span class="service-num"><?= sprintf('%02d', $i++) ?> —</span>
                    <h2 class="service-card-title" style="font-size: 1.65rem; margin-bottom: 1rem;"><?= e($srv['title']) ?></h2>
                    <p class="service-card-desc" style="font-size: 1rem; margin-bottom: 1.5rem;"><?= e($srv['short_description']) ?></p>
                    
                    <?php if (!empty($bullets)): ?>
                    <div style="margin-bottom: 2rem;">
                        <span class="badge-tag" style="font-size: 0.72rem; margin-bottom: 0.85rem; display: inline-block;">Destek Olunan Temel Durumlar:</span>
                        <ul class="bullets-list" style="margin-top: 0.5rem;">
                            <?php foreach (array_slice($bullets, 0, 4) as $b): ?>
                            <li><?= e($b) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>

                <a href="<?= e(url('hizmetler/' . $srv['slug'])) ?>" class="btn btn-outline btn-block" style="margin-top: 1.5rem; font-size: 0.78rem;">
                    Detaylı İncele ve İlgili Bölüme Git &rarr;
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
