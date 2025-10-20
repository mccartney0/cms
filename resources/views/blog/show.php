<?php
$meta = $meta ?? [];
ob_start();
?>
<article class="post-detail">
    <header>
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <?php if (!empty($post['published_at'])): ?>
            <p class="post-meta">Publicado em <?= date('d/m/Y H:i', strtotime($post['published_at'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($categories)): ?>
            <ul class="tag-list">
                <?php foreach ($categories as $category): ?>
                    <li><a href="/categoria/<?= htmlspecialchars($category['slug']) ?>"><?= htmlspecialchars($category['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </header>
    <div class="post-content">
        <?= nl2br(htmlspecialchars($post['body'])) ?>
    </div>
</article>
<aside class="related">
    <h2>Outras categorias</h2>
    <ul class="tag-list">
        <?php foreach ($allCategories as $category): ?>
            <li><a href="/categoria/<?= htmlspecialchars($category['slug']) ?>"><?= htmlspecialchars($category['name']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</aside>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
