<?php
require 'config.php';

$stmt = $pdo->query("SELECT id, email, username, subscription_status FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Felhasználók</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="admin-container">
        <h2>Felhasználók Listája</h2>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Email</th>
                <th>Előfizetés</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']); ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['subscription_status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
