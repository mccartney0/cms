<?php
$post = $post ?? [];
$selectedCategories = $selectedCategories ?? [];
$errors = $errors ?? [];
$editing = isset($post['id']);
$action = $editing ? '/admin/posts/' . (int) $post['id'] : '/admin/posts';
$publishedAtValue = '';

if (!empty($post['published_at'])) {
    $publishedAtValue = date('Y-m-d\TH:i', strtotime($post['published_at']));
}

ob_start();
?>
<section class="admin-section">
    <header class="admin-section__header">
        <h1><?= $editing ? 'Editar post' : 'Novo post' ?></h1>
        <a class="button ghost" href="/admin/posts">Voltar</a>
    </header>

    <?php if (!empty($errors)): ?>
        <div class="alert">
            <h2>Revise os campos a seguir:</h2>
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
            <label for="title">Título *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
        </div>
        <div class="field">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($post['slug'] ?? '') ?>" placeholder="meu-post-otimizado">
            <small>Utilizado na URL. É gerado automaticamente a partir do título.</small>
        </div>
        <div class="field">
            <label for="excerpt">Resumo</label>
            <textarea id="excerpt" name="excerpt" rows="3" maxlength="255" placeholder="Descrição curta para SEO."><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
        </div>
        <div class="field">
            <label for="body">Conteúdo *</label>
            <textarea id="body" name="body" rows="10" required><?= htmlspecialchars($post['body'] ?? '') ?></textarea>
        </div>
        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <?php $status = $post['status'] ?? 'draft'; ?>
                <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Rascunho</option>
                <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Publicado</option>
            </select>
        </div>
        <div class="field">
            <label for="published_at">Data de publicação</label>
            <input type="datetime-local" id="published_at" name="published_at" value="<?= htmlspecialchars($publishedAtValue) ?>">
        </div>
        <div class="field">
            <label for="categories">Categorias</label>
            <select id="categories" name="categories[]" multiple size="5">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>" <?= in_array($category['id'], $selectedCategories, true) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small>Segure CTRL (Windows) ou CMD (Mac) para selecionar múltiplas categorias.</small>
        </div>
        <fieldset class="fieldset">
            <legend>SEO</legend>
            <div class="field">
                <label for="meta_title">Meta title</label>
                <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($post['meta_title'] ?? '') ?>" maxlength="70">
            </div>
            <div class="field">
                <label for="meta_description">Meta description</label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"><?= htmlspecialchars($post['meta_description'] ?? '') ?></textarea>
            </div>
        </fieldset>
        <div class="form-actions">
            <button class="button" type="submit">Salvar</button>
        </div>
    </form>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
