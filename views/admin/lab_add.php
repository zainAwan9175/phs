<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <div style="max-width:580px;margin:48px auto;background:#fff;padding:40px;border-radius:28px;box-shadow:0 12px 0 rgba(0,0,0,0.04)">
    <h1 style="text-align:center;margin:0 0 32px;font-size:32px;color:#2d3b7a">Add Lab</h1>
    
    <form method="post" action="?page=admin_lab_action" enctype="multipart/form-data">
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Lab Name</label>
        <input type="text" name="name" required style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" placeholder="Enter lab name" />
      </div>
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Location</label>
        <input type="text" name="location" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" placeholder="Building or room number" />
      </div>
      
      <div style="margin-bottom:18px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Description</label>
        <textarea name="description" rows="4" style="width:100%;padding:14px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#f8f8f8" placeholder="Brief description of the lab"></textarea>
      </div>
      
      <div style="margin-bottom:24px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Lab Image</label>
        <input type="file" name="image" accept="image/*" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e0e0e0;background:#f8f8f8" />
      </div>
      
      <div style="text-align:center">
        <button type="submit" class="btn" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:600">Save</button>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
