<?php
session_start();
require_once "../config/database.php";

$db = new Database();
$conn = $db->conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']) ?? '';
    $password = trim($_POST['password']) ?? '';

    $error = [];

    if (empty($email) || empty($password)) {
        $error['login'] = "Please enter your email and password";
    }



    // Prepare SQL to fetch user by email
    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error['email'] = "Email not found";

        }
        if ($user) {
            if (!password_verify($password, $user['password'])) {
                $error['password'] = "Wrong password";

            }
        }
    }
    // var_dump($_SESSION);

    if (!empty($error)) {
        $_SESSION['error'] = $error;
        $_SESSION['old'] = $_POST;
        header("Location: login.php");
        exit;
    }

    $_SESSION['username'] = $user['username'];
    $_SESSION['logged'] = true;

    header('Location: /forms/home.php');
    exit;


}
?>