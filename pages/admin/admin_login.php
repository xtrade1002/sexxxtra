<?php
require '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT id, password FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin["password"])) {
        $_SESSION["admin_id"] = $admin["id"];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Hibás email vagy jelszó!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bejelentkezés</title>
    <link rel="stylesheet" href="..assets/css/admin.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="admin-login-container">
        <h2>Admin Bejelentkezés</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="admin_login.php" method="POST">
            <input type="email" name="email" placeholder="Email cím" required>
            <input type="password" name="password" placeholder="Jelszó" required>
            <button type="submit">Bejelentkezés</button>
        </form>
    </div>

</body>
</html>
