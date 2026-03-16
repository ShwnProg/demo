<?php
session_start();


$error = $_SESSION['error'] ?? [];
$old = $_SESSION['old'] ?? [];

// session_unset();

// var_dump($old);

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
    <form action="login_process.php" method="post">
        <h2>Login</h2>

        <?php foreach ($error as $e): ?>
            <div class="error"><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>

        <input type="text" name="email" id="" placeholder="Enter email"
            value="<?= htmlspecialchars($old['email'] ?? '')?>">

        <input type="password" name="password" id="" placeholder="Enter password"
            value="<?= htmlspecialchars($old['password']?? '')?>">

        <button type="submit" name="Login">Login</button>
        <a class='link' href="/forms/registration.php">Register</a>
    </form>
</body>

</html>