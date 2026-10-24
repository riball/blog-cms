<?php
require __DIR__ . '/../../src/bootstrap.php';
Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(link_to('admin/index.php'));
}
require_csrf();

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    Post::delete($id);
    flash_set('success', 'Post deleted.');
}
redirect(link_to('admin/index.php'));
