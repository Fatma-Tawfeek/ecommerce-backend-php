<?php

namespace App\Database;

use PDO;

class DB
{
    public static function connect(): PDO
    {
        $config = require __DIR__ . '/../Config/config.php';
        $db = $config['db'];

        $pdo = new PDO(
            "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8",
            $db['user'],
            $db['pass']
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
