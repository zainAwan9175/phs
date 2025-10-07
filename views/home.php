<?php require __DIR__ . '/header.php'; ?>
<div class="container">
  <?php $user = function_exists('current_user') ? current_user() : null; ?>
  <?php if (!$user): ?>
    <div class="hero">
      <h1>Welcome to Smart Lab</h1>
      <p>Easily book and manage your lab equipment</p>
      <div style="margin-top:18px">
        <a class="btn" href="?page=login">Sign In</a>
        <a class="btn secondary" href="?page=register" style="margin-left:12px">Register</a>
      </div>
    </div>
  <?php else: ?>
    <div style="text-align:center;padding:40px 20px">
      <h2 style="color:#2d3b7a;margin:0;font-size:32px">WELCOME TO SMART LAB</h2>
      <p style="color:#666;margin-top:12px;font-size:18px">Easily book and manage your lab equipment</p>
    </div>
  </div>

  <!-- Full width labs panel -->
  <div class="labs-full">
    <div class="labs-panel">
      <div class="labs-inner">
        <div class="labs-grid">
        <?php
          $labs = $pdo->query('SELECT * FROM labs ORDER BY name')->fetchAll();
          foreach($labs as $l):
            $img = $l['image_base64'] ? $l['image_base64'] : 'https://via.placeholder.com/320x180?text=Lab';
        ?>
          <div class="lab-card">
            <div class="lab-card-image">
              <img src="<?php echo $img ?>" alt="<?php echo htmlspecialchars($l['name']) ?>" />
            </div>

            <div class="lab-title"><?php echo htmlspecialchars($l['name']) ?></div>

            <a class="view-btn" href="?page=lab&id=<?php echo $l['id'] ?>">View Lab</a>
          </div>
        <?php endforeach; ?>

        </div>
      </div>
    </div>
  </div>
  <div class="container">
  <?php endif; ?>
</div>

<?php require __DIR__ . '/footer.php'; ?>
