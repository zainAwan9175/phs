<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px)">
  <?php $user = function_exists('current_user') ? current_user() : null; if (!$user || ($user['role_name'] ?? '') !== 'admin') { echo 'Access denied'; exit; } ?>
  
  <div style="text-align:center;padding-top:48px">
    <h1 style="color:#2d3b7a;font-size:36px;margin:0;font-weight:700">Admin Dashboard</h1>
    <p style="color:#666;margin-top:8px;font-size:16px">Manage labs, equipment, users and system settings</p>
  </div>
  
  <div style="max-width:900px;margin:48px auto;padding:0 24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px">
    
    <!-- Manage Labs -->
    <a href="?page=admin_labs" style="text-decoration:none;background:#fff;padding:32px 24px;border-radius:20px;box-shadow:0 6px 0 rgba(0,0,0,0.08);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:all 0.2s;min-height:160px">
      <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;font-size:32px">
        🏢
      </div>
      <h2 style="color:#2d3b7a;margin:0;font-size:18px;font-weight:700;text-align:center">Manage Labs</h2>
      <p style="color:#666;margin:0;font-size:13px;text-align:center">Add, edit or delete labs</p>
    </a>
    
    <!-- Manage Equipment -->
    <a href="?page=admin_equipment" style="text-decoration:none;background:#fff;padding:32px 24px;border-radius:20px;box-shadow:0 6px 0 rgba(0,0,0,0.08);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:all 0.2s;min-height:160px">
      <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);display:flex;align-items:center;justify-content:center;font-size:32px">
        🔧
      </div>
      <h2 style="color:#2d3b7a;margin:0;font-size:18px;font-weight:700;text-align:center">Manage Equipment</h2>
      <p style="color:#666;margin:0;font-size:13px;text-align:center">Add, edit equipment inventory</p>
    </a>
    
    <!-- User Management -->
    <a href="?page=admin_users" style="text-decoration:none;background:#fff;padding:32px 24px;border-radius:20px;box-shadow:0 6px 0 rgba(0,0,0,0.08);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:all 0.2s;min-height:160px">
      <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);display:flex;align-items:center;justify-content:center;font-size:32px">
        👥
      </div>
      <h2 style="color:#2d3b7a;margin:0;font-size:18px;font-weight:700;text-align:center">User Management</h2>
      <p style="color:#666;margin:0;font-size:13px;text-align:center">Manage users and roles</p>
    </a>
    
    <!-- Contact Messages -->
    <a href="?page=admin_contact_messages" style="text-decoration:none;background:#fff;padding:32px 24px;border-radius:20px;box-shadow:0 6px 0 rgba(0,0,0,0.08);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:all 0.2s;min-height:160px">
      <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,#fa709a 0%,#fee140 100%);display:flex;align-items:center;justify-content:center;font-size:32px">
        📧
      </div>
      <h2 style="color:#2d3b7a;margin:0;font-size:18px;font-weight:700;text-align:center">Contact Messages</h2>
      <p style="color:#666;margin:0;font-size:13px;text-align:center">View user inquiries</p>
    </a>
    
    <!-- Rules & Guidelines -->
    <a href="?page=admin_rules" style="text-decoration:none;background:#fff;padding:32px 24px;border-radius:20px;box-shadow:0 6px 0 rgba(0,0,0,0.08);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;transition:all 0.2s;min-height:160px">
      <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,#a8edea 0%,#fed6e3 100%);display:flex;align-items:center;justify-content:center;font-size:32px">
        📋
      </div>
      <h2 style="color:#2d3b7a;margin:0;font-size:18px;font-weight:700;text-align:center">Rules & Guidelines</h2>
      <p style="color:#666;margin:0;font-size:13px;text-align:center">Manage lab rules</p>
    </a>
    
  </div>
</div>

<style>
a[href*="page="]:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
}
</style>

<?php require __DIR__ . '/../footer.php'; ?>
