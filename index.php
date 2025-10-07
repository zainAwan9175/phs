<?php
// Simple front controller for Smart Lab
require_once __DIR__ . '/db.php';
session_start();

$page = $_GET['page'] ?? 'home';
function view($name, $data = []) {
    // make the global PDO available to view templates
    global $pdo;
    extract($data);
    require __DIR__ . '/views/' . $name . '.php';
}

// Simple helpers
function redirect($url) { header('Location: ' . $url); exit; }

// Handle actions
if ($page === 'register_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!$email || !$password) {
        $_SESSION['flash'] = 'Email and password are required.';
        redirect('?page=register');
    }
    if ($password !== $confirm) {
        $_SESSION['flash'] = 'Passwords do not match.';
        redirect('?page=register');
    }
    try {
        $roleStmt = $GLOBALS['pdo']->prepare("SELECT id FROM roles WHERE name = ?");
        $roleStmt->execute(['student']);
        $roleId = $roleStmt->fetchColumn();
        if (!$roleId) throw new Exception('Student role not found. Please run the seeder.');
        $hash = password_hash($password, PASSWORD_BCRYPT);
        // try to include phone if the column exists; otherwise insert without it
        try {
            $ins = $GLOBALS['pdo']->prepare('INSERT INTO users (role_id, first_name, last_name, email, phone, password_hash) VALUES (?,?,?,?,?,?)');
            $ins->execute([$roleId, $first, $last, $email, $phone, $hash]);
        } catch (Exception $e) {
            // fallback if phone column doesn't exist
            $ins = $GLOBALS['pdo']->prepare('INSERT INTO users (role_id, first_name, last_name, email, password_hash) VALUES (?,?,?,?,?)');
            $ins->execute([$roleId, $first, $last, $email, $hash]);
        }
        $_SESSION['user_id'] = $GLOBALS['pdo']->lastInsertId();
        $_SESSION['flash'] = 'Registration successful. You are now logged in.';
        redirect('?page=home');
    } catch (Exception $e) {
        $_SESSION['flash'] = 'Registration failed: ' . $e->getMessage();
        redirect('?page=register');
    }
}

if ($page === 'login_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        $_SESSION['flash'] = 'Email and password are required.';
        redirect('?page=login');
    }
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND status = "active"');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['flash'] = 'Login successful.';
            redirect('?page=home');
        } else {
            $_SESSION['flash'] = 'Invalid credentials.';
            redirect('?page=login');
        }
    } catch (Exception $e) {
        $_SESSION['flash'] = 'Login failed: ' . $e->getMessage();
        redirect('?page=login');
    }
}

// Logout action (after confirmation)
if ($page === 'logout_action') {
    session_destroy();
    view('logout_success');
    exit;
}

// Old logout page (kept for compatibility)
if ($page === 'logout') {
    session_destroy();
    redirect('?page=home');
}

// Forgot Password page
if ($page === 'forgot_password') {
    view('forgot_password');
    exit;
}

// Forgot Password action
if ($page === 'forgot_password_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    // Validate email format
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Please enter a valid email address.';
        redirect('?page=forgot_password');
        exit;
    }
    
    try {
        // Check if user exists and is active
        $stmt = $pdo->prepare('SELECT id, first_name, email FROM users WHERE email = ? AND status = ?');
        $stmt->execute([$email, 'active']);
        $user = $stmt->fetch();
        
        if (!$user) {
            // Check if email exists but inactive
            $stmt = $pdo->prepare('SELECT id, status FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $anyUser = $stmt->fetch();
            
            if ($anyUser) {
                $_SESSION['error'] = 'This account is inactive. Please contact the administrator.';
                redirect('?page=forgot_password');
                exit;
            }
            
            // Email doesn't exist
            $_SESSION['error'] = 'No account found with this email address.';
            redirect('?page=forgot_password');
            exit;
        }
        
        // User exists and is active - store email in session and redirect to reset page
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_user_id'] = $user['id'];
        $_SESSION['success'] = 'Email verified! Please enter your new password below.';
        redirect('?page=reset_password');
        
    } catch (Exception $e) {
        error_log('Forgot password error: ' . $e->getMessage());
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        redirect('?page=forgot_password');
    }
    exit;
}

// Forgot Password Sent confirmation page (for future production use with email)
if ($page === 'forgot_password_sent') {
    view('forgot_password_sent');
    exit;
}

// Reset Password page
if ($page === 'reset_password') {
    // Check if user came from forgot password flow
    if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_user_id'])) {
        $_SESSION['error'] = 'Please enter your email first to reset your password.';
        redirect('?page=forgot_password');
        exit;
    }
    
    // Show reset password form
    view('reset_password');
    exit;
}

// Reset Password Action (update password in database)
if ($page === 'reset_password_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify session data exists
    if (!isset($_SESSION['reset_user_id'])) {
        $_SESSION['error'] = 'Session expired. Please start again.';
        redirect('?page=forgot_password');
        exit;
    }
    
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['reset_user_id'];
    
    // Simple validation - only check if passwords match
    if (empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'Please enter password in both fields.';
        redirect('?page=reset_password');
        exit;
    }
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match.';
        redirect('?page=reset_password');
        exit;
    }
    
    try {
        // Update password in database
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $stmt->execute([$hashedPassword, $user_id]);
        
        // Clear session data
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_user_id']);
        
        // Success! Redirect to login
        $_SESSION['success'] = 'Password updated successfully! Sign in with your new password.';
        redirect('?page=login');
        
    } catch (Exception $e) {
        error_log('Password reset error: ' . $e->getMessage());
        $_SESSION['error'] = 'Database error. Please try again.';
        redirect('?page=reset_password');
    }
    exit;
}

// OLD TOKEN-BASED CODE (keeping for reference, will be removed)
if (false && $page === 'reset_password_old') {
    $token = $_GET['token'] ?? '';
    if (!$token) {
        redirect('?page=login');
    }
    
    // Verify token is valid
    try {
        $stmt = $pdo->prepare('SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()');
        $stmt->execute([$token]);
        $reset = $stmt->fetch();
        
        if (!$reset) {
            $_SESSION['error'] = 'Invalid or expired reset link.';
            redirect('?page=login');
        }
        
        view('reset_password');
        exit;
    } catch (Exception $e) {
        redirect('?page=login');
    }
}

// Reset Password action
// Contact page
if ($page === 'contact') {
    view('contact');
    exit;
}

// Contact form submission
if ($page === 'contact_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if ($first_name && $last_name && $email && $message) {
        // Save contact message to database
        try {
            $stmt = $pdo->prepare('INSERT INTO contact_messages (first_name, last_name, email, phone, message) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$first_name, $last_name, $email, $phone, $message]);
            $_SESSION['contact_flash'] = 'Thank you for contacting us! We will get back to you soon.';
        } catch (Exception $e) {
            $_SESSION['contact_flash'] = 'Error sending message. Please try again.';
        }
    } else {
        $_SESSION['contact_flash'] = 'Please fill in all required fields.';
    }
    redirect('?page=contact');
}

// Labs list
if ($page === 'labs') {
    $labs = $pdo->query('SELECT * FROM labs ORDER BY name')->fetchAll();
    view('labs/list', ['labs'=>$labs]);
    exit;
}

// Lab detail
if ($page === 'lab' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $lab = $pdo->prepare('SELECT * FROM labs WHERE id = ?');
    $lab->execute([$id]);
    $lab = $lab->fetch();
    $equipment = $pdo->prepare('SELECT * FROM equipment WHERE lab_id = ?');
    $equipment->execute([$id]);
    $equipment = $equipment->fetchAll();
    view('labs/detail', ['lab'=>$lab,'equipment'=>$equipment]);
    exit;
}

