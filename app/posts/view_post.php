<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/includes/navbar.php"); 
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/DatabaseConnect.php");

// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

// Change: use 'post_id' instead of 'id'
$postId = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($conn) {
    // Fetch the post details (updated 'post_id' instead of 'id')
    $postQuery = "SELECT p.post_id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, u.username 
                  FROM posts p
                  JOIN users u ON p.user_id = u.user_id
                  WHERE p.post_id = :post_id";
    $postStmt = $conn->prepare($postQuery);
    $postStmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $postStmt->execute();
    $post = $postStmt->fetch();

    // Fetch the comments (updated 'comment_id' and 'post_id' field names)
    $commentQuery = "SELECT c.comment_id, c.comment, c.created_at, u.username, c.user_id AS comment_user_id
                     FROM comments c
                     JOIN users u ON c.user_id = u.user_id
                     WHERE c.post_id = :post_id
                     ORDER BY c.created_at ASC";
    $commentStmt = $conn->prepare($commentQuery);
    $commentStmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $commentStmt->execute();
    $comments = $commentStmt->fetchAll();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'] ?? '';
    $userId = $_SESSION['user_id'] ?? null;

    if ($userId && $comment) {
        $insertQuery = "INSERT INTO comments (post_id, user_id, comment, created_at) 
                        VALUES (:post_id, :user_id, :comment, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->execute([
            ':post_id' => $postId,
            ':user_id' => $userId,
            ':comment' => $comment,
        ]);
        header("Location: view_post.php?post_id=" . $postId);
        exit();
    }
}

// Handle comment editing
if (isset($_GET['edit_comment_id']) && isset($_SESSION['user_id'])) {
    $editCommentId = $_GET['edit_comment_id'];
    $userId = $_SESSION['user_id'];

    // Check if the logged-in user is the owner of the comment
    $editQuery = "SELECT * FROM comments WHERE comment_id = :comment_id AND user_id = :user_id";
    $editStmt = $conn->prepare($editQuery);
    $editStmt->execute([':comment_id' => $editCommentId, ':user_id' => $userId]);
    $commentToEdit = $editStmt->fetch();

    if ($commentToEdit) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edited_comment'])) {
            $editedComment = $_POST['edited_comment'];

            // Update the comment in the database
            $updateQuery = "UPDATE comments SET comment = :edited_comment, created_at = NOW() WHERE comment_id = :comment_id";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->execute([
                ':edited_comment' => $editedComment,
                ':comment_id' => $editCommentId
            ]);
            header("Location: view_post.php?post_id=" . $postId);
            exit();
        }
    }
}

