<?php
declare(strict_types=1);

$adminPageTitle = 'Kullanıcı & Yönetici Yönetimi';
require_once __DIR__ . '/includes/auth.php';

$db = get_db();
$error = '';
$success = '';

// Form İşlemleri (Ekleme / Silme / Düzenleme)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!validate_csrf($csrfToken)) {
        set_flash('error', 'Güvenlik doğrulaması geçersiz.');
        header('Location: ' . url('xpanel/users.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'create_user') {
        $username = trim((string)($_POST['username'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $role = trim((string)($_POST['role'] ?? 'admin'));

        if (empty($username) || empty($email) || empty($password)) {
            $error = 'Kullanıcı adı, e-posta ve şifre zorunludur.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Geçerli bir e-posta adresi giriniz.';
        } elseif (strlen($password) < 4) {
            $error = 'Şifre en az 4 karakter olmalıdır.';
        } else {
            // Check username or email uniqueness
            $checkStmt = $db->prepare("SELECT id FROM admins WHERE username = :u OR email = :e LIMIT 1");
            $checkStmt->execute([':u' => $username, ':e' => $email]);
            if ($checkStmt->fetch()) {
                $error = 'Bu kullanıcı adı veya e-posta adresi zaten kullanımda.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $db->prepare("INSERT INTO admins (username, email, password_hash, role, is_permanent, created_at) VALUES (:u, :e, :p, :r, 0, NOW())");
                $ins->execute([
                    ':u' => $username,
                    ':e' => $email,
                    ':p' => $hash,
                    ':r' => $role
                ]);
                set_flash('success', 'Yeni kullanıcı ("' . e($username) . '") başarıyla oluşturuldu.');
                header('Location: ' . url('xpanel/users.php'));
                exit;
            }
        }
    } elseif ($action === 'delete_user') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $userCheck = $db->prepare("SELECT * FROM admins WHERE id = :id LIMIT 1");
        $userCheck->execute([':id' => $userId]);
        $targetUser = $userCheck->fetch();

        if (!$targetUser) {
            set_flash('error', 'Kullanıcı bulunamadı.');
        } elseif (!empty($targetUser['is_permanent']) || $targetUser['email'] === 'suleymannozer0@gmail.com') {
            set_flash('error', 'Hata: Bu kullanıcı dokunulmaz ve daimi yöneticidir. Silinemez veya değiştirilemez!');
        } elseif ($targetUser['id'] == ($_SESSION['admin_id'] ?? 0)) {
            set_flash('error', 'Kendi oturum açtığınız hesabı silemezsiniz.');
        } else {
            $del = $db->prepare("DELETE FROM admins WHERE id = :id");
            $del->execute([':id' => $userId]);
            set_flash('success', 'Kullanıcı başarıyla silindi.');
        }
        header('Location: ' . url('xpanel/users.php'));
        exit;
    } elseif ($action === 'change_password') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $newPass = (string)($_POST['new_password'] ?? '');

        if (empty($newPass) || strlen($newPass) < 4) {
            set_flash('error', 'Şifre en az 4 karakter olmalıdır.');
        } else {
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $up = $db->prepare("UPDATE admins SET password_hash = :p WHERE id = :id");
            $up->execute([':p' => $hash, ':id' => $userId]);
            set_flash('success', 'Kullanıcı şifresi başarıyla güncellendi.');
        }
        header('Location: ' . url('xpanel/users.php'));
        exit;
    }
}

// Tüm kullanıcıları çek
$users = $db->query("SELECT * FROM admins ORDER BY is_permanent DESC, id ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <span class="badge-tag">Yetkilendirme & Erişim</span>
                <h1 style="font-size: 1.85rem; margin-top: 0.25rem;">Kullanıcı & Yönetici Yönetimi</h1>
            </div>
            <a href="#addUserSection" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.75rem 1.4rem;">
                + Yeni Kullanıcı Oluştur
            </a>
        </div>

        <?php if (!empty($flash['success'])): ?>
            <div class="form-alert form-alert-success"><?= e($flash['success']) ?></div>
        <?php endif; ?>

        <?php if (!empty($flash['error'])): ?>
            <div class="form-alert form-alert-danger"><?= e($flash['error']) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="form-alert form-alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <!-- Kullanıcı Listesi Kartı -->
        <div class="admin-card">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem;">Sistemde Kayıtlı Yöneticiler & Kullanıcılar</h3>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Kullanıcı Adı</th>
                            <th>E-Posta Adresi</th>
                            <th>Yetki Rolü</th>
                            <th>Durum</th>
                            <th>Son Giriş</th>
                            <th>Oluşturulma</th>
                            <th style="text-align: right;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?= (int)$u['id'] ?></td>
                            <td>
                                <strong><?= e(format_user_display_name($u['username'])) ?></strong>
                                <?php if (!empty($u['is_permanent'])): ?>
                                    <span style="display: block; font-size: 0.72rem; color: #2D5837; font-weight: 700;">(Daimi Yönetici)</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($u['email']) ?></td>
                            <td>
                                <span class="badge-tag" style="font-size: 0.7rem; padding: 0.2rem 0.6rem; margin-bottom: 0;">
                                    <?= e(strtoupper($u['role'] ?? 'ADMIN')) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($u['is_permanent']) || $u['email'] === 'suleymannozer0@gmail.com'): ?>
                                    <span style="color: #2D5837; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                                        &#128274; Dokunulmaz Daimi
                                    </span>
                                <?php else: ?>
                                    <span style="color: #666; font-size: 0.8rem;">Standart Kullanıcı</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($u['last_login']) ? date('d.m.Y H:i', strtotime($u['last_login'])) : '<span style="color:#999;">Henüz yok</span>' ?></td>
                            <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                            <td style="text-align: right;">
                                <?php if (!empty($u['is_permanent']) || $u['email'] === 'suleymannozer0@gmail.com'): ?>
                                    <span style="font-size: 0.75rem; color: #888; font-style: italic;">Dokunulmaz Hesap</span>
                                <?php else: ?>
                                    <form method="POST" action="" style="display: inline-block;" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete_user">
                                        <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                        <button type="submit" class="btn btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.72rem; color: #C53030; border-color: #C53030;">Sil</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Yeni Kullanıcı Oluşturma Kartı -->
        <div class="admin-card" id="addUserSection">
            <span class="badge-tag">Yeni Erişim Tanımla</span>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Yeni Kullanıcı / Yönetici Ekle</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.75rem;">
                Web sitesini ve paneli kullanacak diğer yetkililer için yeni bir giriş hesabı oluşturabilirsiniz.
            </p>

            <form method="POST" action="">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create_user">

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Kullanıcı Adı</label>
                        <input type="text" name="username" class="form-control" required placeholder="Örn: editör1 veya adsoyad" value="<?= e($_POST['username'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">E-Posta Adresi</label>
                        <input type="email" name="email" class="form-control" required placeholder="ornek@alanadi.com" value="<?= e($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Giriş Şifresi</label>
                        <input type="text" name="password" class="form-control" required placeholder="En az 4 karakter">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Yetki Rolü</label>
                        <select name="role" class="form-control">
                            <option value="admin">Yönetici (Admin - Tam Yetki)</option>
                            <option value="editor">Editör (İçerik ve Blog Yönetimi)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 0.85rem 2rem; font-size: 0.82rem;">
                    Kullanıcıyı Kaydet & Yetkilendir &rarr;
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
