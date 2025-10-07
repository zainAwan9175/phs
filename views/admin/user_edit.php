<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <div style="max-width:580px;margin:48px auto;background:#fff;padding:40px;border-radius:28px;box-shadow:0 12px 0 rgba(0,0,0,0.04)">
    <h1 style="text-align:center;margin:0 0 32px;font-size:32px;color:#2d3b7a">User management</h1>
    
    <form method="post" action="?page=admin_user_update">
      <input type="hidden" name="id" value="<?php echo $editUser['id'] ?>" />
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Name</label>
        <input type="text" readonly value="<?php echo htmlspecialchars($editUser['first_name'].' '.$editUser['last_name']) ?>" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" />
      </div>
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Email</label>
        <input type="text" readonly value="<?php echo htmlspecialchars($editUser['email']) ?>" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" />
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Role</label>
          <select name="role_id" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8">
            <option value="">Select Role</option>
            <?php foreach($roles as $r): ?>
              <option value="<?php echo $r['id'] ?>" <?php echo ($r['id']==$editUser['role_id']?' selected':'') ?>>
                <?php echo htmlspecialchars(ucwords(str_replace('_',' ',$r['name']))) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Student/Staff ID</label>
          <input type="text" readonly value="<?php echo htmlspecialchars($editUser['student_or_staff_id'] ?? 'N/A') ?>" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" />
        </div>
      </div>
      
      <div style="margin-bottom:24px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Status</label>
        <select name="status" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8">
          <option value="active" <?php echo ($editUser['status']=='active'?' selected':'') ?>>Active</option>
          <option value="inactive" <?php echo ($editUser['status']=='inactive'?' selected':'') ?>>Inactive</option>
          <option value="suspended" <?php echo ($editUser['status']=='suspended'?' selected':'') ?>>Suspended</option>
        </select>
      </div>
      
      <div style="text-align:center">
        <button type="submit" class="btn" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:600">Update</button>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
