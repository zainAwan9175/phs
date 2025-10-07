<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; } ?>
  
  <div style="max-width:800px;margin:0 auto">
    
    <!-- Back Button -->
    <div style="margin-bottom:24px">
      <a href="?page=admin_contact_messages" style="color:#168890;text-decoration:none;font-weight:600">← Back to Messages</a>
    </div>
    
    <!-- Message Card -->
    <div style="background:#fff;padding:48px;border-radius:24px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h1 style="color:#2d3b7a;font-size:32px;margin:0 0 32px;font-weight:700">Contact Message</h1>
      
      <!-- Sender Info -->
      <div style="background:#f8f9fa;padding:24px;border-radius:12px;margin-bottom:32px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
          <div>
            <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">NAME</div>
            <div style="color:#333;font-size:16px;font-weight:600">
              <?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']) ?>
            </div>
          </div>
          <div>
            <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">EMAIL</div>
            <div style="color:#333;font-size:16px">
              <a href="mailto:<?php echo htmlspecialchars($message['email']) ?>" style="color:#1976d2;text-decoration:none">
                <?php echo htmlspecialchars($message['email']) ?>
              </a>
            </div>
          </div>
          <div>
            <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">PHONE</div>
            <div style="color:#333;font-size:16px">
              <?php echo htmlspecialchars($message['phone'] ?? 'Not provided') ?>
            </div>
          </div>
          <div>
            <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">DATE</div>
            <div style="color:#333;font-size:16px">
              <?php echo date('M d, Y g:i A', strtotime($message['created_at'])) ?>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Message Content -->
      <div style="margin-bottom:32px">
        <div style="font-size:14px;color:#999;margin-bottom:12px;font-weight:600">MESSAGE</div>
        <div style="background:#f8f9fa;padding:24px;border-radius:12px;color:#333;font-size:15px;line-height:1.8;white-space:pre-wrap">
<?php echo htmlspecialchars($message['message']) ?>
        </div>
      </div>
      
      <!-- Status Update -->
      <div style="display:flex;gap:12px;justify-content:flex-end">
        <?php if ($message['status'] !== 'replied'): ?>
          <form method="POST" action="?page=admin_contact_status" style="display:inline">
            <input type="hidden" name="message_id" value="<?php echo $message['id'] ?>">
            <input type="hidden" name="status" value="replied">
            <button type="submit" style="background:#28a745;color:#fff;padding:12px 32px;border-radius:8px;border:none;cursor:pointer;font-weight:600">
              Mark as Replied
            </button>
          </form>
        <?php else: ?>
          <span style="color:#28a745;font-weight:600;padding:12px 32px">✓ Replied</span>
        <?php endif; ?>
      </div>
      
    </div>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
