<?php
// Single admin login: checks the password and tracks the session.

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $stmt = Database::connection()->prepare('SELECT id, password_hash FROM admins WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect(link_to('admin/login.php'));
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
