<?php require __DIR__ . '/header.php'; ?>
<div style="background:#49BBBD;min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div class="auth-card" style="max-width:520px;padding:48px 40px">
    <div style="text-align:center;margin-bottom:32px">
      <div style="width:80px;height:80px;margin:0 auto 20px;background:#10b981;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px">
        🔑
      </div>
      <h2 style="color:#2d3b7a;font-size:28px;margin:0 0 8px;font-weight:700">Set New Password</h2>
      <p style="color:#666;margin:0;font-size:14px;line-height:1.6">Create a strong password with at least 8 characters.</p>
    </div>
    
    <?php if (!empty($_SESSION['flash'])): ?>
      <div style="background:#d1fae5;color:#065f46;padding:14px 16px;border-radius:12px;margin-bottom:20px;text-align:left;border-left:4px solid #10b981">
        <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
      </div>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['error'])): ?>
      <div style="background:#fee2e2;color:#991b1b;padding:14px 16px;border-radius:12px;margin-bottom:20px;text-align:left;border-left:4px solid #dc2626">
        <strong>Error:</strong> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" action="?page=reset_password_action" id="resetPasswordForm">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? '') ?>" />
      
      <div style="margin-bottom:20px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">New Password</label>
        <input type="password" name="password" id="password" required placeholder="Enter new password" minlength="8"
               style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;transition:border-color 0.2s"
               onfocus="this.style.borderColor='#2d3b7a'" onblur="this.style.borderColor='#e5e7eb'"
               oninput="checkPasswordStrength()" />
        <div id="passwordStrength" style="margin-top:8px;font-size:13px"></div>
      </div>
      
      <div style="margin-bottom:24px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm new password" minlength="8"
               style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;transition:border-color 0.2s"
               onfocus="this.style.borderColor='#2d3b7a'" onblur="this.style.borderColor='#e5e7eb'"
               oninput="checkPasswordMatch()" />
        <div id="passwordMatch" style="margin-top:8px;font-size:13px"></div>
      </div>
      
      <div style="background:#f3f4f6;padding:16px;border-radius:10px;margin-bottom:24px">
        <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#374151">Password requirements:</p>
        <ul style="margin:0;padding-left:20px;font-size:13px;color:#6b7280;line-height:1.8">
          <li>At least 8 characters long</li>
          <li>Mix of uppercase and lowercase letters</li>
          <li>Include numbers and special characters</li>
        </ul>
      </div>
      
      <button type="submit" id="submitBtn" style="width:100%;background:#2d3b7a;color:#fff;padding:16px;border-radius:12px;border:none;cursor:pointer;font-size:16px;font-weight:700;transition:all 0.2s;box-shadow:0 4px 0 rgba(0,0,0,0.1)">
        Reset Password
      </button>
      
      <div style="margin-top:24px;text-align:center;padding-top:20px;border-top:1px solid #e5e7eb">
        <a href="?page=login" style="color:#2d3b7a;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:6px">
          <span style="font-size:18px">←</span> Back to Sign In
        </a>
      </div>
    </form>
  </div>
</div>

<script>
function checkPasswordStrength() {
  const password = document.getElementById('password').value;
  const strengthDiv = document.getElementById('passwordStrength');
  
  if (password.length === 0) {
    strengthDiv.innerHTML = '';
    return;
  }
  
  let strength = 0;
  if (password.length >= 8) strength++;
  if (password.length >= 12) strength++;
  if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
  if (/\d/.test(password)) strength++;
  if (/[^a-zA-Z0-9]/.test(password)) strength++;
  
  const colors = ['#dc2626', '#f59e0b', '#10b981', '#059669'];
  const labels = ['Weak', 'Fair', 'Good', 'Strong'];
  
  const level = Math.min(strength - 1, 3);
  if (level >= 0) {
    strengthDiv.innerHTML = `<span style="color:${colors[level]};font-weight:600">Password strength: ${labels[level]}</span>`;
  }
}

function checkPasswordMatch() {
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm_password').value;
  const matchDiv = document.getElementById('passwordMatch');
  
  if (confirmPassword.length === 0) {
    matchDiv.innerHTML = '';
    return;
  }
  
  if (password === confirmPassword) {
    matchDiv.innerHTML = '<span style="color:#10b981;font-weight:600">✓ Passwords match</span>';
  } else {
    matchDiv.innerHTML = '<span style="color:#dc2626;font-weight:600">✗ Passwords do not match</span>';
  }
}

document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm_password').value;
  
  if (password !== confirmPassword) {
    e.preventDefault();
    alert('Passwords do not match. Please try again.');
    return;
  }
  
  if (password.length < 8) {
    e.preventDefault();
    alert('Password must be at least 8 characters long.');
    return;
  }
  
  const btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.innerHTML = 'Updating...';
  btn.style.opacity = '0.6';
});
</script>

<?php require __DIR__ . '/footer.php'; ?>
