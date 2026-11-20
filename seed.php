<?php
declare(strict_types=1);

$dsn = 'mysql:host=localhost;dbname=fztevrimsenol_db;charset=utf8mb4';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // 1. Admin Oluştur (varsa güncelle)
    $username = 'admin';
    $password = 'Evrim2026!*';
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $email = 'evrimmsenol@yahoo.com.tr';

    $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash, email, role) 
        VALUES (:u, :p, :e, 'admin') 
        ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), email = VALUES(email)");
    $stmt->execute([':u' => $username, ':p' => $password_hash, ':e' => $email]);
    echo "Admin olusturuldu (Kullanici: admin, Sifre: Evrim2026!*)\n";

    // 2. Site Ayarları
    $settings = [
        ['site_title', 'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi', 'general', 'Resmi Klinik Adı'],
        ['specialist_name', 'Fzt. Evrim Şenol Akarca', 'general', 'Yetkili Uzman Adı'],
        ['specialist_title', 'Fizyoterapist', 'general', 'Uzman Unvanı'],
        ['phone', '0532 470 04 80', 'contact', 'İletişim Telefonu'],
        ['phone_raw', '+905324700480', 'contact', 'Tıklanabilir Telefon'],
        ['whatsapp', '0532 470 04 80', 'contact', 'WhatsApp Numarası'],
        ['whatsapp_raw', '905324700480', 'contact', 'WhatsApp Bağlantı Numarası'],
        ['email', 'evrimmsenol@yahoo.com.tr', 'contact', 'E-Posta Adresi'],
        ['address', 'Hurmalı Mah. Kurtuluş Cad. No:39 Central Plaza Kat:8/81 Seyhan / Adana', 'contact', 'Klinik Adresi'],
        ['city', 'Adana', 'contact', 'Şehir'],
        ['district', 'Seyhan', 'contact', 'İlçe'],
        ['instagram', '@fzt.evrimsenol', 'social', 'Instagram Kullanıcı Adı'],
        ['instagram_url', 'https://instagram.com/fzt.evrimsenol', 'social', 'Instagram Profili'],
        ['working_hours', 'Pazartesi - Cumartesi: 09:00 - 18:30 (Ön Randevu ile)', 'general', 'Çalışma Saatleri'],
        ['meta_description', 'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi Adana. Pelvik taban, biofeedback, lipödem, lenfödem, omurga ve kişiye özel fizyoterapi.', 'seo', 'Genel Meta Açıklama'],
        ['google_maps_embed', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3186.297775988294!2d35.3182!3d36.9944!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15288f62f8373b95%3A0xc3c9f2256722d4f!2sKurtulu%C5%9F%20Cd.%20No%3A39%2C%20Hurmal%C4%B1%2C%2001060%20Seyhan%2FAdana!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str" width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', 'contact', 'Google Maps Embed Kodu']
    ];

    $stmtSetting = $pdo->prepare("INSERT INTO site_settings (key_name, key_value, group_name, description) 
        VALUES (:k, :v, :g, :d) 
        ON DUPLICATE KEY UPDATE key_value = VALUES(key_value), description = VALUES(description)");
    foreach ($settings as $s) {
        $stmtSetting->execute([':k' => $s[0], ':v' => $s[1], ':g' => $s[2], ':d' => $s[3]]);
    }
    echo "Site ayarlari eklendi.\n";

    // 3. Hizmetler
    $services = [
        [
            'slug' => 'pelvik-taban-fizyoterapisi',
            'title' => 'Pelvik Taban ve İdrar Kaçırma Problemleri',
            'short_description' => 'Kadınlarda, erkeklerde ve çocuklarda pelvik taban disfonksiyonları, idrar kaçırma problemleri ve biofeedback destekli modern kas rehabilitasyonu.',
            'full_content' => '<p>Pelvik taban kasları; leğen kemiğinin tabanında yer alan, mesane, rahim ve bağırsakları destekleyen, idrar ve dışkı kontrolünü sağlayan ve cinsel fonksiyonda kritik rol oynayan hayati bir kas grubudur. Bu kasların aşırı zayıflaması veya aşırı gergin/spazm halinde olması; idrar kaçırma, organ sarkması, kronik pelvik ağrı ve boşaltım problemlerine yol açar.</p><p>Adana’daki kliniğimizde uyguladığımız pelvik taban fizyoterapisi; bireyin kas fonksiyonunun ayrıntılı değerlendirilmesi, kişiye özel egzersiz reçeteleri, manuel terapi ve görsel-işitsel geri bildirim sağlayan <strong>Biofeedback</strong> teknolojisi ile güvenle yürütülmektedir.</p>',
            'bullet_points' => json_encode([
                'Kadınlarda ve erkeklerde idrar kaçırma',
                'Öksürürken, hapşırırken veya gülerken idrar kaçırma (Stres İnkontinans)',
                'Ani sıkışma hissi ve tuvalete yetişememe (Urge İnkontinans)',
                'Sık idrara çıkma ve mesane kontrol güçlüğü',
                'Çocuklarda gündüz idrar kaçırma ve gece alt ıslatma (Enürezis Noktürna)',
                'Dışkı veya gaz tutamama problemleri',
                'Cinsel ilişki sırasında ağrı (Disparoni, Vajinismus desteği)',
                'Vajina ve genital bölgede ağrı, yanma veya hassasiyet',
                'Pelvik taban kaslarında güçsüzlük veya aşırı gerginlik'
            ], JSON_UNESCAPED_UNICODE),
            'has_biofeedback' => 1,
            'biofeedback_title' => 'Biofeedback Destekli Fizyoterapi',
            'biofeedback_content' => '<p>Biofeedback yöntemi, pelvik taban kaslarının çalışma mekanizmasını görsel ve işitsel sensörler yardımıyla kişiye anlık olarak gösteren, bilimsel etkinliği kanıtlanmış bir eğitim ve tedavi teknolojisidir.</p><p>Çoğu kişi pelvik taban kaslarının tam nerede olduğunu ve nasıl doğru sıkıp gevşeteceğini hissetmekte zorlanır. Yanlış kasma karın kaslarını zorlayabilir ve sorunu artırabilir. Biofeedback cihazı sayesinde danışan, ekrandaki grafikler üzerinden kasını ne kadar doğru çalıştırdığını ve ne kadar gevşettiğini somut olarak gözlemler. Çocuklarda ise bu süreç özel oyun tabanlı yazılımlar ile eğlenceli ve korkusuz bir kas kontrol eğitimine dönüştürülür.</p>',
            'biofeedback_bullets' => json_encode([
                'İdrar kontrolünü geliştirmeye yönelik hedefe yönelik kas eğitimi',
                'Pelvik taban kaslarını doğru çalıştırmayı ve koordine etmeyi öğrenme',
                'Çocuklarda oyunla desteklenen biofeedback uygulamaları (alt ıslatma ve tutma)',
                'Pelvik taban kaslarının gevşeme ve koordinasyon eğitimi (aşırı gergin kaslar için)'
            ], JSON_UNESCAPED_UNICODE),
            'display_order' => 1
        ],
        [
            'slug' => 'lipodem-lenfodem-rehabilitasyonu',
            'title' => 'Lipödem, Lenfödem ve Şişlik Problemleri',
            'short_description' => 'Bacaklarda orantısız yağlanma ve hassasiyet (lipödem) ile lenf dolaşımı yetersizliğine bağlı şişliklerde (lenfödem) kompleks boşaltıcı fizyoterapi yaklaşımı.',
            'full_content' => '<p>Lipödem; özellikle kadınlarda bacaklarda ve bazen kollarda simetrik, ağrılı yağ dokusu birikimi ile karakterize kronik bir durumdur. Kolay morarma, bacaklarda ağırlaşma ve dokunmaya karşı yoğun hassasiyet en belirgin semptomlar arasındadır.</p><p>Lenfödem ise lenfatik sistemin yetersizliğine bağlı olarak dokularda lenf sıvısının birikmesiyle oluşan ilerleyici bir şişlik tablosudur; sıklıkla kanser cerrahileri (örneğin meme kanseri ameliyatı) veya doğuştan lenfatik yetmezlikler sonrasında gelişir.</p><p>Kliniğimizde; Manuel Lenf Drenajı (MLD), çok katmanlı kompresyon bandajlama, kişiye özel bası giysisi yönetimi, cilt bakımı ve lenfatik drenajı hızlandıran özel egzersizleri içeren Kompleks Boşaltıcı Fizyoterapi uygulanmaktadır.</p>',
            'bullet_points' => json_encode([
                'Bacaklarda orantısız kalınlaşma ve dokunma hassasiyeti (Lipödem)',
                'Bacaklarda ağrı, ağırlık hissi ve kolay morarma',
                'Kol veya bacaklarda lenf sıvısı birikimine bağlı şişlik (Lenfödem)',
                'Ameliyat ve travma sonrası gelişen lenfatik ödem',
                'Meme kanseri ameliyatı ve lenf nodu diseksiyonu sonrası kol şişliği',
                'Lipödem ve lenfödemde kompresyon, manuel lenf drenajı ve solunum/egzersiz uygulamaları'
            ], JSON_UNESCAPED_UNICODE),
            'has_biofeedback' => 0,
            'biofeedback_title' => null,
            'biofeedback_content' => null,
            'biofeedback_bullets' => null,
            'display_order' => 2
        ],
        [
            'slug' => 'bel-boyun-sirt-agrilari',
            'title' => 'Bel, Boyun ve Sırt Ağrıları',
            'short_description' => 'Bel ve boyun fıtıkları, omurga gerginlikleri, kalça/bacak/kol uyuşmaları ve uzun süre masa başı çalışmaya bağlı duruş bozukluklarında kanıta dayalı fizyoterapi.',
            'full_content' => '<p>Omurga, insan bedeninin ana taşıyıcı sütunudur. Günümüzün hareketsiz yaşam tarzı, uzun süreli masa başı oturma, yanlış yük kaldırma ve stres; bel, boyun ve sırt bölgesinde mekanik yüklenmelere, fıtıklara ve kas spazmlarına yol açmaktadır.</p><p>Biz omurga problemlerine yalnızca bir MR veya röntgen görüntüsü üzerinden yaklaşmıyoruz. Bireyin hareket kalıplarını, duruş dinamiklerini, kas dengesizliklerini ve günlük yaşam aktivitelerini kapsamlı şekilde değerlendiriyoruz. Manuel terapi, mobilizasyon teknikleri, faset eklem rahatlatma ve omurgayı koruyucu derin stabilizasyon egzersizleriyle kalıcı iyileşmeyi hedefliyoruz.</p>',
            'bullet_points' => json_encode([
                'Bel fıtığı (Lomber disk hernisi) ve mekanik bel ağrısı',
                'Boyun fıtığı (Servikal disk hernisi) ve boyun ağrısı',
                'Sırt ağrıları, kürek kemiği çevresi spazmlar ve kas gerginlikleri',
                'Kalçaya veya bacağa yayılan ağrılar (Siyatik sinir hassasiyeti)',
                'Boyundan kola ve parmaklara yayılan ağrı, uyuşma hissi',
                'Uzun süre oturmaya bağlı ağrı, postür bozukluğu ve hareket kısıtlılığı',
                'Kronikleşen ve tekrarlayan kas-iskelet sistemi ağrıları'
            ], JSON_UNESCAPED_UNICODE),
            'has_biofeedback' => 0,
            'biofeedback_title' => null,
            'biofeedback_content' => null,
            'biofeedback_bullets' => null,
            'display_order' => 3
        ],
        [
            'slug' => 'omuz-kalca-diz-problemleri',
            'title' => 'Omuz, Kalça ve Diz Problemleri',
            'short_description' => 'Donuk omuz, rotator kılıf yaralanmaları, kalça trokanterik ağrı, diz menisküs/bağ lezyonları ve protez ameliyatları sonrası fonksiyonel rehabilitasyon.',
            'full_content' => '<p>Omuz, kalça ve diz eklemleri günlük yaşamın hareket özgürlüğünü belirleyen en kritik ve yük taşıyan eklemlerdir. Bu eklemlerde meydana gelen tendon dejenerasyonları, bursitler, kıkırdak aşınmaları veya travmatik yaralanmalar kişinin yürümesini, merdiven çıkmasını veya kolunu kaldırmasını dahi imkânsız kılabilir.</p><p>Klinik değerlendirmemizde eklem hareket açıklığı, kas gücü, eklem kinematiği ve denge hassasiyetle test edilir. Donuk omuz açma protokolleri, omuz sıkışma sendromu (impingement) rehabilitasyonu, kalça stabilizasyonu ve diz kapağı (patellofemoral) uyumu için kişiselleştirilmiş programlar sunulur.</p>',
            'bullet_points' => json_encode([
                'Omuz ağrısı ve hareket kısıtlılığı',
                'Donuk omuz (Adeziv kapsülit) rehabilitasyonu',
                'Omuz kas ve tendon problemleri (Rotator kılıf lezyonları, impingement)',
                'Kalçanın yan tarafında ağrı (Trokanterik ağrı sendromu / Bursit)',
                'Diz eklemi ağrıları, menisküs ve bağ problemleri',
                'Diz kapağı çevresinde ağrı (Patellofemoral ağrı sendromu)',
                'Kalça ve diz eklem protezi ameliyatları sonrası rehabilitasyon',
                'Kas güçsüzlüğü, eklem sertliği ve denge/yürüme problemleri'
            ], JSON_UNESCAPED_UNICODE),
            'has_biofeedback' => 0,
            'biofeedback_title' => null,
            'biofeedback_content' => null,
            'biofeedback_bullets' => null,
            'display_order' => 4
        ],
        [
            'slug' => 'cene-bas-boyun-problemleri',
            'title' => 'Çene ve Baş-Boyun Bölgesi Problemleri',
            'short_description' => 'Çene eklemi (TME) disfonksiyonu, çiğneme kası spazmları, diş sıkma (bruksizm) ve çeneden ses gelmesi durumlarında manuel terapi ve kraniyomandibuler rehabilitasyon.',
            'full_content' => '<p>Temporomandibular eklem (TME), kafatası ile alt çene kemiğini birbirine bağlayan ve gün içinde konuşurken, çiğnerken ve yutkunurken binlerce kez çalışan son derece hassas bir eklemdir. Diş sıkma (bruksizm), stres, diş kapanış bozuklukları veya travmalar çene ekleminde diskin kaymasına ve şiddetli baş-boyun ağrılarına yol açar.</p><p>Çene fizyoterapisi; çiğneme kaslarına yönelik ağız içi ve dışı manuel gevşetme tekniklerini, eklem mobilizasyonunu, baş-boyun postürünün düzeltilmesini ve çene eklemi koordinasyon egzersizlerini kapsar.</p>',
            'bullet_points' => json_encode([
                'Çene ekleminde ağrı ve çiğnerken zorlanma',
                'Ağız açıp kapatırken çeneden tıkırtı, klik veya sürtünme sesi gelmesi',
                'Çene hareketlerinde kısıtlılık veya çenenin kilitlenmesi',
                'Gece veya gündüz diş sıkmayla (bruksizm) ilişkili çene ve şakak kası gerginliği',
                'Çene ve boyun bölgesindeki kas-iskelet sistemi problemleri ile baş ağrıları'
            ], JSON_UNESCAPED_UNICODE),
            'has_biofeedback' => 0,
            'biofeedback_title' => null,
            'biofeedback_content' => null,
            'biofeedback_bullets' => null,
            'display_order' => 5
        ],
        [
            'slug' => 'kisiye-ozel-egzersiz-egitimi',
            'title' => 'Kişiye Özel Egzersiz ve Hareket Eğitimi',
            'short_description' => 'Bireyin beden tipine, postürüne ve günlük yaşam hedeflerine özel bilimsel kuvvetlendirme, core stabilizasyonu ve yoga temelli terapötik hareket seansları.',
            'full_content' => '<p>Egzersiz, doğru dozda ve doğru teknikle uygulandığında en güçlü koruyucu ve iyileştirici fizyoterapi aracıdır. Ancak her bireyin kas uzunluğu, eklem esnekliği, omurga eğrilikleri ve hareket kabiliyeti birbirinden tamamen farklıdır. İnternetten veya genel listelerden kopyalanan egzersizler sıklıkla sakatlıklara neden olabilir.</p><p>Kişiye özel hareket eğitimimizde; detaylı postür analizi yapılır, zayıf kaslar belirlenerek kuvvetlendirilir, kısalmış ve gergin dokular esnetilir. Yoga temelli terapötik hareket farkındalığı ile zihin-beden uyumu desteklenerek bireye ömür boyu sürdürebileceği sağlıklı bir hareket alışkanlığı kazandırılır.</p>',
            'bullet_points' => json_encode([
                'Duruş (postür) ve hareket alışkanlıklarının biyomekanik değerlendirilmesi',
                'Kas güçsüzlüğü, doku sertlikleri ve hareket kısıtlılığı',
                'Denge ve nöromüsküler koordinasyon problemleri',
                'Günlük yaşam ve iş aktivitelerinde çabuk yorulma ve zorlanma',
                'Kişiye özel omurga ve core kuvvetlendirme/stabilizasyon egzersizleri',
                'Yoga temelli terapötik hareket ve nefes farkındalığı çalışmaları'
            ], JSON_UNESCAPED_UNICODE),
            'has_biofeedback' => 0,
            'biofeedback_title' => null,
            'biofeedback_content' => null,
            'biofeedback_bullets' => null,
            'display_order' => 6
        ]
    ];

    $stmtService = $pdo->prepare("INSERT INTO services 
        (slug, title, short_description, full_content, bullet_points, has_biofeedback, biofeedback_title, biofeedback_content, biofeedback_bullets, display_order)
        VALUES (:slug, :title, :short_desc, :full_content, :bullet_points, :has_biofeedback, :biofeedback_title, :biofeedback_content, :biofeedback_bullets, :display_order)
        ON DUPLICATE KEY UPDATE 
            title = VALUES(title), short_description = VALUES(short_description), full_content = VALUES(full_content), 
            bullet_points = VALUES(bullet_points), has_biofeedback = VALUES(has_biofeedback), 
            biofeedback_title = VALUES(biofeedback_title), biofeedback_content = VALUES(biofeedback_content), 
            biofeedback_bullets = VALUES(biofeedback_bullets), display_order = VALUES(display_order)");

    foreach ($services as $srv) {
        $stmtService->execute([
            ':slug' => $srv['slug'],
            ':title' => $srv['title'],
            ':short_desc' => $srv['short_description'],
            ':full_content' => $srv['full_content'],
            ':bullet_points' => $srv['bullet_points'],
            ':has_biofeedback' => $srv['has_biofeedback'],
            ':biofeedback_title' => $srv['biofeedback_title'],
            ':biofeedback_content' => $srv['biofeedback_content'],
            ':biofeedback_bullets' => $srv['biofeedback_bullets'],
            ':display_order' => $srv['display_order']
        ]);
    }
    echo "6 Hizmet basariyla eklendi.\n";

    // 4. Sıkça Sorulan Sorular (SSS)
    $faqs = [
        [
            'question' => 'Fizyoterapi değerlendirme süreci nasıl işler?',
            'answer' => 'İlk seansta danışanımızın sağlık geçmişi, hekim muayene raporları ve görüntüleme sonuçları detaylıca incelenir. Ardından kas-iskelet sistemi, hareket kalitesi, postür ve özel klinik testler uygulanarak sorunun kök nedeni tespit edilir. Değerlendirme sonucunda danışanın hedefleri ve yaşam tarzı doğrultusunda tamamen kişiye özel bir seans planı oluşturulur.',
            'order' => 1
        ],
        [
            'question' => 'Pelvik taban fizyoterapisi ve biofeedback uygulamaları kimler için uygundur?',
            'answer' => 'Öksürürken, gülerken veya aniden sıkışarak idrar kaçırma problemi yaşayan kadın ve erkekler, pelvik organ sarkması olanlar, cinsel ilişki veya genital bölgede ağrı yaşayanlar, prostat cerrahisi sonrası kontrol güçlüğü çekenler ve pelvik kas spazmı olan bireyler için uygundur. Biofeedback ile kas kontrolü güvenli ve acısız bir şekilde öğrenilir.',
            'order' => 2
        ],
        [
            'question' => 'Çocuklarda gece alt ıslatma ve gündüz idrar kaçırma için oyun destekli biofeedback nasıl uygulanır?',
            'answer' => 'Çocuklarda pelvik taban kaslarını doğru kullanabilme bilinci henüz tam oturmamış olabilir. Kliniğimizde uygulanan çocuk biofeedback seanslarında cilde yapıştırılan elektrotlar kullanılır; kesinlikle ağrılı veya invaziv bir işlem değildir. Çocuk, kaslarını doğru şekilde kastığında ekrandaki çizgi film karakterini veya oyunu hareket ettirir. Böylece oyun oynayarak mesane kontrolünü doğal ve keyifli bir yöntemle öğrenir.',
            'order' => 3
        ],
        [
            'question' => 'Lipödem ve lenfödem rehabilitasyonunda hangi yöntemler kullanılır?',
            'answer' => 'Lipödem ve lenfödem yönetiminde uluslararası kabul görmüş Kompleks Boşaltıcı Fizyoterapi protokolü uygulanır. Bu protokol; özel el manevralarıyla lenf sıvısının yönünü değiştiren Manuel Lenf Drenajı (MLD), çok tabakalı kompresyon bandajlaması, kişiye özel bası giysisi rehberliği, cilt bakımı ve lenfatik drenajı artıran solunum ve hareket egzersizlerini içerir.',
            'order' => 4
        ],
        [
            'question' => 'Seans süreleri ve sıklığı nasıl belirlenir?',
            'answer' => 'Seans süreleri genellikle 50 ila 60 dakika sürmektedir. Seansların sıklığı ve toplam tedavi süresi danışanın mevcut durumuna, doku iyileşme hızına ve hedeflerine göre ilk değerlendirmede şeffaf bir şekilde planlanır. Bireysel gelişime göre program düzenli olarak güncellenir.',
            'order' => 5
        ],
        [
            'question' => 'Kliniğinize nasıl ulaşabilir ve randevu oluşturabilirim?',
            'answer' => 'Kliniğimiz Adana Seyhan’da Kurtuluş Caddesi üzerinde bulunan Central Plaza Kat:8 No:81 adresinde yer almaktadır. Danışanlarımıza birebir odaklanabilmek adına tüm görüşmeler ve seanslar önceden randevu alınarak gerçekleştirilmektedir. 0532 470 04 80 numaralı telefonumuzdan bizi arayabilir, WhatsApp hattımız üzerinden yazabilir veya web sitemizdeki randevu formunu doldurabilirsiniz.',
            'order' => 6
        ]
    ];

    $stmtFaq = $pdo->prepare("INSERT INTO faqs (question, answer, display_order, is_active)
        VALUES (:q, :a, :o, 1)");
    $pdo->exec("TRUNCATE TABLE faqs");
    foreach ($faqs as $f) {
        $stmtFaq->execute([':q' => $f['question'], ':a' => $f['answer'], ':o' => $f['order']]);
    }
    echo "SSS sorulari eklendi.\n";

    // 5. Sayfa İçerikleri (pages_content)
    $pages = [
        // Ana Sayfa Bölümleri
        [
            'home', 'hero',
            'Her Bedenin Hikâyesi Farklıdır. Fizyoterapi de Kişiye Özel Olmalıdır.',
            'Adana | Özel Sağlık Meslek Hizmet Birimi',
            'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi olarak; Adana\'da sunduğumuz fizyoterapi hizmetlerinde bilimsel temelli değerlendirme yöntemlerini, manuel terapi uygulamalarını ve kişiye özel egzersiz programlarını bir araya getiriyoruz.',
            json_encode(['btn1_text' => 'WhatsApp ile Bilgi & Randevu Al', 'btn1_url' => 'https://wa.me/905324700480', 'btn2_text' => 'Hizmetlerimizi İnceleyin', 'btn2_url' => '/hizmetlerimiz'])
        ],
        [
            'home', 'approach',
            'Yalnızca Şikâyetlere Değil, İnsanın Bütününe Odaklanıyoruz',
            'Bütüncül Klinik Yaklaşımımız',
            'Fizyoterapi yaklaşımımızın merkezinde yalnızca ağrı veya şikâyetler değil, bireyin kendisi yer alır. Amacımız yalnızca mevcut şikâyetleri azaltmak değil; bireyin bedenini tanımasını, hareket kapasitesini geliştirmesini ve yaşam kalitesini artırmasını desteklemektir. Çünkü iyi bir fizyoterapi süreci, yalnızca görüntüleme sonuçlarına değil, insanın bütününe odaklanır.',
            null
        ],
        [
            'home', 'vision_mission',
            'Değerlerimiz ve İlkelerimiz',
            'Vizyon & Misyon',
            'Bilimsel bilgi ve bütüncül değerlendirme yaklaşımıyla kişiye özel, sürdürülebilir ve güvenilir fizyoterapi hizmeti sunmak vizyonumuz; her bireyin ihtiyaçlarını ve yaşam koşullarını dikkate alarak hareket özgürlüğünü, fonksiyonel bağımsızlığını ve yaşam kalitesini desteklemek misyonumuzdur.',
            json_encode([
                'vision' => 'Bilimsel bilgi ve bütüncül değerlendirme yaklaşımıyla kişiye özel, sürdürülebilir ve güvenilir fizyoterapi hizmeti sunmak.',
                'mission' => 'Her bireyin ihtiyaçlarını ve yaşam koşullarını dikkate alarak hareket özgürlüğünü, fonksiyonel bağımsızlığını ve yaşam kalitesini desteklemek.'
            ], JSON_UNESCAPED_UNICODE)
        ],
        // Hakkımızda Sayfası
        [
            'about', 'main',
            'Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi',
            'Bilimsel temelli değerlendirme ve bütüncül fizyoterapi yaklaşımı',
            "<p>Her bedenin hikâyesi farklıdır. Fizyoterapi de kişiye özel olmalıdır.</p><p>Fizyoterapi yaklaşımımın merkezinde yalnızca ağrı veya şikâyetler değil, bireyin kendisi yer alır. Adana’da sunduğum fizyoterapi hizmetlerinde bilimsel temelli değerlendirme yöntemlerini, manuel terapi uygulamalarını ve kişiye özel egzersiz programlarını bir araya getiriyorum.</p><p>Pelvik taban fizyoterapisi, lipödem ve lenfödem rehabilitasyonu, kas-iskelet sistemi problemleri ve hareket eğitimi başta olmak üzere farklı alanlarda çalışıyorum.</p><p>Amacım yalnızca mevcut şikâyetleri azaltmak değil; bireyin bedenini tanımasını, hareket kapasitesini geliştirmesini ve yaşam kalitesini artırmasını desteklemektir. Çünkü iyi bir fizyoterapi süreci, yalnızca görüntüleme sonuçlarına değil, insanın bütününe odaklanır.</p>",
            json_encode([
                'vision' => 'Bilimsel bilgi ve bütüncül değerlendirme yaklaşımıyla kişiye özel, sürdürülebilir ve güvenilir fizyoterapi hizmeti sunmak.',
                'mission' => 'Her bireyin ihtiyaçlarını ve yaşam koşullarını dikkate alarak hareket özgürlüğünü, fonksiyonel bağımsızlığını ve yaşam kalitesini desteklemek.'
            ], JSON_UNESCAPED_UNICODE)
        ],
        // KVKK Metni
        [
            'legal', 'kvkk',
            'Kişisel Verilerin Korunması Kanunu (KVKK) Aydınlatma Metni',
            '6698 Sayılı Kanun Uyarınca Bilgilendirme',
            '<p><strong>Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi</strong> (“Klinik / Veri Sorumlusu”) olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu (“KVKK”) ve ilgili mevzuat uyarınca kişisel verilerinizin güvenliğine ve gizliliğine azami önem veriyoruz.</p><h4>1. İşlenen Kişisel Veriler</h4><p>Web sitemizdeki iletişim ve randevu talep formları aracılığıyla adınız, soyadınız, telefon numaranız, e-posta adresiniz, ilgilendiğiniz hizmet konusu ve ilettiğiniz mesaj bilgileri toplanmaktadır.</p><h4>2. Verilerin İşlenme Amacı</h4><p>Toplanan kişisel verileriniz; randevu taleplerinizin oluşturulması, fizyoterapi hizmetleri öncesinde sizinle iletişime geçilmesi, soru ve taleplerinizin yanıtlanması ve yasal yükümlülüklerimizin yerine getirilmesi amaçlarıyla sınırlı olarak işlenir.</p><h4>3. Verilerin Aktarılması</h4><p>Kişisel verileriniz yasal zorunluluklar ve yetkili kamu kurumları talepleri haricinde üçüncü şahıs veya ticari kuruluşlara kesinlikle aktarılmaz ve satılmaz.</p><h4>4. Haklarınız</h4><p>KVKK’nın 11. maddesi kapsamında; verilerinizin işlenip işlenmediğini öğrenme, düzeltilmesini isteme, silinmesini veya yok edilmesini talep etme haklarına sahipsiniz. Taleplerinizi evrimmsenol@yahoo.com.tr adresine yazılı olarak iletebilirsiniz.</p>',
            null
        ],
        // Çerez Politikası
        [
            'legal', 'cookie_policy',
            'Çerez (Cookie) Politikası',
            'Web Sitemizde Kullanılan Çerezler Hakkında Bilgilendirme',
            '<p>Fizyoterapist Evrim Şenol Akarca web sitesinde, kullanıcı deneyimini iyileştirmek, sitemizin teknik güvenliğini sağlamak ve tercihlerinizi hatırlamak amacıyla çerezler kullanılmaktadır.</p><h4>1. Çerez Nedir?</h4><p>Çerezler, ziyaret ettiğiniz web siteleri tarafından tarayıcınız aracılığıyla cihazınıza kaydedilen küçük metin dosyalarıdır.</p><h4>2. Kullanılan Çerez Türleri</h4><ul><li><strong>Zorunlu Çerezler:</strong> Sitenin düzgün çalışması, oturum güvenliği ve CSRF form koruması için zorunludur.</li><li><strong>İşlevsel Çerezler:</strong> Çerez tercihlerinizi hatırlamamızı sağlar.</li><li><strong>Performans / Analitik Çerezler:</strong> Ziyaretçi sayısını ve sayfaların kullanım istatistiklerini anonim olarak ölçümlememize yardımcı olur.</li></ul><h4>3. Çerez Tercihlerini Değiştirme</h4><p>Sitemizin sol alt köşesinde yer alan çerez ikonuna tıklayarak veya tarayıcı ayarlarınız üzerinden çerez tercihlerinizi dilediğiniz zaman güncelleyebilir veya reddedebilirsiniz.</p>',
            null
        ],
        // Yasal Uyarı / Tıbbi Teşhis Reddi
        [
            'legal', 'terms',
            'Yasal Uyarı ve Tıbbi Teşhis Reddi (Medical Disclaimer)',
            'Kullanım Koşulları ve Bilgilendirme',
            '<p>Bu web sitesinde yer alan tüm yazılar, makaleler, görseller ve açıklamalar <strong>yalnızca genel bilgilendirme amacıyla</strong> hazırlanmıştır.</p><h4>Önemli Tıbbi Bildirim:</h4><p>Web sitemizdeki içeriklerin hiçbiri tıbbi muayene, hekim teşhisi, tedavi veya reçete yerine geçmez. Sağlık sorunlarınız, tanı ve tıbbi tedavileriniz için öncelikle mutlaka uzman bir tıp hekimine başvurunuz.</p><p>Fizyoterapi ve rehabilitasyon uygulamaları; ilgili mevzuat çerçevesinde, hekim tanısı ve yönlendirmesi doğrultusunda fizyoterapistlik meslek yetkileri sınırlarında planlanmaktadır.</p>',
            null
        ],
        // Randevu ve Seans İptal Politikası
        [
            'legal', 'appointment_policy',
            'Randevu, Seans ve İptal Politikası',
            'Seans Düzeni ve Danışan Bilgilendirmesi',
            '<p>Danışanlarımızın her birine kesintisiz, titiz ve kaliteli vakit ayırabilmemiz için seanslarımız önceden planlanan randevu saatinde başlamaktadır.</p><h4>1. Randevu İptal ve Değişiklik Bildirimi</h4><p>Planlanmış randevunuza katılamayacağınız durumlarda, diğer danışanlarımızın haklarını korumak adına <strong>en az 24 saat öncesinden</strong> telefon veya WhatsApp hattımızdan bilgi vermenizi rica ederiz.</p><h4>2. Seans Saatlerine Uyum</h4><p>Seans süresinin verimli kullanılabilmesi için randevu saatinizden 5-10 dakika önce klinikte bulunmanız önerilir. Gecikme durumunda seans süresi sonraki danışanların hakkını korumak için randevu bitiş saatinde sonlandırılır.</p>',
            null
        ]
    ];

    $stmtPage = $pdo->prepare("INSERT INTO pages_content (page_key, section_key, title, subtitle, content, extra_data)
        VALUES (:pk, :sk, :t, :sub, :c, :ed)
        ON DUPLICATE KEY UPDATE title = VALUES(title), subtitle = VALUES(subtitle), content = VALUES(content), extra_data = VALUES(extra_data)");

    foreach ($pages as $p) {
        $stmtPage->execute([
            ':pk' => $p[0],
            ':sk' => $p[1],
            ':t' => $p[2],
            ':sub' => $p[3],
            ':c' => $p[4],
            ':ed' => $p[5]
        ]);
    }
    echo "Sayfa icerikleri eklendi.\n";
    echo "Tum tohumlama (seeding) basariyla tamamlandi!\n";

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
    exit(1);
}
