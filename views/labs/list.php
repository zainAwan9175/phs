<?php require __DIR__ . '/../header.php'; ?>
<div style="background:var(--mint);min-height:calc(100vh - 200px);padding:48px 24px">
  
  <div style="max-width:1100px;margin:0 auto">
    
    <!-- Page Title Card -->
    <div style="background:#fff;padding:32px;border-radius:24px;box-shadow:0 4px 12px rgba(0,0,0,0.08);margin-bottom:40px">
      <h1 style="text-align:center;color:#2d3b7a;font-size:36px;margin:0;font-weight:700">Labs</h1>
    </div>
    
    <!-- Labs Grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:32px">
      
      <?php foreach($labs as $l): ?>
        <div style="background:#fff;border-radius:24px;overflow:hidden;box-shadow:0 6px 12px rgba(0,0,0,0.08);transition:transform 0.2s,box-shadow 0.2s" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.08)'">
          
          <!-- Lab Image -->
          <div style="width:100%;height:200px;overflow:hidden;background:#f0f0f0">
            <?php if (!empty($l['image_base64'])): ?>
              <img src="<?php echo $l['image_base64'] ?>" alt="<?php echo htmlspecialchars($l['name']) ?>" style="width:100%;height:100%;object-fit:cover" />
            <?php else: ?>
              <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#168890 0%,#49BBBD 100%);color:#fff;font-size:48px">
                🔬
              </div>
            <?php endif; ?>
          </div>
          
          <!-- Lab Info -->
          <div style="padding:24px;text-align:center">
            <h3 style="margin:0 0 16px;color:#2d3b7a;font-size:20px;font-weight:700"><?php echo htmlspecialchars($l['name']) ?></h3>
            
            <!-- View Button -->
            <a href="?page=lab&id=<?php echo $l['id'] ?>" style="display:inline-block;background:#0b84ff;color:#fff;padding:10px 32px;border-radius:24px;text-decoration:none;font-weight:600;font-size:15px;transition:background 0.2s" onmouseover="this.style.background='#0066cc'" onmouseout="this.style.background='#0b84ff'">
              View
            </a>
          </div>
          
        </div>
      <?php endforeach; ?>
      
    </div>
    
  </div>
  
</div>
<?php require __DIR__ . '/../footer.php'; ?>
