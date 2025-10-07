<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 300px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; } ?>
  
  <div style="max-width:800px;margin:0 auto">
    
    <?php if (!empty($_SESSION['flash'])): ?>
      <div style="background:#e6ffed;padding:12px 20px;border-radius:12px;margin-bottom:20px;color:#155724;font-weight:600;text-align:center"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    
    <!-- Add Rule Card -->
    <div style="background:#fff;padding:48px;border-radius:32px;box-shadow:0 6px 12px rgba(0,0,0,0.08);margin-bottom:40px">
      
      <h1 style="text-align:center;color:#2d3b7a;font-size:32px;margin:0 0 32px;font-weight:800">Rules & Guidelines</h1>
      
      <form method="POST" action="?page=admin_rule_action">
        
        <!-- Lab Selection -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Lab</label>
          <select name="lab_id" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">All Labs (General Rules)</option>
            <?php foreach ($labs as $lab): ?>
              <option value="<?php echo $lab['id'] ?>"><?php echo htmlspecialchars($lab['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <!-- Title -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Title</label>
          <input type="text" name="title" required placeholder="e.g., Safety Guidelines" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Body -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Body</label>
          <textarea name="body" rows="6" required placeholder="Enter the rule details..." style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff;resize:vertical;font-family:inherit"></textarea>
        </div>
        
        <!-- Submit Button -->
        <div style="text-align:center">
          <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Save</button>
        </div>
        
      </form>
      
    </div>
    
    <!-- Existing Rules -->
    <div style="background:#fff;padding:40px;border-radius:24px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h2 style="color:#2d3b7a;font-size:24px;margin:0 0 24px;font-weight:700">Existing Rules</h2>
      
      <?php if (empty($rules)): ?>
        <p style="text-align:center;color:#999;padding:40px">No rules added yet.</p>
      <?php else: ?>
        
        <div style="display:flex;flex-direction:column;gap:16px">
          <?php foreach ($rules as $rule): ?>
            <div style="border:2px solid #e9ecef;border-radius:12px;padding:20px;background:#f8f9fa">
              
              <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px">
                <div>
                  <h3 style="margin:0 0 4px;color:#2d3b7a;font-size:18px;font-weight:700"><?php echo htmlspecialchars($rule['title']) ?></h3>
                  <span style="display:inline-block;background:#168890;color:#fff;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600">
                    <?php echo $rule['lab_id'] ? htmlspecialchars($rule['lab_name']) : 'All Labs' ?>
                  </span>
                </div>
                <div style="display:flex;gap:8px">
                  <a href="?page=admin_rule_edit&id=<?php echo $rule['id'] ?>" style="color:#1976d2;text-decoration:none;font-weight:600;font-size:14px">Edit</a>
                  <form method="POST" action="?page=admin_rule_delete" style="display:inline" onsubmit="return confirm('Delete this rule?')">
                    <input type="hidden" name="rule_id" value="<?php echo $rule['id'] ?>">
                    <button type="submit" style="background:none;border:none;color:#dc3545;cursor:pointer;font-weight:600;font-size:14px;padding:0">Delete</button>
                  </form>
                </div>
              </div>
              
              <p style="margin:0;color:#666;line-height:1.6;white-space:pre-wrap"><?php echo htmlspecialchars($rule['body']) ?></p>
              
            </div>
          <?php endforeach; ?>
        </div>
        
      <?php endif; ?>
      
    </div>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
