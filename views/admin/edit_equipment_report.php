<?php require __DIR__ . '/../header.php'; ?>

<div style="background:#49BBBD;min-height:100vh;padding:40px 20px">
  <div style="max-width:1400px;margin:0 auto">
    <!-- Header -->
    <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:32px">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div>
          <h1 style="color:#2d3b7a;font-size:32px;margin:0 0 8px;font-weight:700">✏️ Edit Equipment Report</h1>
          <p style="color:#6b7280;margin:0;font-size:15px">Update equipment condition log</p>
        </div>
        <a href="?page=admin_equipment_reports" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#2d3b7a;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:600;border:2px solid #2d3b7a;transition:all 0.2s">
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

      <form method="POST" action="?page=admin_update_equipment_report_action" id="reportForm">
        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
        
        <!-- Equipment Info (Read Only) -->
        <?php
        $stmt = $pdo->prepare('
          SELECT e.name as equipment_name, e.category, l.name as lab_name 
          FROM equipment e 
          JOIN labs l ON e.lab_id = l.id 
          WHERE e.id = ?
        ');
        $stmt->execute([$report['equipment_id']]);
        $equipment = $stmt->fetch();
        ?>
        
        <div style="background:#f9fafb;padding:20px;border-radius:10px;margin-bottom:24px;border-left:4px solid #3b82f6">
          <div style="font-size:13px;color:#6b7280;font-weight:600;margin-bottom:4px">EQUIPMENT</div>
          <div style="font-size:16px;color:#374151;font-weight:600">
            <?php echo htmlspecialchars($equipment['lab_name'] . ' - ' . $equipment['equipment_name']); ?>
            <?php if ($equipment['category']): ?>
              (<?php echo htmlspecialchars($equipment['category']); ?>)
            <?php endif; ?>
          </div>
        </div>

        <!-- Date Fields -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-bottom:24px">
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Date of Issue</label>
            <input type="date" name="booking_date" value="<?php echo htmlspecialchars($report['booking_date'] ?? ''); ?>" style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px">
          </div>

          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Date of Closing</label>
            <input type="date" name="return_date" value="<?php echo htmlspecialchars($report['return_date'] ?? ''); ?>" style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px">
          </div>
        </div>

        <!-- Condition Before -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Condition Before Booking Start *</label>
          <textarea name="condition_before" required rows="4" style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;resize:vertical;font-family:inherit"><?php echo htmlspecialchars($report['condition_before']); ?></textarea>
        </div>

        <!-- Condition After -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Condition After Booking End</label>
          <textarea name="condition_after" rows="4" style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;resize:vertical;font-family:inherit"><?php echo htmlspecialchars($report['condition_after'] ?? ''); ?></textarea>
        </div>

        <!-- Notes -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Additional Notes</label>
          <textarea name="notes" rows="3" placeholder="Optional notes..." style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;resize:vertical;font-family:inherit"><?php echo htmlspecialchars($report['notes'] ?? ''); ?></textarea>
        </div>

        <!-- Status -->
        <div style="margin-bottom:24px">
          <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:15px">Status</label>
          <select name="status" style="width:100%;padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;font-size:15px;background:#fff">
            <option value="pending_return" <?php echo ($report['status'] ?? '') === 'pending_return' ? 'selected' : ''; ?>>Pending Return</option>
            <option value="returned" <?php echo ($report['status'] ?? '') === 'returned' ? 'selected' : ''; ?>>Returned</option>
            <option value="damaged" <?php echo ($report['status'] ?? '') === 'damaged' ? 'selected' : ''; ?>>Damaged</option>
            <option value="normal" <?php echo ($report['status'] ?? '') === 'normal' ? 'selected' : ''; ?>>Normal</option>
          </select>
        </div>

        <!-- Submit Buttons -->
        <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:24px;border-top:2px solid #e5e7eb">
          <a href="?page=admin_equipment_reports" style="display:inline-block;padding:14px 32px;border-radius:10px;border:2px solid #d1d5db;color:#6b7280;text-decoration:none;font-weight:600;transition:all 0.2s">Cancel</a>
          <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 32px;border-radius:10px;border:none;cursor:pointer;font-size:16px;font-weight:600;transition:all 0.2s;box-shadow:0 4px 0 rgba(0,0,0,0.1)">
            💾 Update Report
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../footer.php'; ?>
