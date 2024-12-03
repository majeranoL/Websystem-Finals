<?php 
    session_start();
    require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");

    if (isset($_SESSION["error"])) {
        $messageErr = $_SESSION["error"];
        unset($_SESSION["error"]);
    }

    if (isset($_SESSION["success"])) {
        $messageSucc = $_SESSION["success"];
        unset($_SESSION["success"]);
    }
?>
<link rel="stylesheet" href="Styles/Regform.css">

<body class="registration-page">
<div class="registration-container">
    <div class="form-card">
        <h2 class="bg-primary text-white">Login</h2>
        <form action="app/auth/Login.php" method="POST"> 
            <input type="text" class="form-control" name="username"  placeholder="Username" required>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <button type="submit" class="btn-register">Login</button>
        </form>
        <a href="Registration.php" style="">No Account yet? Sign up here</a>
    </div>
</div>
</body>


<?php require_once(ROOT_DIR . "includes/footer.php"); ?>
