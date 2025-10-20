<?php
$meta = $meta ?? [];
ob_start();
?>
<header class="page-header">
    <h1><?= isset($selectedCategory) ? 'Categoria: ' . htmlspecialchars($selectedCategory['name']) : 'Blog' ?></h1>
    <p>Descubra insights técnicos e editoriais para escalar sua presença digital.</p>
</header>
<div class="grid">
    <aside>
        <h2>Categorias</h2>
        <ul class="tag-list">
            <?php foreach ($categories as $category): ?>
                <li>
                    <a href="/categoria/<?= htmlspecialchars($category['slug']) ?>"
                       class="<?= isset($selectedCategory) && $selectedCategory['id'] === $category['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>
    <section>
        <?php if ($posts): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <h2><a href="/blog/<?= htmlspecialchars($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                    <p><?= htmlspecialchars($post['excerpt']) ?></p>
                    <?php if (!empty($post['published_at'])): ?>
                        <small>Publicado em <?= date('d/m/Y H:i', strtotime($post['published_at'])) ?></small>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum post encontrado.</p>
        <?php endif; ?>
    </section>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
