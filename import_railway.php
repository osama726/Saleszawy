<?php

$port = $argv[1] ?? null;
$password = $argv[2] ?? null;

if (!$port) {
    echo "Usage: php import_railway.php <PORT> [PASSWORD]\n";
    echo "Example: php import_railway.php 55487 MyNewPassword\n\n";
    echo "Enter Port: ";
    $port = trim(fgets(STDIN));
}

if (!$password) {
    echo "Enter MySQL Password (printed by railway connect or from Railway Variables): ";
    $password = trim(fgets(STDIN));
}

$host = '127.0.0.1';
$dbname = 'railway';

echo "\n===========================================\n";
echo " Railway MySQL Data Importer\n";
echo "===========================================\n";
echo "Target: {$host}:{$port} (Database: {$dbname})\n";

$users = ['root'];
$pdo = null;

foreach ($users as $user) {
    try {
        echo "Attempting connection as user '{$user}'...\n";
        $pdo = new PDO(
            "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]
        );
        echo " Connected successfully as '{$user}'!\n";
        break;
    } catch (Exception $e) {
        echo " Failed as '{$user}': " . $e->getMessage() . "\n";
    }
}

if (!$pdo) {
    echo "\n❌ Connection failed. Please check the port and password.\n";
    exit(1);
}

$sqlFile = __DIR__ . '/saleszawy.sql';
if (!file_exists($sqlFile)) {
    echo "\n❌ Error: saleszawy.sql not found in " . __DIR__ . "\n";
    exit(1);
}

echo "Reading saleszawy.sql (" . round(filesize($sqlFile) / 1024, 2) . " KB)...\n";
$sql = file_get_contents($sqlFile);

echo "Importing database tables and records...\n";
try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec($sql);
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "\n🎉 SUCCESS! All tables and data have been imported to Railway MySQL successfully!\n";
} catch (Exception $e) {
    echo "\n❌ Import Error: " . $e->getMessage() . "\n";
    exit(1);
}
