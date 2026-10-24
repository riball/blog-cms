<?php
// Protects admin forms from being submitted by other websites.

class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . e(self::token()) . '">';
    }

    public static function verify(): bool
    {
        $sent = $_POST['csrf_token'] ?? '';
        return is_string($sent) && hash_equals(self::token(), $sent);
    }
}
