<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../src/db.php';

// Sadece admin kullanıcılarının erişmesine izin ver
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: /?page=login");
    exit;
}

// Dinamik olarak filtre eklemek için açık bir parametre bırakılıyor
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$query = "SELECT * FROM users WHERE username LIKE '%$filter%'"; // SQL Injection'a açık sorgu

try {
    $result = $conn->query($query);

    if (!$result) {
        die("Sorgu başarısız: " . $conn->errorInfo()[2]);
    }
} catch (PDOException $e) {
    die("SQL Hatası: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="admin-panel">
    <h1>Admin Panel</h1>
    <form method="GET" action="">
        <input type="text" name="filter" placeholder="Filtrele (ör: admin)">
        <button type="submit">Filtrele</button>
    </form>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Şifre</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['password'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
