<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <div class="auth-card">
    <h2>Sign In</h2>
    <form method="post" action="?page=login_action">
      <div style="text-align:left;margin-top:12px">
        <label>Email address</label>
        <input name="email" type="email" />
      </div>
      <div style="text-align:left;margin-top:12px">
        <label>Password</label>
        <input name="password" type="password" />
      </div>
      <div style="text-align:right;margin-top:8px"><a href="?page=forgot_password">Forgot password?</a></div>
      <div style="margin-top:18px">
        <button class="btn" type="submit">Sign in</button>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
