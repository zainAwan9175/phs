<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:40px 20px">
  
  <div style="max-width:480px;width:100%;background:#fff;padding:48px;border-radius:36px;box-shadow:0 12px 0 rgba(0,0,0,0.04)">
    
    <!-- Heading -->
    <div style="text-align:center;margin-bottom:12px">
      <h1 style="margin:0;font-size:36px;color:#2d3b7a;font-weight:800">Edit/Cancel Booking</h1>
      <p style="margin:8px 0 0;color:#666;font-size:16px">Edit or cancel booking request</p>
    </div>
    
    <!-- Booking Details (Read-only) -->
    <div style="margin-top:32px;padding:20px;background:#f8f8f8;border-radius:12px">
      <p style="margin:0 0 8px;color:#666"><strong>Equipment:</strong> <?php echo htmlspecialchars($booking['equipment_name']) ?></p>
      <p style="margin:0 0 8px;color:#666"><strong>Lab:</strong> <?php echo htmlspecialchars($booking['lab_name']) ?></p>
      <p style="margin:0 0 8px;color:#666"><strong>Start:</strong> <?php echo date('M d, Y h:i A', strtotime($booking['start_time'])) ?></p>
      <p style="margin:0 0 8px;color:#666"><strong>End:</strong> <?php echo date('M d, Y h:i A', strtotime($booking['end_time'])) ?></p>
      <p style="margin:0;color:#666"><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($booking['status'])) ?></p>
    </div>
    
    <form method="post" action="?page=booking_update" style="margin-top:28px">
      <input type="hidden" name="booking_id" value="<?php echo $booking['id'] ?>" />
      
      <!-- Reason -->
      <div style="margin-bottom:28px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:15px">Reason</label>
        <textarea name="reason" rows="4" placeholder="Enter reason for edit/cancel booking" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff;resize:vertical"></textarea>
      </div>
      
      <!-- Action Buttons -->
      <div style="text-align:center;display:flex;flex-direction:column;gap:12px">
        <button type="submit" name="action" value="update" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Save Changes</button>
        <button type="submit" name="action" value="cancel" onclick="return confirm('Are you sure you want to cancel this booking?')" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 4px 0 rgba(0,0,0,0.1)">Cancel Booking</button>
      </div>
      
    </form>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
