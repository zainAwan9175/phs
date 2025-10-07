<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <div class="auth-card">
    <h2>Register</h2>
    <form method="post" action="?page=register_action">
      <div style="text-align:left;margin-top:8px">
        <label>Select user</label>
        <select name="role_select"><option value="student">Student</option></select>
      </div>
      <div style="display:flex;gap:8px;margin-top:12px">
        <input name="first_name" placeholder="First name" />
        <input name="last_name" placeholder="Last name" />
      </div>
      <div style="display:flex;gap:8px;margin-top:12px">
        <input name="student_id" placeholder="Student Id" />
        <input name="dob" placeholder="D.O.B" />
      </div>
      <div style="margin-top:12px;text-align:left">
        <label>Email Address</label>
        <input name="email" type="email" />
      </div>
      <div style="margin-top:12px;display:flex;gap:8px">
        <input name="password" type="password" placeholder="Password" />
        <input name="confirm_password" type="password" placeholder="Confirm Password" />
      </div>
      <div style="margin-top:18px">
        <button class="btn" type="submit">Register</button>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
