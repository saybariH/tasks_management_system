<?php 

function insert_task($conn, $data){
	$sql = "INSERT INTO tasks (title, description, assigned_to, due_date) VALUES(?,?,?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function get_all_tasks($conn){
	$sql = "SELECT * FROM tasks ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}
function get_tasks_by_team($conn, $team_id) {
    try {
        $sql = "
            SELECT t.*
            FROM tasks t
            INNER JOIN projects p ON t.assigned_to = p.id
            INNER JOIN teams tm ON p.assigned_to = tm.id
            WHERE tm.id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$team_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return as an associative array
    } catch (PDOException $e) {
        error_log("Error fetching tasks by team: " . $e->getMessage());
        return []; // Return an empty array in case of error
    }
}

function count_tasks_by_team($conn, $team_id) {
    try {
        $sql = "
            SELECT COUNT(*) 
            FROM tasks t
            INNER JOIN projects p ON t.assigned_to = p.id
            INNER JOIN teams tm ON p.assigned_to = tm.id
            WHERE tm.id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$team_id]);
        return (int) $stmt->fetchColumn(); // Cast result to integer
    } catch (PDOException $e) {
        error_log("Error counting tasks by team: " . $e->getMessage());
        return 0; // Return 0 in case of error
    }
}






function delete_task($conn, $data){
	$sql = "DELETE FROM tasks WHERE id=? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}


function get_task_by_id($conn, $id){
	$sql = "SELECT * FROM tasks WHERE id =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$task = $stmt->fetch();
	}else $task = 0;

	return $task;
}
function count_tasks($conn){
	$sql = "SELECT id FROM tasks";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function update_task($conn, $data){
	$sql = "UPDATE tasks SET title=?, description=?, assigned_to=?, due_date=? WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}


function update_task_status($conn, $task_id, $status) {
    $sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$status, $task_id]);
}


function get_all_tasks_by_id($conn, $user_id) {
    // Define the SQL query with joins
    $sql = "
        SELECT 
            tasks.id,
            tasks.title,
            tasks.description,
            tasks.status,
            tasks.due_date
        FROM 
            users
        INNER JOIN 
            teams ON users.team_id = teams.id
        INNER JOIN 
            projects ON teams.id = projects.assigned_to
        INNER JOIN 
            tasks ON projects.id = tasks.assigned_to
        WHERE 
            users.id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Execute with the provided user ID
    $stmt->execute([$user_id]);

    // Check if any tasks were retrieved
    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch tasks as an associative array
    } else {
        $tasks = 0; // No tasks found
    }

    // Return the result
    return $tasks;
}




function count_pending_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'pending'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_in_progress_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'in_progress'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_completed_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}


function count_my_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_tasks_overdue($conn, $id){
	$sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND assigned_to=? AND due_date != '0000-00-00'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_tasks_NoDeadline($conn, $id){
	$sql = "SELECT id FROM tasks WHERE assigned_to=? AND status != 'completed' AND due_date IS NULL OR due_date = '0000-00-00'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_pending_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'pending' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_in_progress_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'in_progress' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_completed_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'completed' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

