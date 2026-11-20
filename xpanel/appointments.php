<?php
declare(strict_types=1);

$adminPageTitle = 'Randevu ve Ön Görüşme Talepleri';
require_once __DIR__ . '/includes/auth.php';

$db = get_db();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$filterStatus = $_GET['status'] ?? 'all';

// Silme İşlemi
if ($action === 'delete' && $id > 0) {
    if (!validate_csrf($_GET['token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) geçersiz.');
    } else {
        $delStmt = $db->prepare("DELETE FROM contact_requests WHERE id = :id");
        $delStmt->execute([':id' => $id]);
        set_flash('success', 'Randevu talebi başarıyla silindi.');
    }
    header('Location: ' . url('xpanel/appointments.php'));
    exit;
}

// Durum ve Not Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
    if (!validate_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) başarısız oldu.');
        header('Location: ' . url('xpanel/appointments.php?id=' . $id));
        exit;
    }

    $newStatus = $_POST['status'] ?? 'new';
    $adminNotes = trim((string)($_POST['admin_notes'] ?? ''));

    $updateStmt = $db->prepare("UPDATE contact_requests SET status = :st, admin_notes = :notes WHERE id = :id");
    $updateStmt->execute([':st' => $newStatus, ':notes' => $adminNotes, ':id' => $id]);

    set_flash('success', 'Talep durumu ve yönetici notu başarıyla güncellendi.');
    header('Location: ' . url('xpanel/appointments.php?id=' . $id));
    exit;
}

// Tek Bir Talep Detayını Görüntüleme
$currentRequest = null;
if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM contact_requests WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $currentRequest = $stmt->fetch();
}

require_once __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <span class="badge-tag">Danışan İletişim Havuzu</span>
        <h1 style="font-size: 2.2rem; font-weight: 700;">Randevu ve Ön Görüşme Talepleri</h1>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">
            Web sitesi formundan gelen tüm danışan başvuruları, telefon numaraları ve şikâyet notları.
        </p>
    </div>
    <?php if ($currentRequest): ?>
    <a href="<?= e(url('xpanel/appointments.php')) ?>" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.75rem 1.5rem;">
        &larr; Tüm Taleplere Dön
    </a>
    <?php endif; ?>
</div>

<?php if ($currentRequest): 
    // Telefonu WhatsApp için temizle
    $phoneDigits = preg_replace('/[^0-9]/', '', $currentRequest['phone']);
    if (str_starts_with($phoneDigits, '0')) {
        $waRecipient = '9' . $phoneDigits;
    } else {
        $waRecipient = $phoneDigits;
    }
    $waQuickText = rawurlencode("Merhaba Sayın " . $currentRequest['full_name'] . ", web sitemiz üzerinden ilettiğiniz randevu talebiniz doğrultusunda Fzt. Evrim Şenol Akarca kliniğinden ulaşıyorum.");
?>
<!-- Talep Detay Kartı -->
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 1.25rem; margin-bottom: 2rem;">
        <div>
            <span class="badge-tag">Talep No: #<?= $currentRequest['id'] ?></span>
            <h2 style="font-size: 1.85rem; margin-top: 0.25rem;"><?= e($currentRequest['full_name']) ?></h2>
        </div>
        <div>
            <?php if ($currentRequest['status'] === 'new'): ?>
                <span class="badge-status badge-new" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Yeni Talep</span>
            <?php elseif ($currentRequest['status'] === 'reviewed'): ?>
                <span class="badge-status badge-reviewed" style="font-size: 0.85rem; padding: 0.4rem 1rem;">İncelendi / İletişime Geçildi</span>
            <?php else: ?>
                <span class="badge-status badge-completed" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Randevu Tamamlandı</span>
            <?php endif; ?>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
        <div>
            <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Danışan Bilgileri</h3>
            
            <p style="margin-bottom: 0.75rem;">
                <strong>Telefon:</strong><br>
                <a href="tel:<?= e($currentRequest['phone']) ?>" style="font-size: 1.15rem; font-weight: 600; color: var(--text-dark);">
                    <?= e($currentRequest['phone']) ?>
                </a>
            </p>

            <p style="margin-bottom: 0.75rem;">
                <strong>İlgilenilen Uzmanlık Alanı:</strong><br>
                <?= e($currentRequest['service_interest']) ?>
            </p>

            <p style="margin-bottom: 0.75rem;">
                <strong>Başvuru Tarihi:</strong><br>
                <?= date('d.m.Y H:i:s', strtotime($currentRequest['created_at'])) ?>
            </p>

            <p style="margin-bottom: 1.5rem;">
                <strong>İstemci IP Adresi:</strong><br>
                <code><?= e($currentRequest['ip_address']) ?></code>
            </p>

            <!-- Hızlı İletişim Butonları -->
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="https://wa.me/<?= e($waRecipient) ?>?text=<?= $waQuickText ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="font-size: 0.78rem;">
                    WhatsApp ile Danışana Yanıt Yaz &rarr;
                </a>
                <a href="tel:<?= e($currentRequest['phone']) ?>" class="btn btn-outline" style="font-size: 0.78rem;">
                    Telefonla Ara
                </a>
            </div>
        </div>

        <div>
            <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Danışanın Mesajı / Şikâyeti</h3>
            <div style="background-color: var(--bg-card-light); border: 1px solid var(--border-subtle); padding: 1.5rem; margin-bottom: 2rem; font-size: 0.95rem; line-height: 1.7;">
                <?= nl2br(e($currentRequest['message'] ?: 'Herhangi bir ek not belirtilmemiş.')) ?>
            </div>

            <!-- Durum Güncelleme Formu -->
            <form method="POST" action="">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Talep Durumu</label>
                    <select name="status" class="form-control">
                        <option value="new" <?= $currentRequest['status'] === 'new' ? 'selected' : '' ?>>Yeni Talep</option>
                        <option value="reviewed" <?= $currentRequest['status'] === 'reviewed' ? 'selected' : '' ?>>İncelendi / Görüşüldü</option>
                        <option value="completed" <?= $currentRequest['status'] === 'completed' ? 'selected' : '' ?>>Randevu Oluşturuldu / Tamamlandı</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Klinik İçi Yönetici Notu</label>
                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Örn: 22 Kasım Çarşamba 14:00 için randevu verildi..."><?= e($currentRequest['admin_notes'] ?? '') ?></textarea>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                    <a href="<?= e(url('xpanel/appointments.php?action=delete&id=' . $currentRequest['id'] . '&token=' . csrf_token())) ?>" 
                       class="btn btn-outline" 
                       style="color: #C0392B; border-color: #C0392B; font-size: 0.75rem;" 
                       onclick="return confirm('Bu talebi kalıcı olarak silmek istediğinize emin misiniz?');">
                        Talebi Sil
                    </a>

                    <button type="submit" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.75rem 1.75rem;">
                        Durumu Kaydet &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php else: 
    // Filtreleme SQL'i
    $sql = "SELECT * FROM contact_requests";
    $params = [];
    if ($filterStatus !== 'all') {
        $sql .= " WHERE status = :st";
        $params[':st'] = $filterStatus;
    }
    $sql .= " ORDER BY id DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $requests = $stmt->fetchAll();
