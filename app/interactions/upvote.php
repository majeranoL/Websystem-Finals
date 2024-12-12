<?php
session_start();
require_once('../config/DatabaseConnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['post_id']) && isset($_POST['vote_value'])) {
        $postId = $_POST['post_id'];
        $voteValue = (int)$_POST['vote_value'];
        $userId = $_SESSION['user_id']; // Assumes the user is logged in

        // Initialize database connection
        $db = new DatabaseConnect();
        $conn = $db->connectDB();

        // Check if the user has already voted on this post
        $checkVoteQuery = "SELECT vote_value FROM votes WHERE post_id = :post_id AND user_id = :user_id";
        $stmt = $conn->prepare($checkVoteQuery);
        $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);
        $existingVote = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingVote) {
            if ($existingVote['vote_value'] == $voteValue) {
                // If the user already voted the same way (no need to update)
                echo json_encode(['success' => false, 'message' => 'You already voted!']);
                exit();
            } else {
                // User is changing vote (from downvote to upvote or vice versa)
                $updateVoteQuery = "UPDATE votes SET vote_value = :vote_value WHERE post_id = :post_id AND user_id = :user_id";
                $stmt = $conn->prepare($updateVoteQuery);
                $stmt->execute([':vote_value' => $voteValue, ':post_id' => $postId, ':user_id' => $userId]);

                // Update the post's vote count
                $updateVoteQuery = "UPDATE posts SET vote = vote + :vote_change WHERE post_id = :post_id";
                $stmt = $conn->prepare($updateVoteQuery);
                $voteChange = ($voteValue == 1) ? 2 : -2;  // Switching votes (up to down or down to up)
                $stmt->execute([':vote_change' => $voteChange, ':post_id' => $postId]);
            }
        } else {
            // If the user has not voted yet, add the vote
            $insertVoteQuery = "INSERT INTO votes (post_id, user_id, vote_value) VALUES (:post_id, :user_id, :vote_value)";
            $stmt = $conn->prepare($insertVoteQuery);
            $stmt->execute([':post_id' => $postId, ':user_id' => $userId, ':vote_value' => $voteValue]);

            // Update the post's vote count
            $updateVoteQuery = "UPDATE posts SET vote = vote + :vote_value WHERE post_id = :post_id";
            $stmt = $conn->prepare($updateVoteQuery);
            $stmt->execute([':vote_value' => $voteValue, ':post_id' => $postId]);
        }

        // After voting, return the updated vote count
        $getVoteQuery = "SELECT vote FROM posts WHERE post_id = :post_id";
        $stmt = $conn->prepare($getVoteQuery);
        $stmt->execute([':post_id' => $postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return success and the updated vote count in JSON format
        echo json_encode(['success' => true, 'new_vote_count' => $post['vote']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    }
}
?>
