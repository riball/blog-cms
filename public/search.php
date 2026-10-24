<?php
require __DIR__ . '/../src/bootstrap.php';

$term = mb_substr(trim((string) ($_GET['q'] ?? '')), 0, 100);
$perPage = (int) config()['per_page'];
$total = $term !== '' ? Post::countPublished(null, $term) : 0;
[$page, $totalPages] = paginate($total, $perPage, (int) ($_GET['page'] ?? 1));
$posts = $term !== '' ? Post::listPublished($page, $perPage, null, $term) : [];

render('header', [
    'pageTitle' => ($term !== '' ? "Search: $term" : 'Search') . ' | ' . config()['site_name'],
    'metaDescription' => 'Search posts on ' . config()['site_name'] . '.',
]);
?>
<h1>Search</h1>
<form class="search-page" action="<?= e(url('search.php')) ?>" method="get">
    <input type="search" name="q" value="<?= e($term) ?>" maxlength="100" placeholder="Search titles and content">
    <button type="submit" class="button">Search</button>
</form>

<?php if ($term !== ''): ?>
    <p>Showing <?= (int) $total ?> result<?= $total === 1 ? '' : 's' ?> for “<?= e($term) ?>”.</p>
    <?php render('post_list', ['posts' => $posts]); ?>
    <?php render('pagination', ['page' => $page, 'totalPages' => $totalPages, 'path' => 'search.php', 'params' => ['q' => $term]]); ?>
<?php endif; ?>
<?php render('footer');