// current user helper
function current_user() {
    static $user = null;
    if ($user !== null) return $user;
    if (empty($_SESSION['user_id'])) return null;
    $stmt = $GLOBALS['pdo']->prepare('SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id=r.id WHERE u.id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    return $user;
}

// Booking request form
if ($page === 'booking_request') {
    if (!current_user()) {
        redirect('?page=login');
    }
    
    // If equipment_id is provided, it's a direct booking from lab page
    if (isset($_GET['equipment_id'])) {
        $eid = (int)$_GET['equipment_id'];
        $stmt = $pdo->prepare('SELECT e.*, l.name as lab_name FROM equipment e JOIN labs l ON e.lab_id=l.id WHERE e.id = ?');
        $stmt->execute([$eid]);
        $equipment = $stmt->fetch();
        if (!$equipment) { echo 'Equipment not found'; exit; }
        
        // Block booking if equipment is in maintenance or in-use
        if (in_array($equipment['status'], ['maintenance', 'in-use', 'in_use'])) {
            $displayStatus = str_replace(['in-use', 'in_use'], 'In Use', ucfirst($equipment['status']));
            $_SESSION['flash'] = 'This equipment is currently unavailable for booking (Status: ' . $displayStatus . ').';
            redirect('?page=labs');
            exit;
        }
        
        view('bookings/request', ['equipment'=>$equipment]);
    } else {
        // No equipment selected, show selection form
        $labs = $pdo->query('SELECT * FROM labs ORDER BY name')->fetchAll();
        view('bookings/request', ['equipment'=>null, 'labs'=>$labs]);
    }
    exit;
}

// Create booking (POST)
if ($page === 'booking_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!current_user()) redirect('?page=login');
    $equipment_id = (int)($_POST['equipment_id'] ?? 0);
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    
    // Use dates with default time (00:00:00)
    $start = $start_date . ' 00:00:00';
    $end = $end_date . ' 23:59:59';
    
    // basic validation
    if (!$equipment_id || !$start_date || !$end_date) { 
        $_SESSION['flash'] = 'All fields are required.';
        redirect('?page=booking_request&equipment_id='.$equipment_id);
    }
    
    // Check if equipment is available (not in maintenance or in-use)
    $stmt = $pdo->prepare('SELECT status FROM equipment WHERE id = ?');
    $stmt->execute([$equipment_id]);
    $equipment_status = $stmt->fetchColumn();
    
    if (in_array($equipment_status, ['maintenance', 'in-use', 'in_use'])) {
        $displayStatus = str_replace(['in-use', 'in_use'], 'In Use', ucfirst($equipment_status));
        $_SESSION['flash'] = 'This equipment is currently unavailable for booking (Status: ' . $displayStatus . ').';
        redirect('?page=labs');
        exit;
    }
    
    $ins = $pdo->prepare('INSERT INTO bookings (equipment_id, requester_id, purpose, start_time, end_time, status) VALUES (?,?,?,?,?,?)');
    $ins->execute([$equipment_id, $_SESSION['user_id'], $purpose, $start, $end, 'pending']);
    $_SESSION['flash'] = 'Booking request submitted successfully!';
    redirect('?page=my_bookings');
}

// User bookings
if ($page === 'my_bookings') {
    if (!current_user()) redirect('?page=login');
    $stmt = $pdo->prepare('SELECT b.*, e.name as equipment_name, l.name as lab_name FROM bookings b JOIN equipment e ON b.equipment_id=e.id JOIN labs l ON e.lab_id=l.id WHERE b.requester_id = ? ORDER BY b.created_at DESC');
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll();
    view('bookings/list', ['bookings'=>$bookings]);
    exit;
}

// Booking edit page
if ($page === 'booking_edit' && isset($_GET['id'])) {
    if (!current_user()) redirect('?page=login');
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare('SELECT b.*, e.name as equipment_name, l.name as lab_name FROM bookings b JOIN equipment e ON b.equipment_id=e.id JOIN labs l ON e.lab_id=l.id WHERE b.id = ? AND b.requester_id = ?');
    $stmt->execute([$id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();
    if (!$booking) { echo 'Booking not found or access denied'; exit; }
    view('bookings/edit', ['booking'=>$booking]);
    exit;
}

// Booking update/cancel action
if ($page === 'booking_update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!current_user()) redirect('?page=login');
    $booking_id = (int)($_POST['booking_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $reason = trim($_POST['reason'] ?? '');
    
    if (!$booking_id || !$reason) { $_SESSION['flash']='Invalid data.'; redirect('?page=my_bookings'); }
    
    // Verify ownership
    $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = ? AND requester_id = ?');
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();
    if (!$booking) { echo 'Access denied'; exit; }
    
    if ($action === 'cancel') {
        // Cancel booking and set equipment back to available
        $pdo->beginTransaction();
        $upd = $pdo->prepare('UPDATE bookings SET status=? WHERE id=?');
        $upd->execute(['cancelled', $booking_id]);
        
        // Set equipment back to available
        if ($booking['equipment_id']) {
            $equipUpd = $pdo->prepare('UPDATE equipment SET status = ? WHERE id = ?');
            $equipUpd->execute(['available', $booking['equipment_id']]);
        }
        
        $pdo->commit();
        $_SESSION['flash']='Booking cancelled successfully.';
    } elseif ($action === 'update') {
        // For now, just log the reason (you can add more update logic later)
        $_SESSION['flash']='Booking update request submitted.';
    }
    
    redirect('?page=my_bookings');
}

// Profile view
if ($page === 'profile') {
    $user = current_user(); if (!$user) redirect('?page=login');
    // ensure phone column exists so the profile form can show it
    try {
        $cols = $pdo->query("SHOW COLUMNS FROM users LIKE 'phone'")->fetchAll();
        if (count($cols) === 0) {
            $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(30) DEFAULT NULL");
            // refetch user so phone appears
            $user = current_user();
        }
    } catch (Exception $e) {
        // ignore migration failure here; the profile form will still show without phone
    }
    view('profile'); exit;
}

// Profile update
if ($page === 'profile_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login');
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($password && $password !== $confirm) { $_SESSION['flash']='Passwords do not match.'; redirect('?page=profile'); }
    // update with phone if column exists; if not, attempt to add it and retry
    try {
        if ($password) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE users SET first_name=?, last_name=?, email=?, phone=?, password_hash=? WHERE id=?');
            $stmt->execute([$first,$last,$email,$phone,$hash,$user['id']]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET first_name=?, last_name=?, email=?, phone=? WHERE id=?');
            $stmt->execute([$first,$last,$email,$phone,$user['id']]);
        }
    } catch (Exception $e) {
        // If phone column missing, try to add it and retry update once
        if (stripos($e->getMessage(), 'unknown column') !== false || stripos($e->getMessage(), '1054') !== false) {
            try { $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(30) DEFAULT NULL"); } catch (Exception $x) {}
            // retry update
            if ($password) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare('UPDATE users SET first_name=?, last_name=?, email=?, phone=?, password_hash=? WHERE id=?');
                $stmt->execute([$first,$last,$email,$phone,$hash,$user['id']]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET first_name=?, last_name=?, email=?, phone=? WHERE id=?');
                $stmt->execute([$first,$last,$email,$phone,$user['id']]);
            }
        } else {
            throw $e;
        }
    }
    // small verification: fetch the user and check phone value
    try {
        $check = $pdo->prepare('SELECT phone FROM users WHERE id = ?'); $check->execute([$user['id']]); $val = $check->fetchColumn();
        $_SESSION['flash']='Profile updated. Phone saved: ' . ($val ?: '(empty)');
    } catch (Exception $e) {
        $_SESSION['flash']='Profile updated.';
    }
    redirect('?page=profile');
}

// Approvals (for lab assistants / managers / admin)
if ($page === 'approvals') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    // simple role check
    if (!in_array($user['role_name'], ['lab_assistant','lab_manager','admin'])) {
        echo 'Access denied'; exit;
    }
    $stmt = $pdo->query("SELECT b.*, e.name as equipment_name, l.name as lab_name, u.first_name, u.last_name FROM bookings b JOIN equipment e ON b.equipment_id=e.id JOIN labs l ON e.lab_id=l.id JOIN users u ON b.requester_id=u.id WHERE b.status='pending' ORDER BY b.created_at ASC");
    $pending = $stmt->fetchAll();
    view('bookings/approvals', ['pending'=>$pending]);
    exit;
}

// Approval action (approve/reject)
if ($page === 'approval_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'], ['lab_assistant','lab_manager','admin'])) { echo 'Denied'; exit; }
    $booking_id = (int)($_POST['booking_id'] ?? 0);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';
    $comment = $_POST['comment'] ?? null;
    if (!$booking_id) { echo 'Missing booking id'; exit; }
    $pdo->beginTransaction();
    
    // Get booking details to update equipment status and send notification
    $booking = $pdo->prepare('SELECT b.*, e.name as equipment_name FROM bookings b JOIN equipment e ON b.equipment_id=e.id WHERE b.id = ?');
    $booking->execute([$booking_id]);
    $booking = $booking->fetch();
    
    // Update booking status
    $upd = $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');
    $upd->execute([$action, $booking_id]);
    
    // Update equipment status
    if ($booking && $booking['equipment_id']) {
        if ($action === 'approved') {
            // Set equipment to in_use when booking is approved
            $equipUpd = $pdo->prepare('UPDATE equipment SET status = ? WHERE id = ?');
            $equipUpd->execute(['in_use', $booking['equipment_id']]);
        } elseif ($action === 'rejected') {
            // Set equipment back to available when booking is rejected
            $equipUpd = $pdo->prepare('UPDATE equipment SET status = ? WHERE id = ?');
            $equipUpd->execute(['available', $booking['equipment_id']]);
        }
    }
    
    // Log the approval/rejection
    $ins = $pdo->prepare('INSERT INTO booking_approvals (booking_id, reviewer_id, action, comment) VALUES (?,?,?,?)');
    $ins->execute([$booking_id, $user['id'], $action, $comment]);
    
    // Create notification for the student
    if ($booking && $booking['requester_id']) {
        $notifMessage = $action === 'approved' 
            ? "Booking request for the equipment " . $booking['equipment_name'] . " has been approved."
            : "Booking request for the equipment " . $booking['equipment_name'] . " has been rejected.";
        $notifIns = $pdo->prepare('INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)');
        $notifIns->execute([$booking['requester_id'], $notifMessage, '?page=bookings']);
    }
    
    $pdo->commit();
    redirect('?page=approvals');
}

// Equipment Use page (assistant marks equipment as given)
if ($page === 'equipment_use') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'], ['lab_assistant','admin'])) { echo 'Access denied'; exit; }
    $labs = $pdo->query('SELECT * FROM labs ORDER BY name')->fetchAll();
    view('assistant/equipment_use', ['labs'=>$labs]);
    exit;
}

