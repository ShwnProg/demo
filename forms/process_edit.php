<?php
require_once "../config/database.php";
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //  GET THE USER'S INPUT
    $username = trim($_POST['username']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $age = trim($_POST['age']) ?? '';
    $website = trim($_POST['website']) ?? '';
    $gender = trim($_POST['gender']) ?? '';
    $password = trim($_POST['password']) ?? '';

    $error = [];

    // CHECK EMPTY FIELD
    if (empty($username))
        $error['username'] = 'Username cannot be empty';
    if (empty($email))
        $error['email'] = 'Email cannot be empty';
    if (empty($age))
        $error['age'] = 'Age cannot be empty';
    if (empty($gender))
        $error['gender'] = 'Gender cannot be empty';

    // INSTANTIATE DB OBJECT
    $connection = new Database();
    $user_id = $_SESSION['user_id'];
    // var_dump($_SESSION); 
    $conn = $connection->conn;

    //CHECK IF THERE ARE ANY CHANGES
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $old_data = $stmt->fetch();

    $isPasswordSame = false;
    $isSameData = false;

    //CHECK IF SAME PASSWORD
    if (!empty($password)) {
        if (password_verify($password, $old_data['password'])) {
            $error['password'] = "Password is the same as the old one";
            $isPasswordSame = true;
        }
    }
    // COMPARE INPUT TO THE OLD DATA
    if (
        $username == $old_data['username'] &&
        $email == $old_data['email'] &&
        $age == $old_data['age'] &&
        $website == $old_data['website'] &&
        $gender == $old_data['gender'] &&
        (empty($password) || $isPasswordSame)
    ) {
        $error['invalid'] = "No changes";
        $isSameData = true;
    }

    if (!$isSameData) {

        // Email validation
        $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $cleanEmail = filter_var($sanitized_email, FILTER_VALIDATE_EMAIL);

        if ($cleanEmail === false) {
            $error['email'] = "Invalid email address.";
        }

        //CHECK DUPLICATE EMAIL
        if ($email != $old_data['email']) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $emailExists = $stmt->fetchColumn();

            if ($emailExists > 0) {
                $error['email'] = 'Email is already exist';
            }
        }

        //Age Validation
        $cleanAge = filter_var($age, FILTER_VALIDATE_INT);
        if ($cleanAge === false) {
            $error['age'] = "Age must be a number.";
        }

        // Website validation (optional)
        if (!empty($website)) {
            $cleanWebsite = filter_var($website, FILTER_VALIDATE_URL);
            if (!$cleanWebsite) {
                $error['website'] = "Invalid website URL.";
            }
        }

        // Gender validation
        $allowedGenders = ['male', 'female'];
        if (!in_array($gender, $allowedGenders)) {
            $error['gender'] = "Invalid gender selection.";
        }

    }

    if (!empty($error)) {
        $_SESSION['error'] = $error;
        $_SESSION['old'] = $_POST;
        header("Location: /forms/edit.php?id=" . $user_id);
        exit;
    }

    if (!empty($password) && !$isPasswordSame) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET username = :username,email=:email,age=:age,gender=:gender,website=:website,password = :password WHERE user_id = :user_id");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':age' => $age,
            ':gender' => $gender,
            ':website' => $website,
            ':password' => $hashed_password,
            ':user_id' => $user_id
        ]);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = :username,email=:email,age=:age,gender=:gender,website=:website WHERE user_id = :user_id");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':age' => $age,
            ':gender' => $gender,
            ':website' => $website,
            ':user_id' => $user_id
        ]);
    }


    $_SESSION['success'] = 'Account Updated';
    header("Location: /forms/edit.php?id=" . $user_id);
    exit;

}


?>