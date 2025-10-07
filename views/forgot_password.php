<?php require __DIR__ . '/header.php'; ?>
<div style="background:#49BBBD;min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div class="auth-card" style="max-width:520px;padding:48px 40px">
    <div style="text-align:center;margin-bottom:32px">
      <div style="width:80px;height:80px;margin:0 auto 20px;background:#2d3b7a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px">
        🔐
      </div>
      <h2 style="color:#2d3b7a;font-size:28px;margin:0 0 8px;font-weight:700">Forgot Password?</h2>
      <p style="color:#666;margin:0;font-size:14px;line-height:1.6">No worries! Enter your email address below and we'll send you instructions to reset your password.</p>
    </div>
    
    <?php if (!empty($_SESSION['error'])): ?>
      <div style="background:#fee2e2;color:#991b1b;padding:14px 16px;border-radius:12px;margin-bottom:20px;text-align:left;border-left:4px solid #dc2626">
        <strong>Error:</strong> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['flash'])): ?>
      <div style="background:#d1fae5;color:#065f46;padding:14px 16px;border-radius:12px;margin-bottom:20px;text-align:left;border-left:4px solid #10b981">
        <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" action="?page=forgot_password_action" id="forgotPasswordForm">
      <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Email Address</label>
      <input type="email" name="email" id="email" required placeholder="your.email@example.com" 
             style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;margin-bottom:24px;transition:border-color 0.2s"
             onfocus="this.style.borderColor='#2d3b7a'" onblur="this.style.borderColor='#e5e7eb'" />
      
      <button type="submit" id="submitBtn" style="width:100%;background:#2d3b7a;color:#fff;padding:16px;border-radius:12px;border:none;cursor:pointer;font-size:16px;font-weight:700;transition:all 0.2s;box-shadow:0 4px 0 rgba(0,0,0,0.1)">
        Send Reset Instructions
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
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
  const btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.innerHTML = 'Sending...';
  btn.style.opacity = '0.6';
});
</script>

<?php require __DIR__ . '/footer.php'; ?>