// Equipment Use action (mark equipment as in_use)
if ($page === 'equipment_use_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'], ['lab_assistant','admin'])) { echo 'Access denied'; exit; }
    
    $equipment_id = (int)($_POST['equipment_id'] ?? 0);
    $booking_id = trim($_POST['booking_id'] ?? '');
    $start_date = trim($_POST['start_date'] ?? '');
    $condition_start = trim($_POST['condition_start'] ?? 'good');
    $notes = trim($_POST['notes'] ?? '');
    
    if (!$equipment_id || !$start_date) {
        $_SESSION['flash'] = 'Equipment and start date are required.';
        redirect('?page=equipment_use');
    }
    
    // Update equipment status to in_use
    $upd = $pdo->prepare('UPDATE equipment SET status = ? WHERE id = ?');
    $upd->execute(['in_use', $equipment_id]);
    
    $_SESSION['flash'] = 'Equipment marked as in use successfully.';
    redirect('?page=equipment_use');
}

// Equipment Return page (assistant marks equipment as returned)
if ($page === 'equipment_return') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'], ['lab_assistant','admin'])) { echo 'Access denied'; exit; }
    $labs = $pdo->query('SELECT * FROM labs ORDER BY name')->fetchAll();
    view('assistant/equipment_return', ['labs'=>$labs]);
    exit;
}

// Equipment Return action (mark equipment as available)
if ($page === 'equipment_return_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'], ['lab_assistant','admin'])) { echo 'Access denied'; exit; }
    
    $equipment_id = (int)($_POST['equipment_id'] ?? 0);
    $booking_id = trim($_POST['booking_id'] ?? '');
    $return_date = trim($_POST['return_date'] ?? '');
    $condition_return = trim($_POST['condition_return'] ?? 'good');
    $status = trim($_POST['status'] ?? 'available');
    $notes = trim($_POST['notes'] ?? '');
    
    if (!$equipment_id || !$return_date) {
        $_SESSION['flash'] = 'Equipment and return date are required.';
        redirect('?page=equipment_return');
    }
    
    // Update equipment status (available or maintenance)
    $upd = $pdo->prepare('UPDATE equipment SET status = ? WHERE id = ?');
    $upd->execute([$status, $equipment_id]);
    
    $_SESSION['flash'] = 'Equipment returned successfully.';
    redirect('?page=equipment_return');
}

// API endpoint to get equipment by lab and status
if ($page === 'api_get_equipment') {
    header('Content-Type: application/json');
    $lab_id = (int)($_GET['lab_id'] ?? 0);
    $status = $_GET['status'] ?? 'available';
    
    if (!$lab_id) {
        echo json_encode(['success' => false, 'message' => 'Lab ID required']);
        exit;
    }
    
    if ($status === 'all') {
        $stmt = $pdo->prepare('SELECT id, name, asset_tag, status FROM equipment WHERE lab_id = ? ORDER BY name');
        $stmt->execute([$lab_id]);
    } else {
        $stmt = $pdo->prepare('SELECT id, name, asset_tag, status FROM equipment WHERE lab_id = ? AND status = ? ORDER BY name');
        $stmt->execute([$lab_id, $status]);
    }
    $equipment = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'equipment' => $equipment]);
    exit;
}

// API endpoint to get rules by lab
if ($page === 'api_get_rules') {
    header('Content-Type: application/json');
    $lab_id = (int)($_GET['lab_id'] ?? 0);
    
    if (!$lab_id) {
        echo json_encode(['success' => false, 'message' => 'Lab ID required']);
        exit;
    }
    
    $stmt = $pdo->prepare('SELECT * FROM rules WHERE (lab_id = ? OR lab_id IS NULL) AND active = 1 ORDER BY lab_id DESC');
    $stmt->execute([$lab_id]);
    $rules = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'rules' => $rules]);
    exit;
}

// Admin labs management (list + create)
if ($page === 'admin_labs') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') { echo 'Access denied'; exit; }
    $labs = $pdo->query('SELECT * FROM labs ORDER BY name')->fetchAll();
    view('admin/labs', ['labs'=>$labs]);
    exit;
}

// Admin add lab form
if ($page === 'admin_lab_add') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') { echo 'Access denied'; exit; }
    view('admin/lab_add');
    exit;
}

// Admin dashboard (landing)
if ($page === 'admin_home') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; }
    view('admin/home'); exit;
}

