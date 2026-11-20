<?php
declare(strict_types=1);

http_response_code(404);

$pageTitle = 'Sayfa Bulunamadı (404) | Fzt. Evrim Şenol Akarca';
$pageDescription = 'Aradığınız sayfa silinmiş, adı değiştirilmiş veya geçici olarak kullanım dışı olabilir.';

require_once __DIR__ . '/includes/header.php';
?>

<section class="service-detail-header" style="padding: 6rem 1rem 4rem 1rem;">
    <div class="container container-narrow">
        <span class="badge-tag">Hata Kodu: 404</span>
        <h1 style="font-size: clamp(2.2rem, 4vw, 3.2rem); margin-top: 1rem;">
            Aradığınız Sayfa <em>Bulunamadı</em>
        </h1>
        <p style="font-size: 1.15rem; margin: 1.5rem auto 2.5rem auto; max-width: 580px; color: var(--text-muted); line-height: 1.7;">
            Ulaşmaya çalıştığınız sayfa taşınmış, yayından kaldırılmış veya bağlantı adresi değişmiş olabilir. Aşağıdaki bağlantıları kullanarak ana sayfaya dönebilir veya doğrudan kliniğimize ulaşabilirsiniz.
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= e(url()) ?>" class="btn btn-primary" style="padding: 0.95rem 2rem;">
                Ana Sayfaya Dönün
            </a>
            <a href="<?= e(url('hizmetlerimiz')) ?>" class="btn btn-outline" style="padding: 0.95rem 2rem;">
                Hizmetlerimizi İnceleyin
            </a>
            <a href="https://wa.me/<?= e(get_setting('whatsapp_raw', '905324700480')) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="padding: 0.95rem 2rem;">
                WhatsApp ile Danışın
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
