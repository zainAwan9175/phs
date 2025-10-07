<?php
$config = require __DIR__ . '/config.php';

try {
    $port = isset($config['db_port']) ? $config['db_port'] : 3306;
    $dsn = "mysql:host={$config['db_host']};port={$port};dbname={$config['db_name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    // Friendly diagnostic for local dev
    $msg = $e->getMessage();
    echo "<h3>Database connection failed</h3>\n";
    echo "<p>Could not connect to MySQL at <strong>" . htmlspecialchars($config['db_host']) . ":" . htmlspecialchars($port) . "</strong>.</p>\n";
    echo "<p>Error: " . htmlspecialchars($msg) . "</p>\n";
    echo "<p>Possible causes: MySQL server is not running (start it from XAMPP control panel), port mismatch, or incorrect credentials in <code>config.php</code>.</p>";
    exit;
}
