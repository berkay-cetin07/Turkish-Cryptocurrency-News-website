<?php
<<<<<<< HEAD
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
=======
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
>>>>>>> main
}
?>
