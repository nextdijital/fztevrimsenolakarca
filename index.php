<?php
declare(strict_types=1);

$pageTitle = 'Ana Sayfa';
$pageDescription = 'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi Adana. Pelvik taban, biofeedback, lipödem, lenfödem, omurga sağlığı ve kişiye özel fizyoterapi.';

require_once __DIR__ . '/includes/header.php';

// Dinamik içerikleri çek
$heroContent = get_page_content('home', 'hero');
$approachContent = get_page_content('home', 'approach');
$vmContent = get_page_content('home', 'vision_mission');
$services = get_all_services(true);

$heroButtons = !empty($heroContent['extra_data']) ? json_decode($heroContent['extra_data'], true) : [];
$approachButtons = !empty($approachContent['extra_data']) ? json_decode($approachContent['extra_data'], true) : [];
$vmData = !empty($vmContent['extra_data']) ? json_decode($vmContent['extra_data'], true) : [];
$heroBgImg = !empty($heroButtons['image_url']) ? url($heroButtons['image_url']) : url('assets/img/hero-banner.webp');
?>

<!-- ==============================================================================
     BÖLÜM 1: HERO ALANI (Görseldeki İle Birebir Fotoğraflı Editorial Banner)
============================================================================== -->
<section class="hero-section">
    <div class="hero-container-wide">
        <div class="hero-frame" style="background-image: linear-gradient(to right, rgba(16, 18, 16, 0.85) 0%, rgba(16, 18, 16, 0.55) 45%, rgba(16, 18, 16, 0.1) 72%, transparent 100%), url('<?= e($heroBgImg) ?>');">
            <!-- Üst Sağ Küçük Rozet / Aksan (Görseldeki Beyaz Kutu / Rozet) -->
            <div class="hero-badge-topright">
                <span class="hero-badge-dot"></span>
                <span>Adana / Seyhan</span>
            </div>

            <!-- Sol Tipografik İçerik Alanı -->
            <div class="hero-overlay-content">
                <span class="badge-tag light"><?= e($heroContent['subtitle'] ?? 'Özel Sağlık Meslek Hizmet Birimi') ?></span>
                
                <h1 class="hero-title">
                    Her Bedenin Hikâyesi Farklıdır.<br>
                    Fizyoterapi de Kişiye Özel Olmalıdır.
                </h1>

                <?php 
                $rawHeroDesc = (string)($heroContent['content'] ?? '');
                $cleanHeroDesc = trim(strip_tags(html_entity_decode($rawHeroDesc, ENT_QUOTES, 'UTF-8')));
                if ($cleanHeroDesc === '') {
                    $cleanHeroDesc = "Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi olarak; Adana'da sunduğumuz fizyoterapi hizmetlerinde bilimsel temelli değerlendirme yöntemlerini, manuel terapi uygulamalarını ve kişiye özel egzersiz programlarını bir araya getiriyoruz.";
                }
                ?>
                <p class="hero-description"><?= e($cleanHeroDesc) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ==============================================================================
     BÖLÜM 2: EDİTORİAL ÇİFT KART ALANI (Görseldeki "La Formazione" & "Shop" Stili)
