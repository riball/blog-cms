<nav class="admin-nav">
    <a href="<?= e(link_to('admin/index.php')) ?>">Posts</a>
    <a href="<?= e(link_to('admin/edit.php')) ?>">New post</a>
    <a href="<?= e(link_to('admin/categories.php')) ?>">Categories</a>
    <form method="post" action="<?= e(link_to('admin/logout.php')) ?>" class="inline">
        <?= Csrf::field() ?>
        <button type="submit" class="link-button">Log out</button>
    </form>
</nav>
