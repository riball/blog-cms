<?php
require __DIR__ . '/../src/bootstrap.php';

$slug = (string) ($_GET['slug'] ?? '');
$category = $slug !== '' ? Category::findBySlug($slug) : null;
if ($category === null) {
    not_found();
}

$categoryId = (int) $category['id'];
$perPage = (int) config()['per_page'];
$total = Post::countPublished($categoryId);
[$page, $totalPages] = paginate($total, $perPage, (int) ($_GET['page'] ?? 1));
$posts = Post::listPublished($page, $perPage, $categoryId);

render('header', [
    'pageTitle' => $category['name'] . ' | ' . config()['site_name'],
    'metaDescription' => 'Posts in the ' . $category['name'] . ' category.',
    'canonical' => link_to('category.php', ['slug' => $category['slug']]),
]);
?>
<h1><?= e($category['name']) ?></h1>
<?php render('post_list', ['posts' => $posts]); ?>
<?php render('pagination', ['page' => $page, 'totalPages' => $totalPages, 'path' => 'category.php', 'params' => ['slug' => $slug]]); ?>
<?php render('footer');
