<?php
$meta = [
    'title' => $title ?? 'Página não encontrada',
    'description' => 'O conteúdo solicitado não foi localizado.'
];
ob_start();
?>
<section class="error">
    <h1><?= htmlspecialchars($meta['title']) ?></h1>
    <p><?= htmlspecialchars($meta['description']) ?></p>
    <a class="button" href="/">Voltar para a página inicial</a>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
