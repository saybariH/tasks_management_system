<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Project.php"; // Include Project model for handling project-related functions

    // Check if the project ID is passed in the URL
    if (!isset($_GET['id'])) {
        header("Location: projects.php");
        exit();
    }

    $id = $_GET['id']; // Get the project ID from the URL

    // Fetch the project by ID
    $project = find_project_by_id($conn, $id); // Function to get project details by ID

    // Check if the project exists
    if ($project == 0) {
        header("Location: projects.php");
        exit();
    }

    // Delete the project
    $data = array($id);
    delete_project($conn, $data); // Function to delete the project

    // Redirect with success message
    $sm = "Project deleted successfully";
    header("Location: projects.php?success=$sm");
    exit();

} else { 
    // If user is not logged in or not an admin
    $em = "Please login first";
    header("Location: login.php?error=$em");
    exit();
}
?>
