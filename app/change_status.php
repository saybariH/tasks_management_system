<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'employee') {
	include "../DB_connection.php";
    include "Model/Task.php";

    if (isset($_POST['task_id']) && isset($_POST['status'])) {
        $task_id = intval($_POST['task_id']);
        $status = $_POST['status'];

        // Validate the status
        $valid_statuses = ['pending', 'in_progress', 'completed'];
        if (!in_array($status, $valid_statuses)) {
            $em = "Invalid status value.";
            header("Location: ../my_task.php?error=$em");
            exit();
        }

        // Update the task status
        if (update_task_status($conn, $task_id, $status)) {
            $sm = "Task status updated successfully.";
            header("Location: ../my_task.php?success=$sm");
            exit();
        } else {
            $em = "Failed to update task status.";
            header("Location: ../my_task.php?error=$em");
            exit();
        }
    } else {
        $em = "Invalid request.";
        header("Location: ../my_task.php?error=$em");
        exit();
    }
} else {
    $em = "Unauthorized access.";
    header("Location: ../login.php?error=$em");
    exit();
}
?>
