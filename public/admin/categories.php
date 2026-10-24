<?php
require __DIR__ . '/../../src/bootstrap.php';
Auth::requireLogin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0) ?: null;

    if ($action === 'delete' && $id !== null) {
        Category::delete($id);
        flash_set('success', 'Category deleted. Its posts are now uncategorised.');
        redirect(link_to('admin/categories.php'));
    }

    if ($action === 'save') {
        $name = trim((string) ($_POST['name'] ?? ''));
        $validator = (new Validator())->required($name, 'Name')->maxLength($name, 100, 'Name');

        if ($validator->passes()) {
            // A new category gets a generated slug. Renaming keeps the existing slug so URLs stay the same.
            $existing = $id !== null ? Category::findById($id) : null;
            $slug = $existing ? $existing['slug'] : Slug::unique(Slug::make($name), 'categories');
            Category::save($name, $slug, $id);
            flash_set('success', 'Category saved.');
            redirect(link_to('admin/categories.php'));
        }
        $errors = $validator->errors();
    }
}

$categories = Category::all();

render('header', ['pageTitle' => 'Categories | Admin', 'bodyClass' => 'admin']);
render('admin_nav');
render('flash');
?>
<h1>Categories</h1>

<?php if ($errors): ?>
    <div class="flash flash-error">
        <ul><?php foreach ($errors as $message): ?><li><?= e($message) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e(link_to('admin/categories.php')) ?>" class="inline-form">
    <?= Csrf::field() ?>
    <input type="hidden" name="action" value="save">
    <input type="text" name="name" maxlength="100" placeholder="New category name" required>
    <button type="submit" class="button">Add category</button>
</form>

<div class="table-wrap">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Slug</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td>
                    <form method="post" action="<?= e(link_to('admin/categories.php')) ?>" class="inline-form">
                        <?= Csrf::field() ?>
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="id" value="<?= (int) $category['id'] ?>">
                        <input type="text" name="name" maxlength="100" value="<?= e($category['name']) ?>" required>
                        <button type="submit" class="link-button">Rename</button>
                    </form>
                </td>
                <td><?= e($category['slug']) ?></td>
                <td class="actions">
                    <form method="post" action="<?= e(link_to('admin/categories.php')) ?>" class="inline"
                          data-confirm="Delete this category? Its posts will become uncategorised.">
                        <?= Csrf::field() ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int) $category['id'] ?>">
                        <button type="submit" class="link-button danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php render('footer', ['pageScript' => 'js/admin.js']);