// Handle comment deletion
if (isset($_GET['delete_comment_id']) && isset($_SESSION['user_id'])) {
    $deleteCommentId = $_GET['delete_comment_id'];
    $userId = $_SESSION['user_id'];

    // Check if the logged-in user is the owner of the comment
    $deleteQuery = "SELECT * FROM comments WHERE comment_id = :comment_id AND user_id = :user_id";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute([':comment_id' => $deleteCommentId, ':user_id' => $userId]);
    $commentToDelete = $deleteStmt->fetch();

    if ($commentToDelete) {
        // Delete the comment from the database
        $deleteQuery = "DELETE FROM comments WHERE comment_id = :comment_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute([':comment_id' => $deleteCommentId]);
        header("Location: view_post.php?post_id=" . $postId);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Styles/index.css">
    <style>
        body {
            background-color: #1a1a1a !important;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
            padding-top: 70px; /* Add padding to avoid navbar overlap */
        }

        .post-container {
            background: #1e2a3a;
            border: 1px solid black;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto; /* Center the container */
            width: 80%; /* Adjust width as needed */
            max-width: 900px; /* Set max width for larger screens */
            color: white;
            text-align: center; /* Center-align the text */
        }

        .post-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .post-category {
            font-size: 14px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: bold;
        }

        .post-container img {
            max-width: 100%; /* Make the image responsive */
            height: auto;
            display: block; /* Ensure it's treated as a block element */
            margin: 20px auto; /* Center the image */
            border-radius: 8px; /* Add rounded corners */
        }

        .comments-section {
            margin-top: 30px;
            color: white;
        }

        .comment {
            background: #2c3e50;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            color: white;
        }

        .comment strong {
            color: white;
        }

        .add-comment textarea {
            width: 100%;
            background-color: #2c3e50;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            color: white;
        }

        .add-comment button {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
        }
    </style>
</head>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/includes/sidebar.php"); ?>
<body>
<div class="container mt-4">
    <div class="post-container">
    <?php if ($post) { ?>
        <!-- Title Section -->
        <div class="post-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="h3 mb-0" style="color: white; text-align: left; flex: 1;">
                <strong><?php echo htmlspecialchars($post['title']); ?></strong>
            </h1>

            <!-- Category on the far right -->
            <span class="post-category" 
                style=" 
                    <?php 
                        if ($post['category'] === 'Technology') {
                            echo 'background-color: #fff3cd; color: #856404;';
                        } elseif ($post['category'] === 'Lifestyle') {
                            echo 'background-color: #d4edda; color: #155724;';
                        } elseif ($post['category'] === 'Travel') {
                            echo 'background-color: #d1ecf1; color: #0c5460;';
                        } else {
                            echo 'background-color: #e2e3e5; color: #383d41;';
                        }
                    ?>">
                <?php echo htmlspecialchars($post['category']); ?>
            </span>
        </div>

        <!-- Content Section -->
        <p style="color: white; text-align: left; margin-bottom: 20px;">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
        </p>
        
        <!-- Image Section (if available) -->
        <?php if (!empty($post['image_url'])) { ?>
            <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post Image" class="img-fluid rounded" style="margin-bottom: 20px;">
        <?php } ?>

        <!-- Posted By Section (Small Text) -->
        <p class="text-white" style="color: white; text-align: left; font-size: small;">
            Posted by
            <strong style="color: black;"><?php echo htmlspecialchars($post['username']); ?></strong>
            on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
        </p>

    <?php } else { ?>
        <p>Post not found.</p>
    <?php } ?>
</div>


<div class="comments-section">
    <h2 style="color: white;">Comments</h2>
    <?php if ($comments) { ?>
        <?php foreach ($comments as $comment) { ?>
            <div class="comment">
                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                <p class="text-muted"><small style="color: white;"> <?php echo date('F j, Y, g:i a', strtotime($comment['created_at'])); ?></small></p>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['comment_user_id']) { ?>
                    <!-- Edit and Delete buttons -->
                    <a href="view_post.php?post_id=<?php echo $postId; ?>&edit_comment_id=<?php echo $comment['comment_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="view_post.php?post_id=<?php echo $postId; ?>&delete_comment_id=<?php echo $comment['comment_id']; ?>" class="btn btn-sm btn-danger" onclick="confirmDelete(event)">Delete</a>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php } ?>

    <!-- Add/Edit comment form -->
    <div class="add-comment">
        <?php if (isset($_GET['edit_comment_id'])) { 
            $edit_comment_id = $_GET['edit_comment_id'];
            $editQuery = "SELECT comment FROM comments WHERE comment_id = :comment_id";
            $editStmt = $conn->prepare($editQuery);
            $editStmt->bindParam(':comment_id', $edit_comment_id, PDO::PARAM_INT);
            $editStmt->execute();
            $editComment = $editStmt->fetch();
        ?>
            <form method="POST" action="">
                <textarea name="edited_comment" rows="4" placeholder="Edit your comment..." required><?php echo htmlspecialchars($editComment['comment']); ?></textarea>
                <button type="submit">Update Comment</button>
            </form>
        <?php } else { ?>
            <!-- Add new comment form -->
            <form method="POST" action="">
                <textarea name="comment" rows="4" placeholder="Write a comment..." required></textarea>
                <button type="submit">Add Comment</button>
            </form>
        <?php } ?>
    </div>
</div>

</div>

<script>
    function confirmDelete(event) {
        if (!confirm("Are you sure you want to delete this comment?")) {
            event.preventDefault();
        }
    }
</script>
</body>
</html>


