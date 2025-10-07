<?php
require_once __DIR__ . '/db.php';

echo "Setting up maintenance_tasks table with equipment selection...\n\n";

try {
    // Drop the old table and create new one with correct structure
    $pdo->exec("DROP TABLE IF EXISTS maintenance_tasks");
    
    $pdo->exec("
        CREATE TABLE maintenance_tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            equipment_id INT NOT NULL,
            equipment_name VARCHAR(255) NOT NULL,
            task_type VARCHAR(100) NOT NULL,
            summary TEXT,
            scheduled_date DATE,
            status VARCHAR(50) DEFAULT 'open',
            cost DECIMAL(10,2) NULL,
            completion_date DATE NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_by INT,
            FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    
    echo "✓ maintenance_tasks table created with correct structure\n";
    echo "\nTable structure:\n";
    echo "  - equipment_id (INT, Foreign Key)\n";
    echo "  - equipment_name (VARCHAR)\n";
    echo "  - task_type (Repair, Inspection, Calibration, etc.)\n";
    echo "  - summary (TEXT)\n";
    echo "  - scheduled_date (DATE)\n";
    echo "  - status (open, completed, cancelled)\n";
    echo "  - cost (DECIMAL)\n";
    echo "  - completion_date (DATE)\n";
    echo "  - created_at (TIMESTAMP)\n";
    echo "  - created_by (INT, Foreign Key)\n";
    
    echo "\n✅ Table is ready for equipment dropdown selection!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
