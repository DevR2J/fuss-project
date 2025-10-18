<?php
// Header is intended to be included inside protected pages only.
// It will render navigation links when user is logged in.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<?php if ($isLoggedIn): ?>
  <header class="site-header">
    <nav class="nav">
      <a class="nav-brand" href="index.php">FUSS</a>
      <div class="nav-links">
        <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
        <a href="profile.php" class="<?= $currentPage === 'profile.php' ? 'active' : '' ?>">Profile</a>
        <a href="skills.php" class="<?= $currentPage === 'skills.php' ? 'active' : '' ?>">Skills</a>
        <a href="browse-requests.php" class="<?= $currentPage === 'browse-requests.php' ? 'active' : '' ?>">Browse Requests</a>
        <a href="history.php" class="<?= $currentPage === 'history.php' ? 'active' : '' ?>">History</a>
      </div>
      <div class="nav-actions">
        <a class="btn btn-primary" href="logout.php">Logout</a>
      </div>
    </nav>
  </header>
<?php endif; ?>
