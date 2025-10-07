<?php
require_once __DIR__ . '/db.php';

// Check if rules table exists
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'rules'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Rules table exists\n";
        
        // Check table structure
        $cols = $pdo->query("DESCRIBE rules")->fetchAll(PDO::FETCH_ASSOC);
        echo "\nTable structure:\n";
        foreach ($cols as $col) {
            echo "  - {$col['Field']} ({$col['Type']})\n";
        }
    } else {
        echo "✗ Rules table NOT found\n";
        echo "Creating rules table...\n";
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS rules (
                id INT AUTO_INCREMENT PRIMARY KEY,
                lab_id INT DEFAULT NULL,
                title VARCHAR(120) DEFAULT NULL,
                body TEXT DEFAULT NULL,
                active TINYINT(1) DEFAULT 1,
                FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE SET NULL
            )
        ");
        echo "✓ Rules table created\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
