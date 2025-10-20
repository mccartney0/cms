<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $driver = Config::get('database.driver', 'sqlite');

        if ($driver !== 'sqlite') {
            throw new PDOException('Somente SQLite é suportado nesta distribuição.');
        }

        $path = Config::get('database.path');

        if ($path === null) {
            throw new PDOException('O caminho do banco de dados não foi configurado.');
        }

        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        if (!file_exists($path)) {
            touch($path);
        }

        $dsn = 'sqlite:' . $path;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        self::$connection = new PDO($dsn, options: $options);

        return self::$connection;
    }
}
