<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'lab_assistant') { echo 'Access denied'; exit; } ?>
  
  <div style="text-align:center;margin-top:32px">
    <h1 style="color:#2d3b7a;font-size:36px;margin:0">Lab Assistant Dashboard</h1>
  </div>
  
  <div style="max-width:700px;margin:48px auto;display:grid;grid-template-columns:1fr 1fr;gap:32px">
    
    <!-- Search Equipment -->
    <a href="?page=labs" style="text-decoration:none;background:#fff;padding:40px 32px;border-radius:24px;box-shadow:0 6px 0 rgba(0,0,0,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:transform 0.2s;min-height:180px">
      <div style="width:80px;height:80px;border-radius:50%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:40px">
        🔍
      </div>
      <h2 style="color:#000;margin:0;font-size:20px;font-weight:700;text-align:center">Search<br/>Equipment</h2>
    </a>
    
    <!-- Approvals -->
    <a href="?page=approvals" style="text-decoration:none;background:#fff;padding:40px 32px;border-radius:24px;box-shadow:0 6px 0 rgba(0,0,0,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:transform 0.2s;min-height:180px">
      <div style="width:80px;height:80px;border-radius:50%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:40px">
        ✅
      </div>
      <h2 style="color:#000;margin:0;font-size:20px;font-weight:700;text-align:center">Approvals</h2>
    </a>
    
    <!-- Equipment In-use -->
    <a href="?page=equipment_use" style="text-decoration:none;background:#fff;padding:40px 32px;border-radius:24px;box-shadow:0 6px 0 rgba(0,0,0,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:transform 0.2s;min-height:180px">
      <div style="width:80px;height:80px;border-radius:50%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:40px">
        ⚙️
      </div>
      <h2 style="color:#000;margin:0;font-size:20px;font-weight:700;text-align:center">Equipment<br/>In-use</h2>
    </a>
    
    <!-- Equipment Returned -->
    <a href="?page=equipment_return" style="text-decoration:none;background:#fff;padding:40px 32px;border-radius:24px;box-shadow:0 6px 0 rgba(0,0,0,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:transform 0.2s;min-height:180px">
      <div style="width:80px;height:80px;border-radius:50%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:40px">
        📦
      </div>
      <h2 style="color:#000;margin:0;font-size:20px;font-weight:700;text-align:center">Equipment<br/>returned</h2>
    </a>
    
  </div>
</div>

<style>
a[href*="page="]:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
}
</style>

<?php require __DIR__ . '/../footer.php'; ?>