?>
<!-- Filtreleme Butonları -->
<div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <a href="<?= e(url('xpanel/appointments.php?status=all')) ?>" class="btn <?= $filterStatus === 'all' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 0.75rem; padding: 0.5rem 1rem;">
        Tümü (<?= (int)$db->query("SELECT COUNT(*) FROM contact_requests")->fetchColumn() ?>)
    </a>
    <a href="<?= e(url('xpanel/appointments.php?status=new')) ?>" class="btn <?= $filterStatus === 'new' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 0.75rem; padding: 0.5rem 1rem;">
        Yeniler (<?= (int)$db->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'new'")->fetchColumn() ?>)
    </a>
    <a href="<?= e(url('xpanel/appointments.php?status=reviewed')) ?>" class="btn <?= $filterStatus === 'reviewed' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 0.75rem; padding: 0.5rem 1rem;">
        İncelenenler (<?= (int)$db->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'reviewed'")->fetchColumn() ?>)
    </a>
    <a href="<?= e(url('xpanel/appointments.php?status=completed')) ?>" class="btn <?= $filterStatus === 'completed' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 0.75rem; padding: 0.5rem 1rem;">
        Tamamlananlar (<?= (int)$db->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'completed'")->fetchColumn() ?>)
    </a>
</div>

<div class="admin-card">
    <?php if (empty($requests)): ?>
        <p style="padding: 3rem 0; text-align: center; color: var(--text-muted);">Bu kriterde herhangi bir randevu veya iletişim talebi bulunamadı.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Ad Soyad</th>
                        <th>Telefon</th>
                        <th>İlgilenilen Alan</th>
                        <th>Mesaj Özeti</th>
                        <th>Durum</th>
                        <th style="text-align: right;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $r): ?>
                    <tr>
                        <td style="font-size: 0.85rem; color: var(--text-muted); white-space: nowrap;"><?= date('d.m.Y H:i', strtotime($r['created_at'])) ?></td>
                        <td><strong><?= e($r['full_name']) ?></strong></td>
                        <td><a href="tel:<?= e($r['phone']) ?>" style="font-weight: 600;"><?= e($r['phone']) ?></a></td>
                        <td><?= e($r['service_interest']) ?></td>
                        <td style="color: var(--text-muted); font-size: 0.85rem;"><?= e(mb_substr($r['message'] ?: '—', 0, 45)) ?></td>
                        <td>
                            <?php if ($r['status'] === 'new'): ?>
                                <span class="badge-status badge-new">Yeni</span>
                            <?php elseif ($r['status'] === 'reviewed'): ?>
                                <span class="badge-status badge-reviewed">İncelendi</span>
                            <?php else: ?>
                                <span class="badge-status badge-completed">Tamamlandı</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="<?= e(url('xpanel/appointments.php?id=' . $r['id'])) ?>" class="btn btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.7rem; margin-right: 0.25rem;">İncele & Yanıtla</a>
                            <a href="<?= e(url('xpanel/appointments.php?action=delete&id=' . $r['id'] . '&token=' . csrf_token())) ?>" 
                               class="btn btn-outline" 
                               style="padding: 0.35rem 0.65rem; font-size: 0.7rem; color: #C0392B; border-color: #C0392B;" 
                               onclick="return confirm('Bu talebi silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
