<?php
require __DIR__ . '/../../src/bootstrap.php';
Auth::requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$existing = null;
if ($id !== null) {
    $existing = Post::findById($id);
    if ($existing === null) {
        not_found();
    }
}

$values = $existing ?? [
    'title' => '', 'slug' => '', 'excerpt' => '', 'content' => '',
    'status' => 'draft', 'seo_title' => '', 'seo_description' => '',
    'category_id' => null, 'published_at' => null,
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $input = [
        'title' => trim((string) ($_POST['title'] ?? '')),
        'slug' => trim((string) ($_POST['slug'] ?? '')),
        'content' => trim((string) ($_POST['content'] ?? '')),
        'category_id' => (int) ($_POST['category_id'] ?? 0) ?: null,
        'status' => (string) ($_POST['status'] ?? 'draft'),
        'seo_title' => trim((string) ($_POST['seo_title'] ?? '')),
        'seo_description' => trim((string) ($_POST['seo_description'] ?? '')),
    ];

    $validator = (new Validator())
        ->required($input['title'], 'Title')
        ->maxLength($input['title'], 200, 'Title')
        ->required($input['content'], 'Content')
        ->oneOf($input['status'], ['draft', 'published'], 'Status')
        ->maxLength($input['seo_title'], 60, 'SEO title')
        ->maxLength($input['seo_description'], 155, 'SEO description');

    if ($input['category_id'] !== null && Category::findById($input['category_id']) === null) {
        $validator->add('Please choose a valid category.');
    }

    if ($validator->passes()) {
        // Keep the existing slug when editing and the slug field is blank, so links don't change.
        $base = $input['slug'] !== '' ? $input['slug'] : ($existing['slug'] ?? $input['title']);
        $slug = Slug::unique(Slug::make($base), 'posts', $id);

        // Set the publish date the first time a post is published, and keep it after that.
        $publishedAt = null;
        if ($input['status'] === 'published') {
            $publishedAt = $existing['published_at'] ?? date('Y-m-d H:i:s');
        }

        $savedId = Post::save([
            'category_id' => $input['category_id'],
            'title' => $input['title'],
            'slug' => $slug,
            'excerpt' => truncate_text(plain_text($input['content']), 200),
            'content' => $input['content'],
            'status' => $input['status'],
            'seo_title' => $input['seo_title'] ?: null,
            'seo_description' => $input['seo_description'] ?: null,
            'published_at' => $publishedAt,
        ], $id);

        flash_set('success', 'Post saved.');
        redirect(link_to('admin/edit.php', ['id' => $savedId]));
    }

    $errors = $validator->errors();
    $values = array_merge($values, $input);
}

$categories = Category::all();
$heading = $id !== null ? 'Edit post' : 'New post';

render('header', ['pageTitle' => $heading . ' | Admin', 'bodyClass' => 'admin']);
render('admin_nav');
render('flash');
?>
<h1><?= e($heading) ?></h1>

<?php if ($errors): ?>
    <div class="flash flash-error">
        <ul>
            <?php foreach ($errors as $message): ?>
                <li><?= e($message) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e(link_to('admin/edit.php', $id !== null ? ['id' => $id] : [])) ?>" class="post-form">
    <?= Csrf::field() ?>

    <label>Title
        <input type="text" name="title" maxlength="200" required value="<?= e($values['title']) ?>">
    </label>

    <label>Slug (leave blank to generate from the title)
        <input type="text" name="slug" maxlength="220" value="<?= e($values['slug']) ?>">
    </label>

    <label>Category
        <select name="category_id">
            <option value="">No category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>"
                    <?= (int) $values['category_id'] === (int) $category['id'] ? 'selected' : '' ?>>
                    <?= e($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Status
        <select name="status">
            <?php foreach (['draft', 'published'] as $option): ?>
                <option value="<?= $option ?>" <?= $values['status'] === $option ? 'selected' : '' ?>><?= ucfirst($option) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Content (plain text; line breaks are kept)
        <textarea name="content" rows="16" required><?= e($values['content']) ?></textarea>
    </label>

    <label>SEO title <span class="counter" id="seo-title-count"></span>
        <input type="text" name="seo_title" maxlength="60" value="<?= e($values['seo_title']) ?>"
               data-counter="seo-title-count" data-max="60">
    </label>

    <label>SEO description <span class="counter" id="seo-desc-count"></span>
        <textarea name="seo_description" maxlength="155" rows="3"
                  data-counter="seo-desc-count" data-max="155"><?= e($values['seo_description']) ?></textarea>
    </label>

    <div class="form-actions">
        <button type="submit" class="button">Save post</button>
        <a href="<?= e(link_to('admin/index.php')) ?>">Cancel</a>
    </div>
</form>
<?php render('footer', ['pageScript' => 'js/admin.js']);
