<?php
// app/posts/fetch_post_data.php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/DatabaseConnect.php");

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    $db = new DatabaseConnect();
    $conn = $db->connectDB();
    
    // Fetch post data by post_id
    $query = "SELECT * FROM posts WHERE post_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($post) {
        echo json_encode([
            'success' => true,
            'post' => $post
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Post not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>