============================================================================== -->
<section class="dual-cards-section">
    <div class="container container-wide">
        <div class="dual-cards-grid">
            <!-- Sol Kart: Adaçayı Yeşili Kurumsal Kart (Soldan İçe Geliş) -->
            <div class="editorial-card sage reveal-from-left">
                <div class="card-top">
                    <span class="badge-tag light"><?= e($approachContent['subtitle'] ?? 'Klinik Yaklaşımımız') ?></span>
                    <h2 class="card-title"><?= e($approachContent['title'] ?? 'Yalnızca Şikâyetlere Değil, İnsanın Bütününe Odaklanıyoruz') ?></h2>
                    <?php 
                    $rawApproachDesc = (string)($approachContent['content'] ?? '');
                    $cleanApproachDesc = trim(strip_tags(html_entity_decode($rawApproachDesc, ENT_QUOTES, 'UTF-8')));
                    if ($cleanApproachDesc === '') {
                        $cleanApproachDesc = 'Fizyoterapi yaklaşımımızın merkezinde yalnızca ağrı değil, bireyin kendisi yer alır.';
                    }
                    ?>
                    <p><?= e($cleanApproachDesc) ?></p>
                </div>
                <div class="card-bottom">
                    <a href="<?= e(!empty($approachButtons['btn1_url']) ? $approachButtons['btn1_url'] : url('hakkimizda')) ?>" class="btn btn-white" style="font-size: 0.78rem; padding: 0.8rem 1.6rem;">
                        <?= e(!empty($approachButtons['btn1_text']) ? $approachButtons['btn1_text'] : 'Yaklaşımımızı İnceleyin') ?>
                    </a>
                </div>
            </div>

            <!-- Sağ Kart: Biofeedback İleri Teknoloji & Cihaz Kartı (Sağdan İçe Geliş) -->
            <div class="editorial-card biofeedback-editorial-card reveal-from-right" style="position: relative; overflow: hidden; background-color: #141A1C; background-image: linear-gradient(135deg, rgba(16, 22, 24, 0.88) 0%, rgba(16, 22, 24, 0.65) 55%, rgba(16, 22, 24, 0.35) 100%), url('<?= e(url('assets/img/feedbackcihaz.webp')) ?>'); background-size: cover; background-position: center right; color: #FFFFFF; border: 1px solid rgba(255, 255, 255, 0.15);">
                <div class="card-top" style="position: relative; z-index: 2;">
                    <span class="badge-tag light" style="margin-bottom: 0.85rem;">İleri Teknoloji & Rehabilitasyon</span>
                    <h2 class="card-title" style="color: #FFFFFF; font-size: clamp(1.6rem, 2.3vw, 2.1rem); line-height: 1.25;">
                        Biofeedback Destekli <em>Pelvik Taban</em> Fizyoterapisi
                    </h2>
                </div>
                <div class="card-bottom" style="position: relative; z-index: 2; display: flex; gap: 0.85rem; flex-wrap: wrap; justify-content: flex-start; align-items: center;">
                    <?php 
                    $btn2Label = !empty($approachButtons['btn2_text']) ? $approachButtons['btn2_text'] : "Biofeedback'i Keşfedin →";
                    $btn2Label = html_entity_decode($btn2Label, ENT_QUOTES, 'UTF-8');
                    $btn2Label = str_ireplace(['&rarr;', '→'], '', $btn2Label) . ' →';
                    ?>
                    <a href="<?= e(!empty($approachButtons['btn2_url']) ? $approachButtons['btn2_url'] : url('hizmetler/pelvik-taban-fizyoterapisi#biofeedback')) ?>" class="btn btn-white" style="font-size: 0.78rem; padding: 0.85rem 1.45rem;">
                        <?= e($btn2Label) ?>
                    </a>
                    <a href="https://wa.me/<?= e(get_setting('whatsapp_raw', '905324700480')) ?>?text=<?= rawurlencode('Merhaba Fzt. Evrim Şenol Akarca, Biofeedback destekli fizyoterapi hakkında bilgi almak istiyorum.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-white" style="font-size: 0.78rem; padding: 0.85rem 1.25rem;">
                        WhatsApp ile Danışın
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==============================================================================
     BÖLÜM 3: HİZMETLERİMİZ (Görseldeki "Calendario" Bölümü Formatı - Temiz Tipografik Kartlar)
