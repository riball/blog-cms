<?php
// Usage: php bin/create-admin.php you@example.com "a-long-password"
if (PHP_SAPI !== 'cli') {
    exit("Run this script from the command line.\n");
}

require __DIR__ . '/../src/bootstrap.php';

$email = $argv[1] ?? '';
$password = $argv[2] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Please give a valid email address.\n");
    exit(1);
}

if (strlen($password) < 12) {
    fwrite(STDERR, "Password must be at least 12 characters.\n");
    exit(1);
}

$stmt = Database::connection()->prepare(
    'INSERT INTO admins (email, password_hash) VALUES (?, ?)
     ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)'
);
$stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);

echo "Admin account saved for $email.\n";
