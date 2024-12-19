<?php
session_start();

// Veritabanı bağlantısı
require_once __DIR__ . '/../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL Injection (1) - Zafiyetli kod
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($sql) === TRUE) {
        header('Location: login.php'); // Kayıt işlemi başarılı, login sayfasına yönlendir
        exit();
    } else {
        $error = 'Kayıt işlemi başarısız!';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="register-container">
        <h2>Kayıt Ol</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Kayıt Ol</button>
        </form>
    </div>
</body>
</html>
