<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap/app.php';

use App\Core\Database;

$pdo = Database::connection();
$pdo->exec('PRAGMA foreign_keys = ON');

$migrations = glob(BASE_PATH . '/database/migrations/*.php');
sort($migrations);

foreach ($migrations as $file) {
    $statements = require $file;

    foreach ($statements as $statement) {
        $pdo->exec($statement);
    }
}

echo "Migrações executadas com sucesso." . PHP_EOL;

if (in_array('--seed', $argv, true)) {
    $seeder = require BASE_PATH . '/database/seeders/DatabaseSeeder.php';
    $inserted = $seeder($pdo);
    echo $inserted ? "Dados de exemplo inseridos." . PHP_EOL : "Banco já possuía dados, nenhum seed aplicado." . PHP_EOL;
}
