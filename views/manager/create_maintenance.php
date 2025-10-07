<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 300px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'lab_manager') { echo 'Access denied'; exit; } ?>
  
  <div style="max-width:700px;margin:0 auto">
    
    <?php if (!empty($_SESSION['flash'])): ?>
      <div style="background:#d4edda;padding:12px 20px;border-radius:12px;margin-bottom:20px;color:#155724;font-weight:600;text-align:center"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    
    <!-- Create Maintenance Task Card -->
    <div style="background:#fff;padding:48px;border-radius:32px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h1 style="text-align:center;color:#2d3b7a;font-size:32px;margin:0 0 32px;font-weight:800">Create maintenance task</h1>
      
      <form method="POST" action="?page=manager_create_maintenance_action">
        
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
        
        <!-- Task Type -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Task type</label>
          <select name="task_type" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select task type</option>
            <option value="Repair">Repair</option>
            <option value="Inspection">Inspection</option>
            <option value="Calibration">Calibration</option>
            <option value="Cleaning">Cleaning</option>
            <option value="Replacement">Replacement</option>
            <option value="Other">Other</option>
          </select>
        </div>
        
        <!-- Summary -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Summary</label>
          <textarea name="summary" rows="3" required placeholder="Brief description of the maintenance task" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff;resize:vertical;font-family:inherit"></textarea>
        </div>
        
        <!-- Date -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Date</label>
          <input type="date" name="scheduled_date" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
        </div>
        
        <!-- Submit Button -->
        <div style="text-align:center">
          <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Create</button>
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
  
  // Fetch all equipment for this lab
  fetch('?page=api_get_equipment&lab_id=' + labId + '&status=all')
    .then(res => res.json())
    .then(data => {
      if (data.success && data.equipment.length > 0) {
        equipmentSelect.innerHTML = '<option value="">Select Equipment</option>';
        data.equipment.forEach(eq => {
          const option = document.createElement('option');
          option.value = eq.id;
          option.textContent = eq.name + (eq.asset_tag ? ' (' + eq.asset_tag + ')' : '') + ' [' + eq.status + ']';
          equipmentSelect.appendChild(option);
        });
      } else {
        equipmentSelect.innerHTML = '<option value="">No equipment in this lab</option>';
      }
    })
    .catch(err => {
      equipmentSelect.innerHTML = '<option value="">Error loading equipment</option>';
    });
});
</script>

<?php require __DIR__ . '/../footer.php'; ?>
