<?php
require __DIR__ . '/../src/bootstrap.php';

$slug = (string) ($_GET['slug'] ?? '');
$post = $slug !== '' ? Post::findPublishedBySlug($slug) : null;
if ($post === null) {
    not_found();
}

render('header', [
    'pageTitle' => Post::seoTitle($post),
    'metaDescription' => Post::seoDescription($post),
    'canonical' => link_to('post.php', ['slug' => $post['slug']]),
]);
?>
<article class="article">
    <header class="article-head">
        <p class="article-meta">
            <time datetime="<?= e(substr($post['published_at'], 0, 10)) ?>"><?= e(format_date($post['published_at'])) ?></time>
            <?php if ($post['category_name']): ?>
                <a class="tag" href="<?= e(link_to('category.php', ['slug' => $post['category_slug']])) ?>"><?= e($post['category_name']) ?></a>
            <?php endif; ?>
        </p>
        <h1><?= e($post['title']) ?></h1>
    </header>
    <div class="post-body"><?= nl2br(e($post['content'])) ?></div>
</article>
<p class="back"><a href="<?= e(url('index.php')) ?>">All posts</a></p>
<?php render('footer');