// Admin contact messages
if ($page === 'admin_contact_messages') {
    $user = current_user(); 
    if (!$user) redirect('?page=login'); 
    if (($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; }
    $messages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();
    view('admin/contact_messages', ['messages' => $messages]);
    exit;
}

// Admin view single contact message
if ($page === 'admin_contact_view' && isset($_GET['id'])) {
    $user = current_user(); 
    if (!$user) redirect('?page=login'); 
    if (($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; }
    
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM contact_messages WHERE id = ?');
    $stmt->execute([$id]);
    $message = $stmt->fetch();
    
    if (!$message) { echo 'Message not found'; exit; }
    
    // Mark as read if it's new
    if ($message['status'] === 'new') {
        $upd = $pdo->prepare('UPDATE contact_messages SET status = "read" WHERE id = ?');
        $upd->execute([$id]);
    }
    
    view('admin/contact_view', ['message' => $message]);
    exit;
}

// Admin update contact message status
if ($page === 'admin_contact_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); 
    if (!$user) redirect('?page=login'); 
    if (($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; }
    
    $message_id = (int)($_POST['message_id'] ?? 0);
    $status = $_POST['status'] ?? 'read';
    
    if ($message_id) {
        $upd = $pdo->prepare('UPDATE contact_messages SET status = ? WHERE id = ?');
        $upd->execute([$status, $message_id]);
        $_SESSION['flash'] = 'Status updated successfully.';
    }
    
    redirect('?page=admin_contact_view&id=' . $message_id);
}

// Admin Rules Management
if ($page === 'admin_rules') {
    $user = current_user(); 
    if (!$user) redirect('?page=login'); 
    if (($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; }
    
    // Get all labs for dropdown
    $labs = $pdo->query('SELECT id, name FROM labs ORDER BY name')->fetchAll();
    
    // Get all rules with lab names
    $rules = $pdo->query('
        SELECT r.*, l.name as lab_name 
        FROM rules r 
        LEFT JOIN labs l ON r.lab_id = l.id 
        ORDER BY r.id DESC
    ')->fetchAll();
    
    view('admin/rules', ['labs' => $labs, 'rules' => $rules]);
    exit;
}

// Admin add/update rule
if ($page === 'admin_rule_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); 
    if (!$user) redirect('?page=login'); 
    if (($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; }
    
    $lab_id = !empty($_POST['lab_id']) ? (int)$_POST['lab_id'] : null;
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $rule_id = !empty($_POST['rule_id']) ? (int)$_POST['rule_id'] : null;
    
    if (!$title || !$body) {
        $_SESSION['flash'] = 'Title and body are required.';
        redirect('?page=admin_rules');
    }
    
    try {
        if ($rule_id) {
            // Update existing rule
            $stmt = $pdo->prepare('UPDATE rules SET lab_id = ?, title = ?, body = ? WHERE id = ?');
            $stmt->execute([$lab_id, $title, $body, $rule_id]);
            $_SESSION['flash'] = 'Rule updated successfully.';
        } else {
            // Create new rule
            $stmt = $pdo->prepare('INSERT INTO rules (lab_id, title, body, active) VALUES (?, ?, ?, 1)');
            $stmt->execute([$lab_id, $title, $body]);
            $_SESSION['flash'] = 'Rule added successfully.';
        }
    } catch (Exception $e) {
        $_SESSION['flash'] = 'Error: ' . $e->getMessage();
    }
    
    redirect('?page=admin_rules');
}

// Admin edit rule
if ($page === 'admin_rule_edit' && isset($_GET['id'])) {
    $user = current_user(); 
    if (!$user) redirect('?page=login'); 
    if (($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; }
    
    $rule_id = (int)$_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM rules WHERE id = ?');
    $stmt->execute([$rule_id]);
    $rule = $stmt->fetch();
    
    if (!$rule) { echo 'Rule not found'; exit; }
    
    // Get all labs for dropdown
    $labs = $pdo->query('SELECT id, name FROM labs ORDER BY name')->fetchAll();
    
    view('admin/rule_edit', ['rule' => $rule, 'labs' => $labs]);
    exit;
}

// Admin delete rule
if ($page === 'admin_rule_delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); 
    if (!$user) redirect('?page=login'); 
    if (($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; }
    
    $rule_id = (int)($_POST['rule_id'] ?? 0);
    
    if ($rule_id) {
        $stmt = $pdo->prepare('DELETE FROM rules WHERE id = ?');
        $stmt->execute([$rule_id]);
        $_SESSION['flash'] = 'Rule deleted successfully.';
    }
    
    redirect('?page=admin_rules');
}

// Admin Equipment Reports - View All
if ($page === 'admin_equipment_reports') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') {
        echo 'Access denied'; exit;
    }
    view('admin/equipment_reports');
    exit;
}

// Admin Equipment Reports - View Single Report
if ($page === 'admin_view_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') {
        echo 'Access denied'; exit;
    }
    $report_id = (int)($_GET['id'] ?? 0);
    if (!$report_id) redirect('?page=admin_equipment_reports');
    
    $stmt = $pdo->prepare("
        SELECT 
          er.*,
          e.name as equipment_name,
          e.category as equipment_category,
          l.name as lab_name,
          m.first_name as manager_first,
          m.last_name as manager_last,
          s.first_name as student_first,
          s.last_name as student_last
        FROM equipment_reports er
        JOIN equipment e ON er.equipment_id = e.id
        JOIN labs l ON e.lab_id = l.id
        JOIN users m ON er.manager_id = m.id
        LEFT JOIN users s ON er.student_id = s.id
        WHERE er.id = ?
    ");
    $stmt->execute([$report_id]);
    $report = $stmt->fetch();
    
    if (!$report) {
        $_SESSION['error'] = 'Report not found.';
        redirect('?page=admin_equipment_reports');
    }
    
    view('admin/view_equipment_report', ['report' => $report]);
    exit;
}

// Admin Equipment Reports - Edit Report Page
if ($page === 'admin_edit_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') {
        echo 'Access denied'; exit;
    }
    $report_id = (int)($_GET['id'] ?? 0);
    if (!$report_id) redirect('?page=admin_equipment_reports');
    
    $stmt = $pdo->prepare('SELECT * FROM equipment_reports WHERE id = ?');
    $stmt->execute([$report_id]);
    $report = $stmt->fetch();
    
    if (!$report) {
        $_SESSION['error'] = 'Report not found.';
        redirect('?page=admin_equipment_reports');
    }
    
    view('admin/edit_equipment_report', ['report' => $report]);
    exit;
}

// Admin Equipment Reports - Update Report Action
if ($page === 'admin_update_equipment_report_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') {
        echo 'Access denied'; exit;
    }
    
    $report_id = (int)($_POST['report_id'] ?? 0);
    $condition_before = trim($_POST['condition_before'] ?? '');
    $condition_after = trim($_POST['condition_after'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $booking_date = $_POST['booking_date'] ?: null;
    $return_date = $_POST['return_date'] ?: null;
    $status = $_POST['status'] ?? 'pending_return';
    
    if (!$report_id || !$condition_before) {
        $_SESSION['error'] = 'Condition before is required.';
        redirect('?page=admin_edit_equipment_report&id=' . $report_id);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare('
            UPDATE equipment_reports 
            SET condition_before = ?, condition_after = ?, notes = ?, booking_date = ?, return_date = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ');
        $stmt->execute([
            $condition_before,
            $condition_after ?: null,
            $notes ?: null,
            $booking_date,
            $return_date,
            $status,
            $report_id
        ]);
        
        $_SESSION['success'] = 'Report updated successfully!';
        redirect('?page=admin_equipment_reports');
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to update report: ' . $e->getMessage();
        redirect('?page=admin_edit_equipment_report&id=' . $report_id);
    }
    exit;
}

// Admin Equipment Reports - Delete Report
if ($page === 'admin_delete_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') {
        echo 'Access denied'; exit;
    }
    
    $report_id = (int)($_GET['id'] ?? 0);
    if (!$report_id) redirect('?page=admin_equipment_reports');
    
    try {
        $stmt = $pdo->prepare('DELETE FROM equipment_reports WHERE id = ?');
        $stmt->execute([$report_id]);
        
        $_SESSION['success'] = 'Report deleted successfully!';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to delete report: ' . $e->getMessage();
    }
    
    redirect('?page=admin_equipment_reports');
    exit;
}

// Student dashboard
if ($page === 'student_dashboard') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'student') { echo 'Access denied'; exit; }
    view('student/dashboard'); exit;
}

// Lab Manager dashboard
if ($page === 'manager_dashboard') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    view('manager/dashboard'); exit;
}

// Manager register user form
if ($page === 'manager_register') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    view('manager/register'); exit;
}

// Manager register user action
if ($page === 'manager_register_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role_id = (int)($_POST['role_id'] ?? 0);
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (!$email || !$password || !$role_id) {
        $_SESSION['flash'] = 'All required fields must be filled.';
        redirect('?page=manager_register');
    }
    if ($password !== $confirm) {
        $_SESSION['flash'] = 'Passwords do not match.';
        redirect('?page=manager_register');
    }
    
    try {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $ins = $pdo->prepare('INSERT INTO users (role_id, first_name, last_name, email, phone, password_hash) VALUES (?,?,?,?,?,?)');
        $ins->execute([$role_id, $first, $last, $email, $phone, $hash]);
        $_SESSION['flash'] = 'User registered successfully.';
        redirect('?page=manager_dashboard');
    } catch (Exception $e) {
        $_SESSION['flash'] = 'Registration failed: ' . $e->getMessage();
        redirect('?page=manager_register');
    }
}

// Manager create booking form
if ($page === 'manager_create_booking') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    view('manager/create_booking'); exit;
}

// Manager create booking action
if ($page === 'manager_create_booking_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    
    $user_id = (int)($_POST['user_id'] ?? 0);
    $equipment_id = (int)($_POST['equipment_id'] ?? 0);
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $purpose = trim($_POST['purpose'] ?? '');
    
    if (!$user_id || !$equipment_id || !$start_date || !$end_date || !$purpose) {
        $_SESSION['flash'] = 'All fields are required.';
        redirect('?page=manager_create_booking');
    }
    
    try {
        $stmt = $pdo->prepare('INSERT INTO bookings (user_id, equipment_id, start_time, end_time, purpose, status) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$user_id, $equipment_id, $start_date, $end_date, $purpose, 'approved']);
        $_SESSION['flash'] = 'Booking created and approved successfully.';
        redirect('?page=manager_dashboard');
    } catch (Exception $e) {
        $_SESSION['flash'] = 'Booking creation failed: ' . $e->getMessage();
        redirect('?page=manager_create_booking');
    }
}

// Manager create maintenance task form
if ($page === 'manager_create_maintenance') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    view('manager/create_maintenance'); exit;
}

// Manager create maintenance task action
if ($page === 'manager_create_maintenance_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    
    $equipment_id = (int)($_POST['equipment_id'] ?? 0);
    $task_type = trim($_POST['task_type'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $scheduled_date = $_POST['scheduled_date'] ?? '';
    
    if (!$equipment_id || !$task_type || !$summary || !$scheduled_date) {
        $_SESSION['flash'] = 'All fields are required.';
        redirect('?page=manager_create_maintenance');
    }
    
    try {
        // Get equipment name for display
        $stmt = $pdo->prepare('SELECT name FROM equipment WHERE id = ?');
        $stmt->execute([$equipment_id]);
        $equipment_name = $stmt->fetchColumn();
        
        // Update maintenance_tasks table structure
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS maintenance_tasks (
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
        
        $stmt = $pdo->prepare('INSERT INTO maintenance_tasks (equipment_id, equipment_name, task_type, summary, scheduled_date, status, created_by) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$equipment_id, $equipment_name, $task_type, $summary, $scheduled_date, 'open', $user['id']]);
        
        // Update equipment status to maintenance
        $pdo->prepare('UPDATE equipment SET status = ? WHERE id = ?')->execute(['maintenance', $equipment_id]);
        
        $_SESSION['flash'] = 'Maintenance task created successfully.';
        redirect('?page=manager_dashboard');
    } catch (Exception $e) {
        $_SESSION['flash'] = 'Task creation failed: ' . $e->getMessage();
        redirect('?page=manager_create_maintenance');
    }
}

// Manager view all maintenance tasks
if ($page === 'manager_maintenance_tasks') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    view('manager/maintenance_tasks'); exit;
}

// Admin view all maintenance tasks (read-only)
if ($page === 'admin_maintenance_tasks') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (!in_array($user['role_name'] ?? '', ['admin'])) { echo 'Access denied'; exit; }
    view('admin/maintenance_tasks'); exit;
}

// Manager close maintenance task form
if ($page === 'manager_close_maintenance') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    view('manager/close_maintenance'); exit;
}

// Manager close maintenance task action
if ($page === 'manager_close_maintenance_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    
    $task_id = (int)($_POST['task_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $cost = !empty($_POST['cost']) ? (float)$_POST['cost'] : null;
    $completion_date = $_POST['completion_date'] ?? '';
    
    if (!$task_id || !$status || !$completion_date) {
        $_SESSION['flash'] = 'Task, status, and date are required.';
        redirect('?page=manager_close_maintenance');
    }
    
    try {
        // Get equipment_id before closing task
        $stmt = $pdo->prepare('SELECT equipment_id FROM maintenance_tasks WHERE id = ?');
        $stmt->execute([$task_id]);
        $equipment_id = $stmt->fetchColumn();
        
        // Close the maintenance task
        $stmt = $pdo->prepare('UPDATE maintenance_tasks SET status = ?, cost = ?, completion_date = ? WHERE id = ?');
        $stmt->execute([$status, $cost, $completion_date, $task_id]);
        
        // Update equipment status back to available if task is completed
        if ($equipment_id && $status === 'completed') {
            $pdo->prepare('UPDATE equipment SET status = ? WHERE id = ?')->execute(['available', $equipment_id]);
        }
        
        $_SESSION['flash'] = 'Maintenance task closed successfully.';
        redirect('?page=manager_dashboard');
    } catch (Exception $e) {
        $_SESSION['flash'] = 'Task closure failed: ' . $e->getMessage();
        redirect('?page=manager_close_maintenance');
    }
}

// Manager report issue form
if ($page === 'manager_report_issue') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    view('manager/report_issue'); exit;
}

// Manager report issue action
if ($page === 'manager_report_issue_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; }
    
    $lab_id = (int)($_POST['lab_id'] ?? 0);
    $equipment_id = (int)($_POST['equipment_id'] ?? 0);
    $details = trim($_POST['details'] ?? '');
    
    if (!$lab_id || !$equipment_id || !$details) {
        $_SESSION['flash'] = 'All fields are required.';
        redirect('?page=manager_report_issue');
    }
    
    try {
        // Ensure issues table exists with correct schema
        $pdo->exec("
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
            )
        ");
        
        // Get equipment name for the title
        $stmt = $pdo->prepare('SELECT name FROM equipment WHERE id = ?');
        $stmt->execute([$equipment_id]);
        $equipment_name = $stmt->fetchColumn();
        
        $issue_title = 'Issue with ' . $equipment_name;
        
        $stmt = $pdo->prepare('INSERT INTO issues (equipment_id, reporter_id, title, details, status, reported_at) VALUES (?,?,?,?,?,NOW())');
        $stmt->execute([$equipment_id, $user['id'], $issue_title, $details, 'open']);
        
        $_SESSION['flash'] = 'Issue reported successfully.';
        redirect('?page=manager_dashboard');
    } catch (Exception $e) {
        $_SESSION['flash'] = 'Issue report failed: ' . $e->getMessage();
        redirect('?page=manager_report_issue');
    }
}

// Equipment Reports - View All
if ($page === 'manager_equipment_reports') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['lab_manager'])) {
        echo 'Access denied'; exit;
    }
    view('manager/equipment_reports');
    exit;
}

