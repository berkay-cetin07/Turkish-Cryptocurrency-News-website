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
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    try {
        $result = $conn->query($query);

        if ($result && $result->rowCount() > 0) {
            $user = $result->fetch(PDO::FETCH_ASSOC);
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: /admin.php");
            } else {
                header("Location: /?page=home");
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
    <style>
        /* Genel Arka Plan */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        /* Login Kutusu */
        .login-container {
            background-color: rgba(255, 255, 255, 0.9); /* Beyaz kutu */
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
            position: relative;
        }

        /* Profil İkonu */
        .login-container .profile-icon {
            width: 80px;
            height: 80px;
            background-color: #ffffff; /* Beyaz yuvarlak */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #1b4332; /* Koyu yeşil figür */
            font-size: 40px;
            margin: -60px auto 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .login-container h2 {
            color: #1b4332; /* Başlık için koyu yeşil renk */
            margin-bottom: 30px;
            font-size: 24px;
        }

        /* Giriş Alanları */
        .form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            color: #555;
            font-size: 16px;
        }

        .form input:focus {
            outline: none;
            border-color: #457fca;
        }

        /* Giriş Butonu */
        .form .btn {
            width: 100%;
            padding: 12px;
            background-color: #457fca; /* Canlı mavi */
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }

        .form .btn:hover {
            background-color: #5691c8;
        }

        .error {
            color: #e63946;
            margin-bottom: 15px;
        }

        /* Alt Bilgi */
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #ffffff;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="profile-icon">
            &#128100; <!-- Anonim insan figürü -->
        </div>
        <h2>Giriş Yap</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" class="form">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="password" name="password" placeholder="Şifre" required>
            <button type="submit" name="login" class="btn">Giriş Yap</button>
        </form>
    <div class="footer">
    </div>
</body>
</html>