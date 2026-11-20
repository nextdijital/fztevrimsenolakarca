# Fzt. Evrim Şenol Akarca - Kurumsal Web Sitesi Standartları ve Yol Haritası (Master Checklist)

Bu doküman; **Fzt. Evrim Şenol Akarca** kurumsal fizyoterapi ve sağlık web sitesinin mimari, güvenlik, kurumsal uyumluluk (KVKK), tam SEO ve kod kalitesi standartlarını ve sırasıyla uygulanacak tüm adımları içermektedir.

---

## 📸 1. Görseldeki 23 Yayına Alma Kuralı (Production Security & Launch Checklist)

Uygulama canlıya çıkmadan önce eksiksiz uygulanacak ve doğrulanacak 23 kritik madde:

- [ ] **1. Anahtarları Çıkar:** API anahtarları, DB şifreleri, JWT gizli anahtarları ve hassas credential'ları kod içerisinden tamamen temizle, `.env` dosyasına taşı.
- [ ] **2. .env'i Geçmişten Sil:** `.env` dosyasını `.gitignore`'a ekle; Git geçmişinde veya repo geçmişinde hiçbir sırrın kalmadığından emin ol.
- [ ] **3. İzin Kurallarını Yaz:** Rol ve yetki matrisini (RBAC - Admin, Editör, Ziyaretçi) net kurallarla tanımla.
- [ ] **4. Yetkiyi Sunucuda Tut:** Kullanıcı rol ve yetki kontrollerini frontend/client tarafında değil, her istekte sunucu tarafında (server-side) doğrula.
- [ ] **5. Girişe Sınır Koy (Rate Limiting / Brute-Force Koruması):** Giriş yapma, randevu/iletişim formu ve şifre sıfırlama gibi uç noktalara IP ve oturum bazlı istek sınırlaması getir.
- [ ] **6. Girdiyi Doğrula (Strict Input Validation):** Kullanıcıdan gelen tüm girdileri (GET/POST/JSON) sunucu tarafında tip, format, uzunluk ve regex ile katı kontrolden geçir.
- [ ] **7. Yüklemeyi Sınırla (File Upload Restrictions):** Dosya yüklemelerinde maksimum boyut limiti koy; sadece izin verilen dosya tiplerini kabul et.
- [ ] **8. CORS'u Kilitle:** CORS başlıklarını sadece izin verilen domainlere (`Access-Control-Allow-Origin`) kısıtla; genel (`*`) erişime kapat.
- [ ] **9. Güvenlik Başlıklarını Ekle (Security Headers):** `Content-Security-Policy`, `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy` ve `Strict-Transport-Security` başlıklarını zorunlu kıl.
- [ ] **10. HTTPS Zorunlu Yap:** Tüm HTTP isteklerini otomatik olarak HTTPS'e yönlendir (`301 Moved Permanently`).
- [ ] **11. Şifreleri Güvenli Hash'le:** Kullanıcı/admin şifrelerini asla düz metin veya MD5/SHA1 ile saklama; `password_hash(..., PASSWORD_DEFAULT)` (Argon2id veya Bcrypt) kullan.
- [ ] **12. Çerezi Güvenli Yap (Secure Cookies):** Session ve çerez bayraklarında `HttpOnly`, `Secure` ve `SameSite=Lax/Strict` ayarlarını aktif et.
- [ ] **13. Hata Mesajını Kıs (Disable Display Errors):** Canlı ortamda `display_errors = 0` yap; kullanıcıya veritabanı, tablo veya sunucu dosya yolu bilgisi veren hata ekranları gösterme.
- [ ] **14. Logları Temizle ve Yönet:** Geliştirme aşamasındaki test loglarını temizle; hata loglamasını güvenli dosyalara yönlendir ve log dosyalarına doğrudan web erişimini engelle.
- [ ] **15. Sorguyu Parametrele (Prepared Statements):** Tüm veritabanı sorgularında PDO prepared statements ve parametre bağlama (`execute([':key' => $val])`) kullan; SQL Injection ihtimalini sıfırla.
- [ ] **16. XSS'e Karşı Kaçır (Output Escaping):** Veritabanından veya kullanıcıdan gelen tüm verileri HTML'e basarken `htmlspecialchars($str, ENT_QUOTES, 'UTF-8')` ile sanitize et.
- [ ] **17. Webhook / Entegrasyon İmzası Doğrula:** Varsa harici servislerden (ödeme, SMS, randevu, mail sağlayıcıları) gelen bildirimlerin HMAC/imza doğrulamasını yap.
- [ ] **18. Admin'e Rol ve Erişim Kontrolü Koy:** Yönetim paneli rotalarını ve yetkilerini kesin çizgilerle sınırla; yetkisiz girişlerde doğrudan 403 / 401 yanıtı ver.
- [ ] **19. Paketleri ve Bağımlılıkları Denetle:** Kullanılan harici kütüphane veya paketlerin güvenlik açıklarını (Composer / NPM audit) tara.
- [ ] **20. Otomatik Yedekleme Mekanizması Kur:** Veritabanı ve yüklenen medya dosyaları için periyodik yedekleme planı oluştur.
- [ ] **21. Hesabı / Veriyi Gerçekten Sil (KVKK / Unutulma Hakkı):** Kullanıcı veya form talebi silindiğinde ilişkili kişisel verileri veritabanından kalıcı olarak temizleme veya anonimleştirme prosedürü uygula.
- [ ] **22. Kaynak / Harcama Uyarısı Kur:** Varsa tüketim bazlı API'lar (Google Maps API, SMS, mail servisleri) için kota ve harcama limitleri tanımla.
- [ ] **23. Saldırgan Gibi Dene (Pentest & Güvenlik Testi):** SQLi, XSS, CSRF, Path Traversal, IDOR ve form manipülasyon testlerini yayından önce gerçekleştir.

