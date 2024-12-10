<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/DatabaseConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure post_id is properly passed via POST
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : null;

    // Check if the post_id exists and is valid
    if (!$post_id) {
        $_SESSION["error"] = "Invalid post ID.";
        header("Location: /user.php");
        exit;
    }

    $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : '';
    $content = isset($_POST["content"]) ? htmlspecialchars($_POST["content"]) : '';
    $category = isset($_POST["category"]) ? htmlspecialchars($_POST["category"]) : '';

    $imagePath = null;

    // Handle file upload if a new image is provided
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/uploads/";
        $fileName = basename($_FILES['image_url']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Move the uploaded file
        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $targetFilePath)) {
            $imagePath = "/uploads/" . $fileName;
        } else {
            $_SESSION["error"] = "Failed to upload image.";
            header("Location: /user.php");
            exit;
        }
    }

    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    try {
        // Build the SQL query dynamically based on whether an image was uploaded
        $query = 'UPDATE posts SET title = :title, content = :content, category = :category, updated_at = NOW()';
        if ($imagePath) {
            $query .= ', image_url = :image_url';
        }
        $query .= ' WHERE post_id = :id AND user_id = :user_id';

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $post_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);

        if ($imagePath) {
            $stmt->bindParam(':image_url', $imagePath);
        }

        $stmt->execute();

        $_SESSION["success"] = "Post updated successfully!";
        header("Location: /user.php"); // Redirect after success
        exit;

    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: /user.php");
        exit;
    }
}
?>
