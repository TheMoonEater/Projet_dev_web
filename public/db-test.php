<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

use App\Core\Db;

$stmt = Db::pdo()->query("SELECT COUNT(*) AS c FROM users");
$row = $stmt->fetch();
echo "OK. Users = " . $row['c'];