// Equipment Reports - Add Report Page
if ($page === 'manager_add_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['lab_manager'])) {
        echo 'Access denied'; exit;
    }
    view('manager/add_equipment_report');
    exit;
}

// Equipment Reports - Add Report Action
if ($page === 'manager_add_equipment_report_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['lab_manager'])) {
        echo 'Access denied'; exit;
    }
    
    $equipment_id = (int)($_POST['equipment_id'] ?? 0);
    $student_id = trim($_POST['student_id'] ?? '');
    $condition_before = trim($_POST['condition_before'] ?? '');
    $condition_after = trim($_POST['condition_after'] ?? '');
    $date_of_issue = $_POST['date_of_issue'] ?? '';
    $date_of_closing = $_POST['date_of_closing'] ?? '';
    
    if (!$equipment_id || !$student_id || !$condition_before || !$condition_after || !$date_of_issue || !$date_of_closing) {
        $_SESSION['error'] = 'All fields are required.';
        redirect('?page=manager_add_equipment_report');
        exit;
    }
    
    try {
        // Try to find user by ID or email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? OR email = ? OR CONCAT(first_name, ' ', last_name) = ?");
        $stmt->execute([$student_id, $student_id, $student_id]);
        $student = $stmt->fetch();
        
        $final_student_id = $student ? $student['id'] : null;
        
        $stmt = $pdo->prepare('
            INSERT INTO equipment_reports 
            (equipment_id, manager_id, student_id, condition_before, condition_after, booking_date, return_date, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ');
        $stmt->execute([
            $equipment_id,
            $user['id'],
            $final_student_id,
            $condition_before,
            $condition_after,
            $date_of_issue,
            $date_of_closing,
            'returned'
        ]);
        
        $_SESSION['success'] = 'Equipment report created successfully!';
        redirect('?page=manager_equipment_reports');
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to create report: ' . $e->getMessage();
        redirect('?page=manager_add_equipment_report');
    }
    exit;
}

// Admin - Add Equipment Report
if ($page === 'admin_add_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['admin'])) {
        echo 'Access denied'; exit;
    }
    view('admin/add_equipment_report');
    exit;
}

