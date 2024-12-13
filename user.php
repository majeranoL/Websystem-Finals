<?php
session_start();
require_once('app/config/DatabaseConnect.php');
require_once('includes/navbar.php');

// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();
if(isset($_GET['category'])){
$category = $_GET['category'];
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

                    if (isset($_GET['category'])){

                        $query = "SELECT p.post_id, p.user_id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, u.username 
                                    FROM posts p
                                    JOIN users u ON p.user_id = u.user_id
                                    WHERE p.category = '$category'
                                    ORDER BY p.created_at DESC
                                    ";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    }
                    else{
                          $query = "SELECT p.post_id, p.user_id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, u.username 
                          FROM posts p
                          JOIN users u ON p.user_id = u.user_id
                          ORDER BY p.created_at DESC
                        ";
                        $stmt = $conn->prepare($query);


                    }
                  
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
                                                    <svg fill="currentColor" height="30" viewBox="0 0 20 20" width="30" xmlns="http://www.w3.org/2000/svg">
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
                                    <div class="post-category" style="text-align: right; margin-top: 10px;" hidden>
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
                                        <form action="" method="POST" class="vote-form" data-post-id="<?php echo $post['post_id']; ?>" style="display: inline;">
                                            <button type="submit" class="upvote" style="transition-duration: 0.2s; cursor: pointer;" data-vote="1">▲</button>
                                        </form>
                                        <span class="vote-count" style="color: white;"><?php echo htmlspecialchars($post['vote']); ?></span>
                                        <form action="" method="POST" class="vote-form" data-post-id="<?php echo $post['post_id']; ?>" style="display: inline;">
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



    <script src="/js/post.js"></script>




    <style>
        button.voted {
            background-color: #4CAF50;
            /* Green for upvote, you can customize this */
            color: white;
        }
    </style>

    <script src="/js/vote.js">

    </script>

</body>

</html>