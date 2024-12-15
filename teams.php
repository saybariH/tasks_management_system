<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Team.php";
    
    // Default text and teams if no filter is applied
    $text = "All Teams";
    $teams = get_all_teams($conn);
    $num_teams = count($teams);

    // Error or success message handling
    $error = $success = "";
    if (isset($_GET['error'])) {
        $error = htmlspecialchars($_GET['error']);
    }
    if (isset($_GET['success'])) {
        $success = htmlspecialchars($_GET['success']);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Teams</title>
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
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <div class="body">
        <?php include "inc/nav.php" ?>

        <section class="section-1">
            <!-- Display Error or Success Messages -->
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

            <h4 class="title-2">
                <a href="create_team.php" class="btn">Create Team</a>
            </h4>
            <h4 class="title-2"><?= $text ?> (<?= $num_teams ?>)</h4>
            
            <?php if ($teams != 0) { ?>
                <table class="main-table">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Members</th>
                        <th>Action</th>
                    </tr>
                    <?php $i = 0; foreach ($teams as $team) { ?>
                    <tr>
                        <td><?= ++$i ?></td>
                        <td><?= $team['label'] ?></td>
                        <td>
                            <?php 
                            // Display members associated with the team
                            $members = get_members_by_team($conn, $team['id']);
                            if ($members) {
                                foreach ($members as $member) {
                                    echo "<li>". $member['full_name'] . "</li>";
                                }
                            } else {
                                echo "<em>No members</em>";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="edit-team.php?id=<?= $team['id'] ?>" class="edit-btn">Edit</a>
                            <a href="delete-team.php?id=<?= $team['id'] ?>" class="delete-btn"
                               onclick="return confirm('Are you sure you want to delete this team?');">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <h3>No Teams Found</h3>
            <?php } ?>
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
