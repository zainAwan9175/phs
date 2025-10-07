<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 300px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; } ?>
  
  <div style="max-width:700px;margin:0 auto">
    
    <?php if (!empty($_SESSION['flash'])): ?>
      <div style="background:#d4edda;padding:12px 20px;border-radius:12px;margin-bottom:20px;color:#155724;font-weight:600;text-align:center"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    
    <!-- Create Booking Card -->
    <div style="background:#fff;padding:48px;border-radius:32px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h1 style="text-align:center;color:#2d3b7a;font-size:32px;margin:0 0 8px;font-weight:800">Create Booking</h1>
      <p style="text-align:center;color:#666;margin:0 0 32px">Book equipment for a user</p>
      
      <form method="POST" action="?page=manager_create_booking_action">
        
        <!-- User Selection -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">User</label>
          <select name="user_id" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select User</option>
            <?php
            $users = $pdo->query('SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.status = "active" ORDER BY u.first_name')->fetchAll();
            foreach ($users as $u): ?>
              <option value="<?php echo $u['id'] ?>"><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name'] . ' (' . $u['role_name'] . ')') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <!-- Lab Selection -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Lab</label>
          <select name="lab_id" id="lab_select" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select Lab</option>
            <?php
            $labs = $pdo->query('SELECT * FROM labs ORDER BY name')->fetchAll();
            foreach ($labs as $lab): ?>
              <option value="<?php echo $lab['id'] ?>"><?php echo htmlspecialchars($lab['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <!-- Equipment Selection -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Equipment</label>
          <select name="equipment_id" id="equipment_select" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select Lab First</option>
          </select>
        </div>
        
        <!-- Date Range -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Start Date</label>
            <input type="date" name="start_date" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
          </div>
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">End Date</label>
            <input type="date" name="end_date" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
          </div>
        </div>
        
        <!-- Purpose -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Purpose</label>
          <textarea name="purpose" rows="3" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff;resize:vertical;font-family:inherit"></textarea>
        </div>
        
        <!-- Buttons -->
        <div style="display:flex;gap:12px;justify-content:center">
          <a href="?page=manager_dashboard" style="background:#6c757d;color:#fff;padding:14px 40px;border-radius:28px;text-decoration:none;font-size:16px;font-weight:700;display:inline-block">Cancel</a>
          <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 40px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Create Booking</button>
        </div>
        
      </form>
      
    </div>
    
  </div>
  
</div>

<script>
// Dynamic equipment loading based on selected lab
document.getElementById('lab_select')?.addEventListener('change', function() {
  const labId = this.value;
  const equipmentSelect = document.getElementById('equipment_select');
  
  if (!labId) {
    equipmentSelect.innerHTML = '<option value="">Select Lab First</option>';
    return;
  }
  
  // Fetch available equipment for this lab
  fetch('?page=api_get_equipment&lab_id=' + labId + '&status=available')
    .then(res => res.json())
    .then(data => {
      if (data.success && data.equipment.length > 0) {
        equipmentSelect.innerHTML = '<option value="">Select Equipment</option>';
        data.equipment.forEach(eq => {
          const option = document.createElement('option');
          option.value = eq.id;
          option.textContent = eq.name + (eq.asset_tag ? ' (' + eq.asset_tag + ')' : '');
          equipmentSelect.appendChild(option);
        });
      } else {
        equipmentSelect.innerHTML = '<option value="">No available equipment in this lab</option>';
      }
    })
    .catch(err => {
      equipmentSelect.innerHTML = '<option value="">Error loading equipment</option>';
    });
});
</script>

<?php require __DIR__ . '/../footer.php'; ?>
