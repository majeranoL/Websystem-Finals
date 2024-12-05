<?php
session_start();
?>
<?php   
    include('includes/navbar.php');  
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogging System</title> <!-- This sets the title of the webpage displayed in the browser tab -->
</head>

<link rel="stylesheet" href="Styles/Buttons.css">
<div class="main-container">
    
    <?php include('includes/sidebar.php'); ?>
    <!-- Content Section -->
    <div class="content">
        <div class="feed">
            <div class="post">
                <div class="post-title">The Future of Web Development</div>
                <div class="post-content">
                    INDEX TO PARA DI AKO MALITO
                    <a href="#" class="text-primary">Read More</a>
                </div>
                <div class="post-meta">Posted by <strong>[user]</strong> on [date]</div>
                <div class="vote-buttons">
                    <button class="upvote">▲</button>
                    <span>25</span>
                    <button class="downvote">▼</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Post Button -->
    <!-- <a href="login.php" class="create-post-btn">+</a> -->
</div>
