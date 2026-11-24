-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: fztevrimsenol_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(20) DEFAULT 'admin',
  `is_permanent` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','$2y$10$SmHGGLPhesWL5sUmo0kC4uEDBeKB9K81A1vi.oKdIOYVa6KwEseiS','evrimmsenol@yahoo.com.tr','admin',0,NULL,'2026-11-20 07:35:00'),(2,'Süleyman','$2y$10$97Qgj5.VZk3oqms0SWAO4O2MEtpTrcC7pifnko3FoFiV6JV2Uzm6u','suleymannozer0@gmail.com','superadmin',1,'2026-11-20 17:14:20','2026-11-20 09:51:59');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT 'Fizyoterapi',
  `summary` text NOT NULL,
  `content` longtext NOT NULL,
  `author` varchar(100) DEFAULT 'Fzt. Evrim Þenol Akarca',
  `read_time` varchar(50) DEFAULT '5 dk okuma',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `published_at` datetime DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_active` (`is_active`),
  KEY `idx_published` (`published_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` VALUES (2,'Biofeedback Teknolojisi ile Kas Kontrolü Nasıl Yeniden Kazanılır?','biofeedback-teknolojisi-ile-kas-kontrolu','İleri Teknoloji','Kaslarınızı göremediğinizde onları doğru çalıştırmak zordur. Biofeedback, pelvik taban kas aktivitelerini görsel ve işitsel sinyallere dönüştürerek hızlı ve etkili kas eğitimi sağlar.','<p>Birçok birey pelvik taban egzersizleri (Kegel egzersizleri) yapmaya çalışırken kalça, uyluk veya karın kaslarını yanlışlıkla sıkar. Biofeedback (biyogeribildirim), bu yanılgıyı ortadan kaldıran yüksek teknolojili bir fizyoterapi cihazıdır.</p>\n\n<h3>Biofeedback Nasıl Çalışır?</h3>\n<p>Kliniğimizde kullanılan modern Biofeedback cihazı, yüzeyel EMG sensörleri aracılığıyla kasın ürettiği mikrovolt düzeyindeki elektriksel sinyalleri yakalar. Bu sinyaller ekranda anlaşılır grafiklere veya çocuklara özel oyun formatlarına dönüştürülür.</p>\n<p>Hasta ekrana baktığında kasını ne kadar sıktığını, kasılmayı ne kadar sürdürebildiğini ve en önemlisi <em>tam olarak gevşeyip gevşeyemediğini</em> anlık olarak görür.</p>\n\n<h3>Başlıca Avantajları Nelerdir?</h3>\n<ul>\n    <li><strong>Tamamen Ağrısız ve Güvenlidir:</strong> Vücuda herhangi bir elektrik akımı veya radyasyon verilmez; yalnızca vücudun kendi kas sinyali dinlenir.</li>\n    <li><strong>Hızlı Farkındalık Sağlar:</strong> Birey dakikalar içerisinde doğru kas grubunu hissedebilir.</li>\n    <li><strong>Çocuklarda Yüksek Uyum:</strong> Alt ıslatma yaşayan çocuklarda oyunlu arayüz sayesinde tedavi süreci eğlenceli bir eğitim haline gelir.</li>\n</ul>','Fzt. Evrim Şenol Akarca','5 dk okuma','Biofeedback ile Kas Kontrolü | Fzt. Evrim Şenol Akarca Adana','Biofeedback teknolojisi ile pelvik taban ve idrar kaçırma problemlerinde kas eğitimi nasıl yapılır? Adana kliniğimizdeki biofeedback uygulamaları.','assets/img/blog/blog-2.webp',1,3,'2026-11-20 11:35:41','2026-11-20 11:35:41','2026-11-20 16:29:24'),(3,'Lipödem ve Lenfödem Arasındaki Temel Farklar Nelerdir?','lipodem-ve-lenfodem-arasindaki-farklar','Lenfödem & Lipödem','Bacaklarda veya kollarda oluşan şişlikler sıkça kilo alma zannedilerek karıştırılabilir. Oysa lipödem ve lenfödem farklı mekanizmalara ve farklı fizyoterapi yöntemlerine sahiptir.','<p>Kadınlar arasında yaygın görülen bacak kalınlaşması ve ağrılı yağlanma durumları çoğu zaman yanlış teşhis edilerek \"kilo verememe\" olarak etiketlenmektedir. Oysa Lipödem kronik bir yağ dağılım bozukluğudur; Lenfödem ise lenfatik dolaşımın yetersizliğine bağlı sıvı birikimidir.</p>\n\n<h3>Lipödem Belirtileri:</h3>\n<ul>\n    <li>Her iki bacakta (ve bazen kollarda) simetrik yağ birikimi</li>\n    <li>Ayak bileğinde sonlanan şişlik (ayak sırtı genellikle etkilenmez, \"bileklik\" belirtisi)</li>\n    <li>Dokuya dokunulduğunda hassasiyet, ağrı ve kolay morarma</li>\n    <li>Diyet ve yoğun spora rağmen bacak incelmesinde direnç</li>\n</ul>\n\n<h3>Lenfödem Belirtileri:</h3>\n<ul>\n    <li>Genellikle tek taraflı (asimetrik) şişlik</li>\n    <li>Ayak veya el sırtını da içeren dolgunluk ve gode bırakan ödem</li>\n    <li>Enfeksiyon riski (erizipel) ve ağırlık hissi</li>\n</ul>\n\n<h3>Kompleks Boşaltıcı Fizyoterapi Yaklaşımı</h3>\n<p>Her iki durumda da manuel lenf drenajı, kişiye özel çok katmanlı bandajlama, tıbbi bası giysileri ve özel solunum-pompa egzersizleri ile doku yükü hafifletilir ve ilerleme kontrol altına alınır.</p>','Fzt. Evrim Şenol Akarca','6 dk okuma','Lipödem ve Lenfödem Farkları | Fzt. Evrim Şenol Akarca','Bacaklarda şişlik, ağrılı yağlanma ve ödem şikâyetlerinde lipödem ve lenfödem farkı. Manuel lenf drenajı ve fizyoterapi yöntemleri.','assets/img/blog/blog-3.webp',1,3,'2026-11-20 11:35:41','2026-11-20 11:35:41','2026-11-20 17:53:09'),(5,'Çene Eklemi (TME) Problemleri ve Baş Ağrısı İlişkisi','cene-eklemi-tme-problemleri-ve-bas-agrisi','Çene & Baş-Boyun','Sabahları çenede yorgunluk, kulak önünde klik sesi veya şakaklarda geçmeyen baş ağrısı yaşıyorsanız, sebep temporomandibular eklem (TME) disfonksiyonu olabilir.','<p>Temporomandibular eklem (çene eklemi), vücudumuzun en karmaşık ve en çok çalışan eklemlerinden biridir. Çiğneme, konuşma, yutkunma ve esneme sırasında sürekli hareket halindedir. Günlük stres ve diş sıkma (bruksizm) gibi alışkanlıklar bu eklemde aşırı yüklenmeye neden olur.</p>\n\n<h3>TME Problemi Belirtileri:</h3>\n<ul>\n    <li>Ağız açıp kaparken tıkırtı, klik veya sürtünme sesi</li>\n    <li>Ağzı tam açamama veya çenenin bir tarafa kayması</li>\n    <li>Şakaklarda, boyunda ve kulak çevresinde zonklayıcı ağrılar</li>\n    <li>Sabah uyanıldığında çene kaslarında sertlik ve yorgunluk</li>\n</ul>\n\n<h3>Çene Ekleminde Fizyoterapi Yaklaşımı:</h3>\n<p>Çene fizyoterapisi; çiğneme kaslarına yönelik intraoral (ağız içi) ve ekstraoral gevşetme teknikleri, boyun omurlarının manuel mobilizasyonu ve çene açma-kapama biyomekaniğini düzelten egzersizleri içerir. Diş hekimi tedavisi (gece plağı vb.) ile entegre yürütülen fizyoterapi, kalıcı rahatlama sağlar.</p>','Fzt. Evrim Şenol Akarca','5 dk okuma','Çene Eklemi (TME) ve Baş Ağrıları | Fzt. Evrim Şenol Akarca','Çene sıkma, diş gıcırdatma ve TME eklem ağrılarında manuel terapi ve fizyoterapi yöntemleri. Fzt. Evrim Şenol Akarca Adana.',NULL,1,1,'2026-11-20 11:35:41','2026-11-20 11:35:41','2026-11-20 16:03:58');
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_requests`
--

