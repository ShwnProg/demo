<?php
require_once "../config/database.php";
session_start();
$user_id = $_GET['id'];
$_SESSION['user_id'] = $user_id;

// var_dump($_SESSION);

$connection = new Database();
$conn = $connection->conn;

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <form action='process_edit.php' method='POST'>
        <h1>EDIT</h1>

        <label for="usernmae">Username</label>
        <input type="text" name='username' value="<?= htmlspecialchars($user['username']) ?>">

        <label for="email">Email</label>
        <input type="text" name='email' value="<?= htmlspecialchars($user['email']) ?>">

        <label for="age">Age</label>
        <input type="text" name='age' value="<?= htmlspecialchars($user['age']) ?>">

        <label for="website">Website</label>
        <input type="text" name='website' value="<?= htmlspecialchars($user['website']) ?>">

        <label for="website">Gender</label>
        <select name="gender" id="">
            <option value="">Select a Gender</option>
            <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
        </select>

        <label>New Password (leave blank if no change)</label>
        <input type="password" name="password">

        <button type='submit' name="update">UPDATE</button>
        <a href="../forms/home.php">BACK</a>
    </form>
</body>

</html>