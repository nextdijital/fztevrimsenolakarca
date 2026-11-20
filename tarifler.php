<?php
declare(strict_types=1);

$pageTitle = 'Sağlıklı & İyileştirici Tarifler';
$pageDescription = 'Fizyoterapist Evrim Şenol Akarca. Pelvik taban, lenfödem, lipödem, omurga ve eklem sağlığı için anti-inflamatuar ve ödem atıcı klinik beslenme tarifleri.';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Üst Başlık Alanı -->
<section class="service-detail-header">
    <div class="container container-narrow">
        <span class="badge-tag">Klinik Beslenme & Bütüncül Destek</span>
        <h1>İyileşmeyi Destekleyen <em>Klinik Tarifler</em></h1>
        <p style="font-size: 1.15rem; margin-top: 1rem; color: var(--text-muted);">
            Fizyoterapi seanslarımızın başarısını kalıcı kılmak, doku yenilenmesini desteklemek ve vücuttaki kronik ödem ile inflamasyonu hafifletmek için danışanlarımıza önerdiğimiz bilimsel temelli tarifler.
        </p>
    </div>
</section>

<!-- Tarifler Listesi -->
<section class="service-detail-body">
    <div class="container container-wide">
        <!-- Klinik Manifesto Vurgusu -->
        <div style="background-color: #F8F9FA; border-left: 4px solid var(--bg-card-sage); border-radius: var(--radius-btn); padding: 1.75rem 2rem; margin-bottom: 3.5rem;">
            <p style="font-size: 1.05rem; line-height: 1.7; color: var(--text-dark); margin-bottom: 0;">
                <em>"Pelvik taban kaslarının aşırı zorlanmasından lenfödem şişliklerine kadar pek çok durum, beslenme alışkanlıklarımız ve hücresel inflamasyon seviyemizle doğrudan ilişkilidir. Doğru besinler, uyguladığımız manuel terapi ve egzersiz programlarının etkisini katlayarak artırır."</em>
                <strong style="display: block; margin-top: 0.5rem; font-size: 0.88rem; color: var(--brand-blue-deep);">&mdash; Fzt. Evrim Şenol Akarca</strong>
            </p>
        </div>

        <div class="recipes-grid">
            <!-- Tarif 1 -->
            <article class="recipe-card">
                <div class="recipe-img-wrap">
                    <img src="<?= e(url('assets/img/tarifler/tarif-1.jpg')) ?>" alt="Lenfödem ve Lipödem İçin Altın Ödem Atıcı Detoks" class="recipe-img" loading="lazy">
                </div>
                <div class="recipe-header-meta">
                    <span class="badge-tag" style="margin-bottom: 0; font-size: 0.68rem;">Lenfödem & Dolaşım</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">5 Dk Hazırlık</span>
                </div>
                <h2 class="recipe-title">Lenfödem & Lipödem İçin Altın Ödem Atıcı İçecek</h2>
                <p class="recipe-summary">Bacak ve kollarda biriken lenf sıvısının hareketlenmesini ve kılcal dolaşımın rahatlamasını destekler.</p>
                
                <span class="recipe-section-title">Gerekli Malzemeler:</span>
                <ul class="recipe-ingredients-list">
                    <li>1 adet taze salatalık (kabuklu)</li>
                    <li>2 adet sap kereviz</li>
                    <li>Yarım demet maydanoz</li>
                    <li>1 fındık büyüklüğünde taze zencefil</li>
                    <li>Yarım limonun taze sıkılmış suyu</li>
                </ul>

                <div class="recipe-benefit-box">
                    <strong>Klinik Faydası:</strong> Hücreler arası lenf dolaşımını uyarır, doku sertliğini yumuşatır ve dolaşım kaynaklı ağırlık hissini hafifletir.
                </div>
            </article>

            <!-- Tarif 2 -->
            <article class="recipe-card">
                <div class="recipe-img-wrap">
                    <img src="<?= e(url('assets/img/tarifler/tarif-2.jpg')) ?>" alt="Pelvik Taban ve Bağırsak Sağlığı İçin Lif Deposu Smoothie" class="recipe-img" loading="lazy">
                </div>
                <div class="recipe-header-meta">
                    <span class="badge-tag" style="margin-bottom: 0; font-size: 0.68rem;">Pelvik Taban & Sindirim</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">7 Dk Hazırlık</span>
                </div>
                <h2 class="recipe-title">Pelvik Taban & Bağırsak Dostu Lif Deposu Smoothie</h2>
                <p class="recipe-summary">Kronik kabızlık ve ıkınmayı önleyerek leğen kemiği taban kaslarını gereksiz basınca karşı korur.</p>
                
                <span class="recipe-section-title">Gerekli Malzemeler:</span>
                <ul class="recipe-ingredients-list">
                    <li>1 yemek kaşığı taze çekilmiş keten tohumu</li>
                    <li>1 su bardağı ev yapımı probiyotik kefir</li>
                    <li>1 tatlı kaşığı chia tohumu</li>
                    <li>1 avuç yaban mersini (antioksidan)</li>
                    <li>1 avuç taze körpe ıspanak</li>
                </ul>

                <div class="recipe-benefit-box">
                    <strong>Klinik Faydası:</strong> Düzenli dışkılama ritmi kazandırır; kronik ıkınmanın pelvik taban kaslarında yol açtığı mikroyırtık ve sarkma riskini önler.
                </div>
            </article>

            <!-- Tarif 3 -->
            <article class="recipe-card">
                <div class="recipe-img-wrap">
                    <img src="<?= e(url('assets/img/tarifler/tarif-3.jpg')) ?>" alt="Eklem ve Omurga Ağrıları İçin Anti-İnflamatuar Altın Süt" class="recipe-img" loading="lazy">
                </div>
                <div class="recipe-header-meta">
                    <span class="badge-tag" style="margin-bottom: 0; font-size: 0.68rem;">Omurga & Eklem Sağlığı</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">10 Dk Hazırlık</span>
                </div>
                <h2 class="recipe-title">Eklem & Bel-Boyun Ağrıları İçin Altın Süt (Golden Milk)</h2>
                <p class="recipe-summary">Fıtık ve kas tutulmalarında sinir köklerindeki enflamatuar baskıyı hafifleten kadim bir klinik destek.</p>
                
                <span class="recipe-section-title">Gerekli Malzemeler:</span>
                <ul class="recipe-ingredients-list">
                    <li>1 tatlı kaşığı saf zerdeçal tozu (kurkumin)</li>
                    <li>1 çimdik taze çekilmiş karabiber (emilimi 20 kat artırır)</li>
                    <li>1 su bardağı badem sütü veya laktozsuz süt</li>
                    <li>Yarım çay kaşığı toz Seylan tarçını</li>
                    <li>Yarım çay kaşığı soğuk sıkım Hindistan cevizi yağı</li>
                </ul>

                <div class="recipe-benefit-box">
                    <strong>Klinik Faydası:</strong> Omurga fıtıklarında ve eklem kireçlenmelerinde doku inflamasyonunu baskılayarak hareket konforunu artırır.
                </div>
            </article>

            <!-- Tarif 4 -->
            <article class="recipe-card">
                <div class="recipe-img-wrap">
                    <img src="<?= e(url('assets/img/tarifler/tarif-4.jpg')) ?>" alt="Çene Sıkma (Bruksizm) & Kas Spazmı İçin Gevşetici Çay" class="recipe-img" loading="lazy">
                </div>
                <div class="recipe-header-meta">
                    <span class="badge-tag" style="margin-bottom: 0; font-size: 0.68rem;">Çene (TME) & Kas Gevşeme</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">8 Dk Hazırlık</span>
                </div>
                <h2 class="recipe-title">Çene Sıkma (Bruksizm) & Kas Spazmı Gevşetici Çay</h2>
                <p class="recipe-summary">Gece diş sıkma ve baş-boyun gerilim tipi ağrılarda nöromüsküler gevşemeyi destekler.</p>
                
                <span class="recipe-section-title">Gerekli Malzemeler:</span>
                <ul class="recipe-ingredients-list">
                    <li>1 tatlı kaşığı tıbbi papatya çiçeği</li>
                    <li>1 tatlı kaşığı melisa (oğul otu) yaprağı</li>
                    <li>Yarım çay kaşığı tıbbi lavanta</li>
                    <li>2 adet ezilmiş kakule tohumu</li>
                    <li>1 dilim taze zencefil</li>
                </ul>

                <div class="recipe-benefit-box">
                    <strong>Klinik Faydası:</strong> Çiğneme kasları (masseter) ve boyun fasyasındaki istemsiz gerginliği yatıştırarak gece diş sıkmayı hafifletir.
                </div>
            </article>

            <!-- Tarif 5 -->
            <article class="recipe-card">
                <div class="recipe-img-wrap">
                    <img src="<?= e(url('assets/img/tarifler/tarif-5.jpg')) ?>" alt="Kişiye Özel Egzersiz Sonrası Kas Onarıcı Yeşil İksir" class="recipe-img" loading="lazy">
                </div>
                <div class="recipe-header-meta">
                    <span class="badge-tag" style="margin-bottom: 0; font-size: 0.68rem;">Egzersiz & Doku Onarımı</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">6 Dk Hazırlık</span>
                </div>
                <h2 class="recipe-title">Egzersiz & Terapi Sonrası Kas Onarıcı Yeşil İksir</h2>
                <p class="recipe-summary">Rehabilitasyon egzersizleri sonrasında kas liflerinin mikro travmalarını hızla toparlar.</p>
                
                <span class="recipe-section-title">Gerekli Malzemeler:</span>
                <ul class="recipe-ingredients-list">
                    <li>Yarım olgunlaşmış avokado (sağlıklı yağ asitleri)</li>
                    <li>1 su bardağı doğal Hindistan cevizi suyu</li>
                    <li>Yarım ekşi yeşil elma</li>
                    <li>1 tatlı kaşığı çiğ kabak çekirdeği (doğal çinko)</li>
                    <li>1 çay kaşığı spirulina veya yeşil matcha</li>
                </ul>

                <div class="recipe-benefit-box">
                    <strong>Klinik Faydası:</strong> Egzersiz sonrası laktik asit atılımını hızlandırır, kas yorgunluğunu önler ve doku elastikiyetini güçlendirir.
                </div>
            </article>

            <!-- Tarif 6 -->
            <article class="recipe-card">
                <div class="recipe-img-wrap">
                    <img src="<?= e(url('assets/img/tarifler/tarif-6.jpg')) ?>" alt="Kronik Şişlikler İçin Doğal Bromelain İçeceği" class="recipe-img" loading="lazy">
                </div>
                <div class="recipe-header-meta">
                    <span class="badge-tag" style="margin-bottom: 0; font-size: 0.68rem;">Lipödem & Şişlik</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">5 Dk Hazırlık</span>
                </div>
                <h2 class="recipe-title">Kronik Şişlik & Ödem İçin Doğal Bromelain İçeceği</h2>
                <p class="recipe-summary">Lipödem ve ameliyat sonrası fibrotik doku sertliklerinde enzim desteği sunar.</p>
                
                <span class="recipe-section-title">Gerekli Malzemeler:</span>
                <ul class="recipe-ingredients-list">
                    <li>2 kalın dilim taze ananas (orta sert lifli kısmı dahil)</li>
                    <li>1 tatlı kaşığı rendelenmiş taze zencefil</li>
                    <li>Yarım taze limon suyu</li>
                    <li>1 şişe doğal zengin mineralli maden suyu</li>
                    <li>5-6 yaprak taze fesleğen veya nane</li>
                </ul>

                <div class="recipe-benefit-box">
                    <strong>Klinik Faydası:</strong> Doğal bromelain enzimi ile dokulardaki sertleşmiş protein birikimlerini parçalayarak venöz akışı destekler.
                </div>
            </article>
        </div>

        <!-- Randevu & Danışma Kartı -->
        <div class="editorial-card sage" style="min-height: auto; text-align: center; padding: 3.5rem 2.5rem; border-radius: var(--radius-main); margin-top: 2rem;">
            <span class="badge-tag light">Kişiye Özel Tedavi & Beslenme Koordinasyonu</span>
            <h2 style="font-size: 1.95rem; margin-bottom: 1.15rem; color: #FFFFFF;">Sağlık Sorunlarınız İçin <em>Bütüncül Değerlendirme Alın</em></h2>
            <p style="max-width: 620px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.92); font-size: 1.02rem; line-height: 1.7;">
                Şikâyetlerinizin kaynağını birlikte değerlendirmek, hareket kapasitenizi geliştirmek ve size özel fizyoterapi yol haritasını çizmek için kliniğimize ulaşabilirsiniz.
            </p>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="https://wa.me/<?= e($whatsappRaw) ?>?text=<?= rawurlencode('Merhaba Fzt. Evrim Şenol Akarca, tarifler sayfanızı inceledim, bilgi ve randevu almak istiyorum.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-white" style="font-size: 0.82rem; padding: 0.95rem 2rem;">
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
