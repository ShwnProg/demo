<?php
require_once '../config/database.php';
$user_id = $_GET['id'];

$connection = new Database();
$conn = $connection->conn;

$stmt = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$result = $stmt->rowCount();


if ($result > 0) {
    // echo "Deleted";
    header("Location: ../forms/home.php");
    exit;
} else {
    // echo "Something went wrong";
    header("Location: ../forms/home.php");
    exit;
}
?>