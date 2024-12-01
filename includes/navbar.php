<?php
// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']); // Get the current page name
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogging System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<!-- Header -->
<header>
    <div class="navbar">
        <div class="logo">Blog</div>
        <div class="search-bar">
            <form action="search.php" method="get">
                <input type="text" name="query" class="search-input" placeholder="Search...">
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
        <div class="menu">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Show user links if logged in -->
                <a href="user.php" <?php echo $current_page == 'user.php' ? 'class="active"' : ''; ?>>Home</a>
                <a href="messages.php" <?php echo $current_page == 'messages.php' ? 'class="active"' : ''; ?>>Messages</a>
                <a href="logout.php">Logout</a>

                <!-- Display Full Name as a clickable button -->
                <a href="profile.php" class="fullname-btn">
                    <?php echo htmlspecialchars($_SESSION['fullname']); ?>
                </a>
            <?php else: ?>
                <!-- Show login link if not logged in -->
                <a href="login.php" <?php echo $current_page == 'login.php' ? 'class="active"' : ''; ?>>Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>
</body>
</html>
