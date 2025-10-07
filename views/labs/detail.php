<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px)">
  <div style="max-width:1100px;margin:0 auto;padding:32px 24px">
    
    <!-- Lab Header Card -->
    <div style="display:flex;align-items:center;gap:24px;background:#fff;padding:28px;border-radius:20px;box-shadow:0 4px 0 rgba(0,0,0,0.04);margin-bottom:32px">
      <div style="width:140px;height:140px;border-radius:16px;overflow:hidden;flex-shrink:0">
        <img src="<?php echo $lab['image_base64'] ?: 'https://via.placeholder.com/140' ?>" alt="<?php echo htmlspecialchars($lab['name']) ?>" style="width:100%;height:100%;object-fit:cover" />
      </div>
      <div>
        <h1 style="margin:0;font-size:32px;color:#2d3b7a"><?php echo htmlspecialchars($lab['name']) ?></h1>
        <?php if (!empty($lab['description'])): ?>
          <p style="margin:8px 0 0;color:#666"><?php echo htmlspecialchars($lab['description']) ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Search Bar -->
    <div style="text-align:center;margin-bottom:32px">
      <div style="position:relative;display:inline-block;width:100%;max-width:600px">
        <span style="position:absolute;left:18px;top:50%;transform:translateY(-50%);font-size:20px">🔍</span>
        <input type="text" id="equipmentSearch" placeholder="Search" style="width:100%;padding:14px 18px 14px 50px;border-radius:30px;border:1px solid #ddd;font-size:16px;background:#fff;box-shadow:0 2px 4px rgba(0,0,0,0.06)" />
      </div>
    </div>

    <!-- Equipment Table -->
    <div style="background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 4px 0 rgba(0,0,0,0.04)">
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#f5f5f5">
            <th style="padding:16px 20px;text-align:left;font-weight:700;color:#2d3b7a;font-size:16px">Equipment name</th>
            <th style="padding:16px 20px;text-align:left;font-weight:700;color:#2d3b7a;font-size:16px">Description</th>
            <th style="padding:16px 20px;text-align:center;font-weight:700;color:#2d3b7a;font-size:16px;min-width:280px">Availability</th>
          </tr>
        </thead>
        <tbody id="equipmentTableBody">
        <?php if (empty($equipment)): ?>
          <tr>
            <td colspan="3" style="padding:40px;text-align:center;color:#999">No equipment available in this lab.</td>
          </tr>
        <?php else: ?>
          <?php foreach($equipment as $e): ?>
            <tr style="border-top:1px solid #e5e5e5" class="equipment-row" data-name="<?php echo htmlspecialchars(strtolower($e['name'])) ?>" data-desc="<?php echo htmlspecialchars(strtolower($e['condition_note'] ?? '')) ?>">
              <td style="padding:16px 20px;font-weight:600;color:#222"><?php echo htmlspecialchars($e['name']) ?></td>
              <td style="padding:16px 20px;color:#666"><?php echo htmlspecialchars($e['condition_note'] ?? '') ?></td>
              <td style="padding:16px 20px;text-align:center;white-space:nowrap">
                <?php if ($e['status'] === 'available'): ?>
                  <span style="display:inline-block;background:#7ed957;color:#fff;padding:8px 20px;border-radius:6px;font-weight:600;font-size:14px;margin-right:12px">Available</span>
                  <a href="?page=booking_request&equipment_id=<?php echo $e['id'] ?>" style="display:inline-block;background:#0b84ff;color:#fff;padding:8px 20px;border-radius:6px;text-decoration:none;font-weight:600;font-size:14px">Book Now</a>
                <?php elseif ($e['status'] === 'maintenance'): ?>
                  <span style="display:inline-block;background:#ffa726;color:#fff;padding:8px 20px;border-radius:6px;font-weight:600;font-size:14px;margin-right:12px">Maintenance</span>
                  <span style="display:inline-block;background:#e0e0e0;color:#757575;padding:8px 20px;border-radius:6px;font-weight:600;font-size:14px;cursor:not-allowed">Unavailable</span>
                <?php elseif ($e['status'] === 'in-use' || $e['status'] === 'in_use'): ?>
                  <span style="display:inline-block;background:#ff7961;color:#fff;padding:8px 20px;border-radius:6px;font-weight:600;font-size:14px;margin-right:12px">In Use</span>
                  <span style="display:inline-block;background:#e0e0e0;color:#757575;padding:8px 20px;border-radius:6px;font-weight:600;font-size:14px;cursor:not-allowed">Unavailable</span>
                <?php else: ?>
                  <span style="display:inline-block;background:#9e9e9e;color:#fff;padding:8px 20px;border-radius:6px;font-weight:600;font-size:14px;margin-right:12px"><?php echo ucfirst($e['status']) ?></span>
                  <span style="display:inline-block;background:#e0e0e0;color:#757575;padding:8px 20px;border-radius:6px;font-weight:600;font-size:14px;cursor:not-allowed">Unavailable</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<script>
// Live search functionality
document.getElementById('equipmentSearch')?.addEventListener('input', function(e) {
  const searchTerm = e.target.value.toLowerCase();
  const rows = document.querySelectorAll('.equipment-row');
  
  rows.forEach(row => {
    const name = row.getAttribute('data-name');
    const desc = row.getAttribute('data-desc');
    
    if (name.includes(searchTerm) || desc.includes(searchTerm)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
});
</script>

<?php require __DIR__ . '/../footer.php'; ?>
