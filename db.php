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

// --- Auto-seed roles (and default admin if appropriate) ---
try {
    // Check if `roles` table exists in this database
    $check = $pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = 'roles'");
    $check->execute([$config['db_name']]);
    $rolesTableExists = (int) $check->fetchColumn() > 0;

    if (! $rolesTableExists) {
        // Create a minimal roles table so registration and role checks won't fail.
        $pdo->exec("CREATE TABLE IF NOT EXISTS roles (
            id INT PRIMARY KEY,
            name VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $rolesTableExists = true;
    }

    if ($rolesTableExists) {
        // Insert default roles if table is empty
        $c = $pdo->query("SELECT COUNT(*) as cnt FROM roles")->fetchColumn();
        if ((int)$c === 0) {
            $pdo->exec("INSERT IGNORE INTO roles (id, name) VALUES
                (1, 'admin'), (2, 'lab_manager'), (3, 'lab_assistant'), (4, 'student')");
        }
    }

    // If users table exists and there's no admin, create a default admin user (safe fallback)
    $checkUsers = $pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = 'users'");
    $checkUsers->execute([$config['db_name']]);
    $usersTableExists = (int) $checkUsers->fetchColumn() > 0;
    if ($usersTableExists) {
        $adminCount = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role_id = 1")->fetchColumn();
        if ($adminCount === 0) {
            // Create a safe default admin (password: admin123) only if no admin exists
            $hashed = password_hash('admin123', PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role_id, created_at) VALUES (:name, :email, :pwd, 1, NOW())");
            $stmt->execute(['name' => 'Admin', 'email' => 'admin@smartlab.com', 'pwd' => $hashed]);
        }
    }
} catch (Exception $e) {
    // Seeding must never break the application. Fail silently and continue;
    // errors will still be visible in host error logs if needed.
}

