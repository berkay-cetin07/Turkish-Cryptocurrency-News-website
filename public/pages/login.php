<?php
session_start(); // Oturum başlat

// Kullanıcı zaten giriş yaptıysa home.php'ye yönlendir
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

// Veritabanı bağlantısı
require_once __DIR__ . '/../config/db.php';

$error = ''; // Hata mesajı

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL Injection (1) - Zafiyetli kod
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Kullanıcı bulunduysa oturum başlat
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: home.php'); // Anasayfaya yönlendir
        exit();
    } else {
        $error = 'Geçersiz kullanıcı adı veya şifre!';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Giriş Yap</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Giriş Yap</button>
        </form>
        <p>Hesabınız yok mu? <a href="register.php">Kayıt Olun</a></p>
    </div>
</body>
</html>
