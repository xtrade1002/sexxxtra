<?php
require '../config.php';
include 'includes/header.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }
    
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $action = $_POST["action"];
    
    if ($user_id) {
        if ($action == "delete") {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        } elseif ($action == "activate") {
            $stmt = $pdo->prepare("UPDATE users SET subscription_status = 'active' WHERE id = ?");
        } elseif ($action == "deactivate") {
            $stmt = $pdo->prepare("UPDATE users SET subscription_status = 'inactive' WHERE id = ?");
        }
        
        if ($stmt->execute([$user_id])) {
            echo "Felhasználó állapota módosítva!";
        } else {
            echo "Hiba történt!";
        }
    }
}

// Felhasználók lekérése
$stmt = $pdo->query("SELECT id, username, email, subscription_status FROM users ORDER BY username ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Felhasználók Kezelése</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-users-container">
        <h2>Felhasználók Kezelése</h2>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Keresés felhasználónév vagy email alapján">
            <button type="submit">Keresés</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Felhasználónév</th>
                <th>Email</th>
                <th>Állapot</th>
                <th>Műveletek</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['subscription_status']); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="action" value="activate">Aktiválás</button>
                        <button type="submit" name="action" value="deactivate">Deaktiválás</button>
                        <button type="submit" name="action" value="delete">Törlés</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