// Admin - Add Equipment Report Action
if ($page === 'admin_add_equipment_report_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['admin'])) {
        echo 'Access denied'; exit;
    }
    
    $equipment_id = (int)($_POST['equipment_id'] ?? 0);
    $student_id = trim($_POST['student_id'] ?? '');
    $condition_before = trim($_POST['condition_before'] ?? '');
    $condition_after = trim($_POST['condition_after'] ?? '');
    $date_of_issue = $_POST['date_of_issue'] ?? '';
    $date_of_closing = $_POST['date_of_closing'] ?? '';
    
    if (!$equipment_id || !$student_id || !$condition_before || !$condition_after || !$date_of_issue || !$date_of_closing) {
        $_SESSION['error'] = 'All fields are required.';
        redirect('?page=admin_add_equipment_report');
        exit;
    }
    
    try {
        // Try to find user by ID or email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? OR email = ? OR CONCAT(first_name, ' ', last_name) = ?");
        $stmt->execute([$student_id, $student_id, $student_id]);
        $student = $stmt->fetch();
        
        $final_student_id = $student ? $student['id'] : null;
        
        $stmt = $pdo->prepare('
            INSERT INTO equipment_reports 
            (equipment_id, manager_id, student_id, condition_before, condition_after, booking_date, return_date, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ');
        $stmt->execute([
            $equipment_id,
            $user['id'],
            $final_student_id,
            $condition_before,
            $condition_after,
            $date_of_issue,
            $date_of_closing,
            'returned'
        ]);
        
        $_SESSION['success'] = 'Equipment report created successfully!';
        redirect('?page=admin_equipment_reports');
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to create report: ' . $e->getMessage();
        redirect('?page=admin_add_equipment_report');
    }
    exit;
}

// Admin - Edit Equipment Report
if ($page === 'admin_edit_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['admin'])) {
        echo 'Access denied'; exit;
    }
    $report_id = (int)($_GET['id'] ?? 0);
    if (!$report_id) redirect('?page=admin_equipment_reports');
    
    $stmt = $pdo->prepare('SELECT * FROM equipment_reports WHERE id = ?');
    $stmt->execute([$report_id]);
    $report = $stmt->fetch();
    
    if (!$report) {
        $_SESSION['error'] = 'Report not found.';
        redirect('?page=admin_equipment_reports');
    }
    
    view('admin/edit_equipment_report', ['report' => $report]);
    exit;
}

// Admin - Update Equipment Report Action
if ($page === 'admin_update_equipment_report_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['admin'])) {
        echo 'Access denied'; exit;
    }
    
    $report_id = (int)($_POST['report_id'] ?? 0);
    $condition_before = trim($_POST['condition_before'] ?? '');
    $condition_after = trim($_POST['condition_after'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $booking_date = $_POST['booking_date'] ?: null;
    $return_date = $_POST['return_date'] ?: null;
    $status = $_POST['status'] ?? 'pending_return';
    
    if (!$report_id || !$condition_before) {
        $_SESSION['error'] = 'Condition before is required.';
        redirect('?page=admin_edit_equipment_report&id=' . $report_id);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare('
            UPDATE equipment_reports 
            SET condition_before = ?, condition_after = ?, notes = ?, booking_date = ?, return_date = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ');
        $stmt->execute([
            $condition_before,
            $condition_after ?: null,
            $notes ?: null,
            $booking_date,
            $return_date,
            $status,
            $report_id
        ]);
        
        $_SESSION['success'] = 'Report updated successfully!';
        redirect('?page=admin_equipment_reports');
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to update report: ' . $e->getMessage();
        redirect('?page=admin_edit_equipment_report&id=' . $report_id);
    }
    exit;
}

// Equipment Reports - View Single Report
if ($page === 'manager_view_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['lab_manager'])) {
        echo 'Access denied'; exit;
    }
    $report_id = (int)($_GET['id'] ?? 0);
    if (!$report_id) redirect('?page=manager_equipment_reports');
    
    $stmt = $pdo->prepare("
        SELECT 
          er.*,
          e.name as equipment_name,
          e.category as equipment_category,
          l.name as lab_name,
          m.first_name as manager_first,
          m.last_name as manager_last,
          s.first_name as student_first,
          s.last_name as student_last
        FROM equipment_reports er
        JOIN equipment e ON er.equipment_id = e.id
        JOIN labs l ON e.lab_id = l.id
        JOIN users m ON er.manager_id = m.id
        LEFT JOIN users s ON er.student_id = s.id
        WHERE er.id = ?
    ");
    $stmt->execute([$report_id]);
    $report = $stmt->fetch();
    
    if (!$report) {
        $_SESSION['error'] = 'Report not found.';
        redirect('?page=manager_equipment_reports');
    }
    
    view('manager/view_equipment_report', ['report' => $report]);
    exit;
}

// Equipment Reports - Edit Report Page
if ($page === 'manager_edit_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['lab_manager'])) {
        echo 'Access denied'; exit;
    }
    $report_id = (int)($_GET['id'] ?? 0);
    if (!$report_id) redirect('?page=manager_equipment_reports');
    
    $stmt = $pdo->prepare('SELECT * FROM equipment_reports WHERE id = ?');
    $stmt->execute([$report_id]);
    $report = $stmt->fetch();
    
    if (!$report) {
        $_SESSION['error'] = 'Report not found.';
        redirect('?page=manager_equipment_reports');
    }
    
    view('manager/edit_equipment_report', ['report' => $report]);
    exit;
}

// Equipment Reports - Update Report Action
if ($page === 'manager_update_equipment_report_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['lab_manager'])) {
        echo 'Access denied'; exit;
    }
    
    $report_id = (int)($_POST['report_id'] ?? 0);
    $condition_before = trim($_POST['condition_before'] ?? '');
    $condition_after = trim($_POST['condition_after'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $booking_date = $_POST['booking_date'] ?: null;
    $return_date = $_POST['return_date'] ?: null;
    $status = $_POST['status'] ?? 'pending_return';
    
    if (!$report_id || !$condition_before) {
        $_SESSION['error'] = 'Condition before is required.';
        redirect('?page=manager_edit_equipment_report&id=' . $report_id);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare('
            UPDATE equipment_reports 
            SET condition_before = ?, condition_after = ?, notes = ?, booking_date = ?, return_date = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ');
        $stmt->execute([
            $condition_before,
            $condition_after ?: null,
            $notes ?: null,
            $booking_date,
            $return_date,
            $status,
            $report_id
        ]);
        
        $_SESSION['success'] = 'Report updated successfully!';
        redirect('?page=manager_equipment_reports');
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to update report: ' . $e->getMessage();
        redirect('?page=manager_edit_equipment_report&id=' . $report_id);
    }
    exit;
}

// Equipment Reports - Delete Report
if ($page === 'manager_delete_equipment_report') {
    $user = current_user(); if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['lab_manager'])) {
        echo 'Access denied'; exit;
    }
    
    $report_id = (int)($_GET['id'] ?? 0);
    if (!$report_id) redirect('?page=manager_equipment_reports');
    
    try {
        $stmt = $pdo->prepare('DELETE FROM equipment_reports WHERE id = ?');
        $stmt->execute([$report_id]);
        
        $_SESSION['success'] = 'Report deleted successfully!';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to delete report: ' . $e->getMessage();
    }
    
    redirect('?page=manager_equipment_reports');
    exit;
}

// Student Notifications page
if ($page === 'notifications') {
    $user = current_user(); if (!$user) redirect('?page=login');
    $stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50');
    $stmt->execute([$user['id']]);
    $notifications = $stmt->fetchAll();
    view('student/notifications', ['notifications' => $notifications]);
    exit;
}

// Mark notification as read
if ($page === 'mark_notification_read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login');
    $notification_id = (int)($_POST['notification_id'] ?? 0);
    if ($notification_id) {
        $upd = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?');
        $upd->execute([$notification_id, $user['id']]);
    }
    redirect('?page=notifications');
}

