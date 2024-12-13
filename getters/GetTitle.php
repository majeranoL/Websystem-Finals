<?php 
include('../app/config/DatabaseConnect.php');
session_start();

$db = new DatabaseConnect();
$conn = $db->connectDB();

$word = $_GET["term"];  

if ($conn) {
    $query = "SELECT title, post_id FROM posts WHERE title LIKE :word";
    $stmt = $conn->prepare($query);
    $stmt->execute([':word' => '%' . $word . '%']);
    try { 
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts) {
            echo json_encode(['posts' => $posts]);
        } else {
            echo json_encode(['error' => 'No posts found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching post titles: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Database connection failed']);
}
?>
