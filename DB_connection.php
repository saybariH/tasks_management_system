<?php  

$sName = "localhost";
$port = "3306";
$uName = "root";
$pass  = "";
$db_name = "task_management_db";

try {
	$conn = new PDO("mysql:host=$sName;port=$port;dbname=$db_name", $uName, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOExeption $e){
	echo "Connection failed: ". $e->getMessage();
	exit;
}