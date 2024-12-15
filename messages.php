<?php 
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    
    // Fetch the team_id based on the logged-in user
    function get_team_by_user($conn, $user_id) {
        $sql = "SELECT team_id FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch the conversation for the given team_id
    function get_conversation_by_team($conn, $team_id) {
        $sql = "SELECT id FROM conversations WHERE team_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$team_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch messages for a specific conversation
    function get_messages_by_conversation($conn, $conversation_id) {
        $sql = "SELECT m.id, m.content, m.message_date, u.full_name AS sender_name, u.id AS sender_id
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                WHERE m.conversation_id = ?
                ORDER BY m.message_date ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$conversation_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch team_id for the logged-in user
    $team = get_team_by_user($conn, $_SESSION['id']);
    
    // Check if the user has a team
    if (!$team['team_id']) {
        $error_message = "No team associated with this user.";
        header("Location: messages.php?error=" . urlencode($error_message));
        exit();
    }

    // Fetch conversation for the team
    $conversation = get_conversation_by_team($conn, $team['team_id']);
    
    // Check if the team has a conversation
    if (!$conversation) {
        $error_message = "No conversation found for your team.";
        header("Location: messages.php?error=" . urlencode($error_message));
        exit();
    }

    // Fetch messages for the conversation
    $messages = get_messages_by_conversation($conn, $conversation['id']);
    if (empty($messages)) {
        $no_messages_error = "No messages in this conversation yet.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General Styles */

        .chat-container {
            width: 90%;
            max-width: 900px;
            margin: 0 auto;
            padding-top: 20px;
        }

        .chat-header {
            background-color:  #4723D9;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .messages-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 500px;
            overflow-y: auto;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            background-color: #f1f1f1;
        }

        .own-message {
            background-color: #AFA5D9;
            text-align: right;
        }

        .message-content {
            display: inline-block;
            max-width: 80%;
        }

        .sender {
            font-weight: bold;
            color: #333;
        }

        .timestamp {
            font-size: 12px;
            color: #888;
        }

        .chat-input {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .chat-input input {
            width: 80%;
            padding: 10px;
            border-radius: 25px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .chat-input button {
            background-color:  #4723D9;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
        }

        .chat-input button:hover {
            background-color:  #4723D9;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .messages-container {
                padding: 10px;
            }

            .chat-input input {
                width: 70%;
            }

            .chat-input button {
                width: 25%;
            }
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <div class="body">
        <?php include "inc/nav.php"; ?>

        <div class="chat-container">
            <div class="chat-header">
                <h2>Group Chat</h2>
            </div>

            <div class="messages-container">
                <?php if (isset($no_messages_error)) { ?>
                    <div class="error"><?= htmlspecialchars($no_messages_error); ?></div>
                <?php } else { ?>
                    <?php foreach ($messages as $message) { ?>
                        <div class="message <?php echo ($_SESSION['id'] == $message['sender_id']) ? 'own-message' : ''; ?>">
                            <div class="message-content">
                                <p class="sender"><?= htmlspecialchars($message['sender_name']); ?></p>
                                <p><?= htmlspecialchars($message['content']); ?></p>
                                <span class="timestamp"><?= date("h:i A", strtotime($message['message_date'])); ?></span>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <div class="chat-input">
            <form action="app/send_message.php" method="POST">
    <textarea name="message" required></textarea>
    <input type="hidden" name="team_id" value="<?php echo $team['team_id']; ?>" />
    <button type="submit">Send Message</button>
</form>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        var active = document.querySelector("#navList li:nth-child(2)");
        active.classList.add("active");
    </script>
</body>
</html>

<?php 
} else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
} 
?>
