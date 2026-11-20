<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Zaten giriş yapmışsa panele yönlendir
if (!empty($_SESSION['admin_logged_in']) && !empty($_SESSION['admin_id'])) {
    header('Location: ' . url('xpanel/index.php'));
    exit;
}

$error = '';
$ip = get_client_ip();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!validate_csrf($csrfToken)) {
        $error = 'Güvenlik doğrulaması (CSRF) geçersiz. Lütfen sayfayı yenileyiniz.';
    } elseif (!check_rate_limit('admin_login', $ip, 5, 15)) {
        $error = 'Çok fazla hatalı giriş denemesi yapıldı. Güvenliğiniz için 15 dakika boyunca giriş engellendi.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = 'Kullanıcı adı ve şifre zorunludur.';
        } else {
            try {
                $db = get_db();
                $stmt = $db->prepare("SELECT * FROM admins WHERE username = :u1 OR email = :u2 LIMIT 1");
                $stmt->execute([':u1' => $username, ':u2' => $username]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin['password_hash'])) {
                    // Başarılı giriş
                    session_regenerate_id(true);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = format_user_display_name($admin['username']);
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['admin_role'] = $admin['role'] ?? 'admin';
                    $_SESSION['admin_ip'] = $ip;

                    // Son giriş zamanını güncelle
                    $updateStmt = $db->prepare("UPDATE admins SET last_login = NOW() WHERE id = :id");
                    $updateStmt->execute([':id' => $admin['id']]);

                    header('Location: ' . url('xpanel/index.php'));
                    exit;
                } else {
                    record_rate_limit_attempt('admin_login', $ip);
                    $error = 'Kullanıcı adı veya şifre hatalı.';
                }
            } catch (Exception $e) {
                error_log('Giriş hatası: ' . $e->getMessage());
                $error = 'Sistemsel bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli Girişi | Fzt. Evrim Şenol Akarca</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: var(--bg-main);
        }
        .login-card {
            background-color: #FFFFFF;
            border: 1px solid var(--border-strong);
            max-width: 440px;
            width: 100%;
            padding: 3.5rem 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <img src="<?= e(url('assets/img/logo-full.png')) ?>" alt="Fzt. Evrim Şenol Akarca" style="max-height: 64px; width: auto; margin: 0 auto 1.25rem auto; display: block;">
            <span class="badge-tag" style="margin-bottom: 0.5rem;">Yönetim Paneli</span>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">Özel Sağlık Meslek Hizmet Birimi</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="form-alert form-alert-danger" style="margin-bottom: 1.75rem;">
            <?= e($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="username">Kullanıcı Adı veya E-Posta</label>
                <input type="text" id="username" name="username" class="form-control" required autocomplete="username" autofocus value="<?= e($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" for="password">Şifre</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Giriş Yap &rarr;
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-size: 0.8rem; border-top: 1px solid var(--border-subtle); padding-top: 1.25rem;">
            <a href="<?= e(url()) ?>" style="color: var(--text-muted);">&larr; Web Sitesine Geri Dön</a>
        </div>
    </div>
</div>

</body>
</html>
