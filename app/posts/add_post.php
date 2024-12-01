<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/DatabaseConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST["title"]);
    $content = htmlspecialchars($_POST["content"]);
    $user_id = $_SESSION['user_id'] ?? null; // Check if the user is logged in

    if (!$user_id) {
        $_SESSION["error"] = "You must be logged in to create a post.";
        header("Location: " . BASE_URL . "login.php");
        exit;
    }

    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    try {
        $stmt = $conn->prepare('INSERT INTO posts (user_id, title, content, created_at, updated_at) VALUES (:user_id, :title, :content, NOW(), NOW())');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        $_SESSION["success"] = "Post created successfully!";
        header("Location: " . BASE_URL . "index.php");
        exit;

    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        header("Location: " . BASE_URL . "add_post.php");
        exit;
    }
}
?>