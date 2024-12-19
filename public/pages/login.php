<?php
session_start();
include_once "../../src/db.php";

// Hata mesajlarını tutacak değişkenler
$error = "";
$success = "";

// Giriş işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Veritabanı sorgusu
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if ($password === $hashedPassword) {
            $_SESSION['username'] = $username;
            header("Location: /?page=home");
            exit;
        } else {
            $error = "Geçersiz kullanıcı adı veya şifre.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }
    $stmt->close();
}

// Kayıt işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kullanıcı adı zaten var mı kontrolü
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Bu kullanıcı adı zaten alınmış.";
    } else {
        // Yeni kullanıcı kaydı
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $success = "Kayıt başarılı! Giriş yapabilirsiniz.";
        } else {
            $error = "Kayıt sırasında bir hata oluştu.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Giriş ve Kayıt</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="login-container">
    <h2>Giriş Yap</h2>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Kullanıcı Adı" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit" name="login" class="btn btn-primary">Giriş Yap</button>
    </form>
    <h2>Veya Kayıt Ol</h2>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Kullanıcı Adı" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit" name="register" class="btn btn-primary">Kayıt Ol</button>
    </form>
</div>
</body>
</html>
