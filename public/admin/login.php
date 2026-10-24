<?php
require __DIR__ . '/../../src/bootstrap.php';

if (Auth::check()) {
    redirect(link_to('admin/index.php'));
}

$error = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (Auth::attempt($email, $password)) {
        redirect(link_to('admin/index.php'));
    }
    $error = 'That email and password do not match.';
}

render('header', ['pageTitle' => 'Admin login | ' . config()['site_name'], 'bodyClass' => 'admin login']);
?>
<h1>Admin login</h1>
<?php if ($error): ?>
    <div class="flash flash-error"><?= e($error) ?></div>
<?php endif; ?>
<form method="post" action="<?= e(link_to('admin/login.php')) ?>" class="post-form">
    <?= Csrf::field() ?>
    <label>Email
        <input type="email" name="email" required value="<?= e($email) ?>">
    </label>
    <label>Password
        <input type="password" name="password" required>
    </label>
    <button type="submit" class="button">Log in</button>
</form>
<?php render('footer');
