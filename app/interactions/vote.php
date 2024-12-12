<?php
session_start();
require_once('app/config/DatabaseConnect.php');

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
    }

    // Redirect back to the post page with the updated vote count
    header("Location: ../../user.php?post_id=$postId");
    exit();
}
?>