---

## 🏗️ 2. Klasör ve Proje Yapısı (Katmanlı Mimari)

Web kök dizinini temiz tutmak ve hassas dosyaların doğrudan URL üzerinden indirilmesini engellemek için modern katmanlı mimari kullanılacaktır.

```text
c:\xampp\htdocs\fztevrimsenolakarca\
│
├── .env.example                  # Çevre değişkenleri şablonu
├── .gitignore                    # Git takip dışı dosyalar (.env, loglar, uploads)
├── .htaccess                     # Apache güvenlik ve yönlendirme kuralları
├── README.md                     # Proje dokümantasyonu
├── STANDARTLAR_VE_GOREV_LISTESI.md # Bu master checklist
│
├── config/                       # Konfigürasyon Dosyaları (URL'den kapalı)
│   ├── .htaccess                 # Dizin engelleme (Deny from all)
│   ├── app.php                   # Uygulama genel ayarları (Adı, URL, Zaman dilimi, Debug)
│   ├── database.php              # PDO DB bağlantı havuzu ve ayarları
│   └── security.php              # CSRF, XSS, CORS, Header tanımları
│
├── core/                         # Çekirdek Sistem Sınıfları ve Motoru
│   ├── Autoloader.php            # PSR-4 standartlarında sınıf yükleyici
│   ├── Database.php              # Güvenli PDO Wrapper sınıfı
│   ├── Router.php                # Temiz URL ve rota yönetimi
│   ├── Request.php               # İstek yönetimi (GET, POST, Header, IP, Sanitizasyon)
│   ├── Response.php              # JSON, Redirect, HTTP durum kodu yönetimi
│   ├── Session.php               # Güvenli oturum ve Flash mesaj yöneticisi
│   ├── CSRF.php                  # CSRF token üretim ve doğrulama motoru
│   ├── Validator.php             # Form validasyon ve kural kontrol motoru
│   └── View.php                  # Şablon render ve layout yöneticisi
│
├── app/                          # Uygulama İş Mantığı
│   ├── Controllers/              # Sayfa ve API İstek Yöneticileri
│   │   ├── HomeController.php    # Ana sayfa ve genel bölümler
│   │   ├── AboutController.php   # Hakkında ve biyografi
│   │   ├── ServiceController.php # Hizmetler ve uzmanlık alanları
│   │   ├── BlogController.php    # Blog / Sağlık Makaleleri
│   │   ├── ContactController.php # İletişim ve randevu talep formu
│   │   ├── LegalController.php   # KVKK, Çerez Politikası, Yasal Uyarı sayfaları
│   │   └── SitemapController.php # Dinamik sitemap.xml ve robots.txt
│   │
│   └── Models/                   # Veritabanı Modelleri
│       ├── Appointment.php       # Randevu talepleri
│       ├── ContactMessage.php    # İletişim mesajları
│       ├── Service.php           # Tedavi ve hizmet verileri
│       └── BlogPost.php          # Blog ve makale verileri
│
├── views/                        # Sunum Katmanı (Views / Templates - URL'den kapalı)
│   ├── .htaccess                 # Dizin engelleme
│   ├── layouts/                  # Ortak İskelet Şablonları
│   │   ├── main.php              # Ana site iskeleti (HTML5, Head, Header, Footer, Modallar)
│   │   └── blank.php             # Sade şablon (Örn: Hata sayfaları)
│   ├── partials/                 # Yeniden kullanılabilir parçalar
│   │   ├── header.php            # Navigasyon, logo, randevu butonu, acil hat
│   │   ├── footer.php            # Kurumsal linkler, iletişim, harita, yasal linkler
│   │   ├── cookie-modal.php      # KVKK uyumlu çerez onay banner/modalı
│   │   ├── kvkk-modal.php        # Aydınlatma metni hızlı önizleme modalı
│   │   ├── appointment-modal.php # Hızlı randevu/ön görüşme modalı
│   │   └── breadcrumbs.php       # SEO uyumlu breadcrumb navigasyonu
│   ├── pages/                    # Sayfa İçerikleri
│   │   ├── home.php              # Ana sayfa
│   │   ├── about.php             # Hakkımızda / Fzt. Evrim Şenol Akarca Kimdir?
│   │   ├── services/             # Hizmet detay sayfaları
│   │   ├── blog/                 # Blog listesi ve detay sayfaları
│   │   ├── contact.php           # İletişim ve harita
│   │   └── legal/                # Hukuki metinler
│   │       ├── kvkk.php          # KVKK Aydınlatma Metni
│   │       ├── cookie-policy.php # Çerez Politikası
│   │       ├── terms.php         # Kullanım Koşulları & Yasal Uyarı (Tıbbi Teşhis Reddi)
│   │       └── appointment-policy.php # Randevu ve Seans İptal Politikası
│   └── errors/                   # HTTP Hata Sayfaları
│       ├── 400.php               # Hatalı İstek
│       ├── 403.php               # Erişim Yasak
│       ├── 404.php               # Sayfa Bulunamadı
│       └── 500.php               # Sunucu Hatası
│
├── public/                       # Dış Dünyaya Açık Tek Dizin (DocumentRoot)
│   ├── index.php                 # Tek Giriş Noktası (Front Controller)
│   ├── robots.txt                # Arama motoru robot direktifleri
│   ├── sitemap.xml               # Arama motoru haritası
│   └── assets/                   # Statik Dosyalar
│       ├── css/
│       │   ├── style.css         # Modern, responsive CSS
│       │   └── cookie-consent.css# Çerez banner ve modal stilleri
│       ├── js/
│       │   ├── main.js           # Ana dinamizm ve form asenkron gönderimleri
│       │   └── cookie-consent.js # Çerez tercihleri yönetim scripti
│       ├── img/                  # Optimize edilmiş görseller (WebP/SVG)
│       └── favicon/              # Çoklu çözünürlükte favicon seti
│
└── storage/                      # Güvenli Depolama Alanı (URL'den kapalı)
    ├── .htaccess                 # Dizin engelleme
    ├── logs/                     # Uygulama ve hata logları
    └── uploads/                  # Kullanıcı yüklemeleri (varsa)
```

