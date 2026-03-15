<?php
require_once '../config/database.php';
session_start();
$user_id = $_GET['id'];

$connection = new Database();
$conn = $connection->conn;

$stmt = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$result = $stmt->rowCount();


if ($result > 0) {
    // echo "Deleted";
    $_SESSION['success'] = 'Deleted Successfully';
    // var_dump($_SESSION);
    header("Location: home.php");
    exit;
} else {
    // echo "Something went wrong";
    $_SESSION['error'] = 'Something went wrong';
    header("Location: home.php");
    exit;
}
?>