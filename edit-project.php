<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Project.php"; // Include the Project model
    include "app/Model/Team.php"; // Include the Team model

    // Check if project ID is provided in the URL
    if (!isset($_GET['id'])) {
        header("Location: projects.php");
        exit();
    }

    $id = $_GET['id'];
    $project = find_project_by_id($conn, $id); // Fetch the project by ID

    if ($project == 0) {
        header("Location: projects.php");
        exit();
    }

    // Fetch all teams to allow project assignment
    $teams = get_all_teams($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title">Edit Project <a href="projects.php">Projects</a></h4>
            <form class="form-1" method="POST" action="app/update-project.php">
                <?php if (isset($_GET['error'])) { ?>
                    <div class="danger" role="alert">
                        <?php echo stripcslashes($_GET['error']); ?>
                    </div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?>
                    <div class="success" role="alert">
                        <?php echo stripcslashes($_GET['success']); ?>
                    </div>
                <?php } ?>
                
                <!-- Project Title -->
                <div class="input-holder">
                    <label>Title</label>
                    <input type="text" name="title" class="input-1" placeholder="Project Title" value="<?=$project['title']?>"><br>
                </div>

                <!-- Project Description -->
                <div class="input-holder">
                    <label>Description</label>
                    <textarea name="description" rows="5" class="input-1" placeholder="Project Description"><?=$project['description']?></textarea><br>
                </div>

                <!-- Assigned Team -->
                <div class="input-holder">
                    <label>Assigned to Team</label>
                    <select name="assigned_to" class="input-1">
                        <option value="0">Select Team</option>
                        <?php if ($teams != 0) { 
                            foreach ($teams as $team) {
                                // Check if the project is already assigned to this team
                                if ($project['assigned_to'] == $team['id']) { ?>
                                    <option selected value="<?=$team['id']?>"><?=$team['label']?></option>
                        <?php } else { ?>
                                    <option value="<?=$team['id']?>"><?=$team['label']?></option>
                        <?php } } } ?>
                    </select><br>
                </div>

                <!-- Hidden Project ID (for submission) -->
                <input type="text" name="id" value="<?=$project['id']?>" hidden>

                <!-- Update Button -->
                <button class="edit-btn">Update Project</button>
            </form>
        </section>
    </div>

<script type="text/javascript">
    var active = document.querySelector("#navList li:nth-child(3)");
    active.classList.add("active");
</script>
</body>
</html>
<?php } else { 
   $em = "Please log in first";
   header("Location: login.php?error=$em");
   exit();
}
?>
