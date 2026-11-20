<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');

$services = get_all_services(true);
$blogPosts = get_all_blog_posts(true);
$now = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= e(url()) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?= e(url('hakkimizda')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= e(url('hizmetlerimiz')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <?php foreach ($services as $srv): ?>
    <url>
        <loc><?= e(url('hizmetler/' . $srv['slug'])) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($srv['updated_at'] ?? 'now')) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    <?php endforeach; ?>
    <url>
        <loc><?= e(url('blog')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    <?php foreach ($blogPosts as $bp): ?>
    <url>
        <loc><?= e(url('blog/' . $bp['slug'])) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($bp['updated_at'] ?? $bp['published_at'])) ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <?php endforeach; ?>
    <url>
        <loc><?= e(url('tarifler')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    <url>
        <loc><?= e(url('sikca-sorulan-sorular')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= e(url('iletisim')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= e(url('kvkk')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?= e(url('cerez-politikasi')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?= e(url('yasal-uyari')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?= e(url('randevu-politikasi')) ?></loc>
        <lastmod><?= $now ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>
</urlset>
