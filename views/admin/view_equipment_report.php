<?php require __DIR__ . '/../header.php'; ?>

<div style="background:#49BBBD;min-height:100vh;padding:40px 20px">
  <div style="max-width:1200px;margin:0 auto">
    <!-- Header -->
    <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:32px">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div>
          <h1 style="color:#2d3b7a;font-size:32px;margin:0 0 8px;font-weight:700">📊 Equipment Report Details</h1>
          <p style="color:#6b7280;margin:0;font-size:15px">View complete equipment report information</p>
        </div>
        <a href="?page=admin_equipment_reports" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#2d3b7a;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:600;border:2px solid #2d3b7a;transition:all 0.2s">
          <span style="font-size:18px">←</span> Back to Reports
        </a>
      </div>
    </div>

    <!-- Report Details -->
    <div style="background:#fff;padding:40px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07)">
      
      <!-- Equipment Info -->
      <div style="margin-bottom:32px;padding-bottom:24px;border-bottom:2px solid #e5e7eb">
        <h2 style="color:#2d3b7a;font-size:24px;margin:0 0 20px;font-weight:700">Equipment Information</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px">
          <div>
            <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:4px">EQUIPMENT NAME</label>
            <div style="color:#374151;font-size:16px;font-weight:600"><?php echo htmlspecialchars($report['equipment_name']); ?></div>
          </div>
          <?php if ($report['equipment_category']): ?>
          <div>
            <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:4px">CATEGORY</label>
            <div style="color:#374151;font-size:16px"><?php echo htmlspecialchars($report['equipment_category']); ?></div>
          </div>
          <?php endif; ?>
          <div>
            <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:4px">LAB</label>
            <div style="color:#374151;font-size:16px"><?php echo htmlspecialchars($report['lab_name']); ?></div>
          </div>
        </div>
      </div>

      <!-- Student & Manager Info -->
      <div style="margin-bottom:32px;padding-bottom:24px;border-bottom:2px solid #e5e7eb">
        <h2 style="color:#2d3b7a;font-size:24px;margin:0 0 20px;font-weight:700">People</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px">
          <div>
            <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:4px">STUDENT</label>
            <div style="color:#374151;font-size:16px">
              <?php if ($report['student_id']): ?>
                <?php echo htmlspecialchars($report['student_first'] . ' ' . $report['student_last']); ?>
              <?php else: ?>
                <span style="color:#9ca3af;font-style:italic">No Student Assigned</span>
              <?php endif; ?>
            </div>
          </div>
          <div>
            <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:4px">MANAGER</label>
            <div style="color:#374151;font-size:16px"><?php echo htmlspecialchars($report['manager_first'] . ' ' . $report['manager_last']); ?></div>
          </div>
        </div>
      </div>

      <!-- Dates -->
      <div style="margin-bottom:32px;padding-bottom:24px;border-bottom:2px solid #e5e7eb">
        <h2 style="color:#2d3b7a;font-size:24px;margin:0 0 20px;font-weight:700">Dates</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px">
          <div>
            <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:4px">DATE OF ISSUE</label>
            <div style="color:#374151;font-size:16px">
              <?php echo $report['booking_date'] ? date('F d, Y', strtotime($report['booking_date'])) : '<span style="color:#9ca3af">Not specified</span>'; ?>
            </div>
          </div>
          <div>
            <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:4px">DATE OF CLOSING</label>
            <div style="color:#374151;font-size:16px">
              <?php echo $report['return_date'] ? date('F d, Y', strtotime($report['return_date'])) : '<span style="color:#9ca3af">Not specified</span>'; ?>
            </div>
          </div>
          <div>
            <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:4px">REPORT CREATED</label>
            <div style="color:#374151;font-size:16px"><?php echo date('F d, Y g:i A', strtotime($report['created_at'])); ?></div>
          </div>
        </div>
      </div>

      <!-- Conditions -->
      <div style="margin-bottom:32px">
        <h2 style="color:#2d3b7a;font-size:24px;margin:0 0 20px;font-weight:700">Equipment Condition</h2>
        
        <div style="margin-bottom:24px">
          <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:8px">CONDITION BEFORE BOOKING START</label>
          <div style="background:#f9fafb;padding:16px;border-radius:10px;border-left:4px solid #3b82f6;color:#374151;line-height:1.6">
            <?php echo nl2br(htmlspecialchars($report['condition_before'])); ?>
          </div>
        </div>

        <div>
          <label style="display:block;color:#6b7280;font-size:13px;font-weight:600;margin-bottom:8px">CONDITION AFTER BOOKING END</label>
          <div style="background:#f9fafb;padding:16px;border-radius:10px;border-left:4px solid #10b981;color:#374151;line-height:1.6">
            <?php if ($report['condition_after']): ?>
              <?php echo nl2br(htmlspecialchars($report['condition_after'])); ?>
            <?php else: ?>
              <span style="color:#9ca3af;font-style:italic">Not recorded yet</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:24px;border-top:2px solid #e5e7eb">
        <a href="?page=admin_equipment_reports" style="display:inline-block;padding:14px 32px;border-radius:10px;border:2px solid #d1d5db;color:#6b7280;text-decoration:none;font-weight:600;transition:all 0.2s">
          Close
        </a>
        <a href="?page=admin_delete_equipment_report&id=<?php echo $report['id']; ?>" onclick="return confirm('Are you sure you want to delete this report?')" style="display:inline-block;background:#dc2626;color:#fff;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:600;transition:all 0.2s;box-shadow:0 4px 0 rgba(0,0,0,0.1)">
          🗑️ Delete Report
        </a>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../footer.php'; ?>
