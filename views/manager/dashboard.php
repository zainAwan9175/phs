<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 300px);padding:48px 24px">
  
  <!-- Title -->
  <h1 style="text-align:center;color:#2d3b7a;font-size:42px;margin:0 0 48px;font-weight:800">Lab Manager Dashboard</h1>
  
  <!-- Tiles Grid -->
  <div style="max-width:1000px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px">
    
    <!-- Register Form -->
    <a href="?page=manager_register" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:#f0f0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px">
        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
          <line x1="12" y1="11" x2="12" y2="17"></line>
          <line x1="9" y1="14" x2="15" y2="14"></line>
        </svg>
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Register Form</h2>
    </a>
    
    <!-- Create Booking -->
    <a href="?page=manager_create_booking" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:#f0f0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px">
        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Create Booking</h2>
    </a>
    
    <!-- Approvals -->
    <a href="?page=approvals" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:#f0f0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px">
        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
          <polyline points="9 11 12 14 22 4"></polyline>
          <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
        </svg>
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Approvals</h2>
    </a>
    
    <!-- Create Maintenance Task -->
    <a href="?page=manager_create_maintenance" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:#f0f0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px">
        🔧
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Create maintenance task</h2>
    </a>
    
    <!-- Close Maintenance Task -->
    <a href="?page=manager_close_maintenance" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:#f0f0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px">
        ✅
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Close maintainence task</h2>
    </a>
    
    <!-- Report Issue -->
    <a href="?page=manager_report_issue" style="background:#fff;padding:40px;border-radius:32px;text-decoration:none;box-shadow:0 8px 16px rgba(0,0,0,0.06);transition:all 0.3s;display:flex;flex-direction:column;align-items:center;gap:20px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.06)'">
      <div style="width:80px;height:80px;background:#2d3b7a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px">
        <span style="color:#fff;font-size:48px;font-weight:900">!</span>
      </div>
      <h2 style="margin:0;color:#2d3b7a;font-size:22px;font-weight:700;text-align:center">Report Issue</h2>
    </a>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
