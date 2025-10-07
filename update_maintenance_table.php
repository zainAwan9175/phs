<?php
require_once __DIR__ . '/db.php';

echo "Updating maintenance_tasks table structure...\n\n";

try {
    // Drop old table if exists
    $pdo->exec("DROP TABLE IF EXISTS maintenance_tasks");
    echo "✓ Dropped old maintenance_tasks table\n";
    
    // Create new table with simplified structure
    $pdo->exec("
        CREATE TABLE maintenance_tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            equipment_name VARCHAR(255) NOT NULL,
            task_type VARCHAR(100) NOT NULL,
            summary TEXT,
            scheduled_date DATE,
            status VARCHAR(50) DEFAULT 'open',
            cost DECIMAL(10,2) NULL,
            completion_date DATE NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_by INT,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "✓ Created new maintenance_tasks table with simplified structure\n";
    
    echo "\n✅ Maintenance tasks table updated successfully!\n";
    echo "\nNew structure:\n";
    echo "  - equipment_name (text input)\n";
    echo "  - task_type (Repair, Inspection, Calibration, etc.)\n";
    echo "  - summary (description)\n";
    echo "  - scheduled_date\n";
    echo "  - status (open, completed, cancelled)\n";
    echo "  - cost (optional)\n";
    echo "  - completion_date\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
