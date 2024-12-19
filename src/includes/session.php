<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Eğer oturum başlatılmamışsa başlat
}

// Eğer oturum açılmamışsa ve kullanıcı 'login' veya 'register' dışında bir sayfaya erişiyorsa yönlendir
if (!isset($_SESSION['username']) && !in_array($_GET['page'] ?? '', ['login', 'register'])) {
    header("Location: /?page=login");
    exit;
}
?>
