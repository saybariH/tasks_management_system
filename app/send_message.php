<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../DB_connection.php";

    // Fetch the team_id based on the logged-in user
    function get_team_by_user($conn, $user_id) {
        $sql = "SELECT team_id FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch conversation for the given team_id
    function get_conversation_by_team($conn, $team_id) {
        $sql = "SELECT id FROM conversations WHERE team_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$team_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch team_id for the logged-in user
    $team = get_team_by_user($conn, $_SESSION['id']);

    // Check if the user has a team
    if (!$team || !$team['team_id']) {
        $error_message = "No team associated with this user.";
        header("Location: ../messages.php?error=" . urlencode($error_message));
        exit();
    }

    // Check if the message is submitted
    if (isset($_POST['message']) && !empty($_POST['message'])) {
        // Sanitize and get the message
        $message = htmlspecialchars($_POST['message']);
        $sender_id = $_SESSION['id'];
        $team_id = $team['team_id'];

        // Fetch the conversation for the team
        $conversation = get_conversation_by_team($conn, $team_id);

        // If a conversation exists, use its ID, else create a new conversation
        if ($conversation) {
            $conversation_id = $conversation['id'];
        } else {
            // No conversation exists, so create a new conversation for the team
            $sql = "INSERT INTO conversations (team_id) VALUES (:team_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':team_id', $team_id);
            $stmt->execute();
            $conversation_id = $conn->lastInsertId(); // Get the new conversation ID
        }

        // Insert the message into the conversation
        $sql = "INSERT INTO messages (content, sender_id, conversation_id, message_date) 
                VALUES (:content, :sender_id, :conversation_id, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':content', $message);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':conversation_id', $conversation_id);
        $stmt->execute();

        // Redirect back to the messages page
        header("Location: ../messages.php?team_id=" . $team_id);
        exit();
    } else {
        // Handle error if message is empty
        header("Location: ../messages.php?team_id=" . $team_id . "&error=Message cannot be empty");
        exit();
    }
} else {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}
?>
