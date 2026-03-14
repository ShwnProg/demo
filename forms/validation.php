<?php
session_start();
require_once "../config/database.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Collect and sanitize
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $gender = trim($_POST['gender'] ?? '');

    $errors = [];

    // Required fields
    if(empty($username)) $errors['username'] = 'Username is required.';
    if(empty($email)) $errors['email'] = 'Email is required.';
    if(empty($age)) $errors['age'] = 'Age is required.';
    if(empty($password)) $errors['password'] = 'Password is required.';
    if(empty($confirm_password)) $errors['confirm_password'] = 'Confirm Password is required.';
    if(empty($gender)) $errors['gender'] = 'Gender is required.';

    // Password match
    if($password !== $confirm_password){
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // Email validation
    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $cleanEmail = filter_var($sanitized_email, FILTER_VALIDATE_EMAIL);
    if($cleanEmail === false){
        $errors['email'] = "Invalid email address.";
    }   

    // Age validation
    $cleanAge = filter_var($age, FILTER_VALIDATE_INT);
    if($cleanAge === false){
        $errors['age'] = "Age must be a number.";
    }

    // Website validation (optional)
    if(!empty($website)){
        $cleanWebsite = filter_var($website, FILTER_VALIDATE_URL);
        if(!$cleanWebsite){
            $errors['website'] = "Invalid website URL.";
        }
    }

    // Gender validation
    $allowedGenders = ['male','female'];
    if(!in_array($gender, $allowedGenders)){
        $errors['gender'] = "Invalid gender selection.";
    }

    // Redirect back with errors if any
    if(!empty($errors)){
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
        header("Location: registration.php");
        exit;
    }

    // Everything is valid → hash password & store
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    /* SAVE DATA TO DB */
    $db = new Database();
    $conn = $db->conn;

    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $cleanEmail]);
        $emailExists = $stmt->fetchColumn();
        
        if($emailExists > 0){
            $_SESSION['errors'] = ['db_error' => 'Email Already Exists.'];
            header("Location: registration.php");
            exit;
        }else{
            //INSERT
            $stmt = $conn->prepare(
                "INSERT INTO users (username, email, age, password, website, gender)
                VALUES (:username, :email, :age, :password, :website, :gender);"
            );

            $stmt->execute([
                'username' => $username,
                'email'    => $cleanEmail,
                'age'      => $cleanAge,
                'password' => $hashedPassword,
                'website'  => $cleanWebsite,
                'gender'   => $gender
            ]);

            $_SESSION['success'] = "Registration Successful";
            header("Location: registration.php");
            exit;
        }
        

    } catch (PDOException $e) {
        $_SESSION['errors'] = ['db_error' => "Something Went Wrong"];
        header("Location: registration.php");
        exit;
    }

}else{
    header("Location: registration.php");
    exit;
}
?>
