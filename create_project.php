<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Project.php"; // Include the Project model to handle project creation
    include "app/Model/Team.php"; // Include the Team model to fetch teams
    
    // Fetch all teams to display in the dropdown
    $teams = get_all_teams($conn); 

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $assigned_to = $_POST['assigned_to'];
        
        // Validate form data
        if (empty($title) || empty($description) || empty($assigned_to)) {
            $error = "Title, description, and team assignment are required.";
            header("Location: create_project.php?error=" . urlencode($error));
            exit();
        }

        // Call the function to add the project
        $result = add_project($conn, $title, $description, $assigned_to);
        
        // Check if the project was added successfully
        if ($result) {
            $success = "Project created successfully!";
            header("Location: create_project.php?success=" . urlencode($success));
        } else {
            $error = "Failed to create the project.";
            header("Location: create_project.php?error=" . urlencode($error));
        }
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title">Create Project</h4>
            <form class="form-1" method="POST" action="create_project.php">
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
                
                <div class="input-holder">
                    <label>Title</label>
                    <input type="text" name="title" class="input-1" placeholder="Project Title" required><br>
                </div>
                <div class="input-holder">
                    <label>Description</label>
                    <textarea name="description" class="input-1" placeholder="Project Description" required></textarea><br>
                </div>
                <!-- Dropdown to select the team the project will be assigned to -->
                <div class="input-holder">
                    <label>Assign to Team</label>
                    <select name="assigned_to" class="input-1" required>
                        <option value="">Select Team</option>
                        <?php foreach ($teams as $team) { ?>
                            <option value="<?= $team['id'] ?>"><?= $team['label'] ?></option>
                        <?php } ?>
                    </select><br>
                </div>
                
                <button class="edit-btn">Create Project</button>
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
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
?>
