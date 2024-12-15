<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) ) {

    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    include "app/Model/Project.php";

    if ($_SESSION['role'] == "admin") {
        $num_task = count_tasks($conn);
        $num_users = count_users($conn);
        $pending = count_pending_tasks($conn);
        $in_progress = count_in_progress_tasks($conn);
        $completed = count_completed_tasks($conn);
        $num_projects = count_projects($conn);
    } else {
        $projects = get_all_projects_by_user($conn, $_SESSION['id']);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .project-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 350px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .project-header {
            background-color: #3498db;
            color: white;
            padding: 16px;
        }

        .project-title {
            margin: 0;
            font-size: 24px;
        }

        .project-description {
            padding: 16px;
            color: #333;
            font-size: 14px;
            border-bottom: 1px solid #e0e0e0;
        }

        .project-tasks {
            padding: 16px;
        }

        .project-tasks h3 {
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 18px;
            color: #333;
        }

        .task-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-name {
            font-size: 14px;
            color: #333;
        }

        .task-status {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: bold;
        }

        .task-status.completed {
            background-color: #2ecc71;
            color: white;
        }

        .task-status.in-progress {
            background-color: #f39c12;
            color: white;
        }

        .task-status.pending {
            background-color: #e74c3c;
            color: white;
        }

        .project-footer {
            padding: 16px;
            text-align: right;
            border-top: 1px solid #e0e0e0;
        }

        .more-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .more-button:hover {
            background-color: #2980b9;
        }

        @media (max-width: 400px) {
            .project-card {
                width: 90%;
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
            <?php if ($_SESSION['role'] == "admin") { ?>
                <!-- Admin part stays unchanged -->
                <div class="dashboard">
                    <div class="dashboard-item">
                        <i class="fa fa-users"></i>
                        <span><?=$num_users?> Employee</span>
                    </div>
                    <div class="dashboard-item">
                        <i class="fa fa-users"></i>
                        <span><?=$num_projects?> Projects</span>
                    </div>
                    <div class="dashboard-item">
                        <i class="fa fa-tasks"></i>
                        <span><?=$num_task?> All Tasks</span>
                    </div>

                    <div class="dashboard-item">
                        <i class="fa fa-square-o"></i>
                        <span><?=$pending?> Pending</span>
                    </div>
                    <div class="dashboard-item">
                        <i class="fa fa-spinner"></i>
                        <span><?=$in_progress?> In progress</span>
                    </div>
                    <div class="dashboard-item">
                        <i class="fa fa-check-square-o"></i>
                        <span><?=$completed?> Completed</span>
                    </div>
                </div>
            <?php } else { ?>
                <!-- Employee part displays projects and tasks -->
                <?php foreach ($projects as $project) { 
                    $tasks = get_tasks_by_project_id($conn, $project['id'], 3); ?>
                    <div class="project-card">
                        <div class="project-header">
                            <h2 class="project-title"><?= htmlspecialchars($project['title']); ?></h2>
                        </div>
                        <div class="project-description">
                            <p><?= htmlspecialchars($project['description']); ?></p>
                        </div>
                        <div class="project-tasks">
                            <h3>Tasks</h3>
                            <ul class="task-list">
                                <?php foreach ($tasks as $task) { ?>
                                    <li class="task-item">
                                        <span class="task-name"><?= htmlspecialchars($task['title']); ?></span>
                                        <span class="task-status <?= strtolower($task['status']); ?>">
                                            <?= ucfirst($task['status']); ?>
                                        </span>
                                    </li>
                                <?php } ?>
                                <?php if (count($tasks) == 0) { ?>
                                    <p>No tasks found.</p>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="project-footer">
                            <a href="my_task.php?id=<?= $project['id']; ?>" class="more-button">All tasks</a>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </section>
    </div>

<script type="text/javascript">
    var active = document.querySelector("#navList li:nth-child(1)");
    active.classList.add("active");
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
} 
?>
