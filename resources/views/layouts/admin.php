<?php use App\Core\Config; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel | <?= htmlspecialchars(Config::get('name')) ?></title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body class="admin">
    <header class="admin-header">
        <div class="container">
            <a href="/admin" class="brand">Painel - <?= htmlspecialchars(Config::get('name')) ?></a>
            <nav>
                <ul class="menu">
                    <li><a href="/admin/posts">Posts</a></li>
                    <li><a href="/admin/categories">Categorias</a></li>
                    <li><a href="/" target="_blank" rel="noopener">Ver site</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
        <?= $content ?? '' ?>
    </main>
    <footer class="admin-footer">
        <div class="container">
            <p>Gerencie seu conteúdo com facilidade.</p>
        </div>
    </footer>
</body>
</html>
