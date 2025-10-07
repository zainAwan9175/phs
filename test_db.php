<?php
$config = require __DIR__ . '/config.php';
echo "<h2>Smart Lab DB Test</h2>";
if (!is_array($config)) {
    echo "<p style='color:red'>Could not load configuration from <code>config.php</code>.</p>";
    exit;
}
echo "<p>Config: host=" . htmlspecialchars($config['db_host'] ?? '') . " port=" . htmlspecialchars($config['db_port'] ?? '') . " user=" . htmlspecialchars($config['db_user'] ?? '') . "</p>";
try {
    $pdo = new PDO("mysql:host={$config['db_host']};port={$config['db_port']}", $config['db_user'], $config['db_pass']);
    echo "<p style='color:green'>Connection successful to MySQL server.</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Try these steps:</p>";
    echo "<ol>";
    echo "<li>Open XAMPP Control Panel and ensure MySQL is running (click Start).</li>";
    echo "<li>If MySQL fails to start, check XAMPP > MySQL > Logs or the MySQL <code>my.ini</code> to confirm the listening port (default 3306).</li>";
    echo "<li>If MySQL uses a different port, update <code>db_port</code> in <code>config.php</code>.</li>";
    echo "<li>If you use a non-default DB user/password, update <code>db_user</code> and <code>db_pass</code> in <code>config.php</code>.</li>";
    echo "</ol>";
}

