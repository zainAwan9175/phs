<?php
require __DIR__ . '/db.php';

try {
    echo "Updating password_resets table...\n\n";
    
    // Drop the old table
    $pdo->exec("DROP TABLE IF EXISTS password_resets");
    echo "✓ Dropped old table\n";
    
    // Create new table with improved structure
    $pdo->exec("
        CREATE TABLE password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(64) NOT NULL UNIQUE,
            expires_at DATETIME NOT NULL,
            used TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_token (token),
            INDEX idx_expires (expires_at),
            INDEX idx_user (user_id)
        )
    ");
    
    echo "✓ Created password_resets table with improved structure\n\n";
    
    echo "New features:\n";
    echo "  • Rate limiting (3 requests per 15 minutes)\n";
    echo "  • Token marked as 'used' after reset (audit trail)\n";
    echo "  • Enhanced password validation (8+ chars, uppercase, lowercase, number)\n";
    echo "  • Password cannot be same as old password\n";
    echo "  • Security audit logging\n";
    echo "  • Better error messages\n";
    echo "  • Token expiration (1 hour)\n\n";
    
    echo "✅ Professional forgot password system ready!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
