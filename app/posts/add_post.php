<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/DatabaseConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST["title"]);
    $content = htmlspecialchars($_POST["content"]);
    $category = htmlspecialchars($_POST["category"]);
    $user_id = $_SESSION['user_id'] ?? null; // Check if the user is logged in

    if (!$user_id) {
        $_SESSION["error"] = "You must be logged in to create a post.";
        exit;
    }

    // Image Processing
    $image_url = ''; // Default image URL to empty
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . '/uploads/'; // Directory where images will be stored
        $fileTmpName = $_FILES['image_url']['tmp_name'];
        $fileName = basename($_FILES['image_url']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Check if the file is an image
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $validExtensions)) {
            // Generate a unique name for the file to avoid name conflicts
            $uniqueFileName = uniqid('post_', true) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $uniqueFileName;

            // Move the uploaded file to the designated directory
            if (move_uploaded_file($fileTmpName, $uploadFile)) {
                $image_url = '/uploads/' . $uniqueFileName; // Save relative path in the database
            } else {
                $_SESSION["error"] = "Failed to upload image.";
                exit;
            }
        } else {
            $_SESSION["error"] = "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
            exit;
        }
    }

    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    try {
        $stmt = $conn->prepare('INSERT INTO posts (user_id, title, content, Category, image_url, created_at, updated_at) 
                               VALUES (:user_id, :title, :content, :category, :images, NOW(), NOW())');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':images', $image_url);
        $stmt->execute();

        $_SESSION["success"] = "Post created successfully!";
        header("Location: " . BASE_URL . "user.php");
        exit;

    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        echo $e;
        exit;
    }
}
?>
