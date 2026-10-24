<?php
// Small shared functions used across pages and templates.

function config(): array
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../config.php';
    }
    return $config;
}

// Full URL for a path relative to the site root, e.g. url('admin/index.php').
function url(string $path = ''): string
{
    return rtrim(config()['base_url'], '/') . '/' . ltrim($path, '/');
}

// URL with query parameters, e.g. link_to('post.php', ['slug' => 'hello']).
function link_to(string $path, array $params = []): string
{
    $query = $params ? '?' . http_build_query($params) : '';
    return url($path) . $query;
}

// Escape any value for safe HTML output.
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $location): never
{
    header('Location: ' . $location);
    exit;
}

// Render a file from templates/, making the given variables available to it.
function render(string $template, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    require __DIR__ . '/../templates/' . $template . '.php';
}

function not_found(): never
{
    http_response_code(404);
    render('header', ['pageTitle' => 'Page not found']);
    echo '<h1>Page not found</h1><p><a href="' . e(url('index.php')) . '">Back to the blog</a></p>';
    render('footer');
    exit;
}

function require_csrf(): void
{
    if (!Csrf::verify()) {
        http_response_code(419);
        exit('Your form expired. Go back, refresh the page, and try again.');
    }
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// Returns the stored message once, then clears it.
function flash_get(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

// Returns [current page, total pages], keeping the page number in range.
function paginate(int $total, int $perPage, int $requested): array
{
    $totalPages = max(1, (int) ceil($total / $perPage));
    return [min(max(1, $requested), $totalPages), $totalPages];
}

function format_date(string $datetime): string
{
    return date('j M Y', strtotime($datetime));
}

// Strips HTML tags and collapses whitespace.
function plain_text(string $text): string
{
    return trim(preg_replace('/\s+/u', ' ', strip_tags($text)));
}

function truncate_text(string $text, int $length): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return rtrim(mb_substr($text, 0, $length - 1)) . '…';
}
