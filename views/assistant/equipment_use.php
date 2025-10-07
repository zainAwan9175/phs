<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || !in_array($user['role_name'] ?? '', ['lab_assistant','admin'])) { echo 'Access denied'; exit; } ?>
  
  <div style="max-width:480px;margin:0 auto">
    
    <?php if (!empty($_SESSION['flash'])): ?>
      <div style="background:#e6ffed;padding:12px 20px;border-radius:12px;margin-bottom:20px;color:#155724;font-weight:600;text-align:center"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    
    <!-- Card -->
    <div style="background:#fff;padding:40px;border-radius:24px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h1 style="text-align:center;color:#2d3b7a;font-size:28px;margin:0 0 8px;font-weight:700">Equipment In Use</h1>
      <p style="text-align:center;color:#666;margin:0 0 32px;font-size:15px">Mark equipment as given to student</p>
      
      <form method="POST" action="?page=equipment_use_action">
        
        <!-- Select Lab -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Lab</label>
          <select name="lab_id" id="lab_select" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8">
            <option value="">Select Lab</option>
            <?php foreach ($labs as $lab): ?>
              <option value="<?php echo $lab['id'] ?>"><?php echo htmlspecialchars($lab['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <!-- Select Equipment (will be populated based on lab) -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Equipment</label>
          <select name="equipment_id" id="equipment_select" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8">
            <option value="">Select Lab First</option>
          </select>
        </div>
        
        <!-- Booking ID (Optional) -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Booking ID (Optional)</label>
          <input type="text" name="booking_id" placeholder="Enter booking ID if available" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" />
        </div>
        
        <!-- Start Date -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Start Date</label>
          <input type="date" name="start_date" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" />
        </div>
        
        <!-- Condition at Start -->
        <div style="margin-bottom:20px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Condition at Start</label>
          <select name="condition_start" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8">
            <option value="good">Good</option>
            <option value="fair">Fair</option>
            <option value="poor">Poor</option>
          </select>
        </div>
        
        <!-- Notes -->
        <div style="margin-bottom:28px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Notes (Optional)</label>
          <textarea name="notes" rows="3" placeholder="Add any notes about the equipment condition..." style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8;resize:vertical;font-family:inherit"></textarea>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" style="width:100%;padding:16px;background:#2d3b7a;color:#fff;border:none;border-radius:12px;font-size:16px;font-weight:700;cursor:pointer;transition:all 0.2s">
          Submit
        </button>
        
      </form>
      
    </div>
    
  </div>
</div>

<script>
// Fetch equipment based on selected lab
document.getElementById('lab_select').addEventListener('change', function() {
  const labId = this.value;
  const equipmentSelect = document.getElementById('equipment_select');
  
  if (!labId) {
    equipmentSelect.innerHTML = '<option value="">Select Lab First</option>';
    return;
  }
  
  // Fetch equipment for this lab (only available ones)
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
        equipmentSelect.innerHTML = '<option value="">No available equipment</option>';
      }
    })
    .catch(err => {
      equipmentSelect.innerHTML = '<option value="">Error loading equipment</option>';
    });
});
</script>

<?php require __DIR__ . '/../footer.php'; ?>