============================================================================== -->
<section class="section-services">
    <div class="container container-wide">
        <div class="section-header">
            <div>
                <span class="badge-tag">Uzmanlık Alanlarımız</span>
                <h2 class="section-title">Hangi Sorunlarda <em>Destek Oluyoruz?</em></h2>
            </div>
            <div>
                <a href="<?= e(url('hizmetlerimiz')) ?>" class="section-link">Tüm Hizmetleri Gör &rarr;</a>
            </div>
        </div>

        <div class="services-grid">
            <?php 
            $i = 1;
            foreach ($services as $srv): 
                $cardImg = !empty($srv['image_url']) ? $srv['image_url'] : 'assets/img/hizmetler/hizmet-' . $i . '.jpg';
                $isActive = ($i === 2) ? ' active' : '';
            ?>
            <div class="service-card<?= $isActive ?>" style="--card-bg-img: url('<?= e(url($cardImg)) ?>');">
                <div>
                    <span class="service-num"><?= sprintf('%02d', $i++) ?> —</span>
                    <h3 class="service-card-title"><?= e($srv['title']) ?></h3>
                    <p class="service-card-desc"><?= e($srv['short_description']) ?></p>
                </div>
                <a href="<?= e(url('hizmetler/' . $srv['slug'])) ?>" class="service-card-link">
                    <span>Detaylı İncele</span>
                    <span>&rarr;</span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ==============================================================================
     BÖLÜM 4: KLİNİK MANİFESTOSU (Görseldeki "Catalogo Corsi" Bölümü Stili)
============================================================================== -->
<section class="approach-section">
    <div class="container container-wide">
        <div class="approach-split">
            <!-- Sol Editorial Alıntı Kartı -->
            <div class="approach-visual">
                <span class="badge-tag light">Klinik Felsefemiz</span>
                <p class="approach-quote">
                    "İyi bir fizyoterapi süreci, yalnızca görüntüleme sonuçlarına değil; insanın bütününe, günlük yaşam alışkanlıklarına ve beden farkındalığına odaklanır."
                </p>
                <div class="approach-author">Fzt. Evrim Şenol Akarca &mdash; Adana</div>
            </div>

            <!-- Sağ Liste -->
            <div class="approach-content">
                <h2>Bilimsel Temelli, <em>Güvenilir</em> ve Sürdürülebilir Süreç</h2>
                <p>Uyguladığımız her terapi protokolü; anatomik değerlendirme, fonksiyonel hareket testleri ve kişinin kendi yaşam koşullarına tam uyumlu olarak tasarlanır.</p>

                <ul class="editorial-list">
                    <li class="editorial-list-item">
                        <span>Ayrıntılı Fonksiyonel Değerlendirme</span>
                        <span class="indicator">Aşama 01</span>
                    </li>
                    <li class="editorial-list-item">
                        <span>Kişiye Özel Manuel Terapi & Egzersiz</span>
                        <span class="indicator">Aşama 02</span>
                    </li>
                    <li class="editorial-list-item">
                        <span>Biofeedback ile Hedefe Yönelik Kas Eğitimi</span>
                        <span class="indicator">Aşama 03</span>
                    </li>
                    <li class="editorial-list-item">
                        <span>Sürdürülebilir Hareket ve Koruyucu Eğitim</span>
                        <span class="indicator">Aşama 04</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ==============================================================================
     BÖLÜM 5: VİZYON & MİSYON VURGU ALANI
