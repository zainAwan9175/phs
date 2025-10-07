<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 300px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; } ?>
  
  <div style="max-width:800px;margin:0 auto">
    
    <!-- Edit Rule Card -->
    <div style="background:#fff;padding:48px;border-radius:32px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h1 style="text-align:center;color:#2d3b7a;font-size:32px;margin:0 0 32px;font-weight:800">Edit Rule</h1>
      
      <form method="POST" action="?page=admin_rule_action">
        <input type="hidden" name="rule_id" value="<?php echo $rule['id'] ?>">
        
        <!-- Lab Selection -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Lab</label>
          <select name="lab_id" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="" <?php echo !$rule['lab_id'] ? 'selected' : '' ?>>All Labs (General Rules)</option>
            <?php foreach ($labs as $lab): ?>
              <option value="<?php echo $lab['id'] ?>" <?php echo $rule['lab_id'] == $lab['id'] ? 'selected' : '' ?>><?php echo htmlspecialchars($lab['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <!-- Title -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Title</label>
          <input type="text" name="title" required value="<?php echo htmlspecialchars($rule['title']) ?>" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Body -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Body</label>
          <textarea name="body" rows="6" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff;resize:vertical;font-family:inherit"><?php echo htmlspecialchars($rule['body']) ?></textarea>
        </div>
        
        <!-- Buttons -->
        <div style="display:flex;gap:12px;justify-content:center">
          <a href="?page=admin_rules" style="background:#6c757d;color:#fff;padding:14px 48px;border-radius:28px;border:none;text-decoration:none;font-size:16px;font-weight:700;display:inline-block">Cancel</a>
          <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Update</button>
        </div>
        
      </form>
      
    </div>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
