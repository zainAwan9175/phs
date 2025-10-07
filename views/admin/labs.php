<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px">
    <h2 style="margin:0;color:#2d3b7a">Admin — Labs</h2>
    <a href="?page=admin_lab_add" class="btn" style="background:#0b84ff;color:#fff;padding:12px 28px;border-radius:28px;text-decoration:none;font-weight:600">+ Add New Lab</a>
  </div>

  <div style="background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 0 rgba(0,0,0,0.04)">
    <h2 style="margin:0 0 24px;color:#2d3b7a">All Labs</h2>
    
    <?php if (empty($labs)): ?>
      <p style="text-align:center;color:#666;padding:40px">No labs found. Add a lab using the button above.</p>
    <?php else: ?>
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#f5f5f5;border-bottom:2px solid #ddd">
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a;width:100px">Image</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Lab Name</th>
            <th style="padding:12px;text-align:left;font-weight:600;color:#2d3b7a">Description</th>
            <th style="padding:12px;text-align:center;font-weight:600;color:#2d3b7a;min-width:400px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($labs as $l): ?>
            <tr style="border-bottom:1px solid #eee">
              <td style="padding:12px">
                <div style="width:80px;height:80px;border-radius:8px;overflow:hidden">
                  <img src="<?php echo $l['image_base64']?:'https://via.placeholder.com/120' ?>" style="width:100%;height:100%;object-fit:cover" />
                </div>
              </td>
              <td style="padding:12px;font-weight:600"><?php echo htmlspecialchars($l['name']) ?></td>
              <td style="padding:12px;color:#666"><?php echo htmlspecialchars($l['description'] ?? '') ?></td>
              <td style="padding:12px;text-align:center;white-space:nowrap">
                <a href="?page=admin_equipment&lab_id=<?php echo $l['id'] ?>" style="color:#168890;text-decoration:none;font-weight:500;padding:6px 12px;border-radius:6px;background:#e0f7f8;display:inline-block;margin:2px">Manage Equipment</a>
                <a href="?page=admin_lab_edit&id=<?php echo $l['id'] ?>" style="color:#1976d2;text-decoration:none;font-weight:500;padding:6px 12px;border-radius:6px;background:#e3f2fd;display:inline-block;margin:2px">Edit</a>
                <a href="?page=admin_lab_delete&id=<?php echo $l['id'] ?>" onclick="return confirm('Delete this lab and all its equipment?')" style="color:#d32f2f;text-decoration:none;font-weight:500;padding:6px 12px;border-radius:6px;background:#ffebee;display:inline-block;margin:2px">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
