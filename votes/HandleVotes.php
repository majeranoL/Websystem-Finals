<?php

require_once '../app/config/DatabaseConnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];
    $voteValue = (int)$_POST['vote_value']; // 1 for upvote, -1 for downvote

    if ($voteValue !== 1 && $voteValue !== -1) {
        echo json_encode(['success' => false, 'message' => 'Invalid vote value']);
        exit();
    }

    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    // Check if the user has already voted
    $checkVoteQuery = "SELECT vote_value FROM votes WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $conn->prepare($checkVoteQuery);
    $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);
    $existingVote = $stmt->fetch(PDO::FETCH_ASSOC);

    $existingVoteValue = $existingVote ? (int)$existingVote['vote_value'] : 0;

    if ($existingVoteValue == $voteValue) {
        // User is removing their vote
        $newVoteValue = 0;
    } else {
        // User is adding or switching their vote
        $newVoteValue = $voteValue;
    }

    $voteDifference = $newVoteValue - $existingVoteValue;

    // Update the votes table
    if ($existingVote) {
        if ($newVoteValue == 0) {
            // Remove the vote
            $deleteVoteQuery = "DELETE FROM votes WHERE post_id = :post_id AND user_id = :user_id";
            $stmt = $conn->prepare($deleteVoteQuery);
            $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);
        } else {
            // Update the existing vote
            $updateVoteQuery = "UPDATE votes SET vote_value = :vote_value WHERE post_id = :post_id AND user_id = :user_id";
            $stmt = $conn->prepare($updateVoteQuery);
            $stmt->execute([':vote_value' => $newVoteValue, ':post_id' => $postId, ':user_id' => $userId]);
        }
    } else {
        if ($newVoteValue != 0) {
            // Insert a new vote
            $insertVoteQuery = "INSERT INTO votes (post_id, user_id, vote_value) VALUES (:post_id, :user_id, :vote_value)";
            $stmt = $conn->prepare($insertVoteQuery);
            $stmt->execute([':post_id' => $postId, ':user_id' => $userId, ':vote_value' => $newVoteValue]);
        }
    }

    // Update the vote count in the posts table
    if ($voteDifference != 0) {
        $updateVoteCountQuery = "UPDATE posts SET vote = vote + :vote_difference WHERE post_id = :post_id";
        $stmt = $conn->prepare($updateVoteCountQuery);
        $stmt->execute([':vote_difference' => $voteDifference, ':post_id' => $postId]);
    }

    // Get the new vote count
    $getVoteCountQuery = "SELECT vote FROM posts WHERE post_id = :post_id";
    $stmt = $conn->prepare($getVoteCountQuery);
    $stmt->execute([':post_id' => $postId]);
    $newVoteCount = $stmt->fetchColumn();

    echo json_encode(['success' => true, 'new_vote_count' => $newVoteCount]);
    exit();
}

?>
