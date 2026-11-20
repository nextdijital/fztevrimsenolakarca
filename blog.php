<?php
declare(strict_types=1);

$pageTitle = 'Blog & Sağlık Rehberi';
$pageDescription = 'Fizyoterapist Evrim Şenol Akarca Blog. Pelvik taban fizyoterapisi, biofeedback teknolojisi, lipödem, lenfödem ve omurga sağlığı üzerine uzman bilgilendirici makaleler.';

require_once __DIR__ . '/includes/header.php';

$posts = get_all_blog_posts(true);
?>

<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Bilimsel Bilgi & Klinik Yazılar</span>
        <h1>Sağlık & Fizyoterapi <em>Rehberi</em></h1>
        <p style="font-size: 1.15rem; margin-top: 1rem; color: var(--text-muted);">
            Pelvik taban sağlığı, biofeedback teknolojisi, lenfödem-lipödem yönetimi ve omurga biyomekaniği üzerine güncel ve bilimsel temelli bilgilendirici makaleler.
        </p>
    </div>
</section>

<section class="service-detail-body">
    <div class="container container-wide">
        <?php if (empty($posts)): ?>
        <div style="text-align: center; padding: 4rem 1rem;">
            <p>Henüz yayınlanmış bir blog yazısı bulunmamaktadır.</p>
        </div>
        <?php else: ?>
        <div class="home-blog-grid">
            <?php 
            $blogImages = ['assets/img/blog/blog-1.jpg', 'assets/img/blog/blog-2.jpg', 'assets/img/blog/blog-3.jpg'];
            $idx = 0;
            foreach ($posts as $post): 
                $imgSrc = !empty($post['image_url']) ? $post['image_url'] : $blogImages[$idx % count($blogImages)];
                $idx++;
            ?>
            <article class="home-blog-card">
                <a href="<?= e(url('blog/' . $post['slug'])) ?>" class="home-blog-img-link">
                    <img src="<?= e(url($imgSrc)) ?>" alt="<?= e($post['title']) ?>" class="home-blog-img" loading="lazy">
                </a>

                <div style="margin-bottom: 0.85rem;">
                    <span class="badge-tag" style="margin-bottom: 0; font-size: 0.68rem;"><?= e($post['category']) ?></span>
                </div>

                <h2 class="home-blog-card-title" style="font-size: 1.25rem;">
                    <a href="<?= e(url('blog/' . $post['slug'])) ?>">
                        <?= e($post['title']) ?>
                    </a>
                </h2>

                <p style="font-size: 0.92rem; line-height: 1.6; color: var(--text-muted); margin-bottom: 1.5rem;">
                    <?= e($post['summary']) ?>
                </p>

                <div class="home-blog-btn-wrap" style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-subtle); padding-top: 1rem;">
                    <span style="font-size: 0.75rem; color: var(--text-muted);"><?= date('d.m.Y', strtotime($post['published_at'])) ?></span>
                    <a href="<?= e(url('blog/' . $post['slug'])) ?>" class="home-blog-btn">
                        <span class="btn-arrow-box">&gt;</span>
                        <span class="btn-text">DEVAMINI OKU</span>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Randevu & Danışma Kartı -->
        <div class="editorial-card sage" style="min-height: auto; text-align: center; padding: 3.5rem 2.5rem; border-radius: 4px; margin-top: 4.5rem;">
            <span class="badge-tag light">Kişiye Özel Klinik Değerlendirme</span>
            <h2 style="font-size: 1.95rem; margin-bottom: 1.15rem; color: #FFFFFF;">Sağlık Sorunlarınız İçin <em>Uzman Görüşü Alın</em></h2>
            <p style="max-width: 620px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.92); font-size: 1.02rem; line-height: 1.7;">
                Blog yazılarımızda yer alan konularla ilgili kendi durumunuzu değerlendirmek ve randevu oluşturmak için bize doğrudan ulaşabilirsiniz.
            </p>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="https://wa.me/<?= e($whatsappRaw) ?>?text=<?= rawurlencode('Merhaba Fzt. Evrim Şenol Akarca, blog yazılarınızı inceledim, bilgi ve randevu almak istiyorum.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-white" style="font-size: 0.82rem; padding: 0.95rem 2rem;">
                    WhatsApp ile İletişime Geçin
                </a>
                <a href="<?= e(url('iletisim')) ?>" class="btn btn-outline-white" style="font-size: 0.82rem; padding: 0.95rem 2rem;">
                    Klinik Bilgileri & Konum
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
