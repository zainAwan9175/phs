<?php
require __DIR__ . '/db.php';

try {
    // Drop the existing issues table
    $pdo->exec("DROP TABLE IF EXISTS issues");
    echo "✓ Dropped old issues table\n";
    
    // Create the issues table with correct schema
    $pdo->exec("
        CREATE TABLE issues (
            id INT AUTO_INCREMENT PRIMARY KEY,
            equipment_id INT NOT NULL,
            reporter_id INT NOT NULL,
            title VARCHAR(120) NOT NULL,
            details TEXT DEFAULT NULL,
            status ENUM('open','in_progress','resolved') DEFAULT 'open',
            reported_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            resolved_at DATETIME DEFAULT NULL,
            FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
            FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Created issues table with correct structure\n\n";
    
    echo "Table structure:\n";
    echo "  - equipment_id (INT, Foreign Key)\n";
    echo "  - reporter_id (INT, Foreign Key)\n";
    echo "  - title (VARCHAR)\n";
    echo "  - details (TEXT)\n";
    echo "  - status (open, in_progress, resolved)\n";
    echo "  - reported_at (DATETIME)\n";
    echo "  - resolved_at (DATETIME)\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
