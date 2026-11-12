<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Larry Clothing & Printing
| Database Configuration
|--------------------------------------------------------------------------
*/

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'larry_clothing_and_printing';

$dbUsername = getenv('DB_USER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: '';

$charset = 'utf8mb4';

$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];

try {

    $pdo = new PDO(
        $dsn,
        $dbUsername,
        $dbPassword,
        $options
    );

} catch (PDOException $e) {

    error_log(
        'Database connection failed: '
        . $e->getMessage()
    );

    die(
        'Database connection failed. Please contact the administrator.'
    );
}
