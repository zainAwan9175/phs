<?php require __DIR__ . '/../header.php'; ?>

<div style="background:#49BBBD;min-height:100vh;padding:40px 20px">
  <div style="max-width:1400px;margin:0 auto">
    <!-- Header -->
    <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:32px">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div>
          <h1 style="color:#2d3b7a;font-size:32px;margin:0 0 8px;font-weight:700">📊 All Equipment Reports</h1>
          <p style="color:#6b7280;margin:0;font-size:15px">View and manage equipment condition logs</p>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <a href="?page=admin_add_equipment_report" style="display:inline-flex;align-items:center;gap:8px;background:#2d3b7a;color:#fff;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:600;box-shadow:0 4px 0 rgba(0,0,0,0.1)">
            <span style="font-size:18px">➕</span> Add Report
          </a>
          <a href="?page=admin_home" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#2d3b7a;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:600;border:2px solid #2d3b7a">
            <span style="font-size:18px">←</span> Dashboard
          </a>
        </div>
      </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
      <div style="background:#d1fae5;color:#065f46;padding:16px 20px;border-radius:12px;margin-bottom:24px;border-left:4px solid #10b981">
        ✅ <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
      <div style="background:#fee2e2;color:#991b1b;padding:16px 20px;border-radius:12px;margin-bottom:24px;border-left:4px solid #dc2626">
        ❌ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <!-- Reports Table -->
    <div style="background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);overflow:hidden">
      <?php
      $stmt = $pdo->query("
        SELECT 
          er.*,
          e.name as equipment_name,
          e.category as equipment_category,
          l.name as lab_name,
          m.first_name as manager_first,
          m.last_name as manager_last,
          s.first_name as student_first,
          s.last_name as student_last
        FROM equipment_reports er
        JOIN equipment e ON er.equipment_id = e.id
        JOIN labs l ON e.lab_id = l.id
        JOIN users m ON er.manager_id = m.id
        LEFT JOIN users s ON er.student_id = s.id
        ORDER BY er.created_at DESC
      ");
      $reports = $stmt->fetchAll();
      ?>

      <?php if (empty($reports)): ?>
        <div style="padding:60px 20px;text-align:center">
          <div style="font-size:64px;margin-bottom:16px">📋</div>
          <h3 style="color:#6b7280;margin:0 0 8px;font-size:20px">No Reports Yet</h3>
          <p style="color:#9ca3af;margin:0">Lab managers can add equipment reports from their dashboard.</p>
        </div>
      <?php else: ?>
        <div style="overflow-x:auto">
          <table style="width:100%;border-collapse:collapse">
            <thead>
              <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">EQUIPMENT</th>
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">STUDENT</th>
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">DATE OF ISSUE</th>
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">DATE OF CLOSING</th>
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">MANAGER</th>
                <th style="padding:16px 20px;text-align:center;font-weight:700;color:#374151;font-size:13px">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reports as $report): ?>
                <tr style="border-bottom:1px solid #e5e7eb;transition:background 0.2s" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                  <td style="padding:20px">
                    <div style="font-weight:600;color:#2d3b7a;margin-bottom:4px"><?php echo htmlspecialchars($report['equipment_name']); ?></div>
                    <?php if ($report['equipment_category']): ?>
                      <div style="font-size:13px;color:#6b7280"><?php echo htmlspecialchars($report['equipment_category']); ?></div>
                    <?php endif; ?>
                    <div style="font-size:12px;color:#9ca3af;margin-top:4px">Lab: <?php echo htmlspecialchars($report['lab_name']); ?></div>
                  </td>
                  <td style="padding:20px">
                    <?php if ($report['student_id']): ?>
                      <div style="color:#374151;font-weight:500"><?php echo htmlspecialchars($report['student_first'] . ' ' . $report['student_last']); ?></div>
                    <?php else: ?>
                      <span style="color:#9ca3af;font-style:italic">No Student</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:20px">
                    <?php if ($report['booking_date']): ?>
                      <div style="font-size:14px;color:#374151;font-weight:500">
                        <?php echo date('M d, Y', strtotime($report['booking_date'])); ?>
                      </div>
                    <?php else: ?>
                      <span style="color:#9ca3af;font-style:italic">-</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:20px">
                    <?php if ($report['return_date']): ?>
                      <div style="font-size:14px;color:#374151;font-weight:500">
                        <?php echo date('M d, Y', strtotime($report['return_date'])); ?>
                      </div>
                    <?php else: ?>
                      <span style="color:#9ca3af;font-style:italic">-</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:20px">
                    <div style="color:#374151;font-size:14px"><?php echo htmlspecialchars($report['manager_first'] . ' ' . $report['manager_last']); ?></div>
                  </td>
                  <td style="padding:20px;text-align:center">
                    <div style="display:inline-flex;gap:8px">
                      <a href="?page=admin_view_equipment_report&id=<?php echo $report['id']; ?>" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#dbeafe;color:#1e40af;border-radius:8px;text-decoration:none;font-size:16px;transition:all 0.2s" title="View Details">👁️</a>
                      <a href="?page=admin_edit_equipment_report&id=<?php echo $report['id']; ?>" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#fef3c7;color:#92400e;border-radius:8px;text-decoration:none;font-size:16px;transition:all 0.2s" title="Edit">✏️</a>
                      <a href="?page=admin_delete_equipment_report&id=<?php echo $report['id']; ?>" onclick="return confirm('Are you sure you want to delete this report?')" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#fee2e2;color:#991b1b;border-radius:8px;text-decoration:none;font-size:16px;transition:all 0.2s" title="Delete">🗑️</a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../footer.php'; ?>
