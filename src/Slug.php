<?php
// Turns titles into URL slugs and keeps them unique.

class Slug
{
    private const ALLOWED_TABLES = ['posts', 'categories'];

    // "My First Post!" -> "my-first-post"
    public static function make(string $text): string
    {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $text), '-'));
        $slug = substr($slug, 0, 200);
        return $slug !== '' ? $slug : 'item';
    }

    // "my-post" -> "my-post-2" if "my-post" is taken. Pass $ignoreId when editing a row.
    public static function unique(string $base, string $table, ?int $ignoreId = null): string
    {
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            throw new InvalidArgumentException("Unknown table: $table");
        }

        $slug = $base;
        $suffix = 2;
        while (self::exists($table, $slug, $ignoreId)) {
            $slug = $base . '-' . $suffix++;
        }
        return $slug;
    }

    private static function exists(string $table, string $slug, ?int $ignoreId): bool
    {
        $sql = "SELECT COUNT(*) FROM $table WHERE slug = ?";
        $params = [$slug];
        if ($ignoreId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }
}
