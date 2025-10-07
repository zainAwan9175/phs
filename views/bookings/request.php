<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:40px 20px">
  
  <div style="max-width:580px;width:100%;background:#fff;padding:48px;border-radius:36px;box-shadow:0 12px 0 rgba(0,0,0,0.04)">
    
    <!-- Heading -->
    <div style="text-align:center;margin-bottom:12px">
      <h1 style="margin:0;font-size:36px;color:#2d3b7a;font-weight:800">Booking Request</h1>
      <p style="margin:8px 0 0;color:#666;font-size:16px">Request to book equipment</p>
    </div>
    
    <form method="post" action="?page=booking_action" style="margin-top:32px">
      
      <?php if ($equipment): ?>
        <!-- Equipment already selected (from lab page) -->
        <input type="hidden" name="equipment_id" value="<?php echo $equipment['id'] ?>" />
        <input type="hidden" id="selected_lab_id" value="<?php echo $equipment['lab_id'] ?>" />
        
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Equipment</label>
          <input type="text" readonly value="<?php echo htmlspecialchars($equipment['name']) ?> — <?php echo htmlspecialchars($equipment['lab_name']) ?>" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8;color:#666" />
        </div>
        
        <?php
        // Check if rules exist for this lab
        $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(*) FROM rules WHERE (lab_id = ? OR lab_id IS NULL) AND active = 1');
        $stmt->execute([$equipment['lab_id']]);
        $rulesCount = $stmt->fetchColumn();
        if ($rulesCount > 0): ?>
          <div style="margin-bottom:24px;text-align:center">
            <button type="button" onclick="openRulesModal()" style="background:#ffc107;color:#333;padding:12px 32px;border-radius:24px;border:2px solid #ffb300;cursor:pointer;font-size:15px;font-weight:700;display:inline-flex;align-items:center;gap:8px;box-shadow:0 3px 0 rgba(0,0,0,0.1)">
              <span style="font-size:20px">⚠️</span>
              View Lab Rules & Guidelines
            </button>
          </div>
        <?php endif; ?>
        
      <?php else: ?>
        <!-- Student needs to select lab and equipment -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Lab</label>
          <select name="lab_id" id="lab_select" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select Lab</option>
            <?php foreach ($labs as $lab): ?>
              <option value="<?php echo $lab['id'] ?>"><?php echo htmlspecialchars($lab['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Equipment</label>
          <select name="equipment_id" id="equipment_select" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff">
            <option value="">Select Lab First</option>
          </select>
        </div>
        
        <!-- Rules button (will be shown when lab is selected) -->
        <div id="rules_button_container" style="margin-bottom:24px;text-align:center;display:none">
          <button type="button" onclick="openRulesModal()" style="background:#ffc107;color:#333;padding:12px 32px;border-radius:24px;border:2px solid #ffb300;cursor:pointer;font-size:15px;font-weight:700;display:inline-flex;align-items:center;gap:8px;box-shadow:0 3px 0 rgba(0,0,0,0.1)">
            <span style="font-size:20px">⚠️</span>
            View Lab Rules & Guidelines
          </button>
        </div>
        
      <?php endif; ?>
      
      <!-- Date Range -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
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
        <textarea name="purpose" rows="3" placeholder="Enter purpose for booking" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff;resize:vertical"></textarea>
      </div>
      
      <!-- Safety Checkbox -->
      <div style="margin-bottom:28px">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
          <input type="checkbox" required style="width:20px;height:20px;cursor:pointer" />
          <span style="color:#333;font-size:15px">I will follow all safety protocols</span>
        </label>
      </div>
      
      <!-- Submit Button -->
      <div style="text-align:center">
        <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Submit request</button>
      </div>
      
    </form>
    
  </div>
  
</div>

<!-- Rules Modal -->
<div id="rulesModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999;overflow:auto;padding:20px">
  <div style="max-width:700px;margin:40px auto;background:#fff;border-radius:24px;box-shadow:0 12px 48px rgba(0,0,0,0.3);position:relative">
    
    <!-- Modal Header -->
    <div style="background:#ffc107;padding:24px 32px;border-radius:24px 24px 0 0;display:flex;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:12px">
        <span style="font-size:32px">⚠️</span>
        <h2 style="margin:0;color:#333;font-size:24px;font-weight:800">Lab Rules & Guidelines</h2>
      </div>
      <button onclick="closeRulesModal()" style="background:transparent;border:none;font-size:32px;cursor:pointer;color:#333;line-height:1;padding:0;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:50%;transition:background 0.2s" onmouseover="this.style.background='rgba(0,0,0,0.1)'" onmouseout="this.style.background='transparent'">&times;</button>
    </div>
    
    <!-- Modal Body -->
    <div id="rulesModalContent" style="padding:32px;max-height:60vh;overflow-y:auto">
      <p style="text-align:center;color:#999">Loading rules...</p>
    </div>
    
    <!-- Modal Footer -->
    <div style="padding:20px 32px;border-top:2px solid #f0f0f0;text-align:center">
      <button onclick="closeRulesModal()" style="background:#2d3b7a;color:#fff;padding:12px 40px;border-radius:24px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 4px 0 rgba(0,0,0,0.1)">I Understand</button>
    </div>
    
  </div>
</div>

<script>
let currentLabRules = [];

// Open rules modal
function openRulesModal() {
  const labId = document.getElementById('selected_lab_id')?.value || document.getElementById('lab_select')?.value;
  
  if (!labId) {
    alert('Please select a lab first');
    return;
  }
  
  const modal = document.getElementById('rulesModal');
  const modalContent = document.getElementById('rulesModalContent');
  
  modal.style.display = 'block';
  document.body.style.overflow = 'hidden';
  modalContent.innerHTML = '<p style="text-align:center;color:#999">Loading rules...</p>';
  
  // Fetch rules
  fetch('?page=api_get_rules&lab_id=' + labId)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.rules.length > 0) {
        currentLabRules = data.rules;
        let rulesHtml = '';
        data.rules.forEach((rule, index) => {
          rulesHtml += '<div style="margin-bottom:24px;padding-bottom:24px;' + (index < data.rules.length - 1 ? 'border-bottom:2px solid #f0f0f0' : '') + '">';
          rulesHtml += '<h3 style="margin:0 0 12px;color:#2d3b7a;font-size:20px;font-weight:700;display:flex;align-items:center;gap:8px">';
          rulesHtml += '<span style="background:#2d3b7a;color:#fff;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px">' + (index + 1) + '</span>';
          rulesHtml += escapeHtml(rule.title);
          rulesHtml += '</h3>';
          rulesHtml += '<p style="margin:0;color:#333;line-height:1.8;white-space:pre-wrap;font-size:15px;padding-left:40px">' + escapeHtml(rule.body) + '</p>';
          rulesHtml += '</div>';
        });
        modalContent.innerHTML = rulesHtml;
      } else {
        modalContent.innerHTML = '<p style="text-align:center;color:#999;padding:40px">No rules available for this lab.</p>';
      }
    })
    .catch(err => {
      modalContent.innerHTML = '<p style="text-align:center;color:#dc3545;padding:40px">Error loading rules. Please try again.</p>';
    });
}

// Close rules modal
function closeRulesModal() {
  document.getElementById('rulesModal').style.display = 'none';
  document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('rulesModal')?.addEventListener('click', function(e) {
  if (e.target === this) {
    closeRulesModal();
  }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && document.getElementById('rulesModal').style.display === 'block') {
    closeRulesModal();
  }
});

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

<?php if (!$equipment): ?>
// Dynamic equipment loading based on selected lab
document.getElementById('lab_select')?.addEventListener('change', function() {
  const labId = this.value;
  const equipmentSelect = document.getElementById('equipment_select');
  const rulesButtonContainer = document.getElementById('rules_button_container');
  
  if (!labId) {
    equipmentSelect.innerHTML = '<option value="">Select Lab First</option>';
    rulesButtonContainer.style.display = 'none';
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
  
  // Check if rules exist and show button
  fetch('?page=api_get_rules&lab_id=' + labId)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.rules.length > 0) {
        rulesButtonContainer.style.display = 'block';
      } else {
        rulesButtonContainer.style.display = 'none';
      }
    })
    .catch(err => {
      rulesButtonContainer.style.display = 'none';
    });
});
<?php endif; ?>
</script>

<?php require __DIR__ . '/../footer.php'; ?>
