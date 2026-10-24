<?php
// Database queries for categories.

class Category
{
    public static function all(): array
    {
        return Database::connection()->query('SELECT id, name, slug FROM categories ORDER BY name')->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        return self::fetchOne('id', $id);
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::fetchOne('slug', $slug);
    }

    public static function save(string $name, string $slug, ?int $id): void
    {
        $pdo = Database::connection();
        if ($id === null) {
            $stmt = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
            $stmt->execute([$name, $slug]);
            return;
        }

        $stmt = $pdo->prepare('UPDATE categories SET name = ?, slug = ? WHERE id = ?');
        $stmt->execute([$name, $slug, $id]);
    }

    // Posts in a deleted category become uncategorised (set by the foreign key).
    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
    }

    private static function fetchOne(string $column, int|string $value): ?array
    {
        $stmt = Database::connection()->prepare("SELECT id, name, slug FROM categories WHERE $column = ? LIMIT 1");
        $stmt->execute([$value]);
        return $stmt->fetch() ?: null;
    }
}
