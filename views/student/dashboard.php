<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px)">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'student') { echo 'Access denied'; exit; } ?>
  
  <div style="text-align:center;padding-top:48px">
    <h1 style="color:#2d3b7a;font-size:36px;margin:0;font-weight:700">Student Dashboard</h1>
  </div>
  
  <div style="max-width:700px;margin:48px auto;padding:0 24px;display:grid;grid-template-columns:1fr 1fr;gap:32px">
    
    <!-- Search Equipment -->
    <a href="?page=labs" style="text-decoration:none;background:#fff;padding:40px 32px;border-radius:24px;box-shadow:0 6px 0 rgba(0,0,0,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:transform 0.2s;min-height:180px">
      <div style="width:80px;height:80px;border-radius:50%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:40px">
        🔍
      </div>
      <h2 style="color:#000;margin:0;font-size:20px;font-weight:700;text-align:center">Search Equipment</h2>
    </a>
    
    <!-- Request Booking -->
    <a href="?page=booking_request" style="text-decoration:none;background:#fff;padding:40px 32px;border-radius:24px;box-shadow:0 6px 0 rgba(0,0,0,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:transform 0.2s;min-height:180px">
      <div style="width:80px;height:80px;border-radius:50%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:40px">
        📝
      </div>
      <h2 style="color:#000;margin:0;font-size:20px;font-weight:700;text-align:center">Request Booking</h2>
    </a>
    
    <!-- Edit/Cancel Booking -->
    <a href="?page=my_bookings" style="text-decoration:none;background:#fff;padding:40px 32px;border-radius:24px;box-shadow:0 6px 0 rgba(0,0,0,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:transform 0.2s;min-height:180px">
      <div style="width:80px;height:80px;border-radius:50%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:40px">
        ✏️
      </div>
      <h2 style="color:#000;margin:0;font-size:20px;font-weight:700;text-align:center">Edit/Cancel Booking</h2>
    </a>
    
    <!-- View equipment details & availability -->
    <a href="?page=labs" style="text-decoration:none;background:#fff;padding:40px 32px;border-radius:24px;box-shadow:0 6px 0 rgba(0,0,0,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:transform 0.2s;min-height:180px">
      <div style="width:80px;height:80px;border-radius:50%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:40px">
        📊
      </div>
      <h2 style="color:#000;margin:0;font-size:18px;font-weight:700;text-align:center;line-height:1.3">View equipment details & availability</h2>
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
