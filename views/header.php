<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Smart Lab</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>h1{font-family: 'Baloo 2', cursive;color:#223}</style>
</head>
<body>
  <?php 
    $currentPage = $_GET['page'] ?? 'home'; 
    $loggedUser = function_exists('current_user') ? current_user() : null;
  ?>
  <header class="site-header">
    <div class="site-left">
      <div class="logo-diamond"></div>
      <div class="brand">Smart lab</div>
    </div>
    <nav class="site-nav">
      <a href="?page=home" <?php echo ($currentPage === 'home' ? 'class="active"' : '') ?>>Home</a>
      <a href="?page=labs" <?php echo ($currentPage === 'labs' ? 'class="active"' : '') ?>>Labs</a>
      <?php if (!empty($loggedUser) && ($loggedUser['role_name'] ?? '') === 'student'): ?>
        <a href="?page=bookings" <?php echo ($currentPage === 'bookings' ? 'class="active"' : '') ?>>Bookings</a>
        <a href="?page=notifications" <?php echo ($currentPage === 'notifications' ? 'class="active"' : '') ?>>Notifications</a>
      <?php endif; ?>
      <?php if (!empty($loggedUser) && in_array($loggedUser['role_name'] ?? '', ['lab_assistant', 'lab_manager'])): ?>
        <a href="?page=approvals" <?php echo ($currentPage === 'approvals' ? 'class="active"' : '') ?>>Approvals</a>
      <?php endif; ?>
      <?php if (!empty($loggedUser) && ($loggedUser['role_name'] ?? '') === 'admin'): ?>
        <a href="?page=admin_home" <?php echo ($currentPage === 'admin_home' ? 'class="active"' : '') ?>>Admin</a>
      <?php endif; ?>
      <a href="?page=contact" <?php echo ($currentPage === 'contact' ? 'class="active"' : '') ?>>Contact</a>
    </nav>
    <div class="site-right">
      <?php if ($loggedUser): ?>
        <a class="profile-button" href="?page=profile"><span class="pf-icon">👤</span> <span class="pf-text">My profile</span></a>
      <?php else: ?>
        <a class="link" href="?page=login">Sign In</a>
        <a class="link" href="?page=register">Register</a>
      <?php endif; ?>
    </div>
  </header>
  <?php if (!empty($_SESSION['flash'])): ?>
    <div style="background:#fff3cd;padding:10px 16px;color:#856404;text-align:center"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>
  <main>
