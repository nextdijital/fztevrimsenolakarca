<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek yöntemi.']);
    exit;
}

// 1. CSRF Doğrulaması
$csrfToken = $_POST['csrf_token'] ?? '';
if (!validate_csrf($csrfToken)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Güvenlik doğrulaması (CSRF) başarısız oldu. Lütfen sayfayı yenileyiniz.']);
    exit;
}

// 2. Rate Limiting (IP Başına Sınır)
$ip = get_client_ip();
if (!check_rate_limit('appointment_form', $ip, 5, 15)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Çok fazla istek gönderdiniz. Lütfen 15 dakika sonra tekrar deneyiniz veya doğrudan arayınız.']);
    exit;
}

// 3. Veri Alma ve Doğrulama
$fullName = trim((string)($_POST['full_name'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$serviceInterest = trim((string)($_POST['service_interest'] ?? 'Genel Bilgi & Randevu'));
$message = trim((string)($_POST['message'] ?? ''));
$kvkkConsent = !empty($_POST['kvkk_consent']);

$errors = [];

if (mb_strlen($fullName) < 3) {
    $errors[] = 'Lütfen adınızı ve soyadınızı eksiksiz belirtiniz.';
}

$cleanPhone = preg_replace('/[^0-9]/', '', $phone);
if (strlen($cleanPhone) < 10) {
    $errors[] = 'Lütfen geçerli bir telefon numarası giriniz.';
}

if (!$kvkkConsent) {
    $errors[] = 'Devam edebilmek için KVKK Aydınlatma Metni\'ni onaylamanız gerekmektedir.';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Rate limit denemesi kaydet
record_rate_limit_attempt('appointment_form', $ip);

// 4. Veritabanına Kayıt
try {
    $db = get_db();
    $stmt = $db->prepare("INSERT INTO contact_requests 
        (full_name, phone, service_interest, message, ip_address, status, created_at) 
        VALUES (:fn, :ph, :si, :msg, :ip, 'new', NOW())");

    $stmt->execute([
        ':fn' => $fullName,
        ':ph' => $phone,
        ':si' => $serviceInterest,
        ':msg' => $message,
        ':ip' => $ip
    ]);

    // WhatsApp Hazır Mesaj Bağlantısı Oluştur
    $whatsappNumber = get_setting('whatsapp_raw', '905324700480');
    $waTextRaw = "Merhaba Fzt. Evrim Şenol Akarca, web siteniz üzerinden randevu talebi oluşturdum.\n\n"
               . "*Ad Soyad:* " . $fullName . "\n"
               . "*Telefon:* " . $phone . "\n"
               . "*İlgilenilen Alan:* " . $serviceInterest . "\n"
               . "*Mesajım:* " . ($message ?: 'Randevu ve detaylı bilgi almak istiyorum.');
    $waUrl = "https://wa.me/{$whatsappNumber}?text=" . rawurlencode($waTextRaw);

    echo json_encode([
        'success' => true,
        'message' => 'Randevu ve bilgi talebiniz kliniğimize başarıyla iletildi. En kısa sürede sizinle iletişime geçeceğiz.',
        'whatsapp_url' => $waUrl
    ]);
} catch (Exception $e) {
    error_log('Randevu kayıt hatası: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Talebiniz kaydedilirken bir hata oluştu. Lütfen doğrudan telefonla arayınız.']);
}
