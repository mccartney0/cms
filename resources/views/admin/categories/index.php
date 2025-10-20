<?php
ob_start();
?>
<section class="admin-section">
    <header class="admin-section__header">
        <h1>Categorias</h1>
        <a class="button" href="/admin/categories/create">Nova categoria</a>
    </header>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Slug</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= htmlspecialchars($category['name']) ?></td>
                <td><?= htmlspecialchars($category['slug']) ?></td>
                <td class="actions">
                    <a class="button ghost" href="/admin/categories/<?= (int) $category['id'] ?>/edit">Editar</a>
                    <form method="POST" action="/admin/categories/<?= (int) $category['id'] ?>" onsubmit="return confirm('Excluir categoria?');">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="button danger" type="submit">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$categories): ?>
            <tr><td colspan="3">Cadastre sua primeira categoria.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
