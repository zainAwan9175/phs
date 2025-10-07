<?php require __DIR__ . '/../header.php'; ?>

<div style="background:#49BBBD;min-height:100vh;padding:40px 20px">
  <div style="max-width:1400px;margin:0 auto">
    <!-- Header -->
    <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:32px">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div>
          <h1 style="color:#2d3b7a;font-size:32px;margin:0 0 8px;font-weight:700">🔧 Maintenance Tasks</h1>
          <p style="color:#6b7280;margin:0;font-size:15px">View all equipment maintenance tasks (Read Only)</p>
        </div>
        <a href="?page=admin_home" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#2d3b7a;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:600;border:2px solid #2d3b7a">
          <span style="font-size:18px">←</span> Dashboard
        </a>
      </div>
    </div>

    <!-- Tasks Table -->
    <div style="background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);overflow:hidden">
      <?php
      $stmt = $pdo->query("
        SELECT 
          mt.*,
          e.name as equipment_name,
          l.name as lab_name,
          u.first_name,
          u.last_name
        FROM maintenance_tasks mt
        JOIN equipment e ON mt.equipment_id = e.id
        JOIN labs l ON e.lab_id = l.id
        LEFT JOIN users u ON mt.created_by = u.id
        ORDER BY 
          CASE mt.status 
            WHEN 'open' THEN 1 
            WHEN 'in_progress' THEN 2 
            ELSE 3 
          END,
          mt.scheduled_date DESC
      ");
      $tasks = $stmt->fetchAll();
      ?>

      <?php if (empty($tasks)): ?>
        <div style="padding:60px 20px;text-align:center">
          <div style="font-size:64px;margin-bottom:16px">🔧</div>
          <h3 style="color:#6b7280;margin:0 0 8px;font-size:20px">No Maintenance Tasks</h3>
          <p style="color:#9ca3af;margin:0">Lab managers can create maintenance tasks.</p>
        </div>
      <?php else: ?>
        <div style="overflow-x:auto">
          <table style="width:100%;border-collapse:collapse">
            <thead>
              <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">EQUIPMENT</th>
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">TASK TYPE</th>
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">SCHEDULED DATE</th>
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">STATUS</th>
                <th style="padding:16px 20px;text-align:left;font-weight:700;color:#374151;font-size:13px">MANAGER</th>
                <th style="padding:16px 20px;text-align:center;font-weight:700;color:#374151;font-size:13px">COST</th>
                <th style="padding:16px 20px;text-align:center;font-weight:700;color:#374151;font-size:13px">COMPLETION</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tasks as $task): ?>
                <tr style="border-bottom:1px solid #e5e7eb;transition:background 0.2s" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                  <td style="padding:20px">
                    <div style="font-weight:600;color:#2d3b7a;margin-bottom:4px"><?php echo htmlspecialchars($task['equipment_name']); ?></div>
                    <div style="font-size:12px;color:#9ca3af">Lab: <?php echo htmlspecialchars($task['lab_name']); ?></div>
                  </td>
                  <td style="padding:20px">
                    <div style="color:#374151;font-size:14px;font-weight:500"><?php echo htmlspecialchars(ucfirst($task['task_type'])); ?></div>
                    <?php if ($task['summary']): ?>
                      <div style="font-size:12px;color:#6b7280;margin-top:4px"><?php echo htmlspecialchars(substr($task['summary'], 0, 50)) . (strlen($task['summary']) > 50 ? '...' : ''); ?></div>
                    <?php endif; ?>
                  </td>
                  <td style="padding:20px">
                    <div style="font-size:14px;color:#374151;font-weight:500">
                      <?php echo date('M d, Y', strtotime($task['scheduled_date'])); ?>
                    </div>
                  </td>
                  <td style="padding:20px">
                    <?php
                    $statusColors = [
                      'open' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                      'in_progress' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                      'completed' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                      'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b']
                    ];
                    $colors = $statusColors[$task['status']] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                    ?>
                    <span style="display:inline-block;background:<?php echo $colors['bg']; ?>;color:<?php echo $colors['text']; ?>;padding:6px 12px;border-radius:6px;font-weight:600;font-size:12px;text-transform:capitalize">
                      <?php echo htmlspecialchars(str_replace('_', ' ', $task['status'])); ?>
                    </span>
                  </td>
                  <td style="padding:20px">
                    <div style="color:#374151;font-size:14px">
                      <?php echo htmlspecialchars($task['first_name'] . ' ' . $task['last_name']); ?>
                    </div>
                  </td>
                  <td style="padding:20px;text-align:center">
                    <?php if ($task['cost']): ?>
                      <div style="color:#374151;font-weight:600;font-size:14px">$<?php echo number_format($task['cost'], 2); ?></div>
                    <?php else: ?>
                      <span style="color:#9ca3af;font-style:italic">-</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:20px;text-align:center">
                    <?php if ($task['completion_date']): ?>
                      <div style="font-size:13px;color:#374151">
                        <?php echo date('M d, Y', strtotime($task['completion_date'])); ?>
                      </div>
                    <?php else: ?>
                      <span style="color:#9ca3af;font-style:italic">-</span>
                    <?php endif; ?>
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