- [ ] Web kök dizini ve `.htaccess` kuralları yapılandırılarak hassas klasörler (`config`, `core`, `views`, `storage`) doğrudan web erişimine kapatılacak.
- [ ] `index.php` tek giriş noktası (Front Controller) olarak çalışacak.

---

## ⚙️ 3. Başlangıç Ayarları, Ortam ve Hata Yönetimi

- [ ] **Karakter Seti ve Yerelleştirme:**
  - `ini_set('default_charset', 'UTF-8');`
  - `mb_internal_encoding('UTF-8');`
  - `date_default_timezone_set('Europe/Istanbul');`
- [ ] **Ortam Kontrolü (Environment):**
  - Geliştirme Ortamı (`APP_ENV=local`):
    - `ini_set('display_errors', '1');`
    - `error_reporting(E_ALL);`
  - Canlı Ortam (`APP_ENV=production`):
    - `ini_set('display_errors', '0');`
    - `ini_set('log_errors', '1');`
    - `ini_set('error_log', STORAGE_PATH . '/logs/error.log');`
- [ ] **Global Exception & Error Handler:**
  - Beklenmedik fatal error veya exception'larda kullanıcıya şık bir `500.php` sayfası sunulacak, teknik ayrıntılar sadece `storage/logs/` altında kaydedilecek.

