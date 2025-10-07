<?php require __DIR__ . '/../header.php'; ?>

<div style="background:#49BBBD;min-height:100vh;padding:40px 20px">
  <div style="max-width:1400px;margin:0 auto">
    <!-- Header -->
    <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:32px">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div>
          <h1 style="color:#2d3b7a;font-size:32px;margin:0 0 8px;font-weight:700">📊 Add Equipment Report</h1>
          <p style="color:#6b7280;margin:0;font-size:15px">Log equipment condition before and after booking</p>
        </div>
        <a href="?page=manager_equipment_reports" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#2d3b7a;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:600;border:2px solid #2d3b7a;transition:all 0.2s">
          <span style="font-size:18px">←</span> Back to Reports
        </a>
      </div>
    </div>

    <!-- Form -->
    <div style="background:#fff;padding:40px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07)">
      <?php if (!empty($_SESSION['error'])): ?>
        <div style="background:#fee2e2;color:#991b1b;padding:16px 20px;border-radius:12px;margin-bottom:24px;border-left:4px solid #dc2626">
          <strong>Error:</strong> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="?page=manager_add_equipment_report_action" id="reportForm">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px">
          
          <!-- Equipment Selection -->
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Equipment *</label>
            <select name="equipment_id" id="equipment_id" required style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;background:#fff;cursor:pointer">
              <option value="">Select Equipment</option>
              <?php
              $stmt = $pdo->query('SELECT e.id, e.name, e.category, l.name as lab_name FROM equipment e JOIN labs l ON e.lab_id = l.id ORDER BY l.name, e.name');
              while ($equip = $stmt->fetch()) {
                  $display = $equip['lab_name'] . ' - ' . $equip['name'];
                  if ($equip['category']) {
                      $display .= ' (' . $equip['category'] . ')';
                  }
                  echo '<option value="' . $equip['id'] . '">' . htmlspecialchars($display) . '</option>';
              }
              ?>
            </select>
          </div>

          <!-- Student ID -->
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Student ID *</label>
            <input type="text" name="student_id" id="student_id" required placeholder="Enter Student ID" style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px">
          </div>
        </div>

        <!-- Date of Issue (Booking Start) -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-top:24px">
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Date of Issue *</label>
            <input type="date" name="date_of_issue" required style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px">
          </div>

          <!-- Date of Closing (Booking End) -->
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Date of Closing *</label>
            <input type="date" name="date_of_closing" required style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px">
          </div>
        </div>

        <!-- Condition Before Booking Start -->
        <div style="margin-top:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Condition Before Booking Start *</label>
          <textarea name="condition_before" required rows="4" placeholder="Describe equipment condition before booking started..." style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;resize:vertical;font-family:inherit"></textarea>
        </div>

        <!-- Condition After Booking End -->
        <div style="margin-top:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Condition After Booking End *</label>
          <textarea name="condition_after" required rows="4" placeholder="Describe equipment condition after booking ended..." style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;resize:vertical;font-family:inherit"></textarea>
        </div>

        <!-- Submit Button -->
        <div style="margin-top:32px;display:flex;gap:12px;justify-content:flex-end">
          <a href="?page=manager_equipment_reports" style="display:inline-block;padding:14px 32px;border-radius:10px;border:2px solid #d1d5db;color:#6b7280;text-decoration:none;font-weight:600;transition:all 0.2s">Cancel</a>
          <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 32px;border-radius:10px;border:none;cursor:pointer;font-size:16px;font-weight:600;transition:all 0.2s;box-shadow:0 4px 0 rgba(0,0,0,0.1)">
            ➕ Create Report
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../footer.php'; ?>
