<?php
require 'config.php';
include 'includes/header.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT id, email, username, subscription_status FROM users WHERE username LIKE ? OR email LIKE ?");
    $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT id, email, username, subscription_status FROM users ORDER BY username ASC");
}
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Felhasználók</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Felhasználók Listája</h2>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Keresés név vagy email alapján" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Keresés</button>
        </form>
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
                <td class="<?= $user['subscription_status'] === 'active' ? 'status-active' : 'status-inactive' ?>">
                    <?= htmlspecialchars($user['subscription_status']); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
