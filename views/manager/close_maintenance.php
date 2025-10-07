<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 300px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; } ?>
  
  <div style="max-width:900px;margin:0 auto">
    
    <h1 style="text-align:center;color:#2d3b7a;font-size:32px;margin:0 0 32px;font-weight:800">Close maintenance task</h1>
    
    <!-- Close Maintenance Form -->
    <div style="background:#fff;padding:40px;border-radius:24px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <?php
      $stmt = $pdo->query('
        SELECT m.* 
        FROM maintenance_tasks m 
        WHERE m.status = "open" 
        ORDER BY m.created_at DESC
      ');
      $tasks = $stmt->fetchAll();
      
      if (empty($tasks)): ?>
        <p style="text-align:center;color:#999;padding:40px">No open maintenance tasks to close.</p>
      <?php else: ?>
      
      <form method="POST" action="?page=manager_close_maintenance_action">
        
        <!-- Equipment Selection -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Equipment</label>
          <select name="task_id" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select equipment to close maintenance</option>
            <?php foreach ($tasks as $task): ?>
              <option value="<?php echo $task['id'] ?>"><?php echo htmlspecialchars($task['equipment_name']) ?> - <?php echo htmlspecialchars($task['task_type']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <!-- Status -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Status</label>
          <select name="status" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select status</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        
        <!-- Cost -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Cost</label>
          <input type="number" name="cost" step="0.01" placeholder="Enter cost (optional)" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Date -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Date</label>
          <input type="date" name="completion_date" required value="<?php echo date('Y-m-d') ?>" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Submit Button -->
        <div style="text-align:center">
          <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Close</button>
        </div>
        
      </form>
      
      <?php endif; ?>
      
    </div>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
