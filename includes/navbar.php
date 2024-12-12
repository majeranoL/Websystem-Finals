<?php
// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']); // Get the current page name
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="Styles/index.css">

<body>
  <!-- Header -->
  <header>
    <div class="navbar">
      <!-- Logo -->
      <div class="logo">Blog</div>
      
      <!-- Search Group -->
      <div class="group" 
        <?php if (isset($_SESSION['user_id'])): ?> 
          style="margin-left: 200px;" 
        <?php endif; ?>
      >
        <!-- Search Icon -->
        <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
          <g>
            <path
              d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"
            ></path>
          </g>
        </svg>

        <!-- Search Input -->
        <input
          id="query"
          class="input"
          type="search"
          placeholder="Search..."
          name="searchbar"
        />
      </div>

      <!-- Navbar Menu -->
      <div class="menu">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="/user.php" <?php echo $current_page == 'user.php' ? 'class="active"' : ''; ?>>Home</a>
          
          <!-- Notifications Link -->
          <a href="notification.php" <?php echo $current_page == 'notification.php' ? 'class=active' : ''; ?>>Notifications</a>
          
          <!-- Dropdown Menu for User Profile -->
          <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo htmlspecialchars($_SESSION['fullname']); ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="userDropdown" style="background-color: #1a1a1a; border: none;">
              <li><a class="dropdown-item" href="profile.php" style="color: rgba(255, 255, 255, 0.9); padding: 10px 20px; background-color: #1a1a1a;">Profile</a></li>
              <li><hr class="dropdown-divider" style="border-color: #333333;"></li>
              <li>
                <form action="/logout.php" method="POST">
                  <button type="submit" class="dropdown-item" style="color: rgba(255, 255, 255, 0.9); padding: 10px 20px; background-color: #1a1a1a;">Logout</button>
                </form>
              </li>
            </ul>
          </div>
        <?php else: ?>
          <a href="login.php" <?php echo $current_page == 'login.php' ? 'class="active"' : ''; ?>>Login</a>
        <?php endif; ?>
      </div>
    </div>
  </header>
</body>

</html>
