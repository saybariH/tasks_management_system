<?php 
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned_to']) && $_SESSION['role'] == 'admin') {
        include "../DB_connection.php";

        // Function to sanitize inputs
        function validate_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Sanitize inputs
        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        $assigned_to = validate_input($_POST['assigned_to']);
        $id = validate_input($_POST['id']);

        // Validate the inputs
        if (empty($title)) {
            $em = "Title is required";
            header("Location: ../edit-project.php?error=$em&id=$id");
            exit();
        } else if (empty($description)) {
            $em = "Description is required";
            header("Location: ../edit-project.php?error=$em&id=$id");
            exit();
        } else if ($assigned_to == 0) {
            $em = "Select a team";
            header("Location: ../edit-project.php?error=$em&id=$id");
            exit();
        } else {
            // Include the Project model
            include "../app/Model/Project.php";

            // Call the update_project function to update the project in the database
            update_project($conn, $id, $title, $description, $assigned_to);

            // Redirect to the project edit page with a success message
            $sm = "Project updated successfully";
            header("Location: ../edit-project.php?success=$sm&id=$id");
            exit();
        }
    } else {
        // If required data is not sent via POST
        $em = "Unknown error occurred";
        header("Location: ../edit-project.php?error=$em");
        exit();
    }

} else { 
    // If user is not logged in
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
}
?>
