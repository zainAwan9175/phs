<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 300px);padding:48px 24px">
  
  <!-- Title -->
  <h1 style="text-align:center;color:#2d3b7a;font-size:42px;margin:0 0 48px;font-weight:800">Lab Manager Dashboard</h1>
  
  <!-- Tiles Grid -->
  <div style="max-width:1000px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px">
    
    <!-- Maintenance Tasks -->
    <a href="?page=manager_maintenance_tasks" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px;box-shadow:0 4px 12px rgba(102,126,234,0.4)">
        🔧
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Maintenance Tasks</h2>
    </a>
    
    <!-- Add & Edit Equipment -->
    <a href="?page=admin_equipment" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px;box-shadow:0 4px 12px rgba(240,147,251,0.4)">
        🔧
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Add & Edit Equipment</h2>
    </a>
    
    <!-- Equipment Reports (NEW) -->
    <a href="?page=manager_equipment_reports" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px;box-shadow:0 4px 12px rgba(79,172,254,0.4)">
        📊
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Equipment Reports</h2>
    </a>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