---

## 🗄️ 4. Veritabanı Bağlantısı ve Sorgu Güvenliği (PDO)

- [ ] **PDO Konfigürasyonu:**
  ```php
  $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
  ];
  ```
- [ ] **Sorgu Kuralları:**
  - Kesinlikle string interpolation veya concatenation (`"SELECT ... WHERE id = " . $id`) yapılmayacak.
  - Tüm sorgular parametreli prepared statement ile çalışacak (`$stmt->execute(['id' => $id])`).
- [ ] **İndeksleme ve Optimizasyon:**
  - Tablolarda `id`, `slug`, `status`, `created_at` ve yabancı anahtarlara (foreign keys) index eklenecek.
  - Tablo yapısı `utf8mb4` ve `utf8mb4_unicode_ci` olarak oluşturulacak.

---

## 🛡️ 5. Form Güvenliği, Doğrulama ve Sanitizasyon

- [ ] **CSRF Koruması (Anti-CSRF Tokens):**
  - Oturum başlatıldığında kriptografik olarak güvenli token üretilecek (`bin2hex(random_bytes(32))`).
  - Tüm POST/PUT formlarına gizli input `csrf_token` eklenecek.
  - AJAX isteklerinde `X-CSRF-TOKEN` header'ı ile doğrulanacak.
  - Eşleşmeyen veya eksik token istekleri anında `403 Forbidden` ile reddedilecek.
- [ ] **Sunucu Taraflı Katı Doğrulama (Server-Side Validation):**
  - Ad Soyad (Min 3 karakter, özel karakter kısıtı).
  - Telefon Numarası (Türkiye formatı `+90 5xx xxx xx xx` regex doğrulaması).
  - E-posta Adresi (`filter_var($email, FILTER_VALIDATE_EMAIL)`).
  - Mesaj / Şikayet Metni (Maksimum uzunluk, zararlı kod taraması).
  - KVKK Onay Kutusu (Zorunlu boolean onay kontrolü).
- [ ] **XSS Önleme:**
  - Ekrana basılan tüm kullanıcı/dinamik metinler için merkezi yardımcı fonksiyon: `e($string)` -> `htmlspecialchars($string, ENT_QUOTES, 'UTF-8')`.
- [ ] **Path Traversal ve Komut Enjeksiyonu Koruması:**
  - Dinamik sayfa yüklemelerinde `basename()` ve katı beyaz liste (`in_array($page, $allowed_pages)`) kullanılacak.

---

## 🔑 6. Oturum (Session) ve Güvenlik Yönetimi

- [ ] **Güvenli Session Konfigürasyonu:**
  - `session_start()` çağrılmadan önce:
    ```php
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Lax');
    // HTTPS aktif olduğunda:
    // ini_set('session.cookie_secure', 1);
    ```
- [ ] **Oturum Sabitleme Koruması (Session Fixation):**
  - Yetki seviyesi değiştiğinde veya oturum başladığında `session_regenerate_id(true)` çalıştırılacak.
- [ ] **Şifreleme Standartları:**
  - Parolalar için `password_hash($pass, PASSWORD_DEFAULT)` ve `password_verify($pass, $hash)` zorunlu olacak.
- [ ] **Rate Limiting (İstek Sınırlandırma):**
  - Form gönderimlerinde dakikada maksimum 5 istek sınırı getirilerek spam/bot saldırıları engellenecek.

---

## 📤 7. Güvenli Dosya Yükleme (Gerektiğinde)

- [ ] Dosya uzantısına asla güvenilmeyecek, MIME tipi `finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tmp_name)` ile kontrol edilecek.
- [ ] Yalnızca izin verilen formatlar: `['image/jpeg', 'image/png', 'image/webp', 'application/pdf']`.
- [ ] Dosyalar benzersiz isimle kaydedilecek: `bin2hex(random_bytes(16)) . '.' . $extension`.
- [ ] Yükleme klasöründe `.htaccess` ile PHP çalıştırma izni kaldırılacak (`php_flag engine off` veya handler engeli).

---

## 🌐 8. HTTP Başlıkları, Durum Kodları ve API Standartları

