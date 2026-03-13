<?php
session_start();
require_once "../config/database.php";

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    header('Location: home.php');
    exit;
}

$db = new Database();
$conn = $db->conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL to fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    /* echo "<pre>";
    print_r($user['username']);
    echo "</pre>"; */

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged'] = true;

            header('Location: home.php');
            exit;
        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "Email not found";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <form action="" method="post">
        <?php echo isset($error) ? "<p style='color: red'> $error </p>" : ''; ?>
        <h2>Login</h2>
        <input type="text" name="email" id="" placeholder="Enter email">
        <input type="password" name="password" id="" placeholder="Enter password">
        <button type="submit" name="Login">Login</button>
        <a class = 'link' href="/forms/registration.php">Register</a>
    </form>
</body>

</html>