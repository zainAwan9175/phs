<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px);padding:48px 24px">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user) { redirect('?page=login'); exit; } ?>
  
  <div style="max-width:1000px;margin:0 auto">
    
    <!-- Card -->
    <div style="background:#fff;padding:40px;border-radius:24px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <h1 style="text-align:center;color:#2d3b7a;font-size:32px;margin:0 0 32px;font-weight:700">Bookings</h1>
      
      <?php if (empty($bookings)): ?>
        <div style="text-align:center;padding:60px 20px;color:#999">
          <div style="font-size:48px;margin-bottom:16px">📅</div>
          <p style="font-size:16px;margin:0 0 20px">No bookings yet</p>
          <a href="?page=labs" style="display:inline-block;background:#168890;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600">Browse Labs</a>
        </div>
      <?php else: ?>
        
        <!-- Bookings List -->
        <div style="display:flex;flex-direction:column;gap:20px">
          <?php foreach ($bookings as $booking): ?>
            <div style="padding:24px;border-radius:16px;background:#f8f9fa;border:2px solid #e9ecef">
              
              <!-- Header Row -->
              <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:16px">
                <div>
                  <h3 style="margin:0 0 4px;color:#2d3b7a;font-size:18px;font-weight:700"><?php echo htmlspecialchars($booking['equipment_name']) ?></h3>
                  <p style="margin:0;color:#666;font-size:14px"><?php echo htmlspecialchars($booking['lab_name']) ?></p>
                </div>
                
                <!-- Status Badge -->
                <div>
                  <?php 
                    $statusColors = [
                      'pending' => ['bg' => '#ffc107', 'text' => '#000'],
                      'approved' => ['bg' => '#7ed957', 'text' => '#fff'],
                      'rejected' => ['bg' => '#dc3545', 'text' => '#fff'],
                      'cancelled' => ['bg' => '#6c757d', 'text' => '#fff']
                    ];
                    $colors = $statusColors[$booking['status']] ?? ['bg' => '#6c757d', 'text' => '#fff'];
                  ?>
                  <span style="display:inline-block;background:<?php echo $colors['bg'] ?>;color:<?php echo $colors['text'] ?>;padding:6px 16px;border-radius:6px;font-weight:600;font-size:13px;text-transform:capitalize">
                    <?php echo htmlspecialchars($booking['status']) ?>
                  </span>
                </div>
              </div>
              
              <!-- Details Grid -->
              <div style="display:grid;grid-template-columns:repeat(2, 1fr);gap:16px;padding:16px;background:#fff;border-radius:12px;margin-bottom:16px">
                
                <!-- Booking ID -->
                <div>
                  <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">BOOKING ID</div>
                  <div style="color:#333;font-size:14px;font-weight:600">#<?php echo str_pad($booking['id'], 4, '0', STR_PAD_LEFT) ?></div>
                </div>
                
                <!-- Lab -->
                <div>
                  <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">LAB</div>
                  <div style="color:#333;font-size:14px"><?php echo htmlspecialchars($booking['lab_name']) ?></div>
                </div>
                
                <!-- Start Date -->
                <div>
                  <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">START DATE</div>
                  <div style="color:#333;font-size:14px"><?php echo date('M d, Y', strtotime($booking['start_time'])) ?></div>
                  <div style="color:#666;font-size:12px"><?php echo date('g:i A', strtotime($booking['start_time'])) ?></div>
                </div>
                
                <!-- End Date -->
                <div>
                  <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">END DATE</div>
                  <div style="color:#333;font-size:14px"><?php echo date('M d, Y', strtotime($booking['end_time'])) ?></div>
                  <div style="color:#666;font-size:12px"><?php echo date('g:i A', strtotime($booking['end_time'])) ?></div>
                </div>
                
              </div>
              
              <!-- Purpose (if exists) -->
              <?php if (!empty($booking['purpose'])): ?>
                <div style="padding:12px 16px;background:#fff;border-radius:12px;margin-bottom:16px">
                  <div style="font-size:12px;color:#999;margin-bottom:4px;font-weight:600">PURPOSE</div>
                  <div style="color:#333;font-size:14px"><?php echo htmlspecialchars($booking['purpose']) ?></div>
                </div>
              <?php endif; ?>
              
              <!-- Actions -->
              <div style="display:flex;gap:12px;justify-content:flex-end">
                <?php if ($booking['status'] === 'pending' || $booking['status'] === 'approved'): ?>
                  <a href="?page=booking_edit&id=<?php echo $booking['id'] ?>" style="padding:10px 24px;background:#168890;color:#fff;text-decoration:none;border-radius:8px;font-weight:600;font-size:14px">
                    Edit/Cancel
                  </a>
                <?php endif; ?>
              </div>
              
            </div>
          <?php endforeach; ?>
        </div>
        
      <?php endif; ?>
      
    </div>
    
  </div>
</div>

<?php require __DIR__ . '/../footer.php'; ?>
