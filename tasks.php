<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/Team.php";
    include "app/Model/Project.php"; // Include the Project model to fetch projects
    
    // Default text and tasks if no filter is applied
    $text = "All Tasks";
    $tasks = get_all_tasks($conn);
    $num_task = count_tasks($conn);

    // Check if team_id filter is applied
    if (isset($_GET['team_id']) && !empty($_GET['team_id'])) {
        $team_id = $_GET['team_id'];
        $tasks = get_tasks_by_team($conn, $team_id); // Function to get tasks by team
        $num_task = count_tasks_by_team($conn, $team_id); // Function to count tasks by team
        $team = find_team_by_id($conn, $team_id); // Function to get team info
        $text = $team ? "Tasks for Team: " . $team['label'] : "Team not found";
    }

    // Check if project_id filter is applied
    if (isset($_GET['project_id']) && !empty($_GET['project_id'])) {
        $project_id = $_GET['project_id'];
        $tasks = get_tasks_by_project($conn, $project_id); // Function to get tasks by project
        $num_task = count_tasks_by_project($conn, $project_id); // Function to count tasks by project
        $project = find_project_by_id($conn, $project_id); // Function to get project info
        $text = $project ? "Tasks for Project: " . $project['title'] : "Project not found";
    }

    $teams = get_all_teams($conn);
    $projects = get_all_projects($conn); // Fetch all projects for the filter
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Wrapper for Select Dropdown and Label */
        .select-wrapper {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* Label Styling */
        .select-label {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* Select Dropdown Styling */
        select.input-1 {
            width: 250px;
            padding: 10px;
            font-size: 14px;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            appearance: none; /* Removes default browser arrow for consistency */
            outline: none;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        /* Hover and Focus Effects for Dropdown */
        select.input-1:hover {
            border-color: #888;
        }

        select.input-1:focus {
            border-color: #555;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        /* Dropdown Option Styling (optional) */
        select.input-1 option {
            padding: 10px;
            font-size: 14px;
            color: #333;
            background-color: #fff;
        }

        /* Add Responsiveness */
        @media screen and (max-width: 768px) {
            .select-wrapper {
                width: 100%;
            }

            select.input-1 {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title-2">
                <a href="create_task.php" class="btn">Create Task</a>
                <form method="GET" action="tasks.php" style="display:inline;">
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

                <!-- Add Project Filter Dropdown -->
                <form method="GET" action="tasks.php" style="display:inline;">
                    <div class="select-wrapper">
                        <label for="project-select" class="select-label">Filter by Project:</label>
                        <select id="project-select" name="project_id" onchange="this.form.submit()" class="input-1">
                            <option value="">Select Project</option>
                            <?php foreach ($projects as $project) { ?>
                                <option value="<?= $project['id'] ?>" <?= isset($project_id) && $project_id == $project['id'] ? 'selected' : '' ?>>
                                    <?= $project['title'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </h4>
            <h4 class="title-2"><?= $text ?> (<?= $num_task ?>)</h4>
            <?php if (isset($_GET['success'])) { ?>
                <div class="success" role="alert">
                    <?= stripcslashes($_GET['success']); ?>
                </div>
            <?php } ?>
            <?php if ($tasks != 0) { ?>
            <table class="main-table">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Project</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php $i = 0; foreach ($tasks as $task) { ?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td><?= $task['title'] ?></td>
                    <td><?= $task['description'] ?></td>
                    <td>
                        <?php 
                        foreach ($projects as $project) {
                            if ($project['id'] == $task['assigned_to']) {
                                echo $project['title'];
                            }
                        } ?>
                    </td>
                    <td><?= empty($task['due_date']) ? "No Deadline" : $task['due_date'] ?></td>
                    <td><?= $task['status'] ?></td>
                    <td>
                        <a href="edit-task.php?id=<?= $task['id'] ?>" class="edit-btn">Edit</a>
                        <a href="delete-task.php?id=<?= $task['id'] ?>" class="delete-btn">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php } else { ?>
                <h3>Empty</h3>
            <?php } ?>
        </section>
    </div>
    <script type="text/javascript">
        var active = document.querySelector("#navList li:nth-child(4)");
        active.classList.add("active");
    </script>
</body>
</html>
<?php } else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
} ?>