// Mark all notifications as read
if ($page === 'mark_all_notifications_read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login');
    $upd = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = ?');
    $upd->execute([$user['id']]);
    $_SESSION['flash'] = 'All notifications marked as read.';
    redirect('?page=notifications');
}

// Student Bookings page
if ($page === 'bookings') {
    $user = current_user(); if (!$user) redirect('?page=login');
    $stmt = $pdo->prepare('SELECT b.*, e.name as equipment_name, l.name as lab_name FROM bookings b JOIN equipment e ON b.equipment_id=e.id JOIN labs l ON e.lab_id=l.id WHERE b.requester_id = ? ORDER BY b.created_at DESC');
    $stmt->execute([$user['id']]);
    $bookings = $stmt->fetchAll();
    view('student/bookings', ['bookings' => $bookings]);
    exit;
}

// Lab Assistant dashboard
if ($page === 'assistant_dashboard') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (($user['role_name'] ?? '') !== 'lab_assistant') { echo 'Access denied'; exit; }
    view('assistant/dashboard'); exit;
}

// Admin users management
if ($page === 'admin_users') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') { echo 'Access denied'; exit; }
    $users = $pdo->query('SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id=r.id ORDER BY u.created_at DESC')->fetchAll();
    view('admin/users', ['users'=>$users]);
    exit;
}

// Admin user edit page
if ($page === 'admin_user_edit' && isset($_GET['id'])) {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') { echo 'Access denied'; exit; }
    $id = (int)$_GET['id'];
    $editUser = $pdo->prepare('SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id=r.id WHERE u.id=?');
    $editUser->execute([$id]);
    $editUser = $editUser->fetch();
    if (!$editUser) { echo 'User not found'; exit; }
    $roles = $pdo->query('SELECT id, name FROM roles ORDER BY name')->fetchAll();
    view('admin/user_edit', ['editUser'=>$editUser,'roles'=>$roles]);
    exit;
}

// Admin update user
if ($page === 'admin_user_update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if ($user['role_name'] !== 'admin') { echo 'Denied'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    $role_id = (int)($_POST['role_id'] ?? 0);
    $status = trim($_POST['status'] ?? 'active');
    if (!$id || !$role_id) { $_SESSION['flash']='Invalid data.'; redirect('?page=admin_users'); }
    $upd = $pdo->prepare('UPDATE users SET role_id=?, status=? WHERE id=?');
    $upd->execute([$role_id, $status, $id]);
    $_SESSION['flash']='User updated successfully.';
    redirect('?page=admin_users');
}

// Admin create/update lab
if ($page === 'admin_lab_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if ($user['role_name'] !== 'admin') { echo 'Access denied'; exit; }
    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_b64 = null;
    // handle file upload
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $raw = file_get_contents($_FILES['image']['tmp_name']);
        $mime = mime_content_type($_FILES['image']['tmp_name']) ?: 'image/png';
        $image_b64 = 'data:' . $mime . ';base64,' . base64_encode($raw);
    }
    $ins = $pdo->prepare('INSERT INTO labs (name, location, description, image_base64) VALUES (?,?,?,?)');
    $ins->execute([$name,$location,$description,$image_b64]);
    redirect('?page=admin_labs');
}

// Admin lab delete
if ($page === 'admin_lab_delete' && isset($_GET['id'])) {
    $user = current_user(); if (!$user) redirect('?page=login'); if ($user['role_name']!=='admin') { echo 'Denied'; exit; }
    $id = (int)$_GET['id'];
    $pdo->prepare('DELETE FROM labs WHERE id = ?')->execute([$id]);
    $_SESSION['flash']='Lab removed.'; redirect('?page=admin_labs');
}

// Admin lab edit form
if ($page === 'admin_lab_edit' && isset($_GET['id'])) {
    $user = current_user(); if (!$user) redirect('?page=login'); if ($user['role_name']!=='admin') { echo 'Denied'; exit; }
    $id = (int)$_GET['id'];
    $lab = $pdo->prepare('SELECT * FROM labs WHERE id = ?'); $lab->execute([$id]); $lab = $lab->fetch();
    view('admin/lab_edit',['lab'=>$lab]); exit;
}

// Admin lab update
if ($page === 'admin_lab_update' && $_SERVER['REQUEST_METHOD']==='POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if ($user['role_name']!=='admin') { echo 'Denied'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? ''); $location = trim($_POST['location'] ?? ''); $description = trim($_POST['description'] ?? '');
    $image_b64 = null;
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $raw = file_get_contents($_FILES['image']['tmp_name']); $mime = mime_content_type($_FILES['image']['tmp_name']) ?: 'image/png';
        $image_b64 = 'data:' . $mime . ';base64,' . base64_encode($raw);
    }
    if ($id) {
        if ($image_b64) {
            $pdo->prepare('UPDATE labs SET name=?,location=?,description=?,image_base64=? WHERE id=?')->execute([$name,$location,$description,$image_b64,$id]);
        } else {
            $pdo->prepare('UPDATE labs SET name=?,location=?,description=? WHERE id=?')->execute([$name,$location,$description,$id]);
        }
    }
    $_SESSION['flash']='Lab updated.'; redirect('?page=admin_labs');
}

// Admin equipment management
if ($page === 'admin_equipment') {
    $user = current_user();
    if (!$user) redirect('?page=login');
    if (!in_array($user['role_name'] ?? '', ['admin', 'lab_manager'])) { echo 'Access denied'; exit; }
    $labs = $pdo->query('SELECT id, name FROM labs ORDER BY name')->fetchAll();
    $selectedLab = isset($_GET['lab_id']) ? (int)$_GET['lab_id'] : 0;
    if ($selectedLab) {
        $equipment = $pdo->prepare('SELECT e.*, l.name as lab_name FROM equipment e JOIN labs l ON e.lab_id=l.id WHERE e.lab_id=? ORDER BY e.name');
        $equipment->execute([$selectedLab]);
        $equipment = $equipment->fetchAll();
    } else {
        $equipment = $pdo->query('SELECT e.*, l.name as lab_name FROM equipment e JOIN labs l ON e.lab_id=l.id ORDER BY l.name, e.name')->fetchAll();
    }
    view('admin/equipment', ['labs'=>$labs,'equipment'=>$equipment,'selectedLab'=>$selectedLab]);
    exit;
}

