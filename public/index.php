<?php
require __DIR__ . '/../src/bootstrap.php';

$perPage = (int) config()['per_page'];
$total = Post::countPublished();
[$page, $totalPages] = paginate($total, $perPage, (int) ($_GET['page'] ?? 1));
$posts = Post::listPublished($page, $perPage);

render('header', [
    'pageTitle' => config()['site_name'],
    'metaDescription' => 'Latest posts from ' . config()['site_name'] . '.',
    'canonical' => url('index.php'),
]);
?>
<h1>Latest posts</h1>
<?php render('post_list', ['posts' => $posts]); ?>
<?php render('pagination', ['page' => $page, 'totalPages' => $totalPages, 'path' => 'index.php', 'params' => []]); ?>
<?php render('footer');
