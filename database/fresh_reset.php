<?php
/**
 * Fresh Database Reset and Seed Script
 * This will DROP the database, recreate it with fresh data
 */

$config = require __DIR__ . '/../config.php';

define('DB_HOST', $config['db_host']);
define('DB_PORT', $config['db_port']);
define('DB_NAME', $config['db_name']);
define('DB_USER', $config['db_user']);
define('DB_PASS', $config['db_pass']);

try {
    // Connect without database first
    $dsn = "mysql:host=" . DB_HOST . (defined('DB_PORT') ? ";port=" . DB_PORT : "");
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MySQL server.\n";
    
    // DROP the database if it exists to remove duplicates
    $pdo->exec("DROP DATABASE IF EXISTS `" . DB_NAME . "`");
    echo "Old database dropped (if existed).\n";
    
    // Create fresh database
    $pdo->exec("CREATE DATABASE `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Fresh database created: " . DB_NAME . "\n";
    
    // Connect to the new database
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // Create tables
    echo "Creating tables...\n";
    
    $pdo->exec("
        CREATE TABLE roles (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(50) NOT NULL UNIQUE
        )
    ");
    
    $pdo->exec("
        CREATE TABLE users (
          id INT AUTO_INCREMENT PRIMARY KEY,
          role_id INT NOT NULL,
          first_name VARCHAR(100) NOT NULL,
          last_name VARCHAR(100) DEFAULT NULL,
          phone VARCHAR(30) DEFAULT NULL,
          email VARCHAR(120) NOT NULL UNIQUE,
          password_hash VARCHAR(255) NOT NULL,
          student_or_staff_id VARCHAR(50) DEFAULT NULL,
          status ENUM('active','inactive','suspended') DEFAULT 'active',
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
        )
    ");
    
    $pdo->exec("
        CREATE TABLE labs (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(100) NOT NULL,
          location VARCHAR(120) DEFAULT NULL,
          description TEXT DEFAULT NULL,
          image_base64 LONGTEXT DEFAULT NULL,
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE equipment (
          id INT AUTO_INCREMENT PRIMARY KEY,
          lab_id INT NOT NULL,
          name VARCHAR(120) NOT NULL,
          category VARCHAR(80) DEFAULT NULL,
          asset_tag VARCHAR(60) DEFAULT NULL,
          status ENUM('available','pending','in_use','maintenance','retired') DEFAULT 'available',
          condition_note VARCHAR(255) DEFAULT NULL,
          image_base64 LONGTEXT DEFAULT NULL,
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE CASCADE
        )
    ");
    
    $pdo->exec("
        CREATE TABLE bookings (
          id INT AUTO_INCREMENT PRIMARY KEY,
          equipment_id INT NOT NULL,
          requester_id INT NOT NULL,
          purpose VARCHAR(255) DEFAULT NULL,
          start_time DATETIME NOT NULL,
          end_time DATETIME NOT NULL,
          status ENUM('pending','approved','rejected','cancelled','completed','no_show') DEFAULT 'pending',
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
          FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    
    $pdo->exec("
        CREATE TABLE booking_approvals (
          id INT AUTO_INCREMENT PRIMARY KEY,
          booking_id INT NOT NULL,
          reviewer_id INT NOT NULL,
          action ENUM('approved','rejected') NOT NULL,
          comment VARCHAR(255) DEFAULT NULL,
          acted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
          FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    
    $pdo->exec("
        CREATE TABLE notifications (
          id INT AUTO_INCREMENT PRIMARY KEY,
          user_id INT NOT NULL,
          message VARCHAR(255) NOT NULL,
          link VARCHAR(255) DEFAULT NULL,
          is_read TINYINT(1) DEFAULT 0,
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    
    $pdo->exec("
        CREATE TABLE rules (
          id INT AUTO_INCREMENT PRIMARY KEY,
          lab_id INT DEFAULT NULL,
          title VARCHAR(120) DEFAULT NULL,
          body TEXT DEFAULT NULL,
          active TINYINT(1) DEFAULT 1,
          FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE SET NULL
        )
    ");
    
    echo "All tables created successfully.\n\n";
    
    // Seed roles
    echo "Seeding roles...\n";
    $pdo->exec("INSERT INTO roles (name) VALUES ('admin'), ('student'), ('lab_assistant'), ('lab_manager')");
    
    // Get role IDs
    $adminRoleId = $pdo->query("SELECT id FROM roles WHERE name='admin'")->fetchColumn();
    
    // Create ONE admin user
    echo "Creating admin user...\n";
    $adminPass = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (role_id, first_name, last_name, email, password_hash, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$adminRoleId, 'Admin', 'User', 'admin@smartlab.local', $adminPass, '+1234567890', 'active']);
    echo "Admin created: admin@smartlab.local / admin123\n\n";
    
    // Seed labs with good data
    echo "Seeding labs...\n";
    $labs = [
        ['Electronics Lab', 'Building A, Room 101', 'Advanced electronics and circuit design laboratory with modern equipment'],
        ['Chemistry Lab', 'Building B, Room 201', 'Fully equipped chemistry laboratory for organic and inorganic experiments'],
        ['Physics Lab', 'Building A, Room 105', 'Physics laboratory with mechanics, optics, and thermodynamics equipment'],
        ['Computer Lab', 'Building C, Room 301', 'Modern computer lab with latest hardware and software for programming'],
        ['Mechanical Lab', 'Building D, Room 150', 'Workshop for mechanical engineering with CNC machines and tools'],
        ['Biology Lab', 'Building B, Room 205', 'Microbiology and biotechnology laboratory with modern instruments']
    ];
    
    $labStmt = $pdo->prepare("INSERT INTO labs (name, location, description) VALUES (?, ?, ?)");
    foreach ($labs as $lab) {
        $labStmt->execute($lab);
    }
    echo count($labs) . " labs created.\n\n";
    
    // Seed equipment for each lab
    echo "Seeding equipment...\n";
    
    $equipmentData = [
        1 => [ // Electronics Lab
            ['Oscilloscope', 'High-precision digital oscilloscope'],
            ['Multimeter', 'Digital multimeter for voltage/current measurement'],
            ['Function Generator', 'Signal generator for testing circuits'],
            ['Power Supply', 'Adjustable DC power supply unit'],
            ['Soldering Station', 'Temperature controlled soldering station'],
            ['Breadboard Kit', 'Prototyping breadboard with components']
        ],
        2 => [ // Chemistry Lab
            ['Beaker Set', 'Glass beakers various sizes'],
            ['Bunsen Burner', 'Gas burner for heating'],
            ['pH Meter', 'Digital pH measurement device'],
            ['Burette', 'Precision liquid dispensing tool'],
            ['Test Tube Rack', 'Holder for test tubes'],
            ['Safety Goggles', 'Protective eyewear for experiments']
        ],
        3 => [ // Physics Lab
            ['Vernier Caliper', 'Precision measurement tool'],
            ['Spring Balance', 'Force measurement instrument'],
            ['Pendulum Setup', 'Simple pendulum experiment kit'],
            ['Prism Set', 'Glass prisms for optics experiments'],
            ['Thermometer', 'Mercury thermometer for temperature'],
            ['Magnetometer', 'Magnetic field measuring device']
        ],
        4 => [ // Computer Lab
            ['Desktop Computer', 'High-performance workstation'],
            ['Programming Software', 'Licensed development tools'],
            ['Network Switch', 'Gigabit ethernet switch'],
            ['Projector', 'HD projector for presentations'],
            ['Printer', 'Network laser printer'],
            ['UPS System', 'Uninterruptible power supply']
        ],
        5 => [ // Mechanical Lab
            ['CNC Machine', 'Computer controlled milling machine'],
            ['Lathe Machine', 'Metal turning lathe'],
            ['Drill Press', 'Precision drilling machine'],
            ['Welding Equipment', 'Arc welding setup'],
            ['Caliper', 'Precision measuring tool'],
            ['Tool Set', 'Complete mechanical tools set']
        ],
        6 => [ // Biology Lab
            ['Microscope', 'Compound microscope for cell study'],
            ['Petri Dishes', 'Sterile culture dishes'],
            ['Centrifuge', 'Laboratory centrifuge machine'],
            ['Incubator', 'Temperature controlled incubator'],
            ['Autoclave', 'Sterilization equipment'],
            ['Micropipette', 'Precision liquid handling tool']
        ]
    ];
    
    $equipStmt = $pdo->prepare("INSERT INTO equipment (lab_id, name, condition_note, status) VALUES (?, ?, ?, 'available')");
    $totalEquipment = 0;
    
    foreach ($equipmentData as $labId => $equipment) {
        foreach ($equipment as $item) {
            $equipStmt->execute([$labId, $item[0], $item[1]]);
            $totalEquipment++;
        }
    }
    
    echo "$totalEquipment equipment items created.\n\n";
    
    echo "========================================\n";
    echo "DATABASE RESET COMPLETE!\n";
    echo "========================================\n";
    echo "Database: " . DB_NAME . "\n";
    echo "Labs: " . count($labs) . "\n";
    echo "Equipment: $totalEquipment\n";
    echo "Users: 1 (Admin only)\n";
    echo "\nAdmin Login:\n";
    echo "Email: admin@smartlab.local\n";
    echo "Password: admin123\n";
    echo "========================================\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
