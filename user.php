<?php
session_start();
require_once('app/config/DatabaseConnect.php');
require_once('includes/navbar.php');

// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];
    $voteValue = (int)$_POST['vote_value']; // 1 for upvote, -1 for downvote

    // Check if the user has already voted
    $checkVoteQuery = "SELECT vote_value FROM votes WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $conn->prepare($checkVoteQuery);
    $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);
    $existingVote = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingVote) {
        if ($existingVote['vote_value'] == $voteValue) {
            // If the user clicks the same vote button, remove the vote
            $deleteVoteQuery = "DELETE FROM votes WHERE post_id = :post_id AND user_id = :user_id";
            $stmt = $conn->prepare($deleteVoteQuery);
            $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);

            // Adjust the vote count by decrementing it
            $updateVoteQuery = "UPDATE posts SET vote = vote - :vote_value WHERE post_id = :post_id";
            $stmt = $conn->prepare($updateVoteQuery);
            $stmt->execute([':vote_value' => $voteValue, ':post_id' => $postId]);

            echo json_encode(['success' => true, 'new_vote_count' => 0]); // Reset to 0 since the vote is removed
        } else {
            // If the user switches vote (upvote to downvote or downvote to upvote)
            // Update the vote_value in the votes table
            $updateVoteQuery = "UPDATE votes SET vote_value = :vote_value WHERE post_id = :post_id AND user_id = :user_id";
            $stmt = $conn->prepare($updateVoteQuery);
            $stmt->execute([':vote_value' => $voteValue, ':post_id' => $postId, ':user_id' => $userId]);

            // Adjust the vote count by switching votes
            $adjustVoteQuery = "UPDATE posts SET vote = vote + :difference WHERE post_id = :post_id";
            $stmt = $conn->prepare($adjustVoteQuery);
            $difference = ($voteValue == 1) ? 2 : -2; // Switching from -1 to 1 or 1 to -1
            $stmt->execute([':difference' => $difference, ':post_id' => $postId]);

            echo json_encode(['success' => true, 'new_vote_count' => $difference]);
        }
    } else {
        // If no previous vote exists, insert a new vote
        $insertVoteQuery = "INSERT INTO votes (post_id, user_id, vote_value) VALUES (:post_id, :user_id, :vote_value)";
        $stmt = $conn->prepare($insertVoteQuery);
        $stmt->execute([':post_id' => $postId, ':user_id' => $userId, ':vote_value' => $voteValue]);

        // Update the vote count for the post
        $updateVoteQuery = "UPDATE posts SET vote = vote + :vote_value WHERE post_id = :post_id";
        $stmt = $conn->prepare($updateVoteQuery);
        $stmt->execute([':vote_value' => $voteValue, ':post_id' => $postId]);

        echo json_encode(['success' => true, 'new_vote_count' => $voteValue]);
    }
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="Styles/Buttons.css">
    <link rel="stylesheet" href="Styles/modal.css">
 
</head>
<body>

<?php
if (isset($_SESSION['success'])) {
    echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success']) . "</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='error-message'>" . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']);
}
?>
<?php include('includes/sidebar.php'); ?>
<div class="main-container">
    <div class="content">
        <div class="feed">
            <?php
            if ($conn) {
                $query = "SELECT p.post_id, p.user_id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, u.username 
                          FROM posts p
                          JOIN users u ON p.user_id = u.user_id
                          ORDER BY p.created_at DESC";
                $stmt = $conn->prepare($query);

                try {
                    $stmt->execute();
                    $posts = $stmt->fetchAll();

                    if ($posts) {
                        foreach ($posts as $post) {
                            ?>
                            
                        <div id="post-<?php echo $post['post_id']; ?>" class="post">
                        <!-- Post Metadata -->
                        <div class="post-meta">
                            <link rel="stylesheet" href="Styles/post.css">
                            <!-- Post Title -->
                            <div class="post-title">
                                <a href="app/posts/view_post.php?post_id=<?php echo $post['post_id']; ?>" style="text-decoration: none; color: inherit;">
                                    <h2 style="color: white;"><?php echo htmlspecialchars($post['title']); ?></h2>
                                </a>
                            </div>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) { ?>
                            <div class="post-dropdown">
                                <button class="post-dropdown-toggle" onclick="toggleDropdown(<?php echo $post['post_id']; ?>)" style="all: unset; cursor: pointer;">
                                    <svg fill="currentColor" height="16" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm6 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"></path>
                                    </svg>
                                </button>
                                <div id="dropdown-<?php echo $post['post_id']; ?>" class="post-dropdown-menu" style="display: none;">
                                    <a href="javascript:void(0)" class="dropdown-item edit-link" data-post-id="<?php echo $post['post_id']; ?>" onclick="openEditPostModal(<?php echo $post['post_id']; ?>)">Edit</a>
                                    <a href="app/posts/delete_post.php?post_id=<?php echo $post['post_id']; ?>" class="dropdown-item" onclick="return confirm('Are you sure?');">Delete</a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- Post Category (Right aligned) -->
                        <div class="post-category" style="text-align: right; margin-top: 10px;"hidden>
                            <strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?>
                        </div>
                        <!-- Post Content -->
                        <div class="post-content" style="margin-top: 15px; font-size: 1.1em; line-height: 1.6;">
                            <?php echo htmlspecialchars(substr($post['content'], 0, 150)); ?>...
                            <a href="app/posts/view_post.php?post_id=<?php echo $post['post_id']; ?>" class="text-primary">Read More</a>
                        </div>
                        <!-- Post Image -->
                        <?php if (!empty($post['image_url'])) { ?>
                            <div class="post-image" style="margin-top: 15px;">
                                <a href="app/posts/view_post.php?post_id=<?php echo $post['post_id']; ?>">
                                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post Image" style="max-width: 100%; height: auto; margin-top: 15px;">
                                </a>
                            </div>
                        <?php } ?>

                        <p style="font-size: 0.85em; color: white;">
                            Posted by <strong style="font-size: 0.85em; color: black;"><?php echo htmlspecialchars($post['username']); ?></strong> 
                            on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                        </p>

                        <div class="vote-buttons" style="margin-top: 20px;">
                        <form action="app/interaction/upvote.php" method="POST" class="vote-form" data-post-id="<?php echo $post['post_id']; ?>" style="display: inline;">
                            <button type="submit" class="upvote" data-vote="1">▲</button>
                        </form>
                        <span class="vote-count" style="color: white;"><?php echo htmlspecialchars($post['vote']); ?></span>
                        <form action="app/interaction/downvote.php" method="POST" class="vote-form" data-post-id="<?php echo $post['post_id']; ?>" style="display: inline;">
                            <button type="submit" class="downvote" data-vote="-1">▼</button>
                        </form>
                    </div>
                    </div>
                        <?php
                        }
                    } else {
                        echo "<p>No posts available. Be the first to post!</p>";
                    }
                } catch (PDOException $e) {
                    echo "Error fetching posts: " . $e->getMessage();
                }
            } else {
                echo "<p>Database connection failed. Please try again later.</p>";
            }
            ?>
        </div>
    </div>

    <button class="create-post-btn" onclick="openAddPostModal()" style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">+</button>
