<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Project.php";
    include "app/Model/Task.php";
    include "app/Model/Team.php";  // Include the Team model to handle team assignment

    // Default text and projects if no filter is applied
    $text = "All Projects";
    $projects = get_all_projects($conn);
    $num_projects = count_projects($conn);

    // Fetch all teams for the dropdown
    $teams = get_all_teams($conn);

    // Check if a team_id filter is applied
    if (isset($_GET['team_id']) && !empty($_GET['team_id'])) {
        $team_id = $_GET['team_id'];
        $projects = get_projects_by_team($conn, $team_id); // Function to get projects by team
        $num_projects = count_projects_by_team($conn, $team_id); // Function to count projects by team
        $text = "Projects assigned to Team: " . find_team_by_id($conn, $team_id)['label']; // Display team name
    }


?>
<!DOCTYPE html>
<html>
<head>
    <title>All Projects</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .select-wrapper {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .select-label {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select.input-1 {
            width: 250px;
            padding: 10px;
            font-size: 14px;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            appearance: none;
            outline: none;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        select.input-1:hover {
            border-color: #888;
        }

        select.input-1:focus {
            border-color: #555;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title-2">
                <a href="create_project.php" class="btn">Create Project</a>
                <form method="GET" action="projects.php" style="display:inline;">
                    <div class="select-wrapper">
                        <label for="team-select" class="select-label">Filter by Team:</label>
                        <select id="team-select" name="team_id" onchange="this.form.submit()" class="input-1">
                            <option value="">Select Team</option>
                            <?php foreach ($teams as $team) { ?>
                                <option value="<?= $team['id'] ?>" <?= isset($team_id) && $team_id == $team['id'] ? 'selected' : '' ?>>
                                    <?= $team['label'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </h4>
            <h4 class="title-2"><?= $text ?> (Projects: <?= $num_projects ?>)</h4>
            <?php if (isset($_GET['success'])) { ?>
                <div class="success" role="alert">
                    <?= stripcslashes($_GET['success']); ?>
                </div>
            <?php } ?>
            <?php if ($projects != 0) { ?>
            <table class="main-table">
                <tr>
                    <th>#</th>
                    <th>Project Name</th>
                    <th>Description</th>
                    <th>Assigned To Team</th>
                    <th>Tasks Count</th>
                    <th>Actions</th>
                </tr>
                <?php $i = 0; foreach ($projects as $project) { ?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td><?= $project['title'] ?></td>
                    <td><?= $project['description'] ?></td>
                    <td>
                        <?php 
                        foreach ($teams as $team) {
                            if ($team['id'] == $project['assigned_to']) {
                                echo $team['label'];
                            }
                        } ?>
                    </td>
                    <td><?= count_tasks_by_project($conn, $project['id']) ?></td>
                    <td>
                        <a href="tasks.php?project_id=<?= $project['id'] ?>" class="view-btn">View Tasks</a>
                        <a href="edit-project.php?id=<?= $project['id'] ?>" class="edit-btn">Edit</a>
                        <a href="delete-project.php?id=<?= $project['id'] ?>" class="delete-btn">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php } else { ?>
                <h3>No Projects Found</h3>
            <?php } ?>
        </section>
    </div>
    <script type="text/javascript">
        var active = document.querySelector("#navList li:nth-child(3)");
        active.classList.add("active");
    </script>
</body>
</html>
<?php } else { 
    $em = "Please login first";
    header("Location: login.php?error=$em");
    exit();
} ?>