- [ ] **Güvenlik Başlıkları (.htaccess ve PHP seviyesinde):**
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: SAMEORIGIN`
  - `X-XSS-Protection: 1; mode=block`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy: camera=(), microphone=(), geolocation=()`
- [ ] **HTTP Durum Kodları:**
  - Başarılı: `200 OK`
  - Yönlendirme: `301 Moved Permanently` (Canonical & HTTPS)
  - Hatalı İstek: `400 Bad Request`
  - Yetkisiz / Yasak: `401 Unauthorized` / `403 Forbidden`
  - Sayfa Bulunamadı: `404 Not Found`
  - Sunucu Hatası: `500 Internal Server Error`
- [ ] **Yönlendirmeler:**
  - `header('Location: ...'); exit;` yapısı eksiksiz kullanılacak.
- [ ] **JSON Yanıtları:**
  - `header('Content-Type: application/json; charset=utf-8');`
  - Standart format: `{"success": true|false, "message": "...", "data": [...]}`.

---

## ⚖️ 9. Kurumsal Sağlık Sitesi Hukuki ve Politika Standartları (KVKK & Uyumluluk)

Fizyoterapi ve sağlık alanında faaliyet gösteren bir klinik web sitesinde bulunması gereken yasal belgeler ve modallar:

- [ ] **KVKK Aydınlatma Metni (6698 Sayılı Kanun Uyarınca):**
  - Veri sorumlusu kimliği (Fzt. Evrim Şenol Akarca).
  - İşlenen kişisel veriler (Kimlik, iletişim, randevu/sağlık şikayet ön bilgisi).
  - Kişisel veri işleme amaçları ve hukuki sebepleri.
  - Haklar (KVKK Md. 11: bilgi edinme, silinmesini isteme, düzeltme).
- [ ] **Çerez Politikası (Cookie Policy):**
  - Zorunlu çerezler (oturum, güvenlik, CSRF).
  - Performans/Analitik çerezleri (Google Analytics vb.).
  - Fonksiyonel ve pazarlama çerezleri.
  - Çerezlerin nasıl devre dışı bırakılacağı bilgisi.
- [ ] **KVKK Uyumlu Çerez Onay Modalı (Cookie Consent Banner):**
  - "Tümünü Kabul Et", "Tümünü Reddet" ve "Tercihleri Yönet" seçenekleri.
  - Kullanıcı onay vermeden önce pazarlama/analitik çerezlerinin çalıştırılmaması.
  - Kullanıcı tercihlerinin çerezde saklanması ve istendiğinde sol alttaki çerez ikonundan tekrar değiştirilebilmesi.
- [ ] **Tıbbi Sorumluluk Reddi ve Yasal Uyarı (Medical Disclaimer):**
  - *"Sitemizde yer alan tüm bilgiler, yazılar ve içerikler yalnızca bilgilendirme amaçlıdır. Hiçbir şekilde tıbbi tanı, teşhis veya tedavi niteliği taşımaz. Şikayetleriniz için lütfen hekiminize ve yetkili sağlık kuruluşlarına başvurunuz."*
- [ ] **Randevu, Seans İptal ve Değişiklik Politikası:**
  - Randevu alma süreçleri, seans iptal bildirim süreleri (en az 24 saat önceden haber verme vb.) ve kuralları.
- [ ] **Form İçi Açık Rıza ve İzin Onay Kutuları:**
  - İletişim ve randevu formlarının altında zorunlu: *"KVKK Aydınlatma Metni'ni okudum ve kabul ediyorum."* onay kutusu ve tıklandığında açılan hızlı modal.

---

## 🚀 10. Tam SEO (Arama Motoru Optimizasyonu) ve Performans

- [ ] **Semantik HTML5 Mimarisi:**
  - Doğru hiyerarşi (`<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`).
  - Her sayfada tek bir `<h1>`, mantıksal `<h2>`, `<h3>` başlık düzeni.
- [ ] **Meta Etiketleri ve Sosyal Paylaşım (Social Meta):**
  - Dinamik ve özgün `<title>` (örn: `Fzt. Evrim Şenol Akarca | Uzman Fizyoterapi & Rehabilitasyon`).
  - Sayfaya özel `<meta name="description" content="...">` (150-160 karakter).
  - `<link rel="canonical" href="...">`.
  - Open Graph etiketleri (`og:title`, `og:description`, `og:image`, `og:url`, `og:type`).
  - Twitter Card etiketleri (`twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`).
