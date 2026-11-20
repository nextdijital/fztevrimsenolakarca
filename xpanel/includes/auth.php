<?php
declare(strict_types=1);

if (ob_get_level() === 0) {
    ob_start();
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';

// Oturum Kontrolü
if (empty($_SESSION['admin_logged_in']) || empty($_SESSION['admin_id'])) {
    header('Location: ' . url('xpanel/login.php'));
    exit;
}

// Session Hijacking Kontrolü
$currentIp = get_client_ip();
if (isset($_SESSION['admin_ip']) && $_SESSION['admin_ip'] !== $currentIp) {
    session_unset();
    session_destroy();
    header('Location: ' . url('xpanel/login.php?error=session_invalid'));
    exit;
}

$adminUsername = format_user_display_name((string)($_SESSION['admin_username'] ?? 'Yönetici'));
$adminEmail = $_SESSION['admin_email'] ?? '';
