<?php
session_start();
?>

<?php include('includes/navbar.php'); ?>
<link rel="stylesheet" href="Styles/Index&admin&user.css">
<link rel="stylesheet" href="Styles/Buttons.css">
<link rel="stylesheet" href="Styles/modal.css">
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
    <button class="create-post-btn" onclick="openModal()">+</button>
</div>

<!-- Modal -->
<div class="modal" id="postModal">
    <div class="modal-content">
        <div class="modal-header">Create a Post</div>
        <form method="POST" action="add_post_handler.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="postTitle">Post Title</label>
                <input type="text" id="postTitle" name="postTitle" placeholder="Enter your title" required>
            </div>
            <div class="form-group">
                <label for="postDescription">Description</label>
                <textarea id="postDescription" name="postDescription" rows="4" placeholder="Write your description" required></textarea>
            </div>
            <div class="form-group">
                <label for="postImage">Image</label>
                <input type="file" id="postImage" name="postImage" accept="image/*">
            </div>
            <div class="form-group">
                <label for="postCategory">Category</label>
                <select id="postCategory" name="postCategory">
                    <option value="Technology">Technology</option>
                    <option value="Lifestyle">Lifestyle</option>
                    <option value="Travel">Travel</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-submit">Post</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Open modal function
    function openModal() {
        document.getElementById("postModal").style.display = "flex";
    }

    // Close modal function
    function closeModal() {
        document.getElementById("postModal").style.display = "none";
    }

    // Close modal when clicking outside the modal content
    window.onclick = function (event) {
        const modal = document.getElementById("postModal");
        if (event.target === modal) {
            closeModal();
        }
    };
</script>

