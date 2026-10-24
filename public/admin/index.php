<?php
require __DIR__ . '/../../src/bootstrap.php';
Auth::requireLogin();

$posts = Post::allForAdmin();

render('header', ['pageTitle' => 'Posts | Admin', 'bodyClass' => 'admin']);
render('admin_nav');
render('flash');
?>
<div class="page-head">
    <h1>Posts</h1>
    <a class="button" href="<?= e(link_to('admin/edit.php')) ?>">New post</a>
</div>

<?php if (!$posts): ?>
    <p>No posts yet.</p>
<?php else: ?>
    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>Title</th><th>Status</th><th>Category</th><th>Updated</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= e($post['title']) ?></td>
                    <td><span class="badge badge-<?= e($post['status']) ?>"><?= e($post['status']) ?></span></td>
                    <td><?= e($post['category_name'] ?? '-') ?></td>
                    <td><?= e(format_date($post['updated_at'])) ?></td>
                    <td class="actions">
                        <a href="<?= e(link_to('admin/edit.php', ['id' => $post['id']])) ?>">Edit</a>
                        <?php if ($post['status'] === 'published'): ?>
                            <a href="<?= e(link_to('post.php', ['slug' => $post['slug']])) ?>">View</a>
                        <?php endif; ?>
                        <form method="post" action="<?= e(link_to('admin/delete.php')) ?>" class="inline"
                              data-confirm="Delete this post? This cannot be undone.">
                            <?= Csrf::field() ?>
                            <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
                            <button type="submit" class="link-button danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php render('footer', ['pageScript' => 'js/admin.js']);
