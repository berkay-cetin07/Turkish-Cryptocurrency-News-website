<?php
// database connection info
$servername = "db"; // Docker'daki MySQL servisinin ismi
$username = "crypto_user"; // MySQL kullanıcı adı
$password = "crypto_pass"; // MySQL şifresi
$dbname = "crypto_news_db"; // Veritabanı adı

try {
    // PDO ile veritabanına bağlan
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // PDO hata ayıklama modu
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
?>
