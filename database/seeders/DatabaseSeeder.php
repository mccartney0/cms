<?php

use App\Core\Str;

return function (\PDO $pdo): bool {
    $count = (int) $pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();

    if ($count > 0) {
        return false;
    }

    $now = date('Y-m-d H:i:s');

    $categories = [
        ['name' => 'Marketing Digital'],
        ['name' => 'Tecnologia'],
        ['name' => 'Conteúdo'],
    ];

    $categoryIds = [];

    $stmt = $pdo->prepare('INSERT INTO categories (name, slug, created_at, updated_at) VALUES (:name, :slug, :created_at, :updated_at)');

    foreach ($categories as $category) {
        $slug = Str::slug($category['name']);
        $stmt->execute([
            'name' => $category['name'],
            'slug' => $slug,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $categoryIds[$slug] = (int) $pdo->lastInsertId();
    }

    $posts = [
        [
            'title' => 'Boas práticas de SEO para blogs corporativos',
            'excerpt' => 'Como estruturar conteúdos e metadados para conquistar tráfego orgânico qualificado.',
            'body' => "Invista em palavras-chave estratégicas, otimize títulos e meta descrições e mantenha uma estrutura de heading organizada. Além disso, priorize a experiência do usuário, com parágrafos curtos e intertítulos informativos.",
            'status' => 'published',
            'meta_title' => 'Boas práticas de SEO para blogs corporativos',
            'meta_description' => 'Passo a passo para otimizar o blog da sua empresa e melhorar o ranqueamento no Google.',
            'categories' => ['marketing-digital', 'conteudo'],
        ],
        [
            'title' => 'Como montar um stack moderno em PHP',
            'excerpt' => 'Dicas para combinar Laravel, boas práticas de cache e hospedagem compartilhada.',
            'body' => "Aposte em um roteamento enxuto, utilize caching para páginas de alto tráfego e mantenha dependências atualizadas. Automatize deploys e monitore desempenho para ajustar gargalos rapidamente.",
            'status' => 'published',
            'meta_title' => 'Stack moderno em PHP para projetos ágeis',
            'meta_description' => 'Sugestões de ferramentas para construir projetos PHP escaláveis e seguros.',
            'categories' => ['tecnologia'],
        ],
    ];

    $postStmt = $pdo->prepare('INSERT INTO posts (title, slug, excerpt, body, meta_title, meta_description, status, published_at, created_at, updated_at) VALUES (:title, :slug, :excerpt, :body, :meta_title, :meta_description, :status, :published_at, :created_at, :updated_at)');
    $pivotStmt = $pdo->prepare('INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)');

    foreach ($posts as $post) {
        $slug = Str::slug($post['title']);
        $publishedAt = date('Y-m-d H:i:s', strtotime('-' . rand(1, 14) . ' days'));

        $postStmt->execute([
            'title' => $post['title'],
            'slug' => $slug,
            'excerpt' => $post['excerpt'],
            'body' => $post['body'],
            'meta_title' => $post['meta_title'],
            'meta_description' => $post['meta_description'],
            'status' => $post['status'],
            'published_at' => $publishedAt,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $postId = (int) $pdo->lastInsertId();

        foreach ($post['categories'] as $categorySlug) {
            if (!isset($categoryIds[$categorySlug])) {
                continue;
            }

            $pivotStmt->execute([
                'post_id' => $postId,
                'category_id' => $categoryIds[$categorySlug],
            ]);
        }
    }

    return true;
};
