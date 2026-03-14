<?php
require_once "../config/database.php";
session_start();

$user_id = $_GET['id'];
$_SESSION['user_id'] = $user_id;

// $user_id = $_SESSION['user_id'];
// var_dump($user_id);

$connection = new Database();
$conn = $connection->conn;
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    echo "Connection Failed : " . $e->getMessage();
}

$error = $_SESSION['error'] ?? [];
$success = $_SESSION['success'] ?? [];
$old = $_SESSION['old'] ?? [];
$gender = $old['gender'] ?? $user['gender'];

unset($_SESSION['error'], $_SESSION['success'], $_SESSION['old']);

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

        <!-- ERROR MESSAGE  -->
        <?php if (!empty($error['invalid'])): ?>
            <div class="error"><?= htmlspecialchars($error['invalid']) ?></div>
        <?php endif; ?>

        <!-- SUCCESS MESSAGE -->
        <?php if (!empty($success)): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- USERNAME -->
        <label for="username">Username</label>
        <input type="text" name='username' value="<?= htmlspecialchars($old['username'] ?? $user['username']) ?>">

        <!-- USERNAME ERROR MESSAGE -->
        <?php if (!empty($error['username'])): ?>
            <div class="error"><?= htmlspecialchars($error['username']) ?></div>
        <?php endif; ?>

        <!-- EMAIL -->
        <label for="email">Email</label>
        <input type="text" name='email' value="<?= htmlspecialchars($old['email'] ?? $user['email']) ?>">

        <!-- EMAIL ERROR MESSAGE -->
        <?php if (!empty($error['email'])): ?>
            <div class="error"><?= htmlspecialchars($error['email']) ?></div>
        <?php endif; ?>

        <!-- AGE -->
        <label for="age">Age</label>
        <input type="text" name='age' value="<?= htmlspecialchars($old['age'] ?? $user['age']) ?>">

        <!-- AGE ERROR MESSAGE -->
        <?php if (!empty($error['age'])): ?>
            <div class="error"><?= htmlspecialchars($error['age']) ?></div>
        <?php endif; ?>

        <!-- WEBSITE(OPTIONAL) -->
        <label for="website">Website(Optional)</label>
        <input type="text" name='website' value="<?= htmlspecialchars($old['website'] ?? $user['website']) ?>">

        <!-- WEBSITE ERROR MESSAGE -->
        <?php if (!empty($error['website'])): ?>
            <div class="error"><?= htmlspecialchars($error['website']) ?></div>
        <?php endif; ?>

        <!-- GENDER -->
        <label for="gender">Gender</label>
        <select name="gender" id="">
            <option value="">Select a Gender</option>
            <option value="male" <?= $gender == 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= $gender == 'female' ? 'selected' : '' ?>>Female</option>
        </select>

        <!-- GENDER ERROR MESSAGE -->
        <?php if (!empty($error['gender'])): ?>
            <div class="error"><?= htmlspecialchars($error['gender']) ?></div>
        <?php endif; ?>

        <!-- PASSWORD -->
        <label>New Password (leave blank if no change)</label>
        <input type="password" name="password" value="<?= htmlspecialchars($old['password']) ?>">

        <!-- PASSWORD ERROR MESSAGE -->
        <?php if (!empty($error['password'])): ?>
            <div class="error"><?= htmlspecialchars($error['password']) ?></div>
        <?php endif; ?>

        <button type='submit' name="update">UPDATE</button>
        <a class='link' href="../forms/home.php">BACK</a>
    </form>
</body>

</html>