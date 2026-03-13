<?php
require_once "../config/database.php";
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $age = trim($_POST['age']) ?? '';
    $website = trim($_POST['website']) ?? '';
    $gender = trim($_POST['gender']) ?? '';
    $password = trim($_POST['passwo$password']) ?? '';

    $error = [];

    if (empty($username))
        $error['username'] = 'Username cannot be empty';
    if (empty($email))
        $error['email'] = 'Email cannot be empty';
    if (empty($age))
        $error['age'] = 'Age cannot be empty';
    if (empty($gender))
        $error['gender'] = 'Gender cannot be empty';

    // //CHECK IF THERE ARE ANY CHANGES
    // $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    // $stmt->execute([':user_id' => $user_id]);
    // $old_data = $stmt->fetch();

    // if($username == $old_data['username'] && 
    //     $email == $old_data['email'] && 
    //     $age == $old_data['age'] && 
    //     $website == $old_data['website'] && 
    //     $gender == $old_data['gender'])
    // {

    // }
    $connection = new Database();
    $user_id = $_SESSION['user_id'];
    // var_dump($_SESSION);
    $conn = $connection->conn;

    $stmt = $conn->prepare("UPDATE users SET username = :username,email=:email,age=:age,gender=:gender,website=:website WHERE user_id = :user_id");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':age' => $age,
        ':gender' => $gender,
        ':website' => $website,
        ':user_id' => $user_id
    ]);

    $result = $stmt->rowCount();

    if ($result > 0) {
        $_SESSION['successful'] = 'Account Updated';
        // $_SESSION['user_id'] = $user_id;
        header("Location: /forms/home.php");
        exit;
        // echo"hello";
    } else {
        $error['invalid'] = 'No changes made or user not found';
        header("Location: /forms/home.php");
        exit;
        // echo "hi";
    }


}


?>