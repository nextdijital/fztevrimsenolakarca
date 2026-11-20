<?php
declare(strict_types=1);

$adminPageTitle = 'Blog Yazıları Yönetimi';
require_once __DIR__ . '/includes/auth.php';

$db = get_db();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Silme İşlemi
if ($action === 'delete' && $id > 0) {
    if (!validate_csrf($_GET['token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) geçersiz.');
    } else {
        $delStmt = $db->prepare("DELETE FROM blog_posts WHERE id = :id");
        $delStmt->execute([':id' => $id]);
        set_flash('success', 'Blog yazısı başarıyla silindi.');
    }
    header('Location: ' . url('xpanel/blog.php'));
    exit;
}

// Kaydetme / Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Güvenlik doğrulaması (CSRF) başarısız oldu.');
        header('Location: ' . url('xpanel/blog.php'));
        exit;
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    $category = trim((string)($_POST['category'] ?? 'Fizyoterapi'));
    $author = trim((string)($_POST['author'] ?? 'Fzt. Evrim Şenol Akarca'));
    $readTime = trim((string)($_POST['read_time'] ?? '5 dk okuma'));
    $summary = trim((string)($_POST['summary'] ?? ''));
    $content = plain_text_to_html(trim((string)($_POST['content'] ?? '')));
    $metaTitle = trim((string)($_POST['meta_title'] ?? ''));
    $metaDescription = trim((string)($_POST['meta_description'] ?? ''));
    $isActive = !empty($_POST['is_active']) ? 1 : 0;

    // Basit slug üretimi
    if (empty($slug)) {
        $slug = mb_strtolower(str_replace([' ', 'ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'], ['-', 'i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'], $title));
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
    }

    if (empty($title) || empty($content)) {
        set_flash('danger', 'Başlık ve makale içeriği zorunludur.');
        header('Location: ' . url('xpanel/blog.php' . ($id > 0 ? '?action=edit&id=' . $id : '?action=new')));
        exit;
    }

    // Görsel yükleme / silme yönetimi
    $imageUrl = null;
    if ($id > 0) {
        $existingImg = $db->query("SELECT image_url FROM blog_posts WHERE id = " . (int)$id)->fetchColumn();
        $imageUrl = $existingImg ?: null;
    }
    
    // Görsel silme isteği
    if (!empty($_POST['delete_blog_image'])) {
        if ($imageUrl && str_contains($imageUrl, 'uploads') && file_exists(__DIR__ . '/../' . ltrim($imageUrl, '/'))) {
            @unlink(__DIR__ . '/../' . ltrim($imageUrl, '/'));
        }
        $imageUrl = null;
    }

    // Yeni görsel yüklendiyse
    if (!empty($_FILES['blog_image']['name'])) {
        $uploaded = handle_image_upload($_FILES['blog_image'], 'blog');
        if ($uploaded) {
            $imageUrl = $uploaded;
        }
    }

    if ($id > 0) {
        $stmt = $db->prepare("
            UPDATE blog_posts 
            SET title = :title, slug = :slug, category = :cat, author = :auth, read_time = :rt, 
                summary = :sum, content = :cont, meta_title = :mt, meta_description = :md, image_url = :img, is_active = :act
            WHERE id = :id
        ");
        $stmt->execute([
            ':title' => $title, ':slug' => $slug, ':cat' => $category, ':auth' => $author,
            ':rt' => $readTime, ':sum' => $summary, ':cont' => $content, ':mt' => $metaTitle,
            ':md' => $metaDescription, ':img' => $imageUrl, ':act' => $isActive, ':id' => $id
        ]);
        set_flash('success', 'Blog yazısı başarıyla güncellendi.');
    } else {
        $stmt = $db->prepare("
            INSERT INTO blog_posts (title, slug, category, author, read_time, summary, content, meta_title, meta_description, image_url, is_active, published_at)
            VALUES (:title, :slug, :cat, :auth, :rt, :sum, :cont, :mt, :md, :img, :act, NOW())
        ");
        $stmt->execute([
            ':title' => $title, ':slug' => $slug, ':cat' => $category, ':auth' => $author,
            ':rt' => $readTime, ':sum' => $summary, ':cont' => $content, ':mt' => $metaTitle,
            ':md' => $metaDescription, ':img' => $imageUrl, ':act' => $isActive
        ]);
        set_flash('success', 'Yeni blog yazısı başarıyla eklendi.');
    }

    header('Location: ' . url('xpanel/blog.php'));
    exit;
}

// Düzenleme kaydını çek
$currentPost = null;
if (($action === 'edit') && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $currentPost = $stmt->fetch();
}

require_once __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <span class="badge-tag">İçerik Pazarlaması & SEO</span>
        <h1 style="font-size: 2rem; font-weight: 700;">Blog & Makale Yönetimi</h1>
    </div>
    <div>
        <?php if ($action === 'list'): ?>
        <a href="<?= e(url('xpanel/blog.php?action=new')) ?>" class="btn btn-primary">
            + Yeni Blog Yazısı Ekle
        </a>
        <?php else: ?>
        <a href="<?= e(url('xpanel/blog.php')) ?>" class="btn btn-outline">
            &larr; Yazı Listesine Dön
        </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($action === 'new' || $action === 'edit'): ?>
<div class="admin-card">
    <h2 style="font-size: 1.45rem; margin-bottom: 1.5rem;">
        <?= $action === 'edit' ? 'Blog Yazısını Düzenle' : 'Yeni Blog Yazısı Ekle' ?>
    </h2>

    <form method="POST" action="<?= e(url('xpanel/blog.php' . ($id > 0 ? '?id=' . $id : ''))) ?>">
        <?= csrf_field() ?>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label" for="title">Yazı Başlığı *</label>
                <input type="text" id="title" name="title" class="form-control" required value="<?= e($currentPost['title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="category">Kategori</label>
                <input type="text" id="category" name="category" class="form-control" placeholder="Örn: Pelvik Taban" value="<?= e($currentPost['category'] ?? 'Fizyoterapi') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label" for="slug">URL Slug (Boş bırakılırsa otomatik oluşur)</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="yazi-basligi-ornegi" value="<?= e($currentPost['slug'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="author">Yazar</label>
                <input type="text" id="author" name="author" class="form-control" value="<?= e($currentPost['author'] ?? 'Fzt. Evrim Şenol Akarca') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="read_time">Okuma Süresi</label>
                <input type="text" id="read_time" name="read_time" class="form-control" placeholder="5 dk okuma" value="<?= e($currentPost['read_time'] ?? '5 dk okuma') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="summary">Kısa Özet (Liste ve kartlarda görünür) *</label>
            <textarea id="summary" name="summary" class="form-control" rows="3" required><?= e($currentPost['summary'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label" for="content">Makale İçeriği *</label>
            <textarea id="content" name="content" class="form-control" rows="12" required><?= e(html_to_plain_text($currentPost['content'] ?? '')) ?></textarea>
            <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.35rem; display: block;">
                Paragraflarınızı ayırmak için Enter tuşuna basmanız yeterlidir. HTML kodu yazmanıza gerek yoktur, otomatik biçimlendirilir.
            </small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; background: var(--bg-card-light); padding: 1.5rem; border: 1px solid var(--border-subtle); margin-bottom: 1.5rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="meta_title">SEO Meta Başlığı (İsteğe bağlı)</label>
                <input type="text" id="meta_title" name="meta_title" class="form-control" value="<?= e($currentPost['meta_title'] ?? '') ?>">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="meta_description">SEO Meta Açıklaması (İsteğe bağlı)</label>
                <input type="text" id="meta_description" name="meta_description" class="form-control" value="<?= e($currentPost['meta_description'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-checkbox">
                <input type="checkbox" name="is_active" value="1" <?= (!isset($currentPost['is_active']) || $currentPost['is_active']) ? 'checked' : '' ?>>
                <span>Bu blog yazısı sitede yayında olsun (Aktif)</span>
            </label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <?= $action === 'edit' ? 'Değişiklikleri Kaydet' : 'Yazıyı Yayınla' ?>
            </button>
        </div>
    </form>
</div>

<?php else: ?>
<!-- Liste Görünümü -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Yazı Başlığı</th>
                    <th>Kategori</th>
                    <th>Okunma</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th style="text-align: right; width: 180px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $posts = $db->query("SELECT * FROM blog_posts ORDER BY published_at DESC, id DESC")->fetchAll();
                if (empty($posts)):
                ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem;">Henüz kayıtlı blog yazısı bulunmuyor.</td>
                </tr>
                <?php else: foreach ($posts as $p): ?>
                <tr>
                    <td>#<?= $p['id'] ?></td>
                    <td>
                        <strong><?= e($p['title']) ?></strong><br>
                        <small style="color: var(--text-muted); font-size: 0.78rem;">/blog/<?= e($p['slug']) ?></small>
                    </td>
                    <td><span class="badge-status badge-new"><?= e($p['category']) ?></span></td>
                    <td><?= (int)$p['views'] ?> kez</td>
                    <td><?= date('d.m.Y', strtotime($p['published_at'])) ?></td>
                    <td>
                        <span class="badge-status <?= $p['is_active'] ? 'badge-new' : 'badge-completed' ?>">
                            <?= $p['is_active'] ? 'Yayında' : 'Taslak / Pasif' ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="<?= e(url('blog/' . $p['slug'])) ?>" target="_blank" rel="noopener noreferrer" style="font-size: 0.8rem; margin-right: 0.75rem; text-decoration: underline;">Gör</a>
                        <a href="<?= e(url('xpanel/blog.php?action=edit&id=' . $p['id'])) ?>" style="font-size: 0.8rem; margin-right: 0.75rem; font-weight: 600;">Düzenle</a>
                        <a href="<?= e(url('xpanel/blog.php?action=delete&id=' . $p['id'] . '&token=' . csrf_token())) ?>" 
                           style="font-size: 0.8rem; color: #E57373;" 
                           onclick="return confirm('Bu blog yazısını silmek istediğinize emin misiniz?');">Sil</a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
