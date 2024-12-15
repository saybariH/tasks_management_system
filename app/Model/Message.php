<?php
// Get all conversations for a specific team
function get_conversations_by_team($conn, $team_id) {
    $sql = "SELECT * FROM conversations WHERE team_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$team_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Get all messages for a specific conversation

// Send a new message
function send_message($conn, $conversation_id, $sender_id, $content) {
    $sql = "INSERT INTO messages (conversation_id, sender_id, content) 
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$conversation_id, $sender_id, $content]);
}
// Include DB connection
include "DB_connection.php";

// Function to fetch messages for a specific conversation
function get_messages_by_conversation($conn, $conversation_id) {
    $sql = "SELECT m.id, m.content, m.message_date, u.full_name AS sender_name
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.message_date ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$conversation_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
