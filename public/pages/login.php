<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Eğer oturum yoksa başlat
}

require_once __DIR__ . '/../../src/db.php';

// Kullanıcı zaten giriş yaptıysa ana sayfaya yönlendir
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /admin.php"); // Admin kullanıcı admin paneline yönlendirilir
    } else {
        header("Location: /?page=home"); // Normal kullanıcı ana sayfaya yönlendirilir
    }
    exit;
}

// Hata mesajlarını tutacak değişkenler
$error = "";

// Giriş işlemi
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    // Kullanıcı girdisi (hiçbir temizlik yapılmıyor, tamamen savunmasız)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL Injection'a tamamen açık sorgu
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    try {
        // SQL sorgusunu çalıştır
        $result = $conn->query($query);

        if ($result && $result->rowCount() > 0) {
            $user = $result->fetch(PDO::FETCH_ASSOC);
            // Kullanıcı oturumunu başlat
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: /admin.php"); // Admin kullanıcı admin paneline yönlendirilir
            } else {
                header("Location: /?page=home"); // Normal kullanıcı ana sayfaya yönlendirilir
            }
            exit;
        } else {
            $error = "Geçersiz kullanıcı adı veya şifre.";
        }
    } catch (PDOException $e) {
        $error = "SQL Hatası: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Giriş</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-container">
        <h2>Giriş Yap</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" class="form">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="text" name="password" placeholder="Şifre" required>
            <button type="submit" name="login" class="btn btn-primary">Giriş Yap</button>
        </form>
    </div>
</div>
</body>
</html>
