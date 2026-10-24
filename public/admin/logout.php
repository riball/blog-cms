<?php
require __DIR__ . '/../../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    Auth::logout();
}
redirect(link_to('admin/login.php'));
