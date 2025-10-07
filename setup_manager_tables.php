<?php
require_once __DIR__ . '/db.php';

echo "Creating Lab Manager support tables...\n\n";

try {
    // Create maintenance_tasks table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS maintenance_tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            equipment_id INT NOT NULL,
            issue_description TEXT,
            priority ENUM('low','medium','high','urgent') DEFAULT 'medium',
            status ENUM('open','closed') DEFAULT 'open',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            closed_at TIMESTAMP NULL,
            FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE
        )
    ");
    echo "✓ maintenance_tasks table created/verified\n";
    
    // Create issues table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS issues (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reported_by INT NOT NULL,
            issue_type ENUM('equipment','lab','safety','other') NOT NULL,
            lab_id INT NOT NULL,
            equipment_id INT NULL,
            issue_title VARCHAR(255) NOT NULL,
            issue_description TEXT,
            priority ENUM('low','medium','high','urgent') DEFAULT 'medium',
            status ENUM('open','in_progress','resolved') DEFAULT 'open',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            resolved_at TIMESTAMP NULL,
            FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE CASCADE,
            FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE SET NULL
        )
    ");
    echo "✓ issues table created/verified\n";
    
    echo "\n✅ All Lab Manager tables are ready!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
