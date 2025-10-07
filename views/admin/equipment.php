<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <?php if (!empty($_SESSION['flash'])): ?><div style="background:#e6ffed;padding:10px;border-radius:8px;margin-bottom:16px"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
  
  <!-- Add Equipment Form -->
  <div style="max-width:580px;margin:24px auto;background:#fff;padding:40px;border-radius:28px;box-shadow:0 12px 0 rgba(0,0,0,0.04)">
    <h1 style="text-align:center;margin:0 0 32px;font-size:32px;color:#2d3b7a">Add Equipment</h1>
    
    <form method="post" action="?page=admin_equipment_action">
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Select Lab</label>
        <select name="lab_id" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8">
          <option value="">Choose a lab...</option>
          <?php foreach($labs as $l): ?>
            <option value="<?php echo $l['id'] ?>" <?php echo ($l['id']==$selectedLab?' selected':'') ?>><?php echo htmlspecialchars($l['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Equipment ID</label>
        <input type="text" name="equipment_id" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" placeholder="Optional unique identifier" />
      </div>
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Equipment Name</label>
        <input type="text" name="name" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" placeholder="e.g., Microscope, Beaker, etc." />
      </div>
      
      <div style="margin-bottom:24px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Description</label>
        <input type="text" name="description" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" placeholder="Brief description" />
      </div>
      
      <div style="text-align:center;display:flex;gap:12px;justify-content:center">
        <button type="submit" class="btn" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:600">Add Equipment</button>
      </div>
    </form>
  </div>

  <!-- Equipment Table -->
  <div style="margin-top:40px">
    <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 0 rgba(0,0,0,0.04)">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h2 style="margin:0;color:#2d3b7a">Equipment List</h2>
        <div>
          <label style="font-weight:600;margin-right:8px;color:#333">Filter by Lab:</label>
          <select onchange="window.location='?page=admin_equipment&lab_id='+this.value" style="padding:10px 16px;border-radius:8px;border:1px solid #e0e0e0;font-size:14px;background:#f8f8f8">
            <option value="">All Labs</option>
            <?php foreach($labs as $l): ?>
              <option value="<?php echo $l['id'] ?>" <?php echo ($l['id']==$selectedLab?' selected':'') ?>><?php echo htmlspecialchars($l['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      
      <?php if ($selectedLab): ?>
        <p style="background:#e3f2fd;padding:12px;border-radius:8px;margin-bottom:16px">
          Showing equipment for: <strong><?php echo htmlspecialchars(array_values(array_filter($labs, function($x) use($selectedLab){return $x['id']==$selectedLab;}))[0]['name'] ?? '') ?></strong>
        </p>
      <?php endif; ?>
      
      <?php if (empty($equipment)): ?>
        <p style="text-align:center;color:#666;padding:40px">No equipment found. <?php echo $selectedLab ? 'Try selecting a different lab.' : 'Add equipment using the form above.' ?></p>
      <?php else: ?>
        <table style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:#f5f5f5;border-bottom:2px solid #ddd">
              <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Lab</th>
              <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Name</th>
              <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Description</th>
              <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($equipment as $e): ?>
              <tr style="border-bottom:1px solid #eee">
                <td style="padding:12px"><?php echo htmlspecialchars($e['lab_name']) ?></td>
                <td style="padding:12px;font-weight:500"><?php echo htmlspecialchars($e['name']) ?></td>
                <td style="padding:12px;color:#666"><?php echo htmlspecialchars($e['condition_note']) ?></td>
                <td style="padding:12px;text-align:center">
                  <a href="?page=admin_equipment_edit&id=<?php echo $e['id'] ?>" style="color:#1976d2;text-decoration:none;font-weight:500;padding:6px 12px;border-radius:6px;background:#e3f2fd;display:inline-block;margin-right:8px">Edit</a>
                  <a href="?page=admin_equipment_delete&id=<?php echo $e['id'] ?>" onclick="return confirm('Delete this equipment?')" style="color:#d32f2f;text-decoration:none;font-weight:500;padding:6px 12px;border-radius:6px;background:#ffebee;display:inline-block">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
