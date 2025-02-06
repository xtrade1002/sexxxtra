<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Nincs bejelentkezve.");
}

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }

    $pdo->prepare("DELETE FROM pricing WHERE provider_id = ?")->execute([$user_id]);

    $stmt = $pdo->prepare("INSERT INTO pricing (provider_id, duration, incall_price, outcall_price) VALUES (?, ?, ?, ?)");

    foreach ($_POST['incall_price'] as $duration => $incall_price) {
        $outcall_price = $_POST['outcall_price'][$duration];
        
        if (is_numeric($incall_price) && is_numeric($outcall_price) && ($incall_price > 0 || $outcall_price > 0)) {
            $stmt->execute([$user_id, $duration, $incall_price, $outcall_price]);
        }
    }

    header("Location: profile.php?success=pricing_updated");
    exit();
}

$stmt = $pdo->prepare("SELECT duration, incall_price, outcall_price FROM pricing WHERE provider_id = ?");
$stmt->execute([$user_id]);
$pricing = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Árak beállítása</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="pricing-container">
    <h2>Árak beállítása</h2>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <table>
            <tr>
                <th>Időtartam</th>
                <th>Incall Ár (€)</th>
                <th>Outcall Ár (€)</th>
            </tr>
            <?php foreach ([30, 60, 90, 120] as $duration): ?>
                <tr>
                    <td><?php echo $duration; ?> perc</td>
                    <td><input type="number" name="incall_price[<?php echo $duration; ?>]" min="0" step="0.01" value="<?php echo isset($pricing[$duration]['incall_price']) ? htmlspecialchars($pricing[$duration]['incall_price']) : ''; ?>"></td>
                    <td><input type="number" name="outcall_price[<?php echo $duration; ?>]" min="0" step="0.01" value="<?php echo isset($pricing[$duration]['outcall_price']) ? htmlspecialchars($pricing[$duration]['outcall_price']) : ''; ?>"></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit">Mentés</button>
    </form>
</div>
</body>
</html>
