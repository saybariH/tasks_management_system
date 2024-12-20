<?php

function get_all_projects($conn) {
    $sql = "SELECT * FROM projects"; // Replace 'projects' with your actual project table name
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $projects;
    } else {
        return 0; // No projects found
    }
}

function count_projects($conn) {
    $sql = "SELECT COUNT(*) AS total FROM projects";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['total'] : 0;
}

function get_tasks_by_project($conn, $project_id) {
    $sql = "SELECT * FROM tasks WHERE assigned_to = :project_id"; // Adjust to your task table and column
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tasks;
    } else {
        return 0; // No tasks found for this project
    }
}

function count_tasks_by_project($conn, $project_id) {
    $sql = "SELECT COUNT(*) AS total FROM tasks WHERE assigned_to = :project_id"; // Adjust as needed
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['total'] : 0;
}

function find_project_by_id($conn, $project_id) {
    $sql = "SELECT * FROM projects WHERE id = :project_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        return $project;
    } else {
        return 0; // No project found
    }
}

function create_project($conn, $title, $description, $assigned_to) {
    $sql = "INSERT INTO projects (title, description, assigned_to) VALUES (:title, :description, :assigned_to)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':assigned_to', $assigned_to, PDO::PARAM_INT);
    return $stmt->execute();
}

// Function to add a project to the database
// Function to add a project to the database
function add_project($conn, $title, $description, $assigned_to) {
    $sql = "INSERT INTO projects (title, description, assigned_to) VALUES (:title, :description, :assigned_to)";
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':assigned_to', $assigned_to);
    
    // Execute the statement and return the result (true if successful, false otherwise)
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}



function update_project($conn, $project_id, $title, $description, $assigned_to) {
    // SQL query to update the project
    $sql = "UPDATE projects SET title = :title, description = :description, assigned_to = :assigned_to WHERE id = :project_id";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters to the query
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':assigned_to', $assigned_to, PDO::PARAM_INT);
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    
    // Execute the query
    return $stmt->execute();
}



function delete_project($conn, $data) {
    // First, delete all tasks assigned to the project
    $stmt = $conn->prepare("DELETE FROM tasks WHERE assigned_to = :assigned_to");
    $stmt->bindParam(':assigned_to', $data[0]);
    $stmt->execute();

    // Now, delete the project
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = :id");
    $stmt->bindParam(':id', $data[0]);
    $stmt->execute();
}
    // Function to get projects by team
    function get_projects_by_team($conn, $team_id) {
        $stmt = $conn->prepare("SELECT * FROM projects WHERE assigned_to = :team_id");
        $stmt->bindParam(':team_id', $team_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to count projects by team
    function count_projects_by_team($conn, $team_id) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM projects WHERE assigned_to = :team_id");
        $stmt->bindParam(':team_id', $team_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
// Query to get all projects assigned to the employee's team
// Query to get all projects assigned to the employee's team
function get_all_projects_by_user($conn, $user_id) {
    // PDO query with a placeholder for the user_id
    $sql = "SELECT p.id, p.title, p.description 
            FROM projects p
            JOIN users u ON u.team_id = p.assigned_to
            WHERE u.id = :user_id";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind the user_id to the placeholder
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch all results
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Get all projects assigned to the user (based on the user's team)
function get_projects_by_user($conn, $user_id) {
    $sql = "SELECT p.id, p.title 
            FROM projects p
            JOIN users u ON u.team_id = p.assigned_to
            WHERE u.id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Query to get up to 3 tasks for a specific project// Query to get up to 3 tasks for a specific project
function get_tasks_by_project_id($conn, $project_id, $limit = 3) {
    // PDO query with a placeholder for project_id
    $sql = "SELECT t.id, t.title, t.status 
            FROM tasks t
            WHERE t.assigned_to = :project_id
            LIMIT :limit";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind the project_id and limit to the placeholders
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch all results
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


        
?>
