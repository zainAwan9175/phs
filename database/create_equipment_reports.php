<?php
require_once __DIR__ . '/../db.php';

try {
    // Create equipment_reports table
    $sql = "CREATE TABLE IF NOT EXISTS equipment_reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        equipment_id INT NOT NULL,
        booking_id INT NULL,
        manager_id INT NOT NULL,
        student_id INT NULL,
        condition_before TEXT NOT NULL,
        condition_after TEXT NULL,
        notes TEXT NULL,
        booking_date DATETIME NULL,
        return_date DATETIME NULL,
        status ENUM('pending_return', 'returned', 'damaged', 'normal') DEFAULT 'pending_return',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
        FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE SET NULL,
        INDEX idx_equipment (equipment_id),
        INDEX idx_manager (manager_id),
        INDEX idx_status (status),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $pdo->exec($sql);
    
    echo "✅ Equipment reports table created successfully!\n";
    
} catch (PDOException $e) {
    echo "❌ Error creating table: " . $e->getMessage() . "\n";
}
?>
