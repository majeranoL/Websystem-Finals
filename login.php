<link rel="stylesheet" href="Styles/login.css">

<body class="login-page">
    <div class="login wrap">
        <div class="h1">Login</div>
        <form action="app/auth/Login.php" method="POST">
        <input placeholder="Username" id="Username" name="username" type="text" required>
        <input placeholder="Password" id="password" name="password" type="password" required>
            <input value="Login" class="btn" type="submit">
        </form>
        <br>
        <div class="registration-link">
            <a href="registration.php">Don't have an account? Register here</a>
        </div>
    </div>
</body>
<?php include("includes/footer.php")?>
