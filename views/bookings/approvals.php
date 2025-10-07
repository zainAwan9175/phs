<?php require __DIR__ . '/../header.php'; ?>
<div class="container" style="min-height:calc(100vh - 300px)">
  <div class="card">
    <h2>Approvals</h2>
    <?php if (empty($pending)): ?>
      <p>No pending bookings.</p>
    <?php else: ?>
      <?php foreach($pending as $p): ?>
        <div style="background:#fff;padding:12px;border-radius:12px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center">
          <div>
            <strong><?php echo htmlspecialchars($p['equipment_name']) ?></strong> — <?php echo htmlspecialchars($p['lab_name']) ?><br/>
            Requested by: <?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?><br/>
            <?php echo htmlspecialchars($p['start_time']) ?> → <?php echo htmlspecialchars($p['end_time']) ?>
          </div>
          <div>
            <form method="post" action="?page=approval_action">
              <input type="hidden" name="booking_id" value="<?php echo $p['id'] ?>" />
              <textarea name="comment" placeholder="Comment" style="width:260px;height:60px;margin-bottom:6px"></textarea>
              <div>
                <button class="btn" name="action" value="approve">Approve</button>
                <button class="btn" name="action" value="reject" style="background:#c33;margin-left:8px">Reject</button>
              </div>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
