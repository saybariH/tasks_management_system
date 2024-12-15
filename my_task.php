<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    include "app/Model/Project.php";  // Include the Project model to fetch projects

    // Get all projects assigned to the user
    $projects = get_projects_by_user($conn, $_SESSION['id']); 

    // Check if a project filter is selected
    $selected_project_id = isset($_GET['project_id']) ? $_GET['project_id'] : null;

    // Get tasks by project or get all tasks
    if ($selected_project_id) {
        $tasks = get_tasks_by_project($conn, $selected_project_id, $_SESSION['id']);
    } else {
        $tasks = get_all_tasks_by_id($conn, $_SESSION['id']);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .task-card {
            background-color: #8587da3d;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .task-header {
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .task-title {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        select.task-state {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            outline: none;
        }

        /* Apply specific colors for states */
        .task-state.todo {
            background-color: #fef3c7;
            color: #92400e;
        }

        .task-state.in-progress {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .task-state.completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        /* Dropdown customization */
        .task-description {
            padding: 16px;
            color: #666;
        }

        .task-footer {
            padding: 16px;
            border-top: 1px solid #e0e0e0;
        }

        .task-due-date {
            margin: 0;
            font-size: 14px;
            color: #888;
        }

        /* Style for project filter */
        .project-filter {
            margin-bottom: 20px;
        }
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
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title">My Tasks</h4>

            <!-- Project Filter Dropdown -->
            <div class="project-filter">
                <form method="GET">
				<div class="select-wrapper">
				<label for="team-select" class="select-label">Filter by Projects:</label>
                    <select id='team-select' name="project_id" onchange="this.form.submit()" class="input-1">
                        <option value="">Select Project</option>
                        <?php foreach ($projects as $project) { ?>
                            <option value="<?= $project['id'] ?>" <?= $selected_project_id == $project['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($project['title']); ?>
                            </option>
                        <?php } ?>
                    </select>
					</div>
                </form>
            </div>

            <?php if (isset($_GET['success'])) { ?>
                <div class="success" role="alert">
                    <?= stripcslashes($_GET['success']); ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['error'])) { ?>
                <div class="error" role="alert">
                    <?= stripcslashes($_GET['error']); ?>
                </div>
            <?php } ?>

            <?php foreach ($tasks as $task) { ?>
                <div class="task-card">
                    <div class="task-header">
                        <h2 class="task-title"><?= htmlspecialchars($task['title']); ?></h2>
                        <form action="app/change_status.php" method="POST">
                            <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                            <select name="status" class="task-state <?= strtolower($task['status']); ?>" onchange="this.className = 'task-state ' + this.value.toLowerCase(); this.form.submit();">
                                <option value="pending" <?= $task['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="in_progress" <?= $task['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="completed" <?= $task['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </form>
                    </div>
                    <div class="task-description">
                        <p><?= htmlspecialchars($task['description']); ?></p>
                    </div>
                    <div class="task-footer">
                        <p class="task-due-date">Due: <?= empty($task['due_date']) ? "No Deadline" : $task['due_date']; ?></p>
                    </div>
                </div>
            <?php } ?>
        </section>
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
