<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Team.php";

    // Initialize variables for feedback messages
    $error = $success = "";

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $team_name = trim($_POST['team_name']);

        // Validate input
        if (empty($team_name)) {
            $error = "Team name is required.";
        } else {
            // Check if the team already exists
            if (team_exists($conn, $team_name)) {
                $error = "A team with this name already exists.";
            } else {
                // Add the team to the database
                $result = create_team($conn, $team_name);
                if ($result) {
                    $success = "Team created successfully.";
                } else {
                    $error = "An error occurred while creating the team. Please try again.";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Team</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #f9f9f9;
        }
        .form-container input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 15px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <div class="body">
        <?php include "inc/nav.php" ?>

        <section class="section-1">
            <div class="form-container">
                <h2>Create a New Team</h2>

                <!-- Display error or success messages -->
                <?php if (!empty($error)) { ?>
                    <div class="message error" role="alert">
                        <?= $error; ?>
                    </div>
                <?php } ?>
                <?php if (!empty($success)) { ?>
                    <div class="message success" role="alert">
                        <?= $success; ?>
                    </div>
                <?php } ?>

                <!-- Team Creation Form -->
                <form action="create_team.php" method="POST">
                    <label for="team_name">Team Name</label><br>
                    <input type="text" name="team_name" id="team_name" placeholder="Enter team name" required><br>
                    <button type="submit">Create Team</button>
                </form>
            </div>
        </section>
    </div>
    <script type="text/javascript">
        var active = document.querySelector("#navList li:nth-child(4)");
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
