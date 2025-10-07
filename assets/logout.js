// Logout confirmation modal functions
function showLogoutModal() {
  const modal = document.getElementById('logoutModal');
  if (modal) {
    modal.style.display = 'flex';
  }
}

function closeLogoutModal() {
  const modal = document.getElementById('logoutModal');
  if (modal) {
    modal.style.display = 'none';
  }
}

function confirmLogout() {
  // Redirect to logout action
  window.location.href = '?page=logout_action';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('logoutModal');
  if (modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        closeLogoutModal();
      }
    });
  }
  
  // Close on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeLogoutModal();
    }
  });
});
