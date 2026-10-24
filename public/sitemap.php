<?php
// XML sitemap of published posts, for search engines.
require __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', "\n";
echo '  <url><loc>', e(url('index.php')), "</loc></url>\n";

foreach (Post::allPublishedForSitemap() as $post) {
    echo '  <url><loc>', e(link_to('post.php', ['slug' => $post['slug']])), '</loc>',
         '<lastmod>', e(date('Y-m-d', strtotime($post['updated_at']))), "</lastmod></url>\n";
}

echo "</urlset>\n";
