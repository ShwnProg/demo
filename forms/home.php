<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    header('Location: login.php');
    exit;
}

require_once "../config/database.php";
$connection = new Database();

$conn = $connection->conn;

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <h1>Welcome <?php echo $_SESSION['username']; ?></h1>

    <div class="container">
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Website</th>
                <th>Gender</th>
                <th>Action</th>

            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['website']) == '' ? 'N/A' : $user['website'] ?></td>
                    <td><?= htmlspecialchars($user['gender']) ?></td>
                    <td><a href="edit.php?id=<?= $user['user_id']; ?>">EDIT</a>
                        <a href="delete.php?id=<?= $user['user_id'] ?>">DELETE</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>