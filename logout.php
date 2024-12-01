<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
session_start();


session_unset(); 
session_destroy();


require_once(ROOT_DIR . "includes/navbar.php");

?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card text-center shadow p-3" style="width: 24rem;">
        <div class="card-body">
            <h5 class="card-title">You have been logged out</h5>
            <p class="card-text">Thank you for visiting. You are now logged out.</p>
            <a href="/index.php" class="btn btn-primary">Go to Home</a>
        </div>
    </div>
</div>

