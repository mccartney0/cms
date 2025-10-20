<?php
ob_start();
?>
<section class="admin-section">
    <header class="admin-section__header">
        <h1>Posts</h1>
        <a class="button" href="/admin/posts/create">Novo post</a>
    </header>
    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Status</th>
                <th>Publicado em</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><a href="/blog/<?= htmlspecialchars($post['slug']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($post['title']) ?></a></td>
                <td><?= htmlspecialchars($post['status']) ?></td>
                <td><?= $post['published_at'] ? date('d/m/Y H:i', strtotime($post['published_at'])) : '-' ?></td>
                <td class="actions">
                    <a class="button ghost" href="/admin/posts/<?= (int) $post['id'] ?>/edit">Editar</a>
                    <form method="POST" action="/admin/posts/<?= (int) $post['id'] ?>" onsubmit="return confirm('Deseja excluir este post?');">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="button danger" type="submit">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$posts): ?>
            <tr><td colspan="4">Cadastre seu primeiro post.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