DROP TABLE IF EXISTS `contact_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `service_interest` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_req_status` (`status`),
  KEY `idx_req_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_requests`
--

LOCK TABLES `contact_requests` WRITE;
/*!40000 ALTER TABLE `contact_requests` DISABLE KEYS */;
INSERT INTO `contact_requests` VALUES (1,'Deneme Danışan','0532 999 88 77','Pelvik Taban ve İdrar Kaçırma Problemleri','Test mesajıdır.','127.0.0.1','new',NULL,'2026-11-20 07:44:35'),(2,'Deneme Danışan','0532 999 88 77','Pelvik Taban ve İdrar Kaçırma Problemleri','Test mesajıdır.','127.0.0.1','new',NULL,'2026-11-20 07:44:49');
/*!40000 ALTER TABLE `contact_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_faq_order` (`display_order`),
  KEY `idx_faq_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'Fizyoterapi değerlendirme süreci nasıl işler?','İlk seansta danışanımızın sağlık geçmişi, hekim muayene raporları ve görüntüleme sonuçları detaylıca incelenir. Ardından kas-iskelet sistemi, hareket kalitesi, postür ve özel klinik testler uygulanarak sorunun kök nedeni tespit edilir. Değerlendirme sonucunda danışanın hedefleri ve yaşam tarzı doğrultusunda tamamen kişiye özel bir seans planı oluşturulur.',1,1,'2026-11-20 07:35:00'),(2,'Pelvik taban fizyoterapisi ve biofeedback uygulamaları kimler için uygundur?','Öksürürken, gülerken veya aniden sıkışarak idrar kaçırma problemi yaşayan kadın ve erkekler, pelvik organ sarkması olanlar, cinsel ilişki veya genital bölgede ağrı yaşayanlar, prostat cerrahisi sonrası kontrol güçlüğü çekenler ve pelvik kas spazmı olan bireyler için uygundur. Biofeedback ile kas kontrolü güvenli ve acısız bir şekilde öğrenilir.',2,1,'2026-11-20 07:35:00'),(3,'Çocuklarda gece alt ıslatma ve gündüz idrar kaçırma için oyun destekli biofeedback nasıl uygulanır?','Çocuklarda pelvik taban kaslarını doğru kullanabilme bilinci henüz tam oturmamış olabilir. Kliniğimizde uygulanan çocuk biofeedback seanslarında cilde yapıştırılan elektrotlar kullanılır; kesinlikle ağrılı veya invaziv bir işlem değildir. Çocuk, kaslarını doğru şekilde kastığında ekrandaki çizgi film karakterini veya oyunu hareket ettirir. Böylece oyun oynayarak mesane kontrolünü doğal ve keyifli bir yöntemle öğrenir.',3,1,'2026-11-20 07:35:00'),(4,'Lipödem ve lenfödem rehabilitasyonunda hangi yöntemler kullanılır?','Lipödem ve lenfödem yönetiminde uluslararası kabul görmüş Kompleks Boşaltıcı Fizyoterapi protokolü uygulanır. Bu protokol; özel el manevralarıyla lenf sıvısının yönünü değiştiren Manuel Lenf Drenajı (MLD), çok tabakalı kompresyon bandajlaması, kişiye özel bası giysisi rehberliği, cilt bakımı ve lenfatik drenajı artıran solunum ve hareket egzersizlerini içerir.',4,1,'2026-11-20 07:35:00'),(5,'Seans süreleri ve sıklığı nasıl belirlenir?','Seans süreleri genellikle 50 ila 60 dakika sürmektedir. Seansların sıklığı ve toplam tedavi süresi danışanın mevcut durumuna, doku iyileşme hızına ve hedeflerine göre ilk değerlendirmede şeffaf bir şekilde planlanır. Bireysel gelişime göre program düzenli olarak güncellenir.',5,1,'2026-11-20 07:35:00'),(6,'Kliniğinize nasıl ulaşabilir ve randevu oluşturabilirim?','Kliniğimiz Adana Seyhan’da Kurtuluş Caddesi üzerinde bulunan Central Plaza Kat:8 No:81 adresinde yer almaktadır. Danışanlarımıza birebir odaklanabilmek adına tüm görüşmeler ve seanslar önceden randevu alınarak gerçekleştirilmektedir. 0532 470 04 80 numaralı telefonumuzdan bizi arayabilir, WhatsApp hattımız üzerinden yazabilir veya web sitemizdeki randevu formunu doldurabilirsiniz.',6,1,'2026-11-20 07:35:00');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages_content`
--

DROP TABLE IF EXISTS `pages_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_key` varchar(50) NOT NULL,
  `section_key` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `extra_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_data`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_page_section` (`page_key`,`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages_content`
--

LOCK TABLES `pages_content` WRITE;
/*!40000 ALTER TABLE `pages_content` DISABLE KEYS */;
INSERT INTO `pages_content` VALUES (1,'home','hero','Her Bedenin Hikâyesi Farklıdır. Fizyoterapi de Kişiye Özel Olmalıdır.','Adana | Özel Sağlık Meslek Hizmet Birimi','Fizyoterapist Evrim Þenol Akarca Özel Saðlýk Meslek Hizmet Birimi olarak; Adana\'da sunduðumuz fizyoterapi hizmetlerinde bilimsel temelli deðerlendirme yöntemlerini, manuel terapi uygulamalarýný ve kiþiye özel egzersiz programlarýný bir araya getiriyoruz.','{\"btn1_text\":\"WhatsApp ile Bilgi & Randevu Al\",\"btn1_url\":\"https:\\/\\/wa.me\\/905324700480\",\"btn2_text\":\"Hizmetlerimizi \\u0130nceleyin\",\"btn2_url\":\"\\/hizmetlerimiz\"}','2026-11-24 12:56:54'),(2,'home','approach','Yalnızca Şikâyetlere Değil, İnsanın Bütününe Odaklanıyoruz','Bütüncül Klinik Yaklaşımımız','Fizyoterapi yaklaþýmýmýzýn merkezinde yalnýzca aðrý veya þikâyetler deðil, bireyin kendisi yer alýr. Amacýmýz yalnýzca mevcut þikâyetleri azaltmak deðil; bireyin bedenini tanýmasýný, hareket kapasitesini geliþtirmesini ve yaþam kalitesini artýrmasýný desteklemektir. Çünkü iyi bir fizyoterapi süreci, yalnýzca görüntüleme sonuçlarýna deðil, insanýn bütününe odaklanýr.',NULL,'2026-11-24 12:56:54'),(3,'home','vision_mission','Değerlerimiz ve İlkelerimiz','Vizyon & Misyon','Bilimsel bilgi ve bütüncül değerlendirme yaklaşımıyla kişiye özel, sürdürülebilir ve güvenilir fizyoterapi hizmeti sunmak vizyonumuz; her bireyin ihtiyaçlarını ve yaşam koşullarını dikkate alarak hareket özgürlüğünü, fonksiyonel bağımsızlığını ve yaşam kalitesini desteklemek misyonumuzdur.','{\"vision\":\"Bilimsel bilgi ve bütüncül değerlendirme yaklaşımıyla kişiye özel, sürdürülebilir ve güvenilir fizyoterapi hizmeti sunmak.\",\"mission\":\"Her bireyin ihtiyaçlarını ve yaşam koşullarını dikkate alarak hareket özgürlüğünü, fonksiyonel bağımsızlığını ve yaşam kalitesini desteklemek.\"}','2026-11-20 07:35:00'),(4,'about','main','Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi','Bilimsel temelli değerlendirme ve bütüncül fizyoterapi yaklaşımı','<p>Her bedenin hikâyesi farklıdır. Fizyoterapi de kişiye özel olmalıdır.</p><p>Fizyoterapi yaklaşımımın merkezinde yalnızca ağrı veya şikâyetler değil, bireyin kendisi yer alır. Adana’da sunduğum fizyoterapi hizmetlerinde bilimsel temelli değerlendirme yöntemlerini, manuel terapi uygulamalarını ve kişiye özel egzersiz programlarını bir araya getiriyorum.</p><p>Pelvik taban fizyoterapisi, lipödem ve lenfödem rehabilitasyonu, kas-iskelet sistemi problemleri ve hareket eğitimi başta olmak üzere farklı alanlarda çalışıyorum.</p><p>Amacım yalnızca mevcut şikâyetleri azaltmak değil; bireyin bedenini tanımasını, hareket kapasitesini geliştirmesini ve yaşam kalitesini artırmasını desteklemektir. Çünkü iyi bir fizyoterapi süreci, yalnızca görüntüleme sonuçlarına değil, insanın bütününe odaklanır.</p>','{\"vision\":\"Bilimsel bilgi ve bütüncül değerlendirme yaklaşımıyla kişiye özel, sürdürülebilir ve güvenilir fizyoterapi hizmeti sunmak.\",\"mission\":\"Her bireyin ihtiyaçlarını ve yaşam koşullarını dikkate alarak hareket özgürlüğünü, fonksiyonel bağımsızlığını ve yaşam kalitesini desteklemek.\"}','2026-11-20 07:35:00'),(5,'legal','kvkk','Kişisel Verilerin Korunması Kanunu (KVKK) Aydınlatma Metni','6698 Sayılı Kanun Uyarınca Bilgilendirme','<p><strong>Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi</strong> (“Klinik / Veri Sorumlusu”) olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu (“KVKK”) ve ilgili mevzuat uyarınca kişisel verilerinizin güvenliğine ve gizliliğine azami önem veriyoruz.</p><h4>1. İşlenen Kişisel Veriler</h4><p>Web sitemizdeki iletişim ve randevu talep formları aracılığıyla adınız, soyadınız, telefon numaranız, e-posta adresiniz, ilgilendiğiniz hizmet konusu ve ilettiğiniz mesaj bilgileri toplanmaktadır.</p><h4>2. Verilerin İşlenme Amacı</h4><p>Toplanan kişisel verileriniz; randevu taleplerinizin oluşturulması, fizyoterapi hizmetleri öncesinde sizinle iletişime geçilmesi, soru ve taleplerinizin yanıtlanması ve yasal yükümlülüklerimizin yerine getirilmesi amaçlarıyla sınırlı olarak işlenir.</p><h4>3. Verilerin Aktarılması</h4><p>Kişisel verileriniz yasal zorunluluklar ve yetkili kamu kurumları talepleri haricinde üçüncü şahıs veya ticari kuruluşlara kesinlikle aktarılmaz ve satılmaz.</p><h4>4. Haklarınız</h4><p>KVKK’nın 11. maddesi kapsamında; verilerinizin işlenip işlenmediğini öğrenme, düzeltilmesini isteme, silinmesini veya yok edilmesini talep etme haklarına sahipsiniz. Taleplerinizi evrimmsenol@yahoo.com.tr adresine yazılı olarak iletebilirsiniz.</p>',NULL,'2026-11-20 07:35:00'),(6,'legal','cookie_policy','Çerez (Cookie) Politikası','Web Sitemizde Kullanılan Çerezler Hakkında Bilgilendirme','<p>Fizyoterapist Evrim Şenol Akarca web sitesinde, kullanıcı deneyimini iyileştirmek, sitemizin teknik güvenliğini sağlamak ve tercihlerinizi hatırlamak amacıyla çerezler kullanılmaktadır.</p><h4>1. Çerez Nedir?</h4><p>Çerezler, ziyaret ettiğiniz web siteleri tarafından tarayıcınız aracılığıyla cihazınıza kaydedilen küçük metin dosyalarıdır.</p><h4>2. Kullanılan Çerez Türleri</h4><ul><li><strong>Zorunlu Çerezler:</strong> Sitenin düzgün çalışması, oturum güvenliği ve CSRF form koruması için zorunludur.</li><li><strong>İşlevsel Çerezler:</strong> Çerez tercihlerinizi hatırlamamızı sağlar.</li><li><strong>Performans / Analitik Çerezler:</strong> Ziyaretçi sayısını ve sayfaların kullanım istatistiklerini anonim olarak ölçümlememize yardımcı olur.</li></ul><h4>3. Çerez Tercihlerini Değiştirme</h4><p>Sitemizin sol alt köşesinde yer alan çerez ikonuna tıklayarak veya tarayıcı ayarlarınız üzerinden çerez tercihlerinizi dilediğiniz zaman güncelleyebilir veya reddedebilirsiniz.</p>',NULL,'2026-11-20 07:35:00'),(7,'legal','terms','Yasal Uyarı ve Tıbbi Teşhis Reddi (Medical Disclaimer)','Kullanım Koşulları ve Bilgilendirme','<p>Bu web sitesinde yer alan tüm yazılar, makaleler, görseller ve açıklamalar <strong>yalnızca genel bilgilendirme amacıyla</strong> hazırlanmıştır.</p><h4>Önemli Tıbbi Bildirim:</h4><p>Web sitemizdeki içeriklerin hiçbiri tıbbi muayene, hekim teşhisi, tedavi veya reçete yerine geçmez. Sağlık sorunlarınız, tanı ve tıbbi tedavileriniz için öncelikle mutlaka uzman bir tıp hekimine başvurunuz.</p><p>Fizyoterapi ve rehabilitasyon uygulamaları; ilgili mevzuat çerçevesinde, hekim tanısı ve yönlendirmesi doğrultusunda fizyoterapistlik meslek yetkileri sınırlarında planlanmaktadır.</p>',NULL,'2026-11-20 07:35:00'),(8,'legal','appointment_policy','Randevu, Seans ve İptal Politikası','Seans Düzeni ve Danışan Bilgilendirmesi','<p>Danışanlarımızın her birine kesintisiz, titiz ve kaliteli vakit ayırabilmemiz için seanslarımız önceden planlanan randevu saatinde başlamaktadır.</p><h4>1. Randevu İptal ve Değişiklik Bildirimi</h4><p>Planlanmış randevunuza katılamayacağınız durumlarda, diğer danışanlarımızın haklarını korumak adına <strong>en az 24 saat öncesinden</strong> telefon veya WhatsApp hattımızdan bilgi vermenizi rica ederiz.</p><h4>2. Seans Saatlerine Uyum</h4><p>Seans süresinin verimli kullanılabilmesi için randevu saatinizden 5-10 dakika önce klinikte bulunmanız önerilir. Gecikme durumunda seans süresi sonraki danışanların hakkını korumak için randevu bitiş saatinde sonlandırılır.</p>',NULL,'2026-11-20 07:35:00');
/*!40000 ALTER TABLE `pages_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rate_limits`
--

DROP TABLE IF EXISTS `rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rate_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_key` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_action_ip` (`action_key`,`ip_address`,`attempted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rate_limits`
--

LOCK TABLES `rate_limits` WRITE;
/*!40000 ALTER TABLE `rate_limits` DISABLE KEYS */;
INSERT INTO `rate_limits` VALUES (1,'admin_login','::1','2026-11-20 11:12:19');
/*!40000 ALTER TABLE `rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(150) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `full_content` longtext DEFAULT NULL,
  `bullet_points` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bullet_points`)),
  `has_biofeedback` tinyint(1) DEFAULT 0,
  `biofeedback_title` varchar(255) DEFAULT NULL,
  `biofeedback_content` longtext DEFAULT NULL,
  `biofeedback_bullets` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`biofeedback_bullets`)),
  `image_url` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_order` (`display_order`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'pelvik-taban-fizyoterapisi','Pelvik Taban ve İdrar Kaçırma Problemleri','Kadınlarda, erkeklerde ve çocuklarda pelvik taban disfonksiyonları, idrar kaçırma problemleri ve biofeedback destekli modern kas rehabilitasyonu.','<p>Pelvik taban kasları; leğen kemiğinin tabanında yer alan, mesane, rahim ve bağırsakları destekleyen, idrar ve dışkı kontrolünü sağlayan ve cinsel fonksiyonda kritik rol oynayan hayati bir kas grubudur. Bu kasların aşırı zayıflaması veya aşırı gergin/spazm halinde olması; idrar kaçırma, organ sarkması, kronik pelvik ağrı ve boşaltım problemlerine yol açar.</p><p>Adana’daki kliniğimizde uyguladığımız pelvik taban fizyoterapisi; bireyin kas fonksiyonunun ayrıntılı değerlendirilmesi, kişiye özel egzersiz reçeteleri, manuel terapi ve görsel-işitsel geri bildirim sağlayan <strong>Biofeedback</strong> teknolojisi ile güvenle yürütülmektedir.</p>','[\"Kadınlarda ve erkeklerde idrar kaçırma\",\"Öksürürken, hapşırırken veya gülerken idrar kaçırma (Stres İnkontinans)\",\"Ani sıkışma hissi ve tuvalete yetişememe (Urge İnkontinans)\",\"Sık idrara çıkma ve mesane kontrol güçlüğü\",\"Çocuklarda gündüz idrar kaçırma ve gece alt ıslatma (Enürezis Noktürna)\",\"Dışkı veya gaz tutamama problemleri\",\"Cinsel ilişki sırasında ağrı (Disparoni, Vajinismus desteği)\",\"Vajina ve genital bölgede ağrı, yanma veya hassasiyet\",\"Pelvik taban kaslarında güçsüzlük veya aşırı gerginlik\"]',1,'Biofeedback Destekli Fizyoterapi','<p>Biofeedback yöntemi, pelvik taban kaslarının çalışma mekanizmasını görsel ve işitsel sensörler yardımıyla kişiye anlık olarak gösteren, bilimsel etkinliği kanıtlanmış bir eğitim ve tedavi teknolojisidir.</p><p>Çoğu kişi pelvik taban kaslarının tam nerede olduğunu ve nasıl doğru sıkıp gevşeteceğini hissetmekte zorlanır. Yanlış kasma karın kaslarını zorlayabilir ve sorunu artırabilir. Biofeedback cihazı sayesinde danışan, ekrandaki grafikler üzerinden kasını ne kadar doğru çalıştırdığını ve ne kadar gevşettiğini somut olarak gözlemler. Çocuklarda ise bu süreç özel oyun tabanlı yazılımlar ile eğlenceli ve korkusuz bir kas kontrol eğitimine dönüştürülür.</p>','[\"İdrar kontrolünü geliştirmeye yönelik hedefe yönelik kas eğitimi\",\"Pelvik taban kaslarını doğru çalıştırmayı ve koordine etmeyi öğrenme\",\"Çocuklarda oyunla desteklenen biofeedback uygulamaları (alt ıslatma ve tutma)\",\"Pelvik taban kaslarının gevşeme ve koordinasyon eğitimi (aşırı gergin kaslar için)\"]','assets/img/hizmetler/hizmet-1.webp',1,1,'2026-11-20 07:35:00','2026-11-20 12:55:16'),(2,'lipodem-lenfodem-rehabilitasyonu','Lipödem, Lenfödem ve Şişlik Problemleri','Bacaklarda orantısız yağlanma ve hassasiyet (lipödem) ile lenf dolaşımı yetersizliğine bağlı şişliklerde (lenfödem) kompleks boşaltıcı fizyoterapi yaklaşımı.','<p>Lipödem; özellikle kadınlarda bacaklarda ve bazen kollarda simetrik, ağrılı yağ dokusu birikimi ile karakterize kronik bir durumdur. Kolay morarma, bacaklarda ağırlaşma ve dokunmaya karşı yoğun hassasiyet en belirgin semptomlar arasındadır.</p><p>Lenfödem ise lenfatik sistemin yetersizliğine bağlı olarak dokularda lenf sıvısının birikmesiyle oluşan ilerleyici bir şişlik tablosudur; sıklıkla kanser cerrahileri (örneğin meme kanseri ameliyatı) veya doğuştan lenfatik yetmezlikler sonrasında gelişir.</p><p>Kliniğimizde; Manuel Lenf Drenajı (MLD), çok katmanlı kompresyon bandajlama, kişiye özel bası giysisi yönetimi, cilt bakımı ve lenfatik drenajı hızlandıran özel egzersizleri içeren Kompleks Boşaltıcı Fizyoterapi uygulanmaktadır.</p>','[\"Bacaklarda orantısız kalınlaşma ve dokunma hassasiyeti (Lipödem)\",\"Bacaklarda ağrı, ağırlık hissi ve kolay morarma\",\"Kol veya bacaklarda lenf sıvısı birikimine bağlı şişlik (Lenfödem)\",\"Ameliyat ve travma sonrası gelişen lenfatik ödem\",\"Meme kanseri ameliyatı ve lenf nodu diseksiyonu sonrası kol şişliği\",\"Lipödem ve lenfödemde kompresyon, manuel lenf drenajı ve solunum\\/egzersiz uygulamaları\"]',0,NULL,NULL,NULL,'assets/img/hizmetler/hizmet-2.webp',2,1,'2026-11-20 07:35:00','2026-11-20 12:55:16'),(3,'bel-boyun-sirt-agrilari','Bel, Boyun ve Sırt Ağrıları','Bel ve boyun fıtıkları, omurga gerginlikleri, kalça/bacak/kol uyuşmaları ve uzun süre masa başı çalışmaya bağlı duruş bozukluklarında kanıta dayalı fizyoterapi.','<p>Omurga, insan bedeninin ana taşıyıcı sütunudur. Günümüzün hareketsiz yaşam tarzı, uzun süreli masa başı oturma, yanlış yük kaldırma ve stres; bel, boyun ve sırt bölgesinde mekanik yüklenmelere, fıtıklara ve kas spazmlarına yol açmaktadır.</p><p>Biz omurga problemlerine yalnızca bir MR veya röntgen görüntüsü üzerinden yaklaşmıyoruz. Bireyin hareket kalıplarını, duruş dinamiklerini, kas dengesizliklerini ve günlük yaşam aktivitelerini kapsamlı şekilde değerlendiriyoruz. Manuel terapi, mobilizasyon teknikleri, faset eklem rahatlatma ve omurgayı koruyucu derin stabilizasyon egzersizleriyle kalıcı iyileşmeyi hedefliyoruz.</p>','[\"Bel fıtığı (Lomber disk hernisi) ve mekanik bel ağrısı\",\"Boyun fıtığı (Servikal disk hernisi) ve boyun ağrısı\",\"Sırt ağrıları, kürek kemiği çevresi spazmlar ve kas gerginlikleri\",\"Kalçaya veya bacağa yayılan ağrılar (Siyatik sinir hassasiyeti)\",\"Boyundan kola ve parmaklara yayılan ağrı, uyuşma hissi\",\"Uzun süre oturmaya bağlı ağrı, postür bozukluğu ve hareket kısıtlılığı\",\"Kronikleşen ve tekrarlayan kas-iskelet sistemi ağrıları\"]',0,NULL,NULL,NULL,'assets/img/hizmetler/hizmet-3.webp',3,1,'2026-11-20 07:35:00','2026-11-20 12:55:16'),(4,'omuz-kalca-diz-problemleri','Omuz, Kalça ve Diz Problemleri','Donuk omuz, rotator kılıf yaralanmaları, kalça trokanterik ağrı, diz menisküs/bağ lezyonları ve protez ameliyatları sonrası fonksiyonel rehabilitasyon.','<p>Omuz, kalça ve diz eklemleri günlük yaşamın hareket özgürlüğünü belirleyen en kritik ve yük taşıyan eklemlerdir. Bu eklemlerde meydana gelen tendon dejenerasyonları, bursitler, kıkırdak aşınmaları veya travmatik yaralanmalar kişinin yürümesini, merdiven çıkmasını veya kolunu kaldırmasını dahi imkânsız kılabilir.</p><p>Klinik değerlendirmemizde eklem hareket açıklığı, kas gücü, eklem kinematiği ve denge hassasiyetle test edilir. Donuk omuz açma protokolleri, omuz sıkışma sendromu (impingement) rehabilitasyonu, kalça stabilizasyonu ve diz kapağı (patellofemoral) uyumu için kişiselleştirilmiş programlar sunulur.</p>','[\"Omuz ağrısı ve hareket kısıtlılığı\",\"Donuk omuz (Adeziv kapsülit) rehabilitasyonu\",\"Omuz kas ve tendon problemleri (Rotator kılıf lezyonları, impingement)\",\"Kalçanın yan tarafında ağrı (Trokanterik ağrı sendromu \\/ Bursit)\",\"Diz eklemi ağrıları, menisküs ve bağ problemleri\",\"Diz kapağı çevresinde ağrı (Patellofemoral ağrı sendromu)\",\"Kalça ve diz eklem protezi ameliyatları sonrası rehabilitasyon\",\"Kas güçsüzlüğü, eklem sertliği ve denge\\/yürüme problemleri\"]',0,NULL,NULL,NULL,'assets/img/hizmetler/hizmet-4.webp',4,1,'2026-11-20 07:35:00','2026-11-20 12:55:16'),(5,'cene-bas-boyun-problemleri','Çene ve Baş-Boyun Bölgesi Problemleri','Çene eklemi (TME) disfonksiyonu, çiğneme kası spazmları, diş sıkma (bruksizm) ve çeneden ses gelmesi durumlarında manuel terapi ve kraniyomandibuler rehabilitasyon.','<p>Temporomandibular eklem (TME), kafatası ile alt çene kemiğini birbirine bağlayan ve gün içinde konuşurken, çiğnerken ve yutkunurken binlerce kez çalışan son derece hassas bir eklemdir. Diş sıkma (bruksizm), stres, diş kapanış bozuklukları veya travmalar çene ekleminde diskin kaymasına ve şiddetli baş-boyun ağrılarına yol açar.</p><p>Çene fizyoterapisi; çiğneme kaslarına yönelik ağız içi ve dışı manuel gevşetme tekniklerini, eklem mobilizasyonunu, baş-boyun postürünün düzeltilmesini ve çene eklemi koordinasyon egzersizlerini kapsar.</p>','[\"Çene ekleminde ağrı ve çiğnerken zorlanma\",\"Ağız açıp kapatırken çeneden tıkırtı, klik veya sürtünme sesi gelmesi\",\"Çene hareketlerinde kısıtlılık veya çenenin kilitlenmesi\",\"Gece veya gündüz diş sıkmayla (bruksizm) ilişkili çene ve şakak kası gerginliği\",\"Çene ve boyun bölgesindeki kas-iskelet sistemi problemleri ile baş ağrıları\"]',0,NULL,NULL,NULL,'assets/img/hizmetler/hizmet-5.webp',5,1,'2026-11-20 07:35:00','2026-11-20 12:55:16'),(6,'kisiye-ozel-egzersiz-egitimi','Kişiye Özel Egzersiz ve Hareket Eğitimi','Bireyin beden tipine, postürüne ve günlük yaşam hedeflerine özel bilimsel kuvvetlendirme, core stabilizasyonu ve yoga temelli terapötik hareket seansları.','<p>Egzersiz, doğru dozda ve doğru teknikle uygulandığında en güçlü koruyucu ve iyileştirici fizyoterapi aracıdır. Ancak her bireyin kas uzunluğu, eklem esnekliği, omurga eğrilikleri ve hareket kabiliyeti birbirinden tamamen farklıdır. İnternetten veya genel listelerden kopyalanan egzersizler sıklıkla sakatlıklara neden olabilir.</p><p>Kişiye özel hareket eğitimimizde; detaylı postür analizi yapılır, zayıf kaslar belirlenerek kuvvetlendirilir, kısalmış ve gergin dokular esnetilir. Yoga temelli terapötik hareket farkındalığı ile zihin-beden uyumu desteklenerek bireye ömür boyu sürdürebileceği sağlıklı bir hareket alışkanlığı kazandırılır.</p>','[\"Duruş (postür) ve hareket alışkanlıklarının biyomekanik değerlendirilmesi\",\"Kas güçsüzlüğü, doku sertlikleri ve hareket kısıtlılığı\",\"Denge ve nöromüsküler koordinasyon problemleri\",\"Günlük yaşam ve iş aktivitelerinde çabuk yorulma ve zorlanma\",\"Kişiye özel omurga ve core kuvvetlendirme\\/stabilizasyon egzersizleri\",\"Yoga temelli terapötik hareket ve nefes farkındalığı çalışmaları\"]',0,NULL,NULL,NULL,'assets/img/hizmetler/hizmet-6.webp',6,1,'2026-11-20 07:35:00','2026-11-20 12:55:16');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(100) NOT NULL,
  `key_value` text DEFAULT NULL,
  `group_name` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_title','Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi','general','Resmi Klinik Adı'),(2,'specialist_name','Fzt. Evrim Şenol Akarca','general','Yetkili Uzman Adı'),(3,'specialist_title','Fizyoterapist','general','Uzman Unvanı'),(4,'phone','0532 470 04 80','contact','İletişim Telefonu'),(5,'phone_raw','+905324700480','contact','Tıklanabilir Telefon'),(6,'whatsapp','0532 470 04 80','contact','WhatsApp Numarası'),(7,'whatsapp_raw','905324700480','contact','WhatsApp Bağlantı Numarası'),(8,'email','evrimmsenol@yahoo.com.tr','contact','E-Posta Adresi'),(9,'address','Hurmalı Mah. Kurtuluş Cad. No:39 Central Plaza Kat:8/81 Seyhan / Adana','contact','Klinik Adresi'),(10,'city','Adana','contact','Şehir'),(11,'district','Seyhan','contact','İlçe'),(12,'instagram','@fzt.evrimsenol','social','Instagram Kullanıcı Adı'),(13,'instagram_url','https://instagram.com/fzt.evrimsenol','social','Instagram Profili'),(14,'working_hours','Pazartesi - Cumartesi: 09:00 - 18:30 (Ön Randevu ile)','general','Çalışma Saatleri'),(15,'meta_description','Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi Adana. Pelvik taban, biofeedback, lipödem, lenfödem, omurga ve kişiye özel fizyoterapi.','seo','Genel Meta Açıklama'),(16,'google_maps_embed','<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3186.297775988294!2d35.3182!3d36.9944!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15288f62f8373b95%3A0xc3c9f2256722d4f!2sKurtulu%C5%9F%20Cd.%20No%3A39%2C%20Hurmal%C4%B1%2C%2001060%20Seyhan%2FAdana!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str\" width=\"100%\" height=\"420\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>','contact','Google Maps Embed Kodu'),(17,'facebook_url','','social','Facebook Profil URL'),(18,'google_maps_link','https://maps.google.com/?q=Hurmal%C4%B1+Mah.+Kurtulu%C5%9F+Cad.+No%3A39+Central+Plaza+Seyhan+Adana','contact','Google Maps Doðrudan Konum / Navigasyon Linki');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-24 16:00:03