if ($page === 'admin_equipment_action' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (!in_array($user['role_name'] ?? '', ['admin', 'lab_manager'])) { echo 'Denied'; exit; }
    $lab_id = (int)($_POST['lab_id'] ?? 0);
    $equipment_id = trim($_POST['equipment_id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $asset = $equipment_id ?: trim($_POST['asset_tag'] ?? null);
    $status = $_POST['status'] ?? 'available';
    if (!$lab_id || !$name) { $_SESSION['flash']='Lab and name are required.'; redirect('?page=admin_equipment'); }
    $ins = $pdo->prepare('INSERT INTO equipment (lab_id,name,category,asset_tag,status,condition_note) VALUES (?,?,?,?,?,?)');
    $ins->execute([$lab_id,$name,'', $asset, $status, $desc]);
    $_SESSION['flash']='Equipment added.';
    redirect('?page=admin_equipment&lab_id='.$lab_id);
}

// Admin equipment edit form
if ($page === 'admin_equipment_edit' && isset($_GET['id'])) {
    $user = current_user(); if (!$user) redirect('?page=login'); if (!in_array($user['role_name'] ?? '', ['admin', 'lab_manager'])) { echo 'Denied'; exit; }
    $id = (int)$_GET['id'];
    $eq = $pdo->prepare('SELECT * FROM equipment WHERE id = ?'); $eq->execute([$id]); $eq = $eq->fetch();
    $labs = $pdo->query('SELECT id,name FROM labs ORDER BY name')->fetchAll();
    view('admin/equipment_edit',['equip'=>$eq,'labs'=>$labs]); exit;
}

// Admin equipment update
if ($page === 'admin_equipment_update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if (!in_array($user['role_name'] ?? '', ['admin', 'lab_manager'])) { echo 'Denied'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    $lab_id = (int)($_POST['lab_id'] ?? 0);
    $name = trim($_POST['name'] ?? ''); $desc = trim($_POST['description'] ?? ''); $status = $_POST['status'] ?? 'available';
    if ($id) {
        $pdo->prepare('UPDATE equipment SET lab_id=?, name=?, condition_note=?, status=? WHERE id=?')->execute([$lab_id,$name,$desc,$status,$id]);
        $_SESSION['flash']='Equipment updated.';
    }
    redirect('?page=admin_equipment');
}


if ($page === 'admin_equipment_delete' && isset($_GET['id'])) {
    $user = current_user(); if (!$user) redirect('?page=login'); if (!in_array($user['role_name'] ?? '', ['admin', 'lab_manager'])) { echo 'Denied'; exit; }
    $id = (int)$_GET['id'];
    $pdo->prepare('DELETE FROM equipment WHERE id = ?')->execute([$id]);
    $_SESSION['flash']='Equipment removed.'; redirect('?page=admin_equipment');
}

// Seed equipment from proposal (admin only)
if ($page === 'seed_equipment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = current_user(); if (!$user) redirect('?page=login'); if ($user['role_name'] !== 'admin') { echo 'Denied'; exit; }
    // Equipment lists (short names and descriptions) - trimmed for brevity
    $proposal = [
        'Electronics Lab' => [
            ['Oscilloscope','Signal observation'],['Function generator','Signal generation'],['Digital Multimeter','Voltage/Current/Resistance measurement'],['Breadboard','Circuit Prototyping'],['Arduino Board','Microcontroller programming'],['Raspberry Pi','Embedded systems development'],['Soldering Iron','Soldering components'],['Power Supply Unit','Voltage source for circuit'],['Logic Analyzer','Digital signal testing'],['Capacitors & Resistors kit','Basic circuit components'],['Wire Strippers','For stripping wires'],['Jumper Wires','For connections on breadboards'],['ICs','Circuit chips'],['Transistors','Circuit components'],['LEDs','Indicator Lights'],['PCB Board','Printed circuit board'],['Signal Probes','For oscilloscope connections'],['Safety Goggles','Safety in handling electronics'],['Multicore Cables','Circuit Wiring'],['De-soldering Pump','For removing solder']
        ],
        'Chemistry Lab' => [
            ['Beaker','Liquid holding/mixing'],['Test tube','Chemical reactions'],['Test tube rack','Holding test tubes'],['Burette','Titration equipment'],['Pipette','Measuring liquids'],['Conical flask','Chemical reactions'],['Funnel','Pouring liquids'],['Measuring Cylinder','Measuring volume'],['Hot plate','Heating chemicals'],['Bunsen burner','Heating source'],['Glass rod','Stirring liquids'],['Safety Goggles','Eye protection'],['Gloves','Hands protection'],['Clamp stand','Holds glassware in place during experiments'],['Fume hood','Ventilation for chemicals'],['pH meter','Acidity measurement'],['Dropper','Small quantity transfer'],['Crucible Tongs','Holding hot items'],['Evaporating Dish','Evaporation of solutions to obtain solids'],['Chemical reagents','Used in experiments']
        ],
        'Physics Lab' => [
            ['Vernier Calliper','Length measurement'],['Screw gauge','Small object measurement'],['Stopwatch','Time measurement'],['Pendulum bob','Simple harmonic motion'],['Light box','Light experiment'],['Lens & mirror set','Optics experiments'],['Prism','Light dispersion'],['Telescope','Astronomy experiments'],['Resistor coil','Ohm’s law experiments'],['Ammeter','Current measurement'],['Voltmeter','Voltage measurement'],['Galvanometer','Electrical current detection'],['Rheostat','Resistance adjustment'],['Inclined plane','Motion experiments'],['Ticker timer','Motion tracking'],['Spring balance','Force measurement'],['Compass','Magnetic field direction'],['Bar magnet','Magnetism'],['Circuit wires','Electrical circuits'],['Multimeter','Electric readings']
        ],
        'Civil Engineering Lab' => [
            ['Total station','Surveying and mapping'],['Auto level','Used for elevation measurement'],['Theodolite','Measures angles in surveying'],['Measuring tape','Distance measurement'],['Surveying chain','Traditional land measurement'],['Plumb bob','Checking vertical alignment'],['Spirit level','Ensures surfaces are level'],['Hand auger','Collecting soil samples'],['Soil core sampler','Extract intact soil cores from ground'],['CBR Mould & Hammer','Field soil compaction testing'],['Sieves & sieve shaker','Particle size analysis'],['Curing tank','Concrete sample curing'],['Slump cone set','Testing the workability of concrete in field'],['Cylindrical moulds','Casting concrete test specimens'],['Stopwatch','Time based testing'],['Thermometer','Measuring temperature'],['Sample bags & tags','Storing and labelling soil'],['Field data log sheet','Provided with tools for experiments'],['Personal safety gear','Safety equipment'],['Drafting tools','Civil drawing instruments']
        ],
        'Multimedia Lab' => [
            ['DSLR Camera','Photography and videography'],['Green Screen kit','Chroma key effects'],['Studio Lighting setup','Proper lighting for video shoots'],['Tripod','Camera stability stand'],['Audio recording mic','High quality audio input'],['Headphones','Audio monitoring'],['External hard Drive','File backup and storage'],['3D printer','Prototyping and modelling'],['Projector','Demo and presentation setup'],['VR headset','Immersive animation experience'],['Storyboarding tools','Planning animations'],['USB Mic','Voice-over recording'],['Animation books & guides','Reference and learning'],['Ring Light','Portable lighting'],['Drawing tablet','Digital sketching'],['Gimbal Stabilizer','Stabilizing camera motion in video shoots'],['Bluetooth speaker','Testing sound output'],['Lens kit','Swappable lenses for DSLR'],['Memory cards','Transferring media files'],['Backdrop cloths & props','Creative and scripted shoots']
        ],
        'Mechanical Lab' => [
            ['Lathe machine','Shaping metals'],['Milling machine','Material removal'],['Drilling machine','Hole making'],['CNC machine','Computer controlled machine'],['Torque wrench','Tightening bolts'],['Micrometer','Internal & external measurement'],['Welding machine','Metal joining'],['Hydraulic jack','Lifting heavy loads'],['Pneumatic kit','Air pressure experiments'],['Gearbox','Transmission study'],['Bearings','Rotational elements'],['Anvil','Forging surface'],['Tool kit','Hand tools'],['Sandpaper','Surface smoothing'],['Chisel set','Shaping materials'],['Hammer','Basic mechanical tasks'],['Feeler gauge','Measures gap width'],['Allen key set','Hex socket screw'],['Spanner set','Tightening/loosening nuts and bolts'],['Hacksaw','Cutting metal or plastics']
        ]
    ];

    $insert = $pdo->prepare('INSERT INTO equipment (lab_id,name,condition_note,status) VALUES (?,?,?,?)');
    foreach ($proposal as $labName => $items) {
        $labId = $pdo->prepare('SELECT id FROM labs WHERE name = ?'); $labId->execute([$labName]); $labId = $labId->fetchColumn();
        if (!$labId) continue;
        foreach ($items as $it) {
            $insert->execute([$labId, $it[0], $it[1], 'available']);
        }
    }
    $_SESSION['flash']='Equipment seeded from proposal.'; redirect('?page=admin_equipment');
}

switch ($page) {
    case 'home':
        view('home');
        break;
    case 'login':
        view('auth/login');
        break;
    case 'register':
        view('auth/register');
        break;
    default:
        http_response_code(404);
        echo "Page not found";
}