- [ ] **Yapılandırılmış Veri (Schema.org / JSON-LD):**
  - **MedicalBusiness / PhysiotherapyClinic:** Klinik adı, logo, adres, telefon, çalışma saatleri, konum koordinatları, sosyal medya hesapları.
  - **Person (Fzt. Evrim Şenol Akarca):** Meslek unvanı, uzmanlık alanları, biyografi.
  - **BreadcrumbList:** Ziyaretçi gezinti yolu işaretlemesi.
  - **MedicalWebPage / Article:** Blog ve tedavi sayfaları için tıbbi içerik şeması.
- [ ] **Arama Motoru Dosyaları:**
  - Dinamik ve güncel `sitemap.xml` (tüm sayfalar, son güncelleme tarihleri, öncelik skorları).
  - `robots.txt` (`Disallow: /config/`, `Disallow: /core/`, `Disallow: /storage/`, `Sitemap: https://.../sitemap.xml`).
- [ ] **Performans ve Core Web Vitals (LCP, CLS, INP):**
  - Tüm görseller modern WebP formatında, genişlik/yükseklik (`width`, `height`) etiketleri belirtilmiş.
  - `loading="lazy"` ve `decoding="async"` etiketleri.
  - Harici fontların `font-display: swap` ile çağrılması.
  - CSS ve JS dosyalarının optimize edilmesi, kritik CSS ayrımı.
  - Mobil uyumlu (Mobile-First) responsive tasarım, parmak dostu dokunma hedefleri (`tap targets > 48px`).

---

## 📋 11. Uygulama Adımları ve Tamamlanma Durumu (Execution Status)

1. [x] **Adım 1:** Temel Dizin ve Dosya Yapısının Kurulması (`config/`, `includes/`, `assets/`, `storage/`, `xpanel/`)
2. [x] **Adım 2:** Apache Güvenlik ve URL Yönlendirme Yapısının Yapılandırılması (`.htaccess`, Clean URLs, Güvenlik Başlıkları, Dizin Engellemeleri)
3. [x] **Adım 3:** Çekirdek PHP Fonksiyonlarının Yazılması (`get_db`, `e`, `csrf_token`, `validate_csrf`, `check_rate_limit`, `get_setting`, `url`, `is_active_nav`)
4. [x] **Adım 4:** Konfigürasyon Dosyalarının Hazırlanması (`config.php`, zaman dilimi, karakter seti, ortam hata yönetimi)
5. [x] **Adım 5:** Veritabanı Şemasının Tasarlanması ve Tohumlanması (`admins`, `site_settings`, `pages_content`, `services`, `faqs`, `contact_requests`, `rate_limits` + MySQL indeksleri)
6. [x] **Adım 6:** Tasarım İskeletinin Hazırlanması (Görseldeki lüks editoryal keten/krem ve adaçayı yeşili palet, serif tipografi, kesinlikle sıfır ikon kuralı)
7. [x] **Adım 7:** Kurumsal Hukuki Modallar ve Sayfalar (KVKK Aydınlatma, Çerez Politikası & Onay Modalı, Yasal Uyarı / Tıbbi Teşhis Reddi, Randevu Politikası)
8. [x] **Adım 8:** Frontend Tüm 11 Sayfanın Geliştirilmesi (Ana Sayfa, Hakkımızda, Hizmetlerimiz, 6 Hizmet Detayı + Biofeedback Bölümü, SSS Akordeon, İletişim & Harita)
9. [x] **Adım 9:** Asenkron Form Gönderimi (`ajax-randevu.php`), CSRF Doğrulaması, IP Rate Limiting ve Tek Tıkla WhatsApp Entegrasyonu
10. [x] **Adım 10:** SEO Optimizasyonu (Schema.org JSON-LD `MedicalBusiness` / `PhysiotherapyClinic`, Dinamik `sitemap.xml`, Korumalı `robots.txt`, Meta & OG)
11. [x] **Adım 11:** Tam Dinamik Yönetim Paneli (`/xpanel`): Güvenli Giriş, Dashboard, Sayfa İçeriklerini Düzenleme, Hizmetler Yönetimi, SSS Yönetimi, Randevu Talepleri İnceleme & Durum Değiştirme, Site Ayarları Düzenleme.
