<?php 

function get_all_users($conn){
	$sql = "SELECT * FROM users WHERE role =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute(["employee"]);

	if($stmt->rowCount() > 0){
		$users = $stmt->fetchAll();
	}else $users = 0;

	return $users;
}

function get_user_team($conn, $user_id) {
    $sql = "SELECT t.id AS team_id, t.label AS team_name
            FROM teams t
            JOIN users u ON u.team_id = t.id
            WHERE u.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



function insert_user($conn, $data){
	$sql = "INSERT INTO users (full_name, username, password, role,team_id) VALUES(?,?,?,?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function update_user($conn, $data){
    // Updated query to include team_id
    $sql = "UPDATE users SET full_name=?, username=?, password=?, role=?, team_id=? WHERE id=? AND role=?";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Execute the statement with the data array
    $stmt->execute($data);
}


function delete_user($conn, $data){
	$sql = "DELETE FROM users WHERE id=? AND role=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}


function get_user_by_id($conn, $id){
	$sql = "SELECT * FROM users WHERE id =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$user = $stmt->fetch();
	}else $user = 0;

	return $user;
}

function update_profile($conn, $data){
	$sql = "UPDATE users SET full_name=?,  password=? WHERE id=? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function count_users($conn){
	$sql = "SELECT id FROM users WHERE role='employee'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}