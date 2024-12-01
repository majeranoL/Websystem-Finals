<link rel="stylesheet" href="styles.css">
<div class="registration-container">
    <div class="form-card">
        <h2 class="bg-primary text-white">Create a Post</h2>
        <?php if (isset($_SESSION["error"])) { ?>
            <div class="alert alert-danger">
                <strong><?php echo $_SESSION["error"]; ?></strong>
            </div>
        <?php unset($_SESSION["error"]); } ?>

        <form action="add_post.php" method="POST">
            <input type="text" class="form-control" name="title" placeholder="Post Title" required>
            <textarea class="form-control" name="content" placeholder="Post Content" required></textarea>
            <button type="submit" class="btn-register">Create Post</button>
        </form>
    </div>
</div>
