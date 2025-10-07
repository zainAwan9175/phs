<?php require __DIR__ . '/header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px);padding:48px 24px">
  
  <div style="max-width:1100px;margin:0 auto">
    
    <!-- Main Card -->
    <div style="background:#fff;padding:48px;border-radius:32px;box-shadow:0 6px 12px rgba(0,0,0,0.08)">
      
      <!-- Title -->
      <h1 style="text-align:center;color:#2d3b7a;font-size:42px;margin:0 0 48px;font-weight:800">Contact Us</h1>
      
      <!-- Two Column Layout -->
      <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:48px">
        
        <!-- Left Column - Contact Info -->
        <div style="background:#f8f9fa;padding:40px 32px;border-radius:20px">
          
          <!-- Address -->
          <div style="margin-bottom:32px">
            <div style="display:flex;align-items:start;gap:16px">
              <div style="width:40px;height:40px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:20px">
                📍
              </div>
              <div>
                <h3 style="margin:0 0 8px;color:#2d3b7a;font-size:18px;font-weight:700">Address</h3>
                <p style="margin:0;color:#666;line-height:1.6">
                  11/123 ABC Street,<br/>
                  Sydney, NSW, 2000
                </p>
              </div>
            </div>
          </div>
          
          <!-- Phone -->
          <div style="margin-bottom:32px">
            <div style="display:flex;align-items:start;gap:16px">
              <div style="width:40px;height:40px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:20px">
                📞
              </div>
              <div>
                <h3 style="margin:0 0 8px;color:#2d3b7a;font-size:18px;font-weight:700">Phone</h3>
                <p style="margin:0;color:#666">+61 234567890</p>
              </div>
            </div>
          </div>
          
          <!-- Email -->
          <div>
            <div style="display:flex;align-items:start;gap:16px">
              <div style="width:40px;height:40px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:20px">
                ✉️
              </div>
              <div>
                <h3 style="margin:0 0 8px;color:#2d3b7a;font-size:18px;font-weight:700">Email</h3>
                <p style="margin:0;color:#666">info@smartlab.edu.au</p>
              </div>
            </div>
          </div>
          
        </div>
        
        <!-- Right Column - Contact Form -->
        <div>
          
          <h2 style="color:#2d3b7a;font-size:28px;margin:0 0 24px;font-weight:700">Online Enquiry</h2>
          
          <form method="POST" action="?page=contact_action">
            
            <!-- Name Row -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
              <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">First Name</label>
                <input type="text" name="first_name" required placeholder="Enter your first name" style="width:100%;padding:12px 16px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
              </div>
              <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Last Name</label>
                <input type="text" name="last_name" required placeholder="Enter your last name" style="width:100%;padding:12px 16px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
              </div>
            </div>
            
            <!-- Contact Details Row -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
              <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Email address</label>
                <input type="email" name="email" required placeholder="Enter your email address" style="width:100%;padding:12px 16px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
              </div>
              <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Phone Number</label>
                <input type="tel" name="phone" placeholder="••••••••••" style="width:100%;padding:12px 16px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff" />
              </div>
            </div>
            
            <!-- Message -->
            <div style="margin-bottom:24px">
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px">Message</label>
              <textarea name="message" rows="5" required placeholder="Enter your message..." style="width:100%;padding:12px 16px;border-radius:8px;border:1px solid #e0e0e0;font-size:15px;background:#fff;resize:vertical;font-family:inherit"></textarea>
            </div>
            
            <!-- Submit Button -->
            <div style="text-align:center">
              <button type="submit" style="background:#2d3b7a;color:#fff;padding:14px 48px;border-radius:28px;border:none;cursor:pointer;font-size:16px;font-weight:700;box-shadow:0 4px 0 rgba(0,0,0,0.1);transition:background 0.2s" onmouseover="this.style.background='#1f2a54'" onmouseout="this.style.background='#2d3b7a'">
                Send
              </button>
            </div>
            
          </form>
          
        </div>
        
      </div>
      
    </div>
    
  </div>
  
</div>

<?php if (!empty($_SESSION['contact_flash'])): ?>
  <script>
    alert('<?php echo addslashes($_SESSION['contact_flash']); ?>');
  </script>
  <?php unset($_SESSION['contact_flash']); ?>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
