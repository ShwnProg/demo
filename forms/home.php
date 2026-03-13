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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- <h1 class='greet'>Welcome <?php echo $_SESSION['username']; ?></h1> -->
    <a class='logout' href="logout.php">Logout</a>

    <div class="container">
        <!-- <div class="container-header"> -->
        <!-- <h1>User's Table</h1> -->
        <!-- </div> -->
        <div class="sub-container">
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
                        <td>
                            <div class="action-btn">
                                <a class='edit-btn' href="edit.php?id=<?= $user['user_id']; ?>">EDIT</a>
                                <a class='delete-btn' href="delete.php?id=<?= $user['user_id'] ?>">DELETE</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>

</html>