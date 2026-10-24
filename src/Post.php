<?php
// Every database query for posts lives here.

class Post
{
    private const BASE_SELECT = 'SELECT p.id, p.category_id, p.title, p.slug, p.excerpt, p.content, p.status,
            p.seo_title, p.seo_description, p.published_at, p.updated_at,
            c.name AS category_name, c.slug AS category_slug
        FROM posts p
        LEFT JOIN categories c ON c.id = p.category_id';

    public static function findPublishedBySlug(string $slug): ?array
    {
        return self::fetchOne(self::BASE_SELECT . " WHERE p.slug = ? AND p.status = 'published'", [$slug]);
    }

    public static function findById(int $id): ?array
    {
        return self::fetchOne(self::BASE_SELECT . ' WHERE p.id = ?', [$id]);
    }

    public static function allForAdmin(): array
    {
        return Database::connection()->query(self::BASE_SELECT . ' ORDER BY p.updated_at DESC')->fetchAll();
    }

    public static function listPublished(int $page, int $perPage, ?int $categoryId = null, ?string $term = null): array
    {
        [$where, $params] = self::publishedFilter($categoryId, $term);
        $sql = self::BASE_SELECT . $where . ' ORDER BY p.published_at DESC LIMIT ? OFFSET ?';
        return self::fetchPage($sql, $params, $perPage, ($page - 1) * $perPage);
    }

    public static function countPublished(?int $categoryId = null, ?string $term = null): int
    {
        [$where, $params] = self::publishedFilter($categoryId, $term);
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM posts p' . $where);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function allPublishedForSitemap(): array
    {
        return Database::connection()
            ->query("SELECT slug, updated_at FROM posts WHERE status = 'published' ORDER BY published_at DESC")
            ->fetchAll();
    }

    // Inserts a new post when $id is null, otherwise updates that post. Returns the post id.
    public static function save(array $data, ?int $id): int
    {
        $columns = ['category_id', 'title', 'slug', 'excerpt', 'content', 'status',
                    'seo_title', 'seo_description', 'published_at'];
        $values = array_map(fn (string $column) => $data[$column] ?? null, $columns);
        $pdo = Database::connection();

        if ($id === null) {
            $placeholders = implode(', ', array_fill(0, count($columns), '?'));
            $stmt = $pdo->prepare('INSERT INTO posts (' . implode(', ', $columns) . ") VALUES ($placeholders)");
            $stmt->execute($values);
            return (int) $pdo->lastInsertId();
        }

        $assignments = implode(', ', array_map(fn (string $column) => "$column = ?", $columns));
        $stmt = $pdo->prepare("UPDATE posts SET $assignments WHERE id = ?");
        $stmt->execute([...$values, $id]);
        return $id;
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$id]);
    }

    // SEO title falls back to the post title when left empty.
    public static function seoTitle(array $post): string
    {
        return $post['seo_title'] ?: $post['title'];
    }

    // SEO description falls back to the first 155 characters of the content.
    public static function seoDescription(array $post): string
    {
        if (!empty($post['seo_description'])) {
            return $post['seo_description'];
        }
        return truncate_text(plain_text($post['content']), 155);
    }

    private static function publishedFilter(?int $categoryId, ?string $term): array
    {
        $where = ["p.status = 'published'"];
        $params = [];

        if ($categoryId !== null) {
            $where[] = 'p.category_id = ?';
            $params[] = $categoryId;
        }

        if ($term !== null && $term !== '') {
            $like = '%' . addcslashes($term, '%_\\') . '%';
            $where[] = '(p.title LIKE ? OR p.content LIKE ?)';
            array_push($params, $like, $like);
        }

        return [' WHERE ' . implode(' AND ', $where), $params];
    }

    private static function fetchOne(string $sql, array $params): ?array
    {
        $stmt = Database::connection()->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private static function fetchPage(string $sql, array $params, int $limit, int $offset): array
    {
        $stmt = Database::connection()->prepare($sql);
        $position = 1;
        foreach ($params as $value) {
            $stmt->bindValue($position++, $value);
        }
        $stmt->bindValue($position++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($position, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
