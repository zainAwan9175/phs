<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <?php if (!empty($_SESSION['flash'])): ?><div style="background:#e6ffed;padding:10px;border-radius:8px;margin-bottom:16px"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px">
    <h2 style="margin:0;color:#2d3b7a">User Management</h2>
  </div>

  <!-- Users Table -->
  <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 0 rgba(0,0,0,0.04)">
    <h2 style="margin:0 0 24px;color:#2d3b7a">All Users</h2>
    
    <?php if (empty($users)): ?>
      <p style="text-align:center;color:#666;padding:40px">No users found.</p>
    <?php else: ?>
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#f5f5f5;border-bottom:2px solid #ddd">
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Name</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Email</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Role</th>
            <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a">Status</th>
            <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($users as $u): ?>
            <tr style="border-bottom:1px solid #eee">
              <td style="padding:12px;font-weight:500"><?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></td>
              <td style="padding:12px;color:#666"><?php echo htmlspecialchars($u['email']) ?></td>
              <td style="padding:12px">
                <span style="padding:4px 12px;border-radius:12px;font-size:13px;font-weight:500;background:#e3f2fd;color:#1976d2">
                  <?php echo htmlspecialchars(ucwords(str_replace('_',' ',$u['role_name']))) ?>
                </span>
              </td>
              <td style="padding:12px;text-align:center">
                <?php 
                  $statusColors = ['active'=>'#4caf50','inactive'=>'#999','suspended'=>'#f44336'];
                  $statusBg = ['active'=>'#e8f5e9','inactive'=>'#f5f5f5','suspended'=>'#ffebee'];
                  $status = $u['status'] ?? 'active';
                ?>
                <span style="padding:4px 12px;border-radius:12px;font-size:13px;font-weight:500;background:<?php echo $statusBg[$status] ?? '#f5f5f5' ?>;color:<?php echo $statusColors[$status] ?? '#666' ?>">
                  <?php echo htmlspecialchars(ucfirst($status)) ?>
                </span>
              </td>
              <td style="padding:12px;text-align:center">
                <a href="?page=admin_user_edit&id=<?php echo $u['id'] ?>" style="color:#1976d2;text-decoration:none;font-weight:500;padding:6px 16px;border-radius:6px;background:#e3f2fd;display:inline-block">Edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
