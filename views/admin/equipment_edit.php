<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <div style="max-width:580px;margin:48px auto;background:#fff;padding:40px;border-radius:28px;box-shadow:0 12px 0 rgba(0,0,0,0.04)">
    <h1 style="text-align:center;margin:0 0 32px;font-size:32px;color:#2d3b7a">Edit Equipment</h1>
    
    <form method="post" action="?page=admin_equipment_update">
      <input type="hidden" name="id" value="<?php echo $equip['id'] ?>" />
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Lab</label>
        <select name="lab_id" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8">
          <?php foreach($labs as $l): ?>
            <option value="<?php echo $l['id'] ?>" <?php echo ($l['id']==$equip['lab_id']?' selected':'') ?>><?php echo htmlspecialchars($l['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Equipment Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($equip['name']) ?>" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" />
      </div>
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Description</label>
        <input type="text" name="description" value="<?php echo htmlspecialchars($equip['condition_note']) ?>" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" />
      </div>
      
      <div style="margin-bottom:24px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Status</label>
        <select name="status" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8">
          <option value="available" <?php echo $equip['status']=='available'?' selected':'' ?>>Available</option>
          <option value="in_use" <?php echo $equip['status']=='in_use'?' selected':'' ?>>In Use</option>
          <option value="maintenance" <?php echo $equip['status']=='maintenance'?' selected':'' ?>>Maintenance</option>
        </select>
      </div>
      
      <div style="text-align:center">
        <button type="submit" class="btn" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:600">Save</button>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
