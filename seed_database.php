<?php
/**
 * Database Seeder Script
 * Run this file ONCE to seed the database with roles
 * Access it at: https://2025s2t.winproject.com.au/doc/smartlab/seed_database.php
 */

// Load configuration
$config = require_once 'config.php';

try {
    // Create database connection
    $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "<h1>Smart Lab Database Seeder</h1>";
    echo "<p>Connection successful!</p>";

    // Seed roles
    $sql = "INSERT IGNORE INTO roles (id, name) VALUES
            (1, 'admin'),
            (2, 'lab_manager'),
            (3, 'lab_assistant'),
            (4, 'student')";
    
    $pdo->exec($sql);
    echo "<p style='color: green;'>✅ Roles seeded successfully!</p>";

    // Check if roles exist
    $stmt = $pdo->query("SELECT * FROM roles ORDER BY id");
    $roles = $stmt->fetchAll();
    
    echo "<h3>Current Roles in Database:</h3>";
    echo "<ul>";
    foreach ($roles as $role) {
        echo "<li>ID: {$role['id']} - Name: {$role['name']}</li>";
    }
    echo "</ul>";

    // Create a default admin user if none exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role_id = 1");
    $adminCount = $stmt->fetch()['count'];

    if ($adminCount == 0) {
        // Password: admin123 (hashed with bcrypt)
        $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (name, email, password, role_id, created_at) 
                VALUES ('Admin', 'admin@smartlab.com', :password, 1, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['password' => $hashedPassword]);
        
        echo "<p style='color: green;'>✅ Default admin user created!</p>";
        echo "<p><strong>Login credentials:</strong><br>";
        echo "Email: admin@smartlab.com<br>";
        echo "Password: admin123</p>";
        echo "<p style='color: orange;'>⚠️ Please change this password after first login!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ Admin user already exists (skipped)</p>";
    }

    echo "<hr>";
    echo "<h2 style='color: green;'>✅ Database seeded successfully!</h2>";
    echo "<p><strong>Next steps:</strong></p>";
    echo "<ol>";
    echo "<li>Delete this file (seed_database.php) for security</li>";
    echo "<li>Go to your website: <a href='{$config['base_url']}'>Smart Lab</a></li>";
    echo "<li>Login with the admin credentials above</li>";
    echo "</ol>";

} catch (PDOException $e) {
    echo "<h1 style='color: red;'>❌ Database Connection Error</h1>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database credentials in config.php</p>";
}
?>
