<?php
$meta = $meta ?? [];
ob_start();
?>
<section class="hero">
    <h1>Construa sites otimizados para SEO com o Aurora CMS</h1>
    <p>Gerencie páginas, categorias e posts em um painel simples, preparado para alta performance e boas práticas de otimização.</p>
    <a class="button" href="/blog">Explorar blog</a>
</section>
<section class="grid">
    <div>
        <h2>Últimos artigos</h2>
        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <article class="card">
                    <h3><a href="/blog/<?= htmlspecialchars($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                    <p><?= htmlspecialchars($post['excerpt']) ?></p>
                    <?php if (!empty($post['published_at'])): ?>
                        <small>Publicado em <?= date('d/m/Y', strtotime($post['published_at'])) ?></small>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
            <?php if (!$posts): ?>
                <p>Cadastre um post publicado para que ele apareça aqui.</p>
            <?php endif; ?>
        </div>
    </div>
    <aside>
        <h2>Categorias</h2>
        <ul class="tag-list">
            <?php foreach ($categories as $category): ?>
                <li><a href="/categoria/<?= htmlspecialchars($category['slug']) ?>"><?= htmlspecialchars($category['name']) ?></a></li>
            <?php endforeach; ?>
            <?php if (!$categories): ?>
                <li>Crie categorias para organizar seus conteúdos.</li>
            <?php endif; ?>
        </ul>
    </aside>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
