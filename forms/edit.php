<?php
require_once "../config/database.php";
session_start();

$user_id = $_GET['id'];
$_SESSION['user_id'] = $user_id;

// $user_id = $_SESSION['user_id'];


// var_dump($user_id);

$connection = new Database();
$conn = $connection->conn;

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch();

$error = $_SESSION['error'] ?? [];
$success = $_SESSION['success'] ?? [];

unset($_SESSION['error'], $_SESSION['success']);

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
        <h2>EDIT</h2>
        <?php if (!empty($error['invalid'])): ?>
            <div class="error"><?= htmlspecialchars($error['invalid']) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <label for="username">Username</label>
        <input type="text" name='username' value="<?= htmlspecialchars($user['username']) ?>">

        <?php if (!empty($error['username'])): ?>
            <div class="error"><?= htmlspecialchars($error['username']) ?></div>
        <?php endif; ?>

        <label for="email">Email</label>
        <input type="text" name='email' value="<?= htmlspecialchars($user['email']) ?>">

        <?php if (!empty($error['email'])): ?>
            <div class="error"><?= htmlspecialchars($error['email']) ?></div>
        <?php endif; ?>

        <label for="age">Age</label>
        <input type="text" name='age' value="<?= htmlspecialchars($user['age']) ?>">

        <?php if (!empty($error['age'])): ?>
            <div class="error"><?= htmlspecialchars($error['age']) ?></div>
        <?php endif; ?>

        <label for="website">Website</label>
        <input type="text" name='website' value="<?= htmlspecialchars($user['website']) ?>">

        <?php if (!empty($error['website'])): ?>
            <div class="error"><?= htmlspecialchars($error['website']) ?></div>
        <?php endif; ?>

        <label for="gender">Gender</label>
        <select name="gender" id="">
            <option value="">Select a Gender</option>
            <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
        </select>

        <?php if (!empty($error['gender'])): ?>
            <div class="error"><?= htmlspecialchars($error['gender']) ?></div>
        <?php endif; ?>

        <label>New Password (leave blank if no change)</label>
        <input type="password" name="password">

        <?php if (!empty($error['password'])): ?>
            <div class="error"><?= htmlspecialchars($error['password']) ?></div>
        <?php endif; ?>

        <button type='submit' name="update">UPDATE</button>
        <a class='link' href="../forms/home.php">BACK</a>
    </form>
</body>

</html>