============================================================================== -->
<section class="vision-mission-section">
    <div class="container container-wide">
        <div class="vm-grid">
            <div class="vm-box">
                <span class="badge-tag">İlkelerimiz</span>
                <h3>Vizyonumuz</h3>
                <p><?= e($vmData['vision'] ?? 'Bilimsel bilgi ve bütüncül değerlendirme yaklaşımıyla kişiye özel, sürdürülebilir ve güvenilir fizyoterapi hizmeti sunmak.') ?></p>
            </div>
            <div class="vm-box">
                <span class="badge-tag">Hedefimiz</span>
                <h3>Misyonumuz</h3>
                <p><?= e($vmData['mission'] ?? 'Her bireyin ihtiyaçlarını ve yaşam koşullarını dikkate alarak hareket özgürlüğünü, fonksiyonel bağımsızlığını ve yaşam kalitesini desteklemek.') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ==============================================================================
     BÖLÜM: GÜNCEL BLOG & SAĞLIK REHBERİ (Görseldeki İle Birebir 3'lü Format)
============================================================================== -->
<section class="section-home-blog">
    <div class="container container-wide">
        <div class="home-blog-header">
            <h2 class="home-blog-main-title">BLOG</h2>
            <a href="<?= e(url('blog')) ?>" class="section-link">Tüm Yazıları Gör &rarr;</a>
        </div>

        <div class="home-blog-grid">
            <?php 
            $homeBlogPosts = get_all_blog_posts(true, 3);
            $blogImages = ['assets/img/blog/blog-1.jpg', 'assets/img/blog/blog-2.jpg', 'assets/img/blog/blog-3.jpg'];
            $idx = 0;
            foreach ($homeBlogPosts as $hbp): 
                $imgSrc = !empty($hbp['image_url']) ? $hbp['image_url'] : (!empty($hbp['cover_image']) ? $hbp['cover_image'] : ($blogImages[$idx % count($blogImages)] ?? 'assets/img/blog/blog-1.jpg'));
                $idx++;
            ?>
            <article class="home-blog-card">
                <a href="<?= e(url('blog/' . $hbp['slug'])) ?>" class="home-blog-img-link">
                    <img src="<?= e(url($imgSrc)) ?>" alt="<?= e($hbp['title']) ?>" class="home-blog-img" loading="lazy">
                </a>
                
                <h3 class="home-blog-card-title">
                    <a href="<?= e(url('blog/' . $hbp['slug'])) ?>">
                        <?= e($hbp['title']) ?>
                    </a>
                </h3>

                <div class="home-blog-btn-wrap">
                    <a href="<?= e(url('blog/' . $hbp['slug'])) ?>" class="home-blog-btn">
                        <span class="btn-arrow-box">&gt;</span>
                        <span class="btn-text">DEVAMINI OKU</span>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==============================================================================
     BÖLÜM 6: ÖN GÖRÜŞME & RANDEVU ÇAĞRISI KARTI (Diğer Sayfalardaki Kartın Aynısı)
============================================================================== -->
<section style="padding: 4.5rem 0; border-top: 1px solid var(--border-subtle);">
    <div class="container container-wide">
        <div class="editorial-card sage" style="min-height: auto; text-align: center; padding: 3.5rem 2.5rem; border-radius: var(--radius-main);">
            <span class="badge-tag light">Sağlıklı Hareket ve Beden Farkındalığı</span>
            <h2 style="font-size: 2rem; margin-bottom: 1.25rem; color: #FFFFFF;">Bedeninizi Yeniden Dinlemeye <em>Hazır Mısınız?</em></h2>
            <p style="max-width: 650px; margin: 0 auto 2.25rem auto; color: rgba(255, 255, 255, 0.92); font-size: 1.05rem; line-height: 1.7;">
                Şikâyetlerinizin kaynağını birlikte değerlendirmek, hareket kapasitenizi geliştirmek ve size en uygun fizyoterapi yol haritasını belirlemek için randevu oluşturabilirsiniz.
            </p>
            <div class="home-cta-btn-group" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="https://wa.me/<?= e(get_setting('whatsapp_raw', '905324700480')) ?>?text=<?= rawurlencode('Merhaba Fzt. Evrim Şenol Akarca, bilgi ve randevu almak istiyorum.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-white" style="font-size: 0.82rem; padding: 0.95rem 2rem;">
                    WhatsApp ile Randevu Alın
                </a>
                <a href="<?= e(url('iletisim')) ?>" class="btn btn-outline-white" style="font-size: 0.82rem; padding: 0.95rem 2rem;">
                    İletişime Geçin & Harita Konumu
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
