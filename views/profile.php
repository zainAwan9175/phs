<?php require __DIR__ . '/header.php'; ?>
<div class="container">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user) { header('Location:?page=login'); exit; } ?>
  <div class="profile-page">
    <div class="profile-header">
      <h1>My Profile</h1>
      <a class="logout-top" href="javascript:void(0)" onclick="showLogoutModal()">Log out</a>
    </div>
    <form method="post" action="?page=profile_action">
      <div class="profile-grid">
        <div>
          <label>First Name</label>
          <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? '') ?>" />
        </div>
        <div>
          <label>Last Name</label>
          <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? '') ?>" />
        </div>
        <div>
          <label>Email address</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? '') ?>" />
        </div>
        <div>
          <label>Phone number</label>
          <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? '') ?>" />
        </div>
        <div>
          <label>Password</label>
          <input type="password" name="password" placeholder="Leave blank to keep current" />
        </div>
        <div>
          <label>Confirm password</label>
          <input type="password" name="confirm_password" />
        </div>
      </div>
      <div class="profile-actions">
        <button class="btn save" type="submit">Save Changes</button>
        <?php if (!empty($user) && ($user['role_name'] ?? '') === 'admin'): ?>
          <div style="height:12px"></div>
          <a class="btn admin-btn" href="?page=admin_home">Admin</a>
        <?php endif; ?>
        <?php if (!empty($user) && ($user['role_name'] ?? '') === 'student'): ?>
          <div style="height:12px"></div>
          <a class="btn admin-btn" href="?page=student_dashboard" style="background:#168890">Student Dashboard</a>
        <?php endif; ?>
        <?php if (!empty($user) && ($user['role_name'] ?? '') === 'lab_assistant'): ?>
          <div style="height:12px"></div>
          <a class="btn admin-btn" href="?page=assistant_dashboard" style="background:#6c5ce7">Assistant Dashboard</a>
        <?php endif; ?>
        <?php if (!empty($user) && ($user['role_name'] ?? '') === 'lab_manager'): ?>
          <div style="height:12px"></div>
          <a class="btn admin-btn" href="?page=manager_dashboard" style="background:#49BBBD">Manager Dashboard</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center">
  <div style="background:#dff3f3;padding:48px;border-radius:32px;max-width:420px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.3)">
    <h2 style="color:#2d3b7a;font-size:26px;margin:0 0 16px;font-weight:700">Are you sure you want to Log Out?</h2>
    <div style="display:flex;gap:16px;justify-content:center;margin-top:32px">
      <button onclick="confirmLogout()" style="background:#2d3b7a;color:#fff;padding:12px 32px;border-radius:24px;border:none;cursor:pointer;font-size:16px;font-weight:700">Log out</button>
      <button onclick="closeLogoutModal()" style="background:#fff;color:#2d3b7a;padding:12px 32px;border-radius:24px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.1)">Cancel</button>
    </div>
  </div>
</div>

<script src="assets/logout.js"></script>
<?php require __DIR__ . '/footer.php'; ?>