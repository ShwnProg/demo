<?php 
session_start();
require_once "../config/database.php";

try {
    $db = new Database();
    $conns = $db->conn;
    //echo "Database successfully connected";
} catch (PDOException $th) {
    echo "Failed " . $th->getMessage();
}

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Register</title>
</head>
<body>
    <form action="validation.php" method="POST">
        <?php if($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if(!empty($errors['db_error'])): ?>
            <div class="error"><?= $errors['db_error'] ?></div>
        <?php endif; ?>

        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
        <?php if(!empty($errors['username'])): ?>
            <div class="error"><?= $errors['username'] ?></div>
        <?php endif; ?>

        <label>Email:</label>
        <input type="text" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        <?php if(!empty($errors['email'])): ?>
            <div class="error"><?= $errors['email'] ?></div>
        <?php endif; ?>

        <label>Age:</label>
        <input type="text" name="age" value="<?= htmlspecialchars($old['age'] ?? '') ?>">
        <?php if(!empty($errors['age'])): ?>
            <div class="error"><?= $errors['age'] ?></div>
        <?php endif; ?>

        <label>Password:</label>
        <input type="password" name="password">
        <?php if(!empty($errors['password'])): ?>
            <div class="error"><?= $errors['password'] ?></div>
        <?php endif; ?>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password">
        <?php if(!empty($errors['confirm_password'])): ?>
            <div class="error"><?= $errors['confirm_password'] ?></div>
        <?php endif; ?>

        <label>Website (optional):</label>
        <input type="text" name="website" value="<?= htmlspecialchars($old['website'] ?? '') ?>">

        <label>Gender:</label>
        <select name="gender">
            <option value="">Select</option>
            <option value="male" <?= (isset($old['gender']) && $old['gender']=='male') ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= (isset($old['gender']) && $old['gender']=='female') ? 'selected' : '' ?>>Female</option>
        </select>
        <?php if(!empty($errors['gender'])): ?>
            <div class="error"><?= $errors['gender'] ?></div>
        <?php endif; ?>

        <button type="submit">Submit</button>
        <div style="text-align: center; margin-top: 10px">
            <a href="/forms/login.php">Login Now</a>
        </div>
    </form>

</body>
</html>