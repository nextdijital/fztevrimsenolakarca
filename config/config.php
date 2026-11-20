<?php
declare(strict_types=1);

// Çıktı Tamponlama (Output Buffering) - Başlık ve yönlendirme çakışmalarını önler
if (ob_get_level() === 0) {
    ob_start();
}

/**
 * Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi
 * Temel Konfigürasyon ve Başlangıç Ayarları
 */

// Zaman Dilimi ve Karakter Seti
date_default_timezone_set('Europe/Istanbul');
ini_set('default_charset', 'UTF-8');
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}

// Ortam Ayarı: 'local' veya 'production'
defined('APP_ENV') || define('APP_ENV', 'local');

if (APP_ENV === 'local') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../storage/logs/error.log');
}

// Oturum (Session) Güvenlik Ayarları
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

// .env Dosyası Desteği (Canlı Sunucu Veritabanı Bilgileri İçin)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $envLines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            [$k, $v] = explode('=', $line, 2);
            $k = trim($k);
            $v = trim($v, " \t\n\r\0\x0B\"'");
            if (!defined($k)) {
                define($k, $v);
            }
        }
    }
}

// Veritabanı Bilgileri (Önce .env / getenv, yoksa yerel XAMPP varsayılanları)
defined('DB_HOST') || define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
defined('DB_NAME') || define('DB_NAME', getenv('DB_NAME') ?: 'fztevrimsenol_db');
defined('DB_USER') || define('DB_USER', getenv('DB_USER') ?: 'root');
defined('DB_PASS') || define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
defined('DB_CHARSET') || define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Otomatik Base URL Tespiti
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

// xpanel dizini içinden çağrılıyorsa ana dizine çek
$scriptDir = preg_replace('#/xpanel.*$#', '', $scriptDir);
$baseUrl = rtrim($protocol . $host . $scriptDir, '/');

defined('BASE_URL') || define('BASE_URL', $baseUrl);
defined('ROOT_PATH') || define('ROOT_PATH', realpath(__DIR__ . '/..'));
defined('STORAGE_PATH') || define('STORAGE_PATH', ROOT_PATH . '/storage');
