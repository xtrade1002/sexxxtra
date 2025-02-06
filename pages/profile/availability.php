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
    
    $pdo->prepare("DELETE FROM availability WHERE provider_id = ?")->execute([$user_id]);
    
    $stmt = $pdo->prepare("INSERT INTO availability (provider_id, day, start_time, end_time) VALUES (?, ?, ?, ?)");
    
    foreach ($_POST['availability'] as $day => $checked) {
        if (!empty($_POST['start_time'][$day]) && !empty($_POST['end_time'][$day])) {
            $start_time = $_POST['start_time'][$day];
            $end_time = $_POST['end_time'][$day];
            
            if (strtotime($start_time) < strtotime($end_time)) {
                $stmt->execute([$user_id, $day, $start_time, $end_time]);
            }
        }
    }
    
    header("Location: profile.php?success=availability_updated");
    exit();
}

$stmt = $pdo->prepare("SELECT day, start_time, end_time FROM availability WHERE provider_id = ?");
$stmt->execute([$user_id]);
$availability = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Elérhetőség beállítása</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="availability-container">
    <h2>Elérhetőség beállítása</h2>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <table>
            <tr>
                <th>Nap</th>
                <th>Kezdési idő</th>
                <th>Befejezési idő</th>
            </tr>
            <?php 
            $days = ["Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat", "Vasárnap"];
            foreach ($days as $index => $day): ?>
                <tr>
                    <td><?php echo $day; ?></td>
                    <td><input type="time" name="start_time[<?php echo $index; ?>]" value="<?php echo isset($availability[$index]['start_time']) ? htmlspecialchars($availability[$index]['start_time']) : ''; ?>"></td>
                    <td><input type="time" name="end_time[<?php echo $index; ?>]" value="<?php echo isset($availability[$index]['end_time']) ? htmlspecialchars($availability[$index]['end_time']) : ''; ?>"></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit">Mentés</button>
    </form>
</div>
</body>
</html>
