<?php
// database connection info
$servername = "db"; // Docker'daki MySQL servisinin ismi
$username = "crypto_user"; // username
$password = "crypto_pass"; // password
$dbname = "crypto_news_db"; // db name

try {
    // PDO ile veritabanına bağlantı
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // PDO hata ayıklama modu
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection error : " . $e->getMessage();
}
?>
