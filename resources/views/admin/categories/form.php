<?php
$category = $category ?? [];
$errors = $errors ?? [];
$editing = isset($category['id']);
$action = $editing ? '/admin/categories/' . (int) $category['id'] : '/admin/categories';
ob_start();
?>
<section class="admin-section">
    <header class="admin-section__header">
        <h1><?= $editing ? 'Editar categoria' : 'Nova categoria' ?></h1>
        <a class="button ghost" href="/admin/categories">Voltar</a>
    </header>

    <?php if (!empty($errors)): ?>
        <div class="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= $action ?>" class="form">
        <?php if ($editing): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>
        <div class="field">
            <label for="name">Nome *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name'] ?? '') ?>" required>
        </div>
        <div class="field">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($category['slug'] ?? '') ?>" placeholder="categoria-super-otimizada">
        </div>
        <div class="form-actions">
            <button class="button" type="submit">Salvar</button>
        </div>
    </form>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
