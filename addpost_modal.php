<!-- Modal Structure for Creating a Post -->
<div class="modal" id="postModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <span>Create a Post</span>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <form method="POST" action="app/posts/add_post.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="postTitle">Post Title</label>
                <input type="text" id="postTitle" name="title" placeholder="Enter your title" required>
            </div>
            <div class="form-group">
                <label for="postDescription">Description</label>
                <textarea id="postDescription" name="content" rows="4" placeholder="Write your description" required></textarea>
            </div>
            <div class="form-group">
                <label for="postImage">Image</label>
                <input type="file" id="postImage" name="image_url" accept="image/*">
            </div>
            <div class="form-group">
                <label for="postCategory">Category</label>
                <select id="postCategory" name="Category">
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

<!-- Script to open and close the modal -->
<script>
    // Open modal function
    function openModal() {
        const modal = document.getElementById("postModal");
        modal.style.display = "flex";
    }

    // Close modal function
    function closeModal() {
        const modal = document.getElementById("postModal");
        modal.style.display = "none";
    }

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById("postModal");
        if (event.target === modal) {
            closeModal();
        }
    };
</script>

<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .modal-content {
        background: #121212;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.2em;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
    }
</style>
