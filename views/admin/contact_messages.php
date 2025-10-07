<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; } ?>
  
  <?php if (!empty($_SESSION['flash'])): ?>
    <div style="background:#e6ffed;padding:10px;border-radius:8px;margin-bottom:16px"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px">
    <h2 style="margin:0;color:#2d3b7a">Contact Messages</h2>
  </div>

  <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 0 rgba(0,0,0,0.04)">
    <?php if (empty($messages)): ?>
      <p style="text-align:center;color:#666;padding:40px">No contact messages yet.</p>
    <?php else: ?>
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#f5f5f5;border-bottom:2px solid #ddd">
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Name</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Email</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Phone</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Message</th>
            <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a">Status</th>
            <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a">Date</th>
            <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($messages as $msg): ?>
            <tr style="border-bottom:1px solid #eee;<?php echo $msg['status'] === 'new' ? 'background:#fffbf0' : '' ?>">
              <td style="padding:12px;font-weight:500"><?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']) ?></td>
              <td style="padding:12px;color:#666"><?php echo htmlspecialchars($msg['email']) ?></td>
              <td style="padding:12px;color:#666"><?php echo htmlspecialchars($msg['phone'] ?? '-') ?></td>
              <td style="padding:12px;color:#666;max-width:300px">
                <div style="max-height:60px;overflow:hidden;text-overflow:ellipsis">
                  <?php echo htmlspecialchars(substr($msg['message'], 0, 100)) ?><?php echo strlen($msg['message']) > 100 ? '...' : '' ?>
                </div>
              </td>
              <td style="padding:12px;text-align:center">
                <?php 
                  $statusColors = [
                    'new'=>['bg'=>'#fff3cd','text'=>'#856404'],
                    'read'=>['bg'=>'#d1ecf1','text'=>'#0c5460'],
                    'replied'=>['bg'=>'#d4edda','text'=>'#155724']
                  ];
                  $color = $statusColors[$msg['status']] ?? ['bg'=>'#f5f5f5','text'=>'#666'];
                ?>
                <span style="padding:4px 12px;border-radius:12px;font-size:13px;font-weight:500;background:<?php echo $color['bg'] ?>;color:<?php echo $color['text'] ?>">
                  <?php echo htmlspecialchars(ucfirst($msg['status'])) ?>
                </span>
              </td>
              <td style="padding:12px;text-align:center;color:#666;font-size:13px">
                <?php echo date('M d, Y', strtotime($msg['created_at'])) ?>
              </td>
              <td style="padding:12px;text-align:center">
                <a href="?page=admin_contact_view&id=<?php echo $msg['id'] ?>" style="color:#1976d2;text-decoration:none;font-weight:500;padding:6px 16px;border-radius:6px;background:#e3f2fd;display:inline-block">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
