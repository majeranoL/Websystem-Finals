<?php
session_start();
?>

<?php include('includes/navbar.php'); ?>
<link rel="stylesheet" href="Styles/index.css">
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
<button class="create-post-btn" onclick="openModal()" style="position: fixed; bottom: 10px; right: 10px; z-index: 1000;">+</button>

<!-- Chat Container (Positioned beside the button) -->
<div id="chatContainer" style="position: fixed; bottom: -20px; right: 80px; max-width: 300px; z-index: 1050;">
    <!-- Chat windows will dynamically appear here -->
</div>

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

<script>
function openChat(userName) {
    // Check if the chat window already exists
    if (document.getElementById('chat-' + userName)) {
        return; // Chat window already open
    }

    // Create a chat window
    const chatWindow = document.createElement('div');
    chatWindow.id = 'chat-' + userName;
    chatWindow.classList.add('chat-window');
    chatWindow.style = `
    position: absolute;
    width: 300px;
    height: 400px;
    background: #1a1a1a;
    border: none; /* Remove the white outline */
    border-radius: 8px;
    margin-bottom: 10px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
`;


    // Add chat header
    chatWindow.innerHTML = `
    <div style="background: #3b5998; color: #1a1a1a; padding: 10px; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between;">
        <span>${userName}</span>
        <button style="background: none; border: none; color: white; font-size: 16px; cursor: pointer;" onclick="closeChat('${userName}')">×</button>
    </div>
    <div style="flex: 1; padding: 10px; overflow-y: auto;">
        <p>Chat with ${userName}</p>
        <!-- Chat messages will go here -->
    </div>
    <div style="padding: 10px; border-top: 1px solid #ccc;">
        <input type="text" placeholder="Type a message" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; background: #1a1a1a; color: white;">
    </div>
`;


    // Append the chat window to the container
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.appendChild(chatWindow);

    // Get the number of existing chat windows
    const chatWindows = document.getElementsByClassName('chat-window');

    // Calculate the right position based on the number of open chats
    const windowWidth = chatWindow.offsetWidth;
    const gap = 10; // Space between chat windows
    const rightPosition = (chatWindows.length * (windowWidth + gap)) + gap; // Adjusted right position

    // Set the position of the new chat window
    chatWindow.style.right = rightPosition + 'px';
    chatWindow.style.bottom = '10px'; // Align all chat windows at the same bottom position
}

function closeChat(userName) {
    const chatWindow = document.getElementById('chat-' + userName);
    if (chatWindow) {
        chatWindow.remove(); // Remove the chat window
    }
}


</script>
