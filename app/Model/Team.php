<?php 
function get_all_teams($conn) {
    // Define the SQL query to retrieve all teams
    $sql = "SELECT * FROM teams";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Check if any rows are returned
    if ($stmt->rowCount() > 0) {
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as an associative array
    } else {
        $teams = 0; // No teams found
    }

    // Return the result
    return $teams;
}

function find_team_by_id($conn, $id){
    // Define the SQL query to get the team by ID
    $sql = "SELECT * FROM teams WHERE id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Execute the statement with the provided ID
    $stmt->execute([$id]);

    // Check if a team is found
    if ($stmt->rowCount() > 0) {
        $team = $stmt->fetch();  // Fetch the team data
    } else {
        $team = 0;  // Return 0 if no team is found
    }

    // Return the team data or 0
    return $team;
}

// Function to add a new team
function add_team($conn, $label) {
    $sql = "INSERT INTO teams (label) VALUES (:label)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':label', $label, PDO::PARAM_STR);
    return $stmt->execute(); // Insert the new team
}

// Function to update a team's details
function update_team($conn, $id, $label) {
    $sql = "UPDATE teams SET label = :label WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':label', $label, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute(); // Update the team
}

// Function to check if there are any users associated with the team
function check_team_has_users($conn, $team_id) {
    $query = "SELECT COUNT(*) FROM users WHERE team_id = :team_id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':team_id', $team_id, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    return $count > 0;  // Returns true if there are users associated with the team
}

// Function to check if there are any projects associated with the team
function check_team_has_projects($conn, $team_id) {
    $query = "SELECT COUNT(*) FROM projects WHERE assigned_to = :team_id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':team_id', $team_id, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    return $count > 0;  // Returns true if there are projects associated with the team
}

// Function to delete the team
function delete_team($conn, $team_id) {
    $query = "DELETE FROM teams WHERE id = :team_id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':team_id', $team_id, PDO::PARAM_INT);
    
    return $stmt->execute();
}
// app/Model/Team.php
// app/Model/Team.php
function get_members_by_team($conn, $team_id) {
    // Query to fetch members for the given team ID
    $query = "SELECT u.id, u.full_name FROM users m JOIN users u ON m.id = u.id WHERE m.team_id = :team_id";
    $stmt = $conn->prepare($query);

    // Use bindValue to bind the parameter for PDO
    $stmt->bindValue(':team_id', $team_id, PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();

    // Fetch the results
    $members = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $members[] = $row;
    }

    return $members;
}
// Check if a team with the same name already exists
function team_exists($conn, $team_name) {
    $sql = "SELECT * FROM teams WHERE label = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$team_name]);
    return $stmt->rowCount() > 0;
}

// Create a new team in the database
function create_team($conn, $team_name) {
    $sql = "INSERT INTO teams (label) VALUES (?)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$team_name]);
}

?>



