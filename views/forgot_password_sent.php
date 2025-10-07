<?php require __DIR__ . '/header.php'; ?>
<div style="background:#49BBBD;min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div class="auth-card" style="max-width:560px;padding:48px 40px;text-align:center">
    <div style="width:100px;height:100px;margin:0 auto 24px;background:#10b981;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:48px;box-shadow:0 8px 16px rgba(16,185,129,0.2)">
      ✉️
    </div>
    
    <h2 style="color:#2d3b7a;font-size:28px;margin:0 0 16px;font-weight:700">Check Your Email</h2>
    
    <p style="color:#4b5563;font-size:16px;line-height:1.6;margin:0 0 24px">
      We've sent password reset instructions to:<br/>
      <strong style="color:#2d3b7a"><?php echo htmlspecialchars($_SESSION['reset_email'] ?? 'your email'); unset($_SESSION['reset_email']); ?></strong>
    </p>
    
    <div style="background:#f3f4f6;padding:20px;border-radius:12px;margin-bottom:32px;text-align:left">
      <p style="margin:0 0 12px;font-weight:600;color:#374151;font-size:15px">📌 Next Steps:</p>
      <ol style="margin:0;padding-left:20px;font-size:14px;color:#6b7280;line-height:1.8">
        <li>Check your email inbox</li>
        <li>Click the reset link in the email</li>
        <li>Create your new password</li>
        <li>Sign in with your new password</li>
      </ol>
    </div>
    
    <div style="background:#fef3c7;padding:16px;border-radius:10px;margin-bottom:24px;text-align:left;border-left:4px solid #f59e0b">
      <p style="margin:0;font-size:13px;color:#92400e">
        <strong>⚠️ Didn't receive the email?</strong><br/>
        Check your spam folder or wait a few minutes. The link expires in 1 hour.
      </p>
    </div>
    
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="?page=forgot_password" style="display:inline-block;padding:12px 24px;border-radius:10px;border:2px solid #2d3b7a;color:#2d3b7a;text-decoration:none;font-weight:600;transition:all 0.2s">
        Resend Email
      </a>
      <a href="?page=login" style="display:inline-block;background:#2d3b7a;color:#fff;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:600;transition:all 0.2s;box-shadow:0 4px 0 rgba(0,0,0,0.1)">
        Back to Sign In
      </a>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
