<?php

namespace App\Models;

use PDO;

class Post extends Model
{
    protected string $table = 'posts';
    protected array $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'status',
        'published_at',
        'created_at',
        'updated_at',
    ];

    public function published(int $limit = 10): array
    {
        $sql = 'SELECT * FROM posts WHERE status = :status ORDER BY published_at DESC LIMIT :limit';
        $stmt = $this->connection()->prepare($sql);
        $stmt->bindValue(':status', 'published');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function publishedByCategory(int $categoryId): array
    {
        $sql = 'SELECT p.* FROM posts p INNER JOIN post_category pc ON pc.post_id = p.id WHERE pc.category_id = :category_id AND p.status = :status ORDER BY p.published_at DESC';
        $stmt = $this->connection()->prepare($sql);
        $stmt->execute([
            'category_id' => $categoryId,
            'status' => 'published',
        ]);

        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->firstWhere('slug', $slug);
    }

    public function categories(int $postId): array
    {
        $sql = 'SELECT c.* FROM categories c INNER JOIN post_category pc ON pc.category_id = c.id WHERE pc.post_id = :id ORDER BY c.name';
        $stmt = $this->connection()->prepare($sql);
        $stmt->execute(['id' => $postId]);

        return $stmt->fetchAll();
    }

    public function syncCategories(int $postId, array $categoryIds): void
    {
        $pdo = $this->connection();
        $pdo->prepare('DELETE FROM post_category WHERE post_id = :id')->execute(['id' => $postId]);

        if (empty($categoryIds)) {
            return;
        }

        $stmt = $pdo->prepare('INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)');

        foreach ($categoryIds as $categoryId) {
            if (!$categoryId) {
                continue;
            }

            $stmt->execute([
                'post_id' => $postId,
                'category_id' => (int) $categoryId,
            ]);
        }
    }
}
