<?php
declare(strict_types=1);

$adminPageTitle = 'Sıkça Sorulan Sorular Yönetimi';
require_once __DIR__ . '/includes/auth.php';

$db = get_db();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Silme İşlemi
if ($action === 'delete' && $id > 0) {
    if (!validate_csrf($_GET['token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) geçersiz.');
    } else {
        $delStmt = $db->prepare("DELETE FROM faqs WHERE id = :id");
        $delStmt->execute([':id' => $id]);
        set_flash('success', 'Soru başarıyla silindi.');
    }
    header('Location: ' . url('xpanel/faqs.php'));
    exit;
}

// Kaydetme / Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) başarısız oldu.');
        header('Location: ' . url('xpanel/faqs.php'));
        exit;
    }

    $question = trim((string)($_POST['question'] ?? ''));
    $answer = trim((string)($_POST['answer'] ?? ''));
    $displayOrder = (int)($_POST['display_order'] ?? 0);
    $isActive = !empty($_POST['is_active']) ? 1 : 0;

    if ($id > 0) {
        $stmt = $db->prepare("UPDATE faqs SET question = :q, answer = :a, display_order = :ord, is_active = :act WHERE id = :id");
        $stmt->execute([':q' => $question, ':a' => $answer, ':ord' => $displayOrder, ':act' => $isActive, ':id' => $id]);
        set_flash('success', 'Soru ve cevap başarıyla güncellendi.');
    } else {
        $stmt = $db->prepare("INSERT INTO faqs (question, answer, display_order, is_active) VALUES (:q, :a, :ord, :act)");
        $stmt->execute([':q' => $question, ':a' => $answer, ':ord' => $displayOrder, ':act' => $isActive]);
        set_flash('success', 'Yeni soru başarıyla eklendi.');
    }

    header('Location: ' . url('xpanel/faqs.php'));
    exit;
}

// Düzenleme kaydını çek
$currentFaq = null;
if (($action === 'edit' || $action === 'new') && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM faqs WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $currentFaq = $stmt->fetch();
}

require_once __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <span class="badge-tag">Danışan Bilgilendirme</span>
        <h1 style="font-size: 2.2rem; font-weight: 700;">Sıkça Sorulan Sorular (SSS)</h1>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">
            Pelvik taban, seans süreçleri, biofeedback ve klinik uygulamaları ile ilgili soruları yönetin.
        </p>
    </div>
    <?php if ($action === 'list'): ?>
    <a href="<?= e(url('xpanel/faqs.php?action=new')) ?>" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.75rem 1.5rem;">
        + Yeni Soru Ekle
    </a>
    <?php else: ?>
    <a href="<?= e(url('xpanel/faqs.php')) ?>" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.75rem 1.5rem;">
        &larr; Soru Listesine Dön
    </a>
    <?php endif; ?>
</div>

<?php if ($action === 'edit' || $action === 'new'): ?>
<form method="POST" action="">
    <?= csrf_field() ?>

    <div class="admin-card">
        <span class="badge-tag"><?= $id > 0 ? 'Soruyu Düzenle' : 'Yeni Soru Tanımla' ?></span>
        <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;"><?= $id > 0 ? 'Soru Detayları' : 'Yeni Soru Ekle' ?></h2>

        <div class="form-group">
            <label class="form-label">Soru Metni *</label>
            <input type="text" name="question" class="form-control" value="<?= e($currentFaq['question'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Cevap Metni *</label>
            <textarea name="answer" class="form-control" rows="6" required><?= e($currentFaq['answer'] ?? '') ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Görüntülenme Sırası</label>
                <input type="number" name="display_order" class="form-control" value="<?= e((string)($currentFaq['display_order'] ?? 0)) ?>">
            </div>
            <div class="form-group" style="display: flex; align-items: center; padding-top: 1.75rem;">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" <?= (!isset($currentFaq['is_active']) || $currentFaq['is_active']) ? 'checked' : '' ?>>
                    <span style="font-weight: 600; color: var(--text-dark);">Soru Sitede Aktif Olarak Görüntülensin</span>
                </label>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
            <a href="<?= e(url('xpanel/faqs.php')) ?>" class="btn btn-outline">İptal</a>
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 0.9rem;">
                Soruyu Kaydet &rarr;
            </button>
        </div>
    </div>
</form>

<?php else: 
    $faqs = $db->query("SELECT * FROM faqs ORDER BY display_order ASC, id ASC")->fetchAll();
?>
<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Sıra</th>
                    <th>Soru</th>
                    <th>Cevap Özeti</th>
                    <th>Durum</th>
                    <th style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faqs as $f): ?>
                <tr>
                    <td><strong><?= e((string)$f['display_order']) ?></strong></td>
                    <td><strong><?= e($f['question']) ?></strong></td>
                    <td style="color: var(--text-muted); font-size: 0.85rem;"><?= e(mb_substr($f['answer'], 0, 90)) ?>...</td>
                    <td>
                        <?php if ($f['is_active']): ?>
                            <span class="badge-status badge-new">Aktif</span>
                        <?php else: ?>
                            <span class="badge-status" style="background: #FEE; color: #C0392B;">Pasif</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="<?= e(url('xpanel/faqs.php?action=edit&id=' . $f['id'])) ?>" class="btn btn-primary" style="padding: 0.35rem 0.65rem; font-size: 0.7rem; margin-right: 0.25rem;">Düzenle</a>
                        <a href="<?= e(url('xpanel/faqs.php?action=delete&id=' . $f['id'] . '&token=' . csrf_token())) ?>" 
                           class="btn btn-outline" 
                           style="padding: 0.35rem 0.65rem; font-size: 0.7rem; color: #C0392B; border-color: #C0392B;" 
                           onclick="return confirm('Bu soruyu silmek istediğinize emin misiniz?');">Sil</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
