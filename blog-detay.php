<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$slug = trim((string)($_GET['slug'] ?? ''));
if (empty($slug)) {
    header('Location: ' . url('blog'));
    exit;
}

$post = get_blog_post_by_slug($slug);
if (!$post) {
    http_response_code(404);
    $pageTitle = 'Yazı Bulunamadı (404)';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container container-narrow" style="padding: 6rem 1rem; text-align: center;">';
    echo '<h1>Yazı <em>Bulunamadı</em></h1>';
    echo '<p style="margin: 1.5rem 0 2rem 0;">Aradığınız blog yazısı mevcut değil veya yayından kaldırılmış olabilir.</p>';
    echo '<a href="' . e(url('blog')) . '" class="btn btn-primary">Tüm Blog Yazılarına Dönün</a>';
    echo '</div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Okunma sayısını artır
increment_blog_views((int)$post['id']);

$pageTitle = $post['meta_title'] ?: $post['title'];
$pageDescription = $post['meta_description'] ?: $post['summary'];
$pageCanonical = url('blog/' . $post['slug']);

require_once __DIR__ . '/includes/header.php';

// Schema.org BlogPosting
$publishedIso = date('c', strtotime($post['published_at']));
$articleUrl = url('blog/' . $post['slug']);
?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "<?= e($post['title']) ?>",
  "description": "<?= e($post['summary']) ?>",
  "datePublished": "<?= $publishedIso ?>",
  "dateModified": "<?= $publishedIso ?>",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?= e($articleUrl) ?>"
  },
  "author": {
    "@type": "Person",
    "name": "<?= e($post['author']) ?>",
    "jobTitle": "Fizyoterapist"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi",
    "url": "<?= e(url()) ?>"
  }
}
</script>

<article>
    <!-- Makale Üst Başlık Bölümü -->
    <header class="service-detail-header">
        <div class="container container-narrow">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem;">
                <a href="<?= e(url('blog')) ?>" class="badge-tag" style="margin-bottom: 0; border-bottom: 1px solid var(--border-strong); padding-bottom: 0.2rem;">
                    &larr; Tüm Blog Yazıları
                </a>
                <span class="badge-tag" style="margin-bottom: 0;"><?= e($post['category']) ?></span>
            </div>

            <h1 style="font-size: clamp(1.85rem, 3.2vw, 2.75rem); line-height: 1.25; margin-bottom: 1.25rem;">
                <?= e($post['title']) ?>
            </h1>

            <div style="display: flex; align-items: center; gap: 1.5rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; flex-wrap: wrap; border-top: 1px solid var(--border-subtle); padding-top: 1rem; margin-top: 1.5rem;">
                <span>Yazar: <strong><?= e($post['author']) ?></strong></span>
                <span>&bull;</span>
                <span>Yayınlanma: <?= date('d F Y', strtotime($post['published_at'])) ?></span>
            </div>
        </div>
    </header>

    <!-- Makale Gövdesi -->
    <section class="service-detail-body">
        <div class="container container-narrow">
            <?php if (!empty($post['image_url'])): ?>
            <div style="margin-bottom: 2.5rem; border-radius: var(--radius-main); overflow: hidden; max-height: 480px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                <img src="<?= e(url($post['image_url'])) ?>" alt="<?= e($post['title']) ?>" style="width: 100%; height: 100%; max-height: 480px; object-fit: cover; display: block;">
            </div>
            <?php endif; ?>

            <!-- Özet Kutusu -->
            <div style="background-color: var(--bg-card-light); border-left: 3px solid var(--bg-card-sage); padding: 1.5rem 1.75rem; margin-bottom: 2.75rem; font-size: 1.1rem; line-height: 1.75; color: var(--text-dark);">
                <?= e($post['summary']) ?>
            </div>

            <!-- Ana Metin -->
            <div class="blog-article-content" style="font-size: 1.15rem; line-height: 1.9; color: var(--text-dark); margin-bottom: 3.5rem;">
                <?= $post['content'] ?>
            </div>

            <!-- Paylaşım & Bilgilendirme Çubuğu -->
            <div class="blog-share-bar" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding: 1.5rem 0; border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle); margin-bottom: 3.5rem;">
                <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-dark);">
                    Bu Yazıyı Faydalı Buldunuz Mu?
                </span>
                <a href="https://api.whatsapp.com/send?text=<?= rawurlencode($post['title'] . ' - ' . $articleUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="font-size: 0.78rem; padding: 0.65rem 1.25rem;">
                    WhatsApp ile Paylaş
                </a>
            </div>

            <!-- Randevu Çağrısı Kartı -->
            <div class="editorial-card sage" style="min-height: auto; text-align: center; padding: 3rem 2rem; border-radius: 4px;">
                <span class="badge-tag light">Kişiye Özel Değerlendirme</span>
                <h2 style="margin-bottom: 1rem; color: #FFFFFF; font-size: 1.85rem;">Bu Problemle İlgili <em>Desteğe İhtiyacınız Var mı?</em></h2>
                <p style="max-width: 600px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.92); font-size: 1rem; line-height: 1.7;">
                    Fzt. Evrim Şenol Akarca ile bire bir klinik değerlendirme ve durumunuza özel fizyoterapi programı oluşturmak için iletişime geçebilirsiniz.
                </p>
                <div>
                    <a href="https://wa.me/<?= e(get_setting('whatsapp_raw', '905324700480')) ?>?text=<?= rawurlencode('Merhaba Fzt. Evrim Şenol Akarca, "' . $post['title'] . '" yazınızı okudum, bilgi ve randevu almak istiyorum.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-white" style="font-size: 0.82rem; padding: 0.95rem 2rem;">
                        WhatsApp ile Bilgi ve Randevu Alın
                    </a>
                </div>
            </div>
        </div>
    </section>
</article>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
