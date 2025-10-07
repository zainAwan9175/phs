-- Smart Lab database schema
CREATE DATABASE IF NOT EXISTS `smartlab` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `smartlab`;

CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) DEFAULT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  student_or_staff_id VARCHAR(50) DEFAULT NULL,
  status ENUM('active','inactive') DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS labs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(120) DEFAULT NULL,
  description TEXT DEFAULT NULL,
  image_base64 LONGTEXT DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS equipment (
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
);

CREATE TABLE IF NOT EXISTS bookings (
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
);

CREATE TABLE IF NOT EXISTS booking_approvals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL,
  reviewer_id INT NOT NULL,
  action ENUM('approved','rejected') NOT NULL,
  comment VARCHAR(255) DEFAULT NULL,
  acted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
  FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS usage_events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL,
  event_type ENUM('check_in','check_out') NOT NULL,
  event_time DATETIME NOT NULL,
  condition_note VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS issues (
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
);

CREATE TABLE IF NOT EXISTS maintenance_tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipment_id INT NOT NULL,
  created_by INT NOT NULL,
  task_type ENUM('preventive','corrective') NOT NULL,
  summary VARCHAR(150) NOT NULL,
  due_date DATE DEFAULT NULL,
  status ENUM('scheduled','in_progress','completed','overdue') DEFAULT 'scheduled',
  vendor VARCHAR(120) DEFAULT NULL,
  cost DECIMAL(10,2) DEFAULT NULL,
  completed_at DATETIME DEFAULT NULL,
  FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  message VARCHAR(255) NOT NULL,
  link VARCHAR(255) DEFAULT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS rules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lab_id INT DEFAULT NULL,
  title VARCHAR(120) DEFAULT NULL,
  body TEXT DEFAULT NULL,
  active TINYINT(1) DEFAULT 1,
  FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  actor_id INT DEFAULT NULL,
  action VARCHAR(80) DEFAULT NULL,
  object_type VARCHAR(60) DEFAULT NULL,
  object_id INT DEFAULT NULL,
  details VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','replied') DEFAULT 'new',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS password_resets (
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
);
