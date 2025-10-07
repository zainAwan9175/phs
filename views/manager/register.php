<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 300px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; } ?>
  
  <div style="max-width:700px;margin:0 auto">
    
    <?php if (!empty($_SESSION['flash'])): ?>
      <div style="background:#d4edda;padding:12px 20px;border-radius:12px;margin-bottom:20px;color:#155724;font-weight:600;text-align:center"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    
    <!-- Register User Card -->
    <div style="background:#fff;padding:48px;border-radius:32px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h1 style="text-align:center;color:#2d3b7a;font-size:32px;margin:0 0 8px;font-weight:800">Register User</h1>
      <p style="text-align:center;color:#666;margin:0 0 32px">Create new user account</p>
      
      <form method="POST" action="?page=manager_register_action">
        
        <!-- First Name -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">First Name</label>
          <input type="text" name="first_name" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Last Name -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Last Name</label>
          <input type="text" name="last_name" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Email -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Email</label>
          <input type="email" name="email" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Phone -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Phone</label>
          <input type="tel" name="phone" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Role -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Role</label>
          <select name="role_id" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select Role</option>
            <?php
            $roles = $pdo->query('SELECT * FROM roles ORDER BY name')->fetchAll();
            foreach ($roles as $role): ?>
              <option value="<?php echo $role['id'] ?>"><?php echo htmlspecialchars($role['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <!-- Password -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Password</label>
          <input type="password" name="password" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Confirm Password -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Confirm Password</label>
          <input type="password" name="confirm_password" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Buttons -->
        <div style="display:flex;gap:12px;justify-content:center">
          <a href="?page=manager_dashboard" style="background:#6c757d;color:#fff;padding:14px 40px;border-radius:28px;text-decoration:none;font-size:16px;font-weight:700;display:inline-block">Cancel</a>
          <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 40px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Register User</button>
        </div>
        
      </form>
      
    </div>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
