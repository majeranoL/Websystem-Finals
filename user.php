<?php
session_start();
?>

<?php include('includes/navbar.php'); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title> <!-- This sets the title of the webpage displayed in the browser tab -->
</head>


<link rel="stylesheet" href="Styles/index.css">
<link rel="stylesheet" href="Styles/Buttons.css">
<link rel="stylesheet" href="Styles/modal.css">

<body>
<div class="main-container">
    <?php include('includes/sidebar.php'); ?>
    <!-- Content Section -->
    <div class="content">
        <div>
            <div class="post">
                <div class="post-title">The Future of Web Development</div>
                <div class="post-content">
                    USER TO PARA MAANGAS
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
<button class="create-post-btn" onclick="openModal()" style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">+</button>

</div>
<?php include('modal/addpost.php'); ?>
</body>
