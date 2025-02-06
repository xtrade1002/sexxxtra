<?php
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $action = $_POST["action"];

    if ($action == "delete") {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    } elseif ($action == "activate") {
        $stmt = $pdo->prepare("UPDATE users SET subscription_status = 'active' WHERE id = ?");
    } elseif ($action == "deactivate") {
        $stmt = $pdo->prepare("UPDATE users SET subscription_status = 'inactive' WHERE id = ?");
    }
    
    $stmt->execute([$user_id]);
    echo "Felhasználó állapota módosítva!";
}

// Felhasználók lekérése
$stmt = $pdo->query("SELECT id, username, email, subscription_status FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Felhasználók Kezelése</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="admin-container">
        <h2>Felhasználók Kezelése</h2>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Email</th>
                <th>Előfizetés</th>
                <th>Műveletek</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']); ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['subscription_status']); ?></td>
                <td class="admin-actions">
                    <form action="admin_manage_users.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                        <button type="submit" name="action" value="activate">Aktiválás</button>
                        <button type="submit" name="action" value="deactivate">Inaktiválás</button>
                        <button type="submit" name="action" value="delete" onclick="return confirm('Biztos törlöd ezt a felhasználót?')">Törlés</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
