<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user) { redirect('?page=login'); exit; } ?>
  
  <div style="max-width:680px;margin:0 auto">
    
    <!-- Card -->
    <div style="background:#fff;padding:40px;border-radius:24px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h1 style="text-align:center;color:#2d3b7a;font-size:32px;margin:0 0 32px;font-weight:700">Notifications</h1>
      
      <?php if (empty($notifications)): ?>
        <div style="text-align:center;padding:60px 20px;color:#999">
          <div style="font-size:48px;margin-bottom:16px">🔔</div>
          <p style="font-size:16px;margin:0">No notifications yet</p>
        </div>
      <?php else: ?>
        
        <!-- Notifications List -->
        <div style="display:flex;flex-direction:column;gap:16px">
          <?php foreach ($notifications as $notif): ?>
            <div style="padding:20px;border-radius:12px;background:<?php echo $notif['is_read'] ? '#f8f9fa' : '#e6f7ff' ?>;border-left:4px solid <?php echo $notif['is_read'] ? '#ccc' : '#168890' ?>;position:relative">
              
              <div style="display:flex;align-items:start;gap:16px">
                <!-- Icon -->
                <div style="width:40px;height:40px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">
                  🔔
                </div>
                
                <!-- Content -->
                <div style="flex:1">
                  <p style="margin:0;color:#333;font-size:15px;line-height:1.6"><?php echo htmlspecialchars($notif['message']) ?></p>
                  <div style="margin-top:8px;font-size:13px;color:#999">
                    <?php 
                      $time = strtotime($notif['created_at']);
                      $diff = time() - $time;
                      if ($diff < 60) echo 'Just now';
                      elseif ($diff < 3600) echo floor($diff / 60) . ' minutes ago';
                      elseif ($diff < 86400) echo floor($diff / 3600) . ' hours ago';
                      elseif ($diff < 604800) echo floor($diff / 86400) . ' days ago';
                      else echo date('M d, Y', $time);
                    ?>
                  </div>
                </div>
                
                <?php if (!$notif['is_read']): ?>
                  <!-- Mark as read button -->
                  <form method="POST" action="?page=mark_notification_read" style="margin:0">
                    <input type="hidden" name="notification_id" value="<?php echo $notif['id'] ?>">
                    <button type="submit" style="background:transparent;border:none;color:#168890;cursor:pointer;font-size:12px;padding:4px 8px;text-decoration:underline">Mark read</button>
                  </form>
                <?php endif; ?>
              </div>
              
            </div>
          <?php endforeach; ?>
        </div>
        
        <!-- Mark all as read -->
        <?php if (array_filter($notifications, fn($n) => !$n['is_read'])): ?>
          <form method="POST" action="?page=mark_all_notifications_read" style="margin-top:24px;text-align:center">
            <button type="submit" style="background:#168890;color:#fff;border:none;padding:12px 32px;border-radius:8px;cursor:pointer;font-weight:600;font-size:14px">
              Mark All as Read
            </button>
          </form>
        <?php endif; ?>
        
      <?php endif; ?>
      
    </div>
    
  </div>
</div>

<?php require __DIR__ . '/../footer.php'; ?>
