<?php
require 'config.php';
session_start();

// Ellenőrizzük, hogy admin-e a felhasználó
if (!isset($_SESSION['admin_id'])) {
    die("Nincs admin jogosultságod!");
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }
    
    $user_id = $_POST['user_id'];
    $pdo->prepare("DELETE FROM pricing WHERE provider_id = ?")->execute([$user_id]);
    
    $stmt = $pdo->prepare("INSERT INTO pricing (provider_id, duration, incall_price, outcall_price) VALUES (?, ?, ?, ?)");
    
    foreach ($_POST['incall_price'] as $duration => $incall_price) {
        $outcall_price = $_POST['outcall_price'][$duration];
        if (is_numeric($incall_price) && is_numeric($outcall_price) && ($incall_price > 0 || $outcall_price > 0)) {
            $stmt->execute([$user_id, $duration, $incall_price, $outcall_price]);
        }
    }
    header("Location: update_pricing.php?success=updated");
    exit();
}

// Lekérdezzük az összes felhasználó árait
$stmt = $pdo->query("SELECT DISTINCT users.id, users.username FROM users JOIN pricing ON users.id = pricing.provider_id ORDER BY users.username ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Árak kezelése - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-pricing-container">
    <h2>Árak kezelése</h2>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="user_id">Felhasználó kiválasztása:</label>
        <select name="user_id" required>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>"> <?php echo htmlspecialchars($user['username']); ?> </option>
            <?php endforeach; ?>
        </select>
        <table>
            <tr>
                <th>Időtartam</th>
                <th>Incall Ár (€)</th>
                <th>Outcall Ár (€)</th>
            </tr>
            <?php foreach ([30, 60, 90, 120] as $duration): ?>
                <tr>
                    <td><?php echo $duration; ?> perc</td>
                    <td><input type="number" name="incall_price[<?php echo $duration; ?>]" min="0" step="0.01"></td>
                    <td><input type="number" name="outcall_price[<?php echo $duration; ?>]" min="0" step="0.01"></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit">Árak frissítése</button>
    </form>
</div>
</body>
</html>
