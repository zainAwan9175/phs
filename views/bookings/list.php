<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <?php if (!empty($_SESSION['flash'])): ?>
    <div style="background:#e6ffed;padding:10px;border-radius:8px;margin-bottom:16px"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px">
    <h2 style="margin:0;color:#2d3b7a">My Bookings</h2>
  </div>

  <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 0 rgba(0,0,0,0.04)">
    <?php if (empty($bookings)): ?>
      <p style="text-align:center;color:#666;padding:40px">You have no bookings yet. <a href="?page=labs">Browse labs to book equipment</a>.</p>
    <?php else: ?>
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#f5f5f5;border-bottom:2px solid #ddd">
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Equipment</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Lab</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Start Time</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">End Time</th>
            <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a">Status</th>
            <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a;min-width:150px">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($bookings as $b): ?>
            <tr style="border-bottom:1px solid #eee">
              <td style="padding:12px;font-weight:500"><?php echo htmlspecialchars($b['equipment_name']) ?></td>
              <td style="padding:12px;color:#666"><?php echo htmlspecialchars($b['lab_name']) ?></td>
              <td style="padding:12px;color:#666"><?php echo date('M d, Y h:i A', strtotime($b['start_time'])) ?></td>
              <td style="padding:12px;color:#666"><?php echo date('M d, Y h:i A', strtotime($b['end_time'])) ?></td>
              <td style="padding:12px;text-align:center">
                <?php 
                  $statusColors = [
                    'pending'=>['bg'=>'#fff3cd','text'=>'#856404'],
                    'approved'=>['bg'=>'#d4edda','text'=>'#155724'],
                    'rejected'=>['bg'=>'#f8d7da','text'=>'#721c24'],
                    'cancelled'=>['bg'=>'#e2e3e5','text'=>'#383d41']
                  ];
                  $color = $statusColors[$b['status']] ?? ['bg'=>'#f5f5f5','text'=>'#666'];
                ?>
                <span style="padding:4px 12px;border-radius:12px;font-size:13px;font-weight:500;background:<?php echo $color['bg'] ?>;color:<?php echo $color['text'] ?>">
                  <?php echo htmlspecialchars(ucfirst($b['status'])) ?>
                </span>
              </td>
              <td style="padding:12px;text-align:center;white-space:nowrap">
                <?php if (in_array($b['status'], ['pending', 'approved'])): ?>
                  <a href="?page=booking_edit&id=<?php echo $b['id'] ?>" style="color:#1976d2;text-decoration:none;font-weight:500;padding:6px 16px;border-radius:6px;background:#e3f2fd;display:inline-block">Edit/Cancel</a>
                <?php else: ?>
                  <span style="color:#999;font-size:13px">No action</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
