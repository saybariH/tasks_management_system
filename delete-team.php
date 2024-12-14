<?php
session_start();

// Check if the user is an admin
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    include "DB_connection.php";
    include "app/Model/Team.php";  // Include the Team model

    // Ensure the team_id is passed
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $team_id = $_GET['id'];

        // Check if the team has associated users or projects
        $team_has_users = check_team_has_users($conn, $team_id);
        $team_has_projects = check_team_has_projects($conn, $team_id);

        if ($team_has_users || $team_has_projects) {
            // If there are associated users or projects, show error
            $error_message = "You cannot delete this team because it has associated users or projects. Please remove them first.";
            header("Location: teams.php?error=" . urlencode($error_message));
            exit();
        }

        // Proceed with deleting the team if no users or projects are associated
        $result = delete_team($conn, $team_id);

        if ($result) {
            // Redirect to teams page with success message
            header("Location: teams.php?success=Team deleted successfully.");
        } else {
            // Redirect to teams page with error message if deletion failed
            header("Location: teams.php?error=Failed to delete the team.");
        }
    } else {
        header("Location: teams.php?error=Invalid team ID.");
    }
} else {
    // Redirect to login if user is not logged in as admin
    header("Location: login.php?error=You need to login first.");
    exit();
}


?>
