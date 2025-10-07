<?php
require __DIR__ . '/db.php';

try {
    // Create password_resets table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(64) NOT NULL UNIQUE,
            expires_at DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX (token),
            INDEX (expires_at)
        )
    ");
    
    echo "✓ password_resets table created successfully\n\n";
    
    echo "Table structure:\n";
    echo "  - user_id (INT, Foreign Key to users)\n";
    echo "  - token (VARCHAR 64, Unique)\n";
    echo "  - expires_at (DATETIME)\n";
    echo "  - created_at (TIMESTAMP)\n\n";
    
    echo "Forgot Password functionality is ready!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
