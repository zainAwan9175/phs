<?php
require_once __DIR__ . '/db.php';

echo "Checking Lab Manager users...\n\n";

try {
    // Get lab_manager role id
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'lab_manager'");
    $manager_role_id = $stmt->fetchColumn();
    
    if (!$manager_role_id) {
        echo "❌ Lab Manager role not found in database!\n";
        echo "Creating lab_manager role...\n";
        $pdo->exec("INSERT INTO roles (name) VALUES ('lab_manager')");
        $manager_role_id = $pdo->lastInsertId();
        echo "✓ Lab Manager role created with ID: $manager_role_id\n";
    } else {
        echo "✓ Lab Manager role exists with ID: $manager_role_id\n";
    }
    
    // Check for existing lab manager users
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role_id = ? AND status = 'active'");
    $stmt->execute([$manager_role_id]);
    $managers = $stmt->fetchAll();
    
    if (empty($managers)) {
        echo "\n⚠ No active Lab Manager users found!\n";
        echo "Creating test lab manager account...\n";
        
        $email = 'manager@smartlab.local';
        $password = 'manager123';
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("INSERT INTO users (role_id, first_name, last_name, email, password_hash, status) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$manager_role_id, 'Lab', 'Manager', $email, $hash, 'active']);
        
        echo "✓ Test Lab Manager created:\n";
        echo "   Email: $email\n";
        echo "   Password: $password\n";
    } else {
        echo "\n✓ Found " . count($managers) . " Lab Manager user(s):\n";
        foreach ($managers as $mgr) {
            echo "   - " . $mgr['first_name'] . " " . $mgr['last_name'] . " (" . $mgr['email'] . ")\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
