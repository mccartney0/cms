<?php use App\Core\Config; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($meta['title'] ?? Config::get('meta.title')) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta['description'] ?? Config::get('meta.description')) ?>">
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="/" class="brand" aria-label="<?= htmlspecialchars(Config::get('name')) ?>">
                <?= htmlspecialchars(Config::get('name')) ?>
            </a>
            <nav aria-label="principal">
                <ul class="menu">
                    <li><a href="/">Início</a></li>
                    <li><a href="/blog">Blog</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
        <?= $content ?? '' ?>
    </main>
    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(Config::get('name')) ?>. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
