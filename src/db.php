<?php
$servername = "db"; // Docker servis adı
$username = "crypto_user"; // MySQL kullanıcı adı
$password = "crypto_pass"; // MySQL şifresi
$dbname = "crypto_news_db"; // Veritabanı adı

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Bağlantıyı ayarla
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Veritabanı bağlantısı başarılı!";
} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
