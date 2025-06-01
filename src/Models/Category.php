<?php

namespace App\Models;

use App\Database\DB;
use PDO;

class Category
{
    public static function all(): array
    {
        $pdo = DB::connect();
        $stmt = $pdo->query("SELECT * FROM categories");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }
}
