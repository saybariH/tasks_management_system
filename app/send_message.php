<?php
session_start();

if (isset($_SESSION['id'])) {
    include "DB_connection.php";

    // Check if the message is submitted
    if (isset($_POST['message']) && !empty($_POST['message'])) {
        // Sanitize and get the message
        $message = htmlspecialchars($_POST['message']);
        $sender_id = $_SESSION['id'];

        // Check if a conversation_id is passed or if we need to create a new one
        $conversation_id = isset($_GET['conversation_id']) ? $_GET['conversation_id'] : null;

        if ($conversation_id) {
            // If conversation_id is provided, just insert the message
            $sql = "INSERT INTO messages (content, sender_id, conversation_id, message_date) 
                    VALUES (:content, :sender_id, :conversation_id, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':content', $message);
            $stmt->bindParam(':sender_id', $sender_id);
            $stmt->bindParam(':conversation_id', $conversation_id);
            $stmt->execute();

            // Redirect back to the chat page
            header("Location: ../messages.php?conversation_id=$conversation_id");
            exit();
        } else {
            // If no conversation_id, create a new conversation
            // For a new conversation, we need to know the team or user with whom the user wants to chat
            // Assuming we have a `team_id` or `user_id` as part of the request (for example, if you are chatting with a team or user)
            if (isset($_GET['team_id']) || isset($_GET['user_id'])) {
                $team_id = isset($_GET['team_id']) ? $_GET['team_id'] : null;
                $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

                // Create a new conversation if not exists
                if ($team_id) {
                    // Create a conversation with the team
                    $sql = "INSERT INTO conversations (team_id, created_by) VALUES (:team_id, :created_by)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':team_id', $team_id);
                    $stmt->bindParam(':created_by', $sender_id);
                    $stmt->execute();

                    // Get the newly created conversation ID
                    $conversation_id = $conn->lastInsertId();
                } elseif ($user_id) {
                    // Create a conversation with another user
                    $sql = "INSERT INTO conversations (user_id_1, user_id_2, created_by) 
                            VALUES (:user_id_1, :user_id_2, :created_by)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':user_id_1', $sender_id);
                    $stmt->bindParam(':user_id_2', $user_id);
                    $stmt->bindParam(':created_by', $sender_id);
                    $stmt->execute();

                    // Get the newly created conversation ID
                    $conversation_id = $conn->lastInsertId();
                }

                // After creating the conversation, insert the first message
                $sql = "INSERT INTO messages (content, sender_id, conversation_id, message_date) 
                        VALUES (:content, :sender_id, :conversation_id, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':content', $message);
                $stmt->bindParam(':sender_id', $sender_id);
                $stmt->bindParam(':conversation_id', $conversation_id);
                $stmt->execute();

                // Redirect to the newly created conversation
                header("Location: ../messages.php?conversation_id=$conversation_id");
                exit();
            } else {
                // If neither team_id nor user_id is provided, handle the error
                header("Location: ../messages.php?error=No team or user specified for the conversation");
                exit();
            }
        }
    } else {
        // Handle error if message is empty
        header("Location: ../messages.php?error=Message cannot be empty");
        exit();
    }
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>