</div>

<?php include('modal/addpost.php'); ?>
<?php include('modal/editpost.php'); ?>



<script>
    // Open the Add Post Modal
    function openAddPostModal() {
        const modal = document.getElementById('addPostModal');
        modal.style.display = 'flex';
    }

    // Open the Edit Post Modal
    function openEditPostModal(postId) {
        fetch('app/posts/fetch_post_data.php?post_id=' + postId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate modal fields with fetched data
                    document.getElementById('postTitle').value = data.post.title;
                    document.getElementById('postDescription').value = data.post.content;
                    document.getElementById('postCategory').value = data.post.category;
                    document.getElementById('currentImage').innerHTML = 'Current image: <img src="' + data.post.image_url + '" alt="Post Image" width="100">';

                    // Set the form's action to point to the correct edit_post.php URL
                    document.getElementById('editPostForm').action = 'app/posts/edit_post.php?post_id=' + postId;

                    // Display the Edit Post Modal
                    document.getElementById('editPostModal').style.display = 'flex';
                } else {
                    console.error('Error fetching post data: ', data.message);
                }
            })
            .catch(error => console.error('Error fetching post data:', error));
    }

    // Close modal function
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const editModal = document.getElementById('editPostModal');
        const addModal = document.getElementById('addPostModal');
        if (event.target === editModal) {
            closeModal('editPostModal');
        }
        if (event.target === addModal) {
            closeModal('addPostModal');
        }
    };

    // Close the Add Post Modal
    function closeAddPostModal() {
        const modal = document.getElementById('addPostModal');
        modal.style.display = 'none';
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle dropdown visibility when clicking the button
    document.querySelectorAll('.post-dropdown-toggle').forEach(function (dropdownToggle) {
        dropdownToggle.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent event from bubbling
            const dropdownMenu = this.nextElementSibling;
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', function () {
        document.querySelectorAll('.post-dropdown-menu').forEach(function (menu) {
            menu.style.display = 'none';
        });
    });
});

</script>
<style>
    button.voted {
    background-color: #4CAF50; /* Green for upvote, you can customize this */
    color: white;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const upvoteButtons = document.querySelectorAll('.upvote');
    const downvoteButtons = document.querySelectorAll('.downvote');
    
    // Function to handle voting
    function handleVote(voteValue, postId) {
        const voteForm = document.querySelector(`form[data-post-id='${postId}']`);
        const voteCountElement = document.querySelector(`#post-${postId} .vote-count`);
        
        // Disable the buttons while the request is being processed
        const upvoteButton = voteForm.querySelector('.upvote');
        const downvoteButton = voteForm.querySelector('.downvote');
        upvoteButton.disabled = true;
        downvoteButton.disabled = true;

        // Create a FormData object to send the form data (including vote value)
        const formData = new FormData(voteForm);
        formData.append('vote_value', voteValue);

        // Send the vote via AJAX
        fetch(voteForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Enable the buttons again after the request is complete
            upvoteButton.disabled = false;
            downvoteButton.disabled = false;

            if (data.success) {
                // Update the vote count in the UI
                voteCountElement.textContent = data.new_vote_count;

                // Remove highlight classes
                upvoteButton.classList.remove('voted');
                downvoteButton.classList.remove('voted');

                // Highlight the clicked button
                if (voteValue === 1) {
                    upvoteButton.classList.add('voted');
                } else if (voteValue === -1) {
                    downvoteButton.classList.add('voted');
                }
            } else {
                alert('Error: Unable to vote.');
            }
        })
        .catch(error => {
            console.error('Error voting:', error);
            // Re-enable buttons in case of an error
            upvoteButton.disabled = false;
            downvoteButton.disabled = false;
        });
    }

    // Attach event listeners to upvote and downvote buttons
    upvoteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
            const postId = this.closest('.vote-form').getAttribute('data-post-id');
            handleVote(1, postId);
        });
    });

    downvoteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
            const postId = this.closest('.vote-form').getAttribute('data-post-id');
            handleVote(-1, postId);
        });
    });
});
</script>

</body>
